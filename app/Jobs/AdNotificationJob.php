<?php

namespace App\Jobs;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AdNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ad;

    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }

    public function handle()
    {
        // Get targeted users
        $users = User::query();

        // Filter by target audience
        if ($this->ad->target_audience) {
            $users->where(function($q) {
                foreach ($this->ad->target_audience as $role) {
                    if ($role === 'all') {
                        return;
                    }
                    $q->orWhere('role', $role);
                }
            });
        }

        // Filter by location
        if ($this->ad->target_location) {
            $location = $this->ad->target_location;
            if (isset($location['country_id'])) {
                $users->where('country_id', $location['country_id']);
            }
            if (isset($location['state_id'])) {
                $users->where('state_id', $location['state_id']);
            }
            if (isset($location['lga_id'])) {
                $users->where('lga_id', $location['lga_id']);
            }
        }

        // Send email to each user
        $users->chunk(100, function($userChunk) {
            foreach ($userChunk as $user) {
                Mail::send('emails.ad-notification', [
                    'ad' => $this->ad,
                    'user' => $user
                ], function($message) use ($user) {
                    $message->to($user->email);
                    $message->subject($this->ad->title . ' - FarmVax');
                });
            }
        });
    }
}