<!-- Floating Chat Bubble -->
<div id="chat-bubble-container" class="fixed bottom-6 right-6 z-50" x-data="{ open: false, unreadCount: 0 }">
    <!-- Chat Button -->
    <button @click="open = !open" class="relative w-16 h-16 bg-gradient-to-r from-primary to-secondary rounded-full shadow-2xl hover:scale-110 transition-transform duration-200 flex items-center justify-center">
        <i class="fas fa-comments text-white text-2xl"></i>
        <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center" x-text="unreadCount"></span>
    </button>

    <!-- Chat Window -->
    <div x-show="open" @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="absolute bottom-20 right-0 w-96 h-[500px] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden">

        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-primary to-secondary p-4 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-robot text-primary"></i>
                </div>
                <div>
                    <h3 class="font-bold text-white">FarmVax AI Assistant</h3>
                    <p class="text-xs text-gray-200 flex items-center">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-1"></span>
                        Online
                    </p>
                </div>
            </div>
            <button @click="open = false" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Messages -->
        <div id="chat-bubble-messages" class="flex-1 p-4 bg-gray-50 overflow-y-auto space-y-3">
            <!-- Welcome Message -->
            <div class="flex justify-start">
                <div class="bg-white rounded-2xl rounded-tl-none p-3 max-w-xs shadow-sm">
                    <p class="text-sm text-gray-800">Hi! I'm your FarmVax AI assistant. How can I help you today?</p>
                    <span class="text-xs text-gray-500 mt-1 block">Just now</span>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="p-3 bg-white border-t border-gray-200">
            <form id="chat-bubble-form" class="flex items-center space-x-2">
                <input type="text" id="chat-bubble-input" placeholder="Ask me anything..."
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-secondary focus:border-transparent text-sm">
                <button type="submit" class="w-10 h-10 bg-secondary text-white rounded-full flex items-center justify-center hover:bg-secondary/90 transition">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Chat bubble AI functionality
document.getElementById('chat-bubble-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('chat-bubble-input');
    const message = input.value.trim();

    if (!message) return;

    const messagesContainer = document.getElementById('chat-bubble-messages');

    // Add user message
    const userMessage = document.createElement('div');
    userMessage.className = 'flex justify-end';
    userMessage.innerHTML = '<div class="bg-secondary text-white rounded-2xl rounded-tr-none p-3 max-w-xs"><p class="text-sm">' + message + '</p><span class="text-xs opacity-75 mt-1 block">Just now</span></div>';
    messagesContainer.appendChild(userMessage);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    input.value = '';

    // Show typing indicator
    const typingIndicator = document.createElement('div');
    typingIndicator.className = 'flex justify-start';
    typingIndicator.id = 'typing-indicator';
    typingIndicator.innerHTML = '<div class="bg-white rounded-2xl p-3 shadow-sm"><div class="flex space-x-1"><div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div><div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div><div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div></div></div>';
    messagesContainer.appendChild(typingIndicator);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Call AI API (replace with your actual API endpoint)
    fetch('/api/ai/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        // Remove typing indicator
        document.getElementById('typing-indicator')?.remove();

        // Add AI response
        const aiMessage = document.createElement('div');
        aiMessage.className = 'flex justify-start';
        aiMessage.innerHTML = '<div class="bg-white rounded-2xl rounded-tl-none p-3 max-w-xs shadow-sm"><p class="text-sm text-gray-800">' + (data.response || 'Sorry, I could not process that request.') + '</p><span class="text-xs text-gray-500 mt-1 block">Just now</span></div>';
        messagesContainer.appendChild(aiMessage);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    })
    .catch(error => {
        // Remove typing indicator
        document.getElementById('typing-indicator')?.remove();

        // Show error or fallback message
        const aiMessage = document.createElement('div');
        aiMessage.className = 'flex justify-start';
        aiMessage.innerHTML = '<div class="bg-white rounded-2xl rounded-tl-none p-3 max-w-xs shadow-sm"><p class="text-sm text-gray-800">Thanks for your message! Our team will get back to you shortly.</p><span class="text-xs text-gray-500 mt-1 block">Just now</span></div>';
        messagesContainer.appendChild(aiMessage);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });
});
</script>
@endpush
