@extends('layouts.admin')

@section('title', 'Chatbot Conversations')
@section('page-title', 'Chatbot Conversations')

@section('content')

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Pending Human Requests -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Waiting for Human</p>
                <h3 class="text-3xl font-bold mt-2" id="human-requests-count">{{ $humanRequests }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Takeovers -->
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium">Active Takeovers</p>
                <h3 class="text-3xl font-bold mt-2">{{ $activeTakeovers }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Active -->
    <div class="bg-gradient-to-r from-[#11455B] to-[#0d3345] rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-200 text-sm font-medium">Total Active Chats</p>
                <h3 class="text-3xl font-bold mt-2">{{ $activeConversations->total() }}</h3>
            </div>
            <div class="bg-white bg-opacity-20 rounded-full p-3">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Conversations List -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-800">Active Conversations</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Message</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Handler</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($activeConversations as $conversation)
                <tr class="hover:bg-gray-50 {{ $conversation->human_requested && !$conversation->human_takeover ? 'bg-red-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-[#11455B] text-white rounded-full flex items-center justify-center font-semibold">
                                {{ substr($conversation->user->name ?? 'Guest', 0, 2) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $conversation->user->name ?? 'Guest User' }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $conversation->user->email ?? $conversation->session_id }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($conversation->human_requested && !$conversation->human_takeover)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 animate-pulse">
                                🔔 Requesting Human
                            </span>
                        @elseif($conversation->human_takeover)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                👤 Human Handling
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                🤖 AI Active
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            {{ Str::limit($conversation->messages->last()?->message ?? 'No messages', 50) }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $conversation->updated_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($conversation->admin)
                            <span class="text-purple-600 font-medium">
                                {{ $conversation->admin->name }}
                            </span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.chatbot.show', $conversation->id) }}"
                           class="inline-flex items-center px-3 py-1 bg-[#11455B] text-white rounded-lg hover:bg-[#0d3345] transition">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <p class="mt-4 text-lg font-medium">No active conversations</p>
                        <p class="mt-1 text-sm">Conversations will appear here when users start chatting</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($activeConversations->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $activeConversations->links() }}
    </div>
    @endif
</div>

<!-- Notification Sound -->
<audio id="notification-sound" preload="auto">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEARKwAAIhYAQACABAAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZizcIGmm98OifTwwNUqjk87ZjHAU3ktjyzXouBSl+zPLajzkJE1+18OytWBkKSKXh8sFuJAUrhM/z3Is5CBtqvvHpo1IPC1Go5fO3ZBwGOJPZ8s98LwYpfs/y3I4+CRZftfDrr1kaCkmm4vPDcCYGK4XQ89yMOggcbL/x6qZQDg1TqOX0uGUcBjmV2vPRgDEHKoDQ8t6OPwsYYLjv7LJaCgpJpuL0xHImByyG0fPejjoIHGy/8OukUQ4OVqnm9L" +
    "lnHQY6ltv00YExByqB0PPfjz8LGmG48OyzWwsLS6fj9MdyJwUshdL03I46ChxuwPHso1AODlap5vW6aBwGO5fc9NKBMQYqgtH04I9BCxtiu/HtuVsLDEyo4/XJcygGLojT9N+PPAseXr/y7q1bDBBaquf1u2kd" +
    "Bjyd3fXTgjIGK4PU9eGPQwwaY7zy7rxcCQ1Lp+P1yXMoBi6I0/Xhjz0LHl+/8++uXAwPW6vo9b1qHQY8nt72" +
    "1YMzByyE1PXjkEMNHGO88u+9XQkOS6jk9st0KQcvitT24JA9Cx5" +
    "gv/LwsF4MD1ur6PW9ax0GPJ/e9tWDMwcshNX15JBDDRtlvPPvvl0KD0yo5PbLdCkHL4rV9uGRPw0fYsHy8bJeDw9crOr1vmseBz6h3/fWhDQHLIXW9eWSRAweZr71" +
    "78BfChBNqeT2zHUpBy+K1fbikcANH2PB8vGyXg8PXKzq9sBuHwc+od/31oQ0By2F1vbmk0QNHGW+9fDBYAoQT" +
    "avl9cx1KggvjNb24pHADh9jwvLysV4PD12t6/fCbiAGPqLf+NeFNQcthdf25pNFDRtlvvXwwWAKEE+q5vbNdSkH" +
    "L4zW9uSTRA0cZb718MJgCxBQquf2z3YpCDCN1/bjkz8OIGPD8/O0XxAPXq7s98RvIAY+ouD41oU1By2G1/b" +
    "nlEUNGmW+9/DCYAoRT6rn9891KQcwjNf35JRFDQ1iw/P0tGAQD1+u7PfEbyEGP6Pg+NeGNQgtiNf35pRGDRpnv/" +
    "j" wvmchBCF61vj52LhgEA9fruz4xnAhBz+k4frYhzYILorY+OeWSQ0aZ7/4869nIQQietf4+di4YBAP" +
    "YK7s+MdwIwc/pOH62Yc2CC6L2fn" +
    "onEgNGWe/+POwZyIEInrX+PrZuWAQD2Ct7fDIdCMHP6Th+tiH">
</audio>

<script>
// Poll for new human requests every 10 seconds
setInterval(function() {
    fetch('{{ route('admin.chatbot.human-requests') }}')
        .then(response => response.json())
        .then(data => {
            const oldCount = parseInt(document.getElementById('human-requests-count').textContent);
            const newCount = data.count;

            // Update count
            document.getElementById('human-requests-count').textContent = newCount;

            // Play sound if new requests
            if (newCount > oldCount && newCount > 0) {
                const sound = document.getElementById('notification-sound');
                sound.play().catch(e => console.log('Could not play sound:', e));

                // Show browser notification
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('New Human Request', {
                        body: `${newCount} user(s) requesting human assistance`,
                        icon: '/favicon.ico',
                        tag: 'human-request'
                    });
                }
            }
        });
}, 10000); // Poll every 10 seconds

// Request notification permission on page load
if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
}
</script>

@endsection
