@extends('layouts.admin')

@section('title', 'Chat Conversation')
@section('page-title', 'Chat with ' . ($conversation->user->name ?? 'Guest'))

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Chat Area -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col" style="height: calc(100vh - 200px);">
            <!-- Chat Header -->
            <div class="bg-gradient-to-r from-[#11455B] to-[#0d3345] text-white p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center font-semibold">
                            {{ substr($conversation->user->name ?? 'Guest', 0, 2) }}
                        </div>
                        <div>
                            <h3 class="font-semibold">{{ $conversation->user->name ?? 'Guest User' }}</h3>
                            <p class="text-xs text-gray-200">
                                @if($conversation->human_takeover)
                                    👤 Handled by {{ $conversation->admin->name ?? 'Admin' }}
                                @elseif($conversation->human_requested)
                                    🔔 Requesting Human Assistance
                                @else
                                    🤖 AI Assistant Active
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        @if(!$conversation->human_takeover)
                            <button onclick="takeoverConversation()"
                                    class="px-4 py-2 bg-white text-[#11455B] rounded-lg hover:bg-gray-100 transition font-medium text-sm">
                                Take Over
                            </button>
                        @else
                            <button onclick="releaseConversation()"
                                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium text-sm">
                                Release to AI
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
                @foreach($conversation->messages as $message)
                    <div class="flex {{ $message->sender_type === 'user' ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                            @if($message->sender_type === 'user')
                                <!-- User Message -->
                                <div class="bg-white rounded-lg shadow p-3">
                                    <p class="text-sm text-gray-800">{{ $message->message }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('H:i') }}</p>
                                </div>
                            @elseif($message->sender_type === 'bot')
                                <!-- Bot Message -->
                                <div class="bg-purple-100 rounded-lg p-3">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <svg class="h-4 w-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-purple-600">AI Assistant</span>
                                    </div>
                                    <p class="text-sm text-gray-800">{!! nl2br(e($message->message)) !!}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->format('H:i') }}</p>
                                </div>
                            @else
                                <!-- Admin Message -->
                                <div class="bg-[#11455B] text-white rounded-lg p-3">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-xs font-medium">{{ $message->user->name ?? 'Admin' }}</span>
                                    </div>
                                    <p class="text-sm">{{ $message->message }}</p>
                                    <p class="text-xs text-gray-300 mt-1">{{ $message->created_at->format('H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Message Input (only if human takeover is active) -->
            @if($conversation->human_takeover)
            <div class="border-t border-gray-200 p-4 bg-white">
                <form id="send-message-form" class="flex space-x-2">
                    <input type="text" id="message-input"
                           placeholder="Type your message..."
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                           required>
                    <button type="submit"
                            class="px-6 py-2 bg-[#11455B] text-white rounded-lg hover:bg-[#0d3345] transition font-medium">
                        Send
                    </button>
                </form>
            </div>
            @else
            <div class="border-t border-gray-200 p-4 bg-yellow-50">
                <p class="text-sm text-yellow-800 text-center">
                    <strong>Take over this conversation</strong> to start sending messages as a human agent
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Conversation Info Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Conversation Details</h3>

            <!-- User Info -->
            <div>
                <p class="text-xs text-gray-500 uppercase mb-1">User</p>
                <p class="text-sm font-medium">{{ $conversation->user->name ?? 'Guest User' }}</p>
                <p class="text-xs text-gray-500">{{ $conversation->user->email ?? 'No email' }}</p>
                <p class="text-xs text-gray-500">Role: {{ ucfirst($conversation->user->role ?? 'Guest') }}</p>
            </div>

            <!-- Status -->
            <div>
                <p class="text-xs text-gray-500 uppercase mb-1">Status</p>
                @if($conversation->human_requested && !$conversation->human_takeover)
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">🔔 Requesting Human</span>
                @elseif($conversation->human_takeover)
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-medium">👤 Human Handling</span>
                @else
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">🤖 AI Active</span>
                @endif
            </div>

            <!-- Handler -->
            @if($conversation->admin)
            <div>
                <p class="text-xs text-gray-500 uppercase mb-1">Handled By</p>
                <p class="text-sm font-medium">{{ $conversation->admin->name }}</p>
                <p class="text-xs text-gray-500">{{ $conversation->human_takeover_at?->diffForHumans() }}</p>
            </div>
            @endif

            <!-- Timestamps -->
            <div>
                <p class="text-xs text-gray-500 uppercase mb-1">Started</p>
                <p class="text-sm">{{ $conversation->created_at->format('M d, Y H:i') }}</p>
                <p class="text-xs text-gray-500">{{ $conversation->created_at->diffForHumans() }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase mb-1">Last Activity</p>
                <p class="text-sm">{{ $conversation->updated_at->format('M d, Y H:i') }}</p>
                <p class="text-xs text-gray-500">{{ $conversation->updated_at->diffForHumans() }}</p>
            </div>

            <!-- Message Count -->
            <div>
                <p class="text-xs text-gray-500 uppercase mb-1">Total Messages</p>
                <p class="text-sm font-medium">{{ $conversation->messages->count() }}</p>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t space-y-2">
                <button onclick="closeConversation()"
                        class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm">
                    Close Conversation
                </button>
                <a href="{{ route('admin.chatbot.index') }}"
                   class="w-full block text-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<script>
const conversationId = {{ $conversation->id }};
let lastMessageId = {{ $conversation->messages->last()?->id ?? 0 }};
let isHumanTakeover = {{ $conversation->human_takeover ? 'true' : 'false' }};

// Scroll to bottom of messages
function scrollToBottom() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
}

scrollToBottom();

// Take over conversation
function takeoverConversation() {
    if (!confirm('Take over this conversation? The AI will stop responding.')) return;

    fetch(`/admin/chatbot/${conversationId}/takeover`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    });
}

// Release conversation back to AI
function releaseConversation() {
    if (!confirm('Release this conversation back to AI?')) return;

    fetch(`/admin/chatbot/${conversationId}/release`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    });
}

// Send message as admin
@if($conversation->human_takeover)
document.getElementById('send-message-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const input = document.getElementById('message-input');
    const message = input.value.trim();

    if (!message) return;

    fetch(`/admin/chatbot/${conversationId}/send`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            pollMessages(); // Immediately poll for new messages
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    });
});
@endif

