<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ChatbotTrainingData;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use Illuminate\Support\Facades\Http;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_history' => 'nullable|array',
        ]);

        // Check if AI is enabled
        $aiEnabled = Setting::get('ai_enabled', false);
        if (!$aiEnabled) {
            return response()->json([
                'success' => false,
                'error' => 'AI chatbot is currently disabled'
            ], 503);
        }

        try {
            // Get AI settings
            $provider = Setting::get('ai_provider', 'openai');
            $systemPrompt = Setting::get('ai_system_prompt', 'You are a helpful agricultural assistant.');
            
            // Add training data context
            $trainingContext = $this->getTrainingContext($validated['message']);
            if ($trainingContext) {
                $systemPrompt .= "\n\nRelevant information from knowledge base:\n" . $trainingContext;
            }

            // Call appropriate AI provider
            $response = match($provider) {
                'openai' => $this->callOpenAI($validated['message'], $systemPrompt, $validated['conversation_history'] ?? []),
                'anthropic' => $this->callAnthropic($validated['message'], $systemPrompt, $validated['conversation_history'] ?? []),
                'google' => $this->callGoogle($validated['message'], $systemPrompt, $validated['conversation_history'] ?? []),
                default => throw new \Exception('Invalid AI provider')
            };

            // Save conversation
            $this->saveConversation($validated['message'], $response);

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            \Log::error('AI Chat Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Sorry, I encountered an error. Please try again.'
            ], 500);
        }
    }

    protected function callOpenAI($message, $systemPrompt, $history)
    {
        $apiKey = Setting::get('openai_api_key') ?? Setting::get('ai_api_key');
        $model = Setting::get('openai_model') ?? Setting::get('ai_model', 'gpt-4o-mini');
        $temperature = (float) Setting::get('ai_temperature', 0.7);
        $maxTokens = (int) Setting::get('ai_max_tokens', 1000);

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        
        // Add conversation history
        foreach ($history as $item) {
            $messages[] = $item;
        }
        
        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => $model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
        ]);

        if (!$response->successful()) {
            throw new \Exception('OpenAI API error: ' . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? 'No response';
    }

    protected function callAnthropic($message, $systemPrompt, $history)
    {
        $apiKey = Setting::get('anthropic_api_key');
        $model = Setting::get('anthropic_model', 'claude-3-5-sonnet-20241022');
        $maxTokens = (int) Setting::get('ai_max_tokens', 1000);

        $messages = [];
        foreach ($history as $item) {
            $messages[] = $item;
        }
        $messages[] = ['role' => 'user', 'content' => $message];

        $response = Http::withHeaders([
            'x-api-key' => $apiKey,
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => $model,
            'system' => $systemPrompt,
            'messages' => $messages,
            'max_tokens' => $maxTokens,
        ]);

        if (!$response->successful()) {
            throw new \Exception('Anthropic API error: ' . $response->body());
        }

        $data = $response->json();
        return $data['content'][0]['text'] ?? 'No response';
    }

    protected function callGoogle($message, $systemPrompt, $history)
    {
        $apiKey = Setting::get('google_api_key');
        $model = Setting::get('google_model', 'gemini-pro');

        $fullPrompt = $systemPrompt . "\n\nUser: " . $message;

        $response = Http::post("https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}", [
            'contents' => [
                ['parts' => [['text' => $fullPrompt]]]
            ]
        ]);

        if (!$response->successful()) {
            throw new \Exception('Google API error: ' . $response->body());
        }

        $data = $response->json();
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';
    }

    protected function getTrainingContext($message)
    {
        // Search training data for relevant context
        $trainingData = ChatbotTrainingData::where('is_active', true)
            ->where(function($query) use ($message) {
                $query->where('content', 'like', '%' . $message . '%')
                      ->orWhere('title', 'like', '%' . $message . '%');
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
            \Log::error('Error saving conversation: ' . $e->getMessage());
        }
    }
}