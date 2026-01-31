<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkMessage;
use App\Models\BulkMessageLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BulkMessageController extends Controller
{
    /**
     * Display a listing of bulk messages
     */
    public function index()
    {
        $messages = BulkMessage::with('creator')->latest()->paginate(20);

        $stats = [
            'total' => BulkMessage::count(),
            'sent' => BulkMessage::where('status', 'sent')->count(),
            'pending' => BulkMessage::where('status', 'draft')->count(),
            'scheduled' => BulkMessage::where('status', 'scheduled')->count(),
            'total_recipients' => BulkMessage::sum('total_recipients'),
        ];

        return view('admin.bulk-messages.index', compact('messages', 'stats'));
    }

    /**
     * Show the form for creating a new bulk message
     */
    public function create()
    {
        return view('admin.bulk-messages.create');
    }

    /**
     * Store a newly created bulk message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:email,sms,both',
            'target_type' => 'required|in:all,role,location,specific',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'in:farmer,animal_health_professional,volunteer',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'specific_users' => 'nullable|array',
            'specific_users.*' => 'exists:users,id',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = $request->has('send_now') ? 'sending' : 'draft';

        // Calculate recipients
        $recipients = $this->getRecipients(
            $validated['target_type'],
            $validated['target_roles'] ?? null,
            $validated['country_id'] ?? null,
            $validated['state_id'] ?? null,
            $validated['lga_id'] ?? null,
            $validated['specific_users'] ?? null
        );

        $validated['total_recipients'] = $recipients->count();

        // Store recipient data as array (model will auto-convert to JSON)
        $validated['recipient_data'] = [
            'target_type' => $validated['target_type'],
            'target_roles' => $validated['target_roles'] ?? [],
            'country_id' => $validated['country_id'] ?? null,
            'state_id' => $validated['state_id'] ?? null,
            'lga_id' => $validated['lga_id'] ?? null,
            'specific_users' => $validated['specific_users'] ?? [],
        ];

        DB::beginTransaction();
        try {
            // Create the message first
            $bulkMessage = BulkMessage::create($validated);
            DB::commit();

            // If send now, process sending (outside transaction so message is saved even if sending fails)
            if ($request->has('send_now')) {
                try {
                    $this->sendMessage($bulkMessage, $recipients);
                    return redirect()->route('admin.bulk-messages.index')
                        ->with('success', 'Bulk message sent successfully to ' . $recipients->count() . ' recipients!');
                } catch (\Exception $e) {
                    // Message was created but sending failed - save as draft
                    $bulkMessage->update(['status' => 'draft']);
                    return redirect()->route('admin.bulk-messages.index')
                        ->with('warning', 'Message created but sending failed: ' . $e->getMessage() . '. Saved as draft.');
                }
            }

            return redirect()->route('admin.bulk-messages.index')
                ->with('success', 'Bulk message saved as draft!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating message: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified bulk message
     */
    public function show($id)
    {
        $message = BulkMessage::with('creator')->findOrFail($id);
        
        $logs = BulkMessageLog::where('bulk_message_id', $id)
            ->with('user')
            ->latest()
            ->paginate(50);

        return view('admin.bulk-messages.show', compact('message', 'logs'));
    }

    /**
     * Send the bulk message
     */
    /**
 * Send the bulk message
 */
public function send($id)
{
    $bulkMessage = BulkMessage::findOrFail($id);

    if ($bulkMessage->status == 'sent') {
        return back()->with('error', 'This message has already been sent!');
    }

    // recipient_data is auto-cast to array by the model
    $recipientData = $bulkMessage->recipient_data;

    // Handle case where recipient_data is null or empty
    if (!$recipientData || !isset($recipientData['target_type'])) {
        return back()->with('error', 'Invalid recipient data. Please recreate this message.');
    }

    $recipients = $this->getRecipients(
        $recipientData['target_type'],
        $recipientData['target_roles'] ?? null,
        $recipientData['country_id'] ?? null,
        $recipientData['state_id'] ?? null,
        $recipientData['lga_id'] ?? null,
        $recipientData['specific_users'] ?? null
    );

    DB::beginTransaction();
    try {
        $this->sendMessage($bulkMessage, $recipients);
        DB::commit();
        
        return redirect()->route('admin.bulk-messages.index')
            ->with('success', 'Message sent successfully to ' . $recipients->count() . ' recipients!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error sending message: ' . $e->getMessage());
    }
}

    /**
     * Delete the bulk message
     */
    public function destroy($id)
    {
        $bulkMessage = BulkMessage::findOrFail($id);
        $bulkMessage->delete();

        return redirect()->route('admin.bulk-messages.index')
            ->with('success', 'Bulk message deleted successfully!');
    }

    /**
     * Get recipients based on targeting criteria
     */
    protected function getRecipients($targetType, $targetRoles = null, $countryId = null, $stateId = null, $lgaId = null, $specificUsers = null)
    {
        $query = User::query();

        switch ($targetType) {
            case 'all':
                // All users
                break;

            case 'role':
                if ($targetRoles) {
                    $query->whereIn('role', $targetRoles);
                }
                break;

            case 'location':
                if ($lgaId) {
                    $query->where('lga_id', $lgaId);
                } elseif ($stateId) {
                    $query->where('state_id', $stateId);
                } elseif ($countryId) {
                    $query->where('country_id', $countryId);
                }
                break;

            case 'specific':
                if ($specificUsers) {
                    $query->whereIn('id', $specificUsers);
                }
                break;
        }

        return $query->get();
    }

    /**
     * Send message to recipients
     */
    protected function sendMessage($bulkMessage, $recipients)
    {
        $successCount = 0;
        $failedCount = 0;

        $smsService = new \App\Services\SmsService();
        $emailService = new \App\Services\EmailService();

        foreach ($recipients as $user) {
            // Send via SMS
            if (in_array($bulkMessage->type, ['sms', 'both']) && $user->phone) {
                try {
                    $result = $smsService->send($user->phone, $bulkMessage->message);

                    BulkMessageLog::create([
                        'bulk_message_id' => $bulkMessage->id,
                        'user_id' => $user->id,
                        'channel' => 'sms',
                        'status' => $result['success'] ? 'sent' : 'failed',
                        'error_message' => $result['success'] ? null : ($result['error'] ?? 'Unknown error'),
                        'sent_at' => now(),
                    ]);

                    if ($result['success']) {
                        $successCount++;
                    } else {
                        $failedCount++;
                    }
                } catch (\Exception $e) {
                    BulkMessageLog::create([
                        'bulk_message_id' => $bulkMessage->id,
                        'user_id' => $user->id,
                        'channel' => 'sms',
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                        'sent_at' => now(),
                    ]);
                    $failedCount++;
                }
            }

            // Send via Email
            if (in_array($bulkMessage->type, ['email', 'both']) && $user->email) {
                try {
                    $result = $emailService->send(
                        $user->email,
                        $bulkMessage->title,
                        $bulkMessage->message
                    );

                    BulkMessageLog::create([
                        'bulk_message_id' => $bulkMessage->id,
                        'user_id' => $user->id,
                        'channel' => 'email',
                        'status' => $result['success'] ? 'sent' : 'failed',
                        'error_message' => $result['success'] ? null : ($result['error'] ?? 'Unknown error'),
                        'sent_at' => now(),
                    ]);

                    if ($result['success']) {
                        $successCount++;
                    } else {
                        $failedCount++;
                    }
                } catch (\Exception $e) {
                    BulkMessageLog::create([
                        'bulk_message_id' => $bulkMessage->id,
                        'user_id' => $user->id,
                        'channel' => 'email',
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                        'sent_at' => now(),
                    ]);
                    $failedCount++;
                }
            }
        }

        // Update bulk message status
        $bulkMessage->update([
            'status' => 'sent',
            'sent_at' => now(),
            'sent_count' => $successCount,
            'failed_count' => $failedCount,
        ]);
    }
}