// Poll for new messages
function pollMessages() {
    fetch(`/admin/chatbot/${conversationId}/messages?last_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(message => {
                    addMessageToUI(message);
                    lastMessageId = message.id;
                });
                scrollToBottom();
            }
        });
}

// Add message to UI
function addMessageToUI(message) {
    const container = document.getElementById('messages-container');
    const messageDiv = document.createElement('div');

    const isUser = message.sender_type === 'user';
    const isBot = message.sender_type === 'bot';
    const isAdmin = message.sender_type === 'admin';

    messageDiv.className = `flex ${isUser ? 'justify-start' : 'justify-end'}`;

    const time = new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

    if (isUser) {
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                <div class="bg-white rounded-lg shadow p-3">
                    <p class="text-sm text-gray-800">${escapeHtml(message.message)}</p>
                    <p class="text-xs text-gray-500 mt-1">${time}</p>
                </div>
            </div>
        `;
    } else if (isBot) {
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                <div class="bg-purple-100 rounded-lg p-3">
                    <div class="flex items-center space-x-2 mb-1">
                        <svg class="h-4 w-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                        </svg>
                        <span class="text-xs font-medium text-purple-600">AI Assistant</span>
                    </div>
                    <p class="text-sm text-gray-800">${escapeHtml(message.message).replace(/\n/g, '<br>')}</p>
                    <p class="text-xs text-gray-500 mt-1">${time}</p>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md xl:max-w-lg">
                <div class="bg-[#11455B] text-white rounded-lg p-3">
                    <div class="flex items-center space-x-2 mb-1">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs font-medium">${message.user?.name || 'Admin'}</span>
                    </div>
                    <p class="text-sm">${escapeHtml(message.message)}</p>
                    <p class="text-xs text-gray-300 mt-1">${time}</p>
                </div>
            </div>
        `;
    }

    container.appendChild(messageDiv);
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Poll every 3 seconds
setInterval(pollMessages, 3000);

// Close conversation
function closeConversation() {
    if (!confirm('Close this conversation? This cannot be undone.')) return;

    fetch(`/admin/chatbot/${conversationId}/close`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route('admin.chatbot.index') }}';
        } else {
            alert('Error: ' + (data.error || 'Unknown error'));
        }
    });
}
</script>

@endsection
