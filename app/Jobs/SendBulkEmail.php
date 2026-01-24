<?php

namespace App\Jobs;

use App\Models\BulkMessage;
use App\Models\BulkMessageLog;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public BulkMessage $bulkMessage,
        public User $user
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $emailService = new EmailService();

        // Find or create log entry
        $log = BulkMessageLog::firstOrCreate([
            'bulk_message_id' => $this->bulkMessage->id,
            'user_id' => $this->user->id,
            'channel' => 'email'
        ]);

        try {
            $result = $emailService->send(
                $this->user->email,
                $this->bulkMessage->title,
                $this->bulkMessage->message
            );

            if ($result['success']) {
                $log->update([
                    'status' => 'sent',
                    'sent_at' => now()
                ]);

                $this->bulkMessage->increment('sent_count');
            } else {
                $log->update([
                    'status' => 'failed',
                    'error_message' => $result['error'] ?? 'Unknown error'
                ]);

                $this->bulkMessage->increment('failed_count');
            }
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            $this->bulkMessage->increment('failed_count');

            throw $e; // Re-throw to trigger retry
        }

        // Update bulk message status if all messages are sent
        $this->updateBulkMessageStatus();
    }

    /**
     * Update bulk message status when all messages are processed
     */
    protected function updateBulkMessageStatus(): void
    {
        $total = $this->bulkMessage->total_recipients;
        $sent = $this->bulkMessage->sent_count;
        $failed = $this->bulkMessage->failed_count;

        if (($sent + $failed) >= $total) {
            $this->bulkMessage->update([
                'status' => $failed > 0 && $sent === 0 ? 'failed' : 'sent',
                'sent_at' => now()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $log = BulkMessageLog::where([
            'bulk_message_id' => $this->bulkMessage->id,
            'user_id' => $this->user->id,
            'channel' => 'email'
        ])->first();

        if ($log) {
            $log->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage()
            ]);
        }

        $this->bulkMessage->increment('failed_count');
    }
}
