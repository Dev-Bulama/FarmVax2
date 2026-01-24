<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BulkMessage;
use App\Models\BulkMessageLog;
use App\Models\User;
use Illuminate\Http\Request;

class BulkMessageController extends Controller
{
    public function index()
    {
        $messages = BulkMessage::with('creator')->latest()->paginate(20);

        $stats = [
            'total' => BulkMessage::count(),
            'sent' => BulkMessage::where('status', 'sent')->count(),
            'pending' => BulkMessage::where('status', 'draft')->count(),
            'total_recipients' => BulkMessage::sum('total_recipients'),
        ];

        return view('admin.bulk-messages.index', compact('messages', 'stats'));
    }

    public function create()
    {
        return view('admin.bulk-messages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:email,sms,both',
            'target_roles' => 'nullable|array',
            'target_locations' => 'nullable|array',
            'specific_users' => 'nullable|array',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = $request->has('send_now') ? 'sending' : 'draft';

        // Calculate recipients
        $recipients = $this->getRecipients(
            $validated['target_roles'] ?? null,
            $validated['target_locations'] ?? null,
            $validated['specific_users'] ?? null
        );

        $validated['total_recipients'] = $recipients->count();

        $bulkMessage = BulkMessage::create($validated);

        // If sending now, queue the messages
        if ($request->has('send_now')) {
            $this->queueMessages($bulkMessage, $recipients);
        }

        return redirect()->route('admin.bulk-messages.index')
            ->with('success', 'Bulk message created successfully');
    }

    public function edit($id)
    {
        $message = BulkMessage::findOrFail($id);

        if ($message->status !== 'draft') {
            return redirect()->route('admin.bulk-messages.index')
                ->with('error', 'Can only edit draft messages');
        }

        return view('admin.bulk-messages.edit', compact('message'));
    }

    public function update(Request $request, $id)
    {
        $message = BulkMessage::findOrFail($id);

        if ($message->status !== 'draft') {
            return redirect()->route('admin.bulk-messages.index')
                ->with('error', 'Can only edit draft messages');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:email,sms,both',
            'target_roles' => 'nullable|array',
            'target_locations' => 'nullable|array',
            'specific_users' => 'nullable|array',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        // Recalculate recipients
        $recipients = $this->getRecipients(
            $validated['target_roles'] ?? null,
            $validated['target_locations'] ?? null,
            $validated['specific_users'] ?? null
        );

        $validated['total_recipients'] = $recipients->count();

        $message->update($validated);

        return redirect()->route('admin.bulk-messages.index')
            ->with('success', 'Bulk message updated successfully');
    }

    public function send($id)
    {
        $message = BulkMessage::findOrFail($id);

        if ($message->status !== 'draft') {
            return back()->with('error', 'Message already sent or sending');
        }

        $recipients = $this->getRecipients(
            $message->target_roles,
            $message->target_locations,
            $message->specific_users
        );

        $this->queueMessages($message, $recipients);

        $message->update([
            'status' => 'sending',
            'sent_at' => now()
        ]);

        return redirect()->route('admin.bulk-messages.index')
            ->with('success', 'Messages are being sent');
    }

    public function logs($id)
    {
        $message = BulkMessage::with(['logs.user'])->findOrFail($id);

        return view('admin.bulk-messages.logs', compact('message'));
    }

    private function getRecipients($roles = null, $locations = null, $specificUsers = null)
    {
        $query = User::where('account_status', 'active');

        if ($specificUsers && count($specificUsers) > 0) {
            return $query->whereIn('id', $specificUsers)->get();
        }

        if ($roles && count($roles) > 0) {
            $query->whereIn('role', $roles);
        }

        if ($locations && count($locations) > 0) {
            $stateIds = array_column($locations, 'state_id');
            if (!empty($stateIds)) {
                $query->whereIn('state_id', $stateIds);
            }
        }

        return $query->get();
    }

    private function queueMessages(BulkMessage $bulkMessage, $recipients)
    {
        foreach ($recipients as $user) {
            // Create email log
            if (in_array($bulkMessage->type, ['email', 'both']) && $user->email) {
                BulkMessageLog::create([
                    'bulk_message_id' => $bulkMessage->id,
                    'user_id' => $user->id,
                    'channel' => 'email',
                    'status' => 'pending'
                ]);

                // Queue email job
                // dispatch(new SendBulkEmail($user, $bulkMessage));
            }

            // Create SMS log
            if (in_array($bulkMessage->type, ['sms', 'both']) && $user->phone) {
                BulkMessageLog::create([
                    'bulk_message_id' => $bulkMessage->id,
                    'user_id' => $user->id,
                    'channel' => 'sms',
                    'status' => 'pending'
                ]);

                // Queue SMS job
                // dispatch(new SendBulkSMS($user, $bulkMessage));
            }
        }
    }
}
