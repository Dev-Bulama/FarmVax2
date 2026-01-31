<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * Show the chatbot dashboard with active conversations
     */
    public function index()
    {
        $activeConversations = ChatbotConversation::with(['user', 'messages', 'admin'])
            ->where('status', 'active')
            ->orderByRaw('human_requested DESC, human_takeover DESC, updated_at DESC')
            ->paginate(20);

        $humanRequests = ChatbotConversation::humanRequested()->count();
        $activeTakeovers = ChatbotConversation::activeTakeovers()->count();

        return view('admin.chatbot.index', compact('activeConversations', 'humanRequests', 'activeTakeovers'));
    }

    /**
     * Show a specific conversation
     */
    public function show($id)
    {
        $conversation = ChatbotConversation::with(['user', 'messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'admin'])->findOrFail($id);

        return view('admin.chatbot.show', compact('conversation'));
    }

    /**
     * Take over a conversation from AI
     */
    public function takeover(Request $request, $id)
    {
        $conversation = ChatbotConversation::findOrFail($id);

        $conversation->update([
            'human_takeover' => true,
            'human_takeover_at' => now(),
            'handled_by_admin_id' => Auth::id(),
        ]);

        // Send a message to the user
        ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => 'Hello! I\'m a human agent from FarmVax. I\'ll be assisting you now. How can I help?',
            'sender_type' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'You have taken over this conversation'
        ]);
    }

    /**
     * Release conversation back to AI
     */
    public function release(Request $request, $id)
    {
        $conversation = ChatbotConversation::findOrFail($id);

        $conversation->update([
            'human_takeover' => false,
            'human_requested' => false,
            'handled_by_admin_id' => null,
        ]);

        // Send a message to the user
        ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => 'This conversation has been returned to AI assistance. You can continue chatting with our AI assistant.',
            'sender_type' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Conversation released back to AI'
        ]);
    }

    /**
     * Send a message as admin
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $conversation = ChatbotConversation::findOrFail($id);

        if (!$conversation->human_takeover) {
            return response()->json([
                'success' => false,
                'error' => 'You must take over the conversation first'
            ], 403);
        }

        $message = ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
            'sender_type' => 'admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get new messages for a conversation (polling endpoint)
     */
    public function getMessages(Request $request, $id)
    {
        $lastId = $request->get('last_id', 0);

        $messages = ChatbotMessage::where('conversation_id', $id)
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->with('user')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Get count of new human requests (for notifications)
     */
    public function getHumanRequests()
    {
        $count = ChatbotConversation::humanRequested()->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Close a conversation
     */
    public function close($id)
    {
        $conversation = ChatbotConversation::findOrFail($id);

        $conversation->update([
            'status' => 'closed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Conversation closed'
        ]);
    }
}
