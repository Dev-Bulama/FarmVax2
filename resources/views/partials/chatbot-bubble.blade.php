@php
    $aiEnabled = \App\Models\Setting::get('ai_enabled', false);
@endphp

@if($aiEnabled)
<!-- AI Chatbot Bubble -->
<div id="chatbot-container" class="fixed bottom-6 right-6 z-50">
    
    <!-- Chat Window -->
    <div id="chat-window" class="hidden mb-4 w-96 h-[500px] bg-white rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-200">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-semibold">FarmVax Assistant</h3>
                    <p class="text-white/80 text-xs">Ask me anything about livestock</p>
                </div>
            </div>
            <button onclick="toggleChat()" class="text-white hover:bg-white/20 rounded-full p-1 transition">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Messages Container -->
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
            <!-- Welcome Message -->
            <div class="flex items-start space-x-2">
                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-lg rounded-tl-none p-3 shadow-sm max-w-[80%]">
                    <p class="text-sm text-gray-800">Hello! 👋 I'm your FarmVax AI assistant. I can help you with livestock vaccination, disease prevention, and farm management. How can I help you today?</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-gray-200">
            <form id="chat-form" class="flex items-center space-x-2">
                <input type="text" id="chat-input" placeholder="Type your message..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                       autocomplete="off">
                <button type="submit" id="send-button" class="bg-purple-600 text-white rounded-full p-2 hover:bg-purple-700 transition">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
            <p class="text-xs text-gray-500 mt-2 text-center">Powered by AI • Not a substitute for professional advice</p>
        </div>
    </div>

    <!-- Floating Button -->
    <button id="chat-bubble" onclick="toggleChat()" 
            class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-2xl hover:shadow-purple-500/50 hover:scale-110 transition-all duration-300 relative">
        <svg id="bubble-icon" class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
        <span id="notification-dot" class="hidden absolute top-0 right-0 w-4 h-4 bg-red-500 rounded-full border-2 border-white"></span>
    </button>
</div>

<script>
    let chatOpen = false;
    let conversationHistory = [];

    function toggleChat() {
        chatOpen = !chatOpen;
        const chatWindow = document.getElementById('chat-window');
        const chatBubble = document.getElementById('chat-bubble');
        
        if (chatOpen) {
            chatWindow.classList.remove('hidden');
            chatBubble.classList.add('scale-0');
            setTimeout(() => chatBubble.classList.add('hidden'), 200);
            document.getElementById('chat-input').focus();
        } else {
            chatWindow.classList.add('hidden');
            chatBubble.classList.remove('hidden');
            setTimeout(() => chatBubble.classList.remove('scale-0'), 10);
        }
    }

    // Handle form submission
    // document.getElementById('chat-form').addEventListener('submit', async function(e) {
    //     e.preventDefault();
        
    //     const input = document.getElementById('chat-input');
    //     const message = input.value.trim();
        
    //     if (!message) return;
        
    //     // Add user message to chat
    //     addMessage(message, 'user');
    //     input.value = '';
        
    //     // Show typing indicator
    //     const typingId = showTypingIndicator();
        
    //     try {
    //         // Send to AI API
    //         const response = await fetch('/api/ai/chat', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    //             },
    //             body: JSON.stringify({
    //                 message: message,
    //                 conversation_history: conversationHistory
    //             })
    //         });
            
    //         const data = await response.json();
            
    //         // Remove typing indicator
    //         removeTypingIndicator(typingId);
            
    //         if (data.success) {
    //             // Add AI response to chat
    //             addMessage(data.response, 'ai');
                
    //             // Update conversation history
    //             conversationHistory.push({
    //                 role: 'user',
    //                 content: message
    //             });
    //             conversationHistory.push({
    //                 role: 'assistant',
    //                 content: data.response
    //             });
                
    //             // Keep only last 10 messages
    //             if (conversationHistory.length > 20) {
    //                 conversationHistory = conversationHistory.slice(-20);
    //             }
    //         } else {
    //             addMessage('Sorry, I encountered an error. Please try again.', 'ai');
    //         }
    //     } catch (error) {
    //         removeTypingIndicator(typingId);
    //         addMessage('Sorry, I\'m having trouble connecting. Please try again later.', 'ai');
    //         console.error('Chat error:', error);
    //     }
    // });
    // Handle form submission
document.getElementById('chat-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const input = document.getElementById('chat-input');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Add user message to chat
    addMessage(message, 'user');
    input.value = '';
    
    // Show typing indicator
    const typingId = showTypingIndicator();
    
    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            throw new Error('CSRF token not found. Please refresh the page.');
        }
        
        // Send to AI API
        const response = await fetch('/api/ai/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                message: message,
                conversation_history: conversationHistory
            })
        });
        
        // Remove typing indicator
        removeTypingIndicator(typingId);
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.error || 'Server error');
        }
        
        const data = await response.json();
        
        if (data.success && data.response) {
            // Add AI response to chat
            addMessage(data.response, 'ai');
            
            // Update conversation history
            conversationHistory.push({
                role: 'user',
                content: message
            });
            conversationHistory.push({
                role: 'assistant',
                content: data.response
            });
            
            // Keep only last 20 messages
            if (conversationHistory.length > 20) {
                conversationHistory = conversationHistory.slice(-20);
            }
        } else {
            addMessage(data.error || 'Sorry, I encountered an error. Please try again.', 'ai');
        }
    } catch (error) {
        removeTypingIndicator(typingId);
        console.error('Chat error:', error);
        addMessage('Error: ' + error.message, 'ai');
    }
});

    function addMessage(text, type) {
        const messagesContainer = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        
        if (type === 'user') {
            messageDiv.className = 'flex items-start space-x-2 justify-end';
            messageDiv.innerHTML = `
                <div class="bg-purple-600 text-white rounded-lg rounded-tr-none p-3 shadow-sm max-w-[80%]">
                    <p class="text-sm">${escapeHtml(text)}</p>
                </div>
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            `;
        } else {
            messageDiv.className = 'flex items-start space-x-2';
            messageDiv.innerHTML = `
                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div class="bg-white rounded-lg rounded-tl-none p-3 shadow-sm max-w-[80%]">
                    <p class="text-sm text-gray-800">${escapeHtml(text)}</p>
                </div>
            `;
        }
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showTypingIndicator() {
        const messagesContainer = document.getElementById('chat-messages');
        const typingDiv = document.createElement('div');
        const id = 'typing-' + Date.now();
        typingDiv.id = id;
        typingDiv.className = 'flex items-start space-x-2';
        typingDiv.innerHTML = `
            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div class="bg-white rounded-lg rounded-tl-none p-3 shadow-sm">
                <div class="flex space-x-1">
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                </div>
            </div>
        `;
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        return id;
    }

    function removeTypingIndicator(id) {
        const typingDiv = document.getElementById(id);
        if (typingDiv) {
            typingDiv.remove();
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>

<style>
    #chatbot-container {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    
    #chat-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    #chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #chat-messages::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }
    
    #chat-messages::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px);
        }
    }
    
    .animate-bounce {
        animation: bounce 1s infinite;
    }
</style>
@endif