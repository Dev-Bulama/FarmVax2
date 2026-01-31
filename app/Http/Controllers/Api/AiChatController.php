<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ChatbotTrainingData;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:1000',
                'conversation_history' => 'nullable|array',
            ]);

            // Check if AI is enabled
            $aiEnabled = Setting::get('ai_enabled', false);
            if (!$aiEnabled) {
                Log::error('AI is disabled in settings');
                return response()->json([
                    'success' => false,
                    'error' => 'AI chatbot is currently disabled'
                ], 503);
            }

            // Get AI settings
            $provider = Setting::get('ai_provider', 'openai');
            $apiKey = Setting::get('openai_api_key');
            
            Log::info('AI Chat Request', [
                'provider' => $provider,
                'has_api_key' => !empty($apiKey),
                'message' => $validated['message']
            ]);

            if (empty($apiKey)) {
                Log::error('No API key found');
                return response()->json([
                    'success' => false,
                    'error' => 'API key not configured. Please contact administrator.'
                ], 500);
            }

            // Check if user is requesting human assistance
            $humanRequested = $this->detectHumanRequest($validated['message']);

            if ($humanRequested) {
                // Mark conversation as requesting human
                $conversation = $this->getOrCreateConversation();

                if (!$conversation->human_requested && !$conversation->human_takeover) {
                    $conversation->update([
                        'human_requested' => true,
                        'human_requested_at' => now(),
                    ]);

                    // Send notification to admin
                    $this->notifyAdmin($conversation);

                    // Save user message
                    ChatbotMessage::create([
                        'conversation_id' => $conversation->id,
                        'user_id' => auth()->id(),
                        'message' => $validated['message'],
                        'sender_type' => 'user',
                    ]);

                    $response = "Thank you for your request! I've notified our team and a human agent will assist you shortly. Please wait for a moment while we connect you.";

                    // Save bot response
                    ChatbotMessage::create([
                        'conversation_id' => $conversation->id,
                        'message' => $response,
                        'sender_type' => 'bot',
                    ]);

                    return response()->json([
                        'success' => true,
                        'response' => $response,
                        'human_requested' => true
                    ]);
                }
            }

            // Check if conversation is in human takeover mode
            $conversation = $this->getOrCreateConversation();
            if ($conversation->human_takeover) {
                // Save user message and wait for admin response
                ChatbotMessage::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => auth()->id(),
                    'message' => $validated['message'],
                    'sender_type' => 'user',
                ]);

                return response()->json([
                    'success' => true,
                    'response' => 'Your message has been sent to our human agent. They will respond shortly.',
                    'human_takeover' => true
                ]);
            }

            $systemPrompt = Setting::get('ai_system_prompt', 'You are a helpful agricultural assistant for FarmVax.');

            // Add training data context
            $trainingContext = $this->getTrainingContext($validated['message']);
            if ($trainingContext) {
                $systemPrompt .= "\n\nRelevant information:\n" . $trainingContext;
            }

            // Call OpenAI
            $response = $this->callOpenAI($validated['message'], $systemPrompt, $validated['conversation_history'] ?? [], $apiKey);

            // Save conversation
            $this->saveConversation($validated['message'], $response);

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('AI Chat Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Sorry, I encountered an error: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function callOpenAI($message, $systemPrompt, $history, $apiKey)
    {
        $model = Setting::get('openai_model', 'gpt-4o-mini');
        $temperature = (float) Setting::get('ai_temperature', 0.7);
        $maxTokens = (int) Setting::get('ai_max_tokens', 1000);

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        
        // Add conversation history
        foreach ($history as $item) {
            $messages[] = $item;
        }
        
        $messages[] = ['role' => 'user', 'content' => $message];

        Log::info('Calling OpenAI API', [
            'model' => $model,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens
        ]);

        try {
            $response = Http::timeout(30)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => $temperature,
                'max_tokens' => $maxTokens,
            ]);

            if (!$response->successful()) {
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('OpenAI API error: ' . $response->body());
            }

            $data = $response->json();
            $responseText = $data['choices'][0]['message']['content'] ?? 'No response';
            
            Log::info('OpenAI Response received', [
                'length' => strlen($responseText)
            ]);
            
            return $responseText;
            
        } catch (\Exception $e) {
            Log::error('OpenAI Request Exception', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function getTrainingContext($message)
    {
        try {
            // Search training data for relevant context
            $keywords = explode(' ', strtolower($message));
            
            $trainingData = ChatbotTrainingData::where('is_active', true)
                ->where(function($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        if (strlen($keyword) > 3) {
                            $query->orWhere('content', 'like', '%' . $keyword . '%')
                                  ->orWhere('title', 'like', '%' . $keyword . '%');
                        }
                    }
                })
                ->limit(3)
                ->get();

            if ($trainingData->isEmpty()) {
                return null;
            }

            $context = '';
            foreach ($trainingData as $data) {
                $context .= "- " . $data->title . ": " . substr($data->content, 0, 200) . "...\n";
            }

            return $context;
        } catch (\Exception $e) {
            Log::error('Error getting training context: ' . $e->getMessage());
            return null;
        }
    }

    protected function saveConversation($userMessage, $aiResponse)
    {
        try {
            $conversation = $this->getOrCreateConversation();

            // Save messages
            ChatbotMessage::create([
                'conversation_id' => $conversation->id,
                'user_id' => auth()->id(),
                'message' => $userMessage,
                'sender_type' => 'user',
            ]);

            ChatbotMessage::create([
                'conversation_id' => $conversation->id,
                'message' => $aiResponse,
                'sender_type' => 'bot',
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving conversation: ' . $e->getMessage());
        }
    }

    protected function getOrCreateConversation()
    {
        $userId = auth()->id();

        return ChatbotConversation::firstOrCreate(
            [
                'user_id' => $userId,
                'status' => 'active',
            ],
            [
                'session_id' => session()->getId(),
            ]
        );
    }

    protected function detectHumanRequest($message)
    {
        $message = strtolower($message);

        // Keywords that indicate user wants human assistance
        $keywords = [
            'human',
            'real person',
            'live agent',
            'speak to someone',
            'talk to someone',
            'customer service',
            'representative',
            'operator',
            'human agent',
            'live chat',
            'speak to a person',
            'talk to a person',
            'real human',
            'actual person',
            'not a bot',
        ];

        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }

    protected function notifyAdmin($conversation)
    {
        try {
            // Prevent duplicate notifications
            if ($conversation->notification_sent) {
                return;
            }

            $user = $conversation->user;
            $userName = $user ? $user->name : 'Guest User';
            $userEmail = $user ? $user->email : 'No email';

            // Get all admin users
            $admins = User::where('role', 'admin')->get();

            if ($admins->isEmpty()) {
                Log::warning('No admin users found to notify');
                return;
            }

            // Send email to all admins
            $emailService = new EmailService();
            $adminEmail = Setting::get('contact_email', 'admin@farmvax.com');

            $subject = "🔔 Human Assistance Requested - FarmVax Chatbot";
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                    <div style='background: linear-gradient(to right, #11455B, #0d3345); padding: 20px; text-align: center;'>
                        <h2 style='color: white; margin: 0;'>🔔 Human Assistance Requested</h2>
                    </div>

                    <div style='background: #f9f9f9; padding: 20px; border: 1px solid #ddd;'>
                        <p style='font-size: 16px; color: #333;'>
                            A user has requested human assistance in the chatbot.
                        </p>

                        <div style='background: white; padding: 15px; border-left: 4px solid #11455B; margin: 20px 0;'>
                            <h3 style='margin-top: 0; color: #11455B;'>User Information</h3>
                            <p><strong>Name:</strong> {$userName}</p>
                            <p><strong>Email:</strong> {$userEmail}</p>
                            <p><strong>Requested:</strong> " . now()->format('M d, Y H:i') . "</p>
                        </div>

                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='" . route('admin.chatbot.show', $conversation->id) . "'
                               style='background: #11455B; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;'>
                                View Conversation & Take Over
                            </a>
                        </div>

                        <p style='color: #666; font-size: 14px; margin-top: 20px;'>
                            Please respond to the user as soon as possible to provide the best customer service experience.
                        </p>
                    </div>

                    <div style='background: #11455B; padding: 15px; text-align: center;'>
                        <p style='color: white; margin: 0; font-size: 12px;'>
                            FarmVax - Livestock Vaccination & Farm Management Platform
                        </p>
                    </div>
                </div>
            ";

            $result = $emailService->send($adminEmail, $subject, $body);

            if ($result['success']) {
                $conversation->update(['notification_sent' => true]);
                Log::info('Admin notified of human request', ['conversation_id' => $conversation->id]);
            } else {
                Log::error('Failed to send admin notification email', ['error' => $result['error'] ?? 'Unknown']);
            }
        } catch (\Exception $e) {
            Log::error('Error notifying admin: ' . $e->getMessage());
        }
    }
}