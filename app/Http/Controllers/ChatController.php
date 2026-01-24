<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Get all conversations for authenticated user
     */
    public function index()
    {
        $user = auth()->user();

        $conversations = ChatConversation::whereJsonContains('participants', $user->id)
            ->with(['participantUsers', 'latestMessage.sender'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        // Add unread counts
        foreach ($conversations as $conversation) {
            $conversation->unread_count = $conversation->getUnreadCountForUser($user->id);
        }

        return response()->json($conversations);
    }

    /**
     * Create a new conversation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_ids' => 'required|array|min:1',
            'participant_ids.*' => 'exists:users,id',
            'subject' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'conversation_type' => 'required|in:direct,group',
            'message' => 'nullable|string',
        ]);

        $userId = auth()->id();

        // Check if direct conversation already exists
        if ($validated['conversation_type'] === 'direct' && count($validated['participant_ids']) === 1) {
            $existingConversation = ChatConversation::where('conversation_type', 'direct')
                ->whereJsonContains('participants', $userId)
                ->whereJsonContains('participants', $validated['participant_ids'][0])
                ->first();

            if ($existingConversation) {
                return response()->json($existingConversation);
            }
        }

        // Add current user to participants
        $participants = array_merge([$userId], $validated['participant_ids']);
        $participants = array_unique($participants);

        $conversation = ChatConversation::create([
            'user_id' => $userId,
            'subject' => $validated['subject'] ?? null,
            'title' => $validated['title'] ?? null,
            'participants' => $participants,
            'conversation_type' => $validated['conversation_type'],
            'status' => 'open',
            'last_message_at' => now()
        ]);

        // Create participant records
        foreach ($participants as $participantId) {
            ChatParticipant::create([
                'conversation_id' => $conversation->id,
                'user_id' => $participantId,
                'joined_at' => now(),
                'is_admin' => $participantId === $userId
            ]);
        }

        // Send initial message if provided
        if (!empty($validated['message'])) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $userId,
                'message' => $validated['message'],
                'message_type' => 'text'
            ]);
        }

        return response()->json($conversation->load('participantUsers'), 201);
    }

    /**
     * Get a specific conversation
     */
    public function show($id)
    {
        $conversation = ChatConversation::with(['participantUsers', 'messages.sender', 'messages.reactions.user'])
            ->findOrFail($id);

        // Check if user is participant
        if (!$conversation->hasParticipant(auth()->id())) {
            abort(403, 'Unauthorized access to conversation');
        }

        // Mark as read
        $participant = $conversation->chatParticipants()
            ->where('user_id', auth()->id())
            ->first();

        if ($participant) {
            $participant->markAsRead();
        }

        return response()->json($conversation);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);

        // Check if user is participant
        if (!$conversation->hasParticipant(auth()->id())) {
            abort(403, 'Unauthorized access to conversation');
        }

        $validated = $request->validate([
            'message' => 'required_without:file|string',
            'message_type' => 'required|in:text,image,video,voice,file,emoji',
            'file' => 'required_if:message_type,image,video,voice,file|file|max:102400', // 100MB max
            'duration' => 'nullable|integer', // For voice/video
        ]);

        $messageData = [
            'conversation_id' => $conversationId,
            'sender_id' => auth()->id(),
            'message' => $validated['message'] ?? '',
            'message_type' => $validated['message_type'],
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('public/chat');

            $messageData['file_url'] = Storage::url($path);
            $messageData['file_type'] = $file->getMimeType();
            $messageData['file_size'] = $file->getSize();
            $messageData['attachment'] = $file->getClientOriginalName();

            // Add metadata for images (dimensions)
            if (str_starts_with($file->getMimeType(), 'image/')) {
                try {
                    $dimensions = getimagesize($file->getRealPath());
                    $messageData['metadata'] = [
                        'width' => $dimensions[0] ?? null,
                        'height' => $dimensions[1] ?? null,
                    ];
                } catch (\Exception $e) {
                    // Ignore if can't get dimensions
                }
            }
        }

        if (isset($validated['duration'])) {
            $messageData['duration'] = $validated['duration'];
        }

        $message = ChatMessage::create($messageData);

        // Update conversation's last message time
        $conversation->update(['last_message_at' => now()]);

        return response()->json($message->load('sender'), 201);
    }

    /**
     * Add reaction to a message
     */
    public function addReaction(Request $request, $messageId)
    {
        $validated = $request->validate([
            'emoji' => 'required|string|max:10'
        ]);

        $message = ChatMessage::findOrFail($messageId);

        // Check if user is participant
        if (!$message->conversation->hasParticipant(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $message->addReaction(auth()->id(), $validated['emoji']);

        return response()->json(['message' => 'Reaction added']);
    }

    /**
     * Remove reaction from a message
     */
    public function removeReaction($messageId)
    {
        $message = ChatMessage::findOrFail($messageId);

        // Check if user is participant
        if (!$message->conversation->hasParticipant(auth()->id())) {
            abort(403, 'Unauthorized');
        }

        $message->removeReaction(auth()->id());

        return response()->json(['message' => 'Reaction removed']);
    }

    /**
     * Add participant to conversation
     */
    public function addParticipant(Request $request, $conversationId)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $conversation = ChatConversation::findOrFail($conversationId);

        // Check if current user is admin
        $currentParticipant = $conversation->chatParticipants()
            ->where('user_id', auth()->id())
            ->first();

        if (!$currentParticipant || !$currentParticipant->is_admin) {
            abort(403, 'Only conversation admins can add participants');
        }

        $conversation->addParticipant($validated['user_id']);

        return response()->json(['message' => 'Participant added']);
    }

    /**
     * Remove participant from conversation
     */
    public function removeParticipant($conversationId, $userId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);

        // Check if current user is admin
        $currentParticipant = $conversation->chatParticipants()
            ->where('user_id', auth()->id())
            ->first();

        if (!$currentParticipant || !$currentParticipant->is_admin) {
            abort(403, 'Only conversation admins can remove participants');
        }

        $conversation->removeParticipant($userId);

        return response()->json(['message' => 'Participant removed']);
    }

    /**
     * Leave conversation
     */
    public function leave($conversationId)
    {
        $conversation = ChatConversation::findOrFail($conversationId);
        $conversation->removeParticipant(auth()->id());

        return response()->json(['message' => 'You have left the conversation']);
    }

    /**
     * Search users to start conversation with
     */
    public function searchUsers(Request $request)
    {
        $search = $request->get('q');
        $currentUserId = auth()->id();

        $users = User::where('id', '!=', $currentUserId)
            ->where('account_status', 'active')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->select('id', 'name', 'email', 'role')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $userId = auth()->id();

        $conversations = ChatConversation::whereJsonContains('participants', $userId)->get();

        $totalUnread = 0;
        foreach ($conversations as $conversation) {
            $totalUnread += $conversation->getUnreadCountForUser($userId);
        }

        return response()->json(['unread_count' => $totalUnread]);
    }
}
