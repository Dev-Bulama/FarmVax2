<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ChatbotTrainingData;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
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
            $userId = auth()->id();
            
            // Create or get conversation
            $conversation = ChatbotConversation::firstOrCreate([
                'user_id' => $userId,
                'status' => 'active',
            ]);

            // Save messages
            ChatbotMessage::create([
                'conversation_id' => $conversation->id,
                'user_id' => $userId,
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
}