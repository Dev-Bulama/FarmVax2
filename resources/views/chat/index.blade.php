@extends('layouts.admin')

@section('title', 'Live Chat')
@section('page-title', 'Live Chat')

@push('styles')
<style>
    .chat-container { height: calc(100vh - 200px); }
    .chat-list { height: 100%; overflow-y: auto; }
    .chat-messages { height: calc(100% - 60px); overflow-y: auto; }
</style>
@endpush

@section('content')
<div class="grid grid-cols-12 gap-4 chat-container">
    <!-- Conversations List -->
    <div class="col-span-4 bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Messages</h3>
                <button onclick="startNewChat()" class="text-green-600 hover:text-green-700">
                    <i class="fas fa-plus-circle text-xl"></i>
                </button>
            </div>
            <input type="text" placeholder="Search conversations..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
        </div>
        <div class="chat-list p-2" id="conversations-list">
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-comments text-4xl mb-2"></i>
                <p>No conversations yet</p>
            </div>
        </div>
    </div>

    <!-- Chat Window -->
    <div class="col-span-8 bg-white rounded-lg shadow flex flex-col">
        <div id="no-chat-selected" class="flex-1 flex items-center justify-center text-gray-500">
            <div class="text-center">
                <i class="fas fa-comment-dots text-6xl mb-4"></i>
                <p>Select a conversation to start chatting</p>
            </div>
        </div>

        <div id="chat-window" class="hidden flex-1 flex flex-col">
            <!-- Chat Header -->
            <div class="p-4 border-b flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-user text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold" id="chat-user-name">User Name</h3>
                        <p class="text-xs text-gray-500">Online</p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-phone"></i>
                    </button>
                    <button class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-video"></i>
                    </button>
                    <button class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>

            <!-- Messages -->
            <div class="chat-messages p-4 bg-gray-50" id="messages-container">
                <!-- Messages will be loaded here -->
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t bg-white">
                <form id="message-form" class="flex items-end space-x-2">
                    <button type="button" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-paperclip text-xl"></i>
                    </button>
                    <button type="button" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-image text-xl"></i>
                    </button>
                    <textarea id="message-input" rows="1" placeholder="Type a message..." 
                              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg resize-none"></textarea>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function startNewChat() {
        alert('Start new chat functionality coming soon!');
    }

    function loadConversations() {
        // Load conversations via AJAX
        fetch('/api/chat/conversations')
            .then(response => response.json())
            .then(data => {
                // Populate conversations list
                console.log('Conversations:', data);
            });
    }

    function selectConversation(id) {
        document.getElementById('no-chat-selected').classList.add('hidden');
        document.getElementById('chat-window').classList.remove('hidden');
        
        // Load messages
        fetch(`/api/chat/conversations/${id}`)
            .then(response => response.json())
            .then(data => {
                console.log('Conversation:', data);
                // Populate messages
            });
    }

    // Load conversations on page load
    loadConversations();
</script>
@endpush
@endsection
