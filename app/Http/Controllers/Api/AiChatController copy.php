<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = $request->input('message');

        // Get AI settings
        $aiEnabled = Setting::where('key', 'ai_enabled')->value('value') == '1';
        $aiProvider = Setting::where('key', 'ai_provider')->value('value') ?? 'openai';
        $apiKey = Setting::where('key', 'ai_api_key')->value('value');

        if (!$aiEnabled || !$apiKey) {
            return response()->json([
                'response' => 'AI chatbot is currently unavailable. Please contact support for assistance.'
            ]);
        }

        try {
            $response = $this->callAiApi($message, $aiProvider, $apiKey);
            return response()->json(['response' => $response]);
        } catch (\Exception $e) {
            \Log::error('AI Chat Error: ' . $e->getMessage());
            return response()->json([
                'response' => 'Thank you for your message. Our team will get back to you shortly.'
            ]);
        }
    }

    private function callAiApi($message, $provider, $apiKey)
    {
        switch ($provider) {
            case 'openai':
                return $this->callOpenAI($message, $apiKey);
            case 'anthropic':
                return $this->callAnthropic($message, $apiKey);
            default:
                return 'AI provider not configured properly.';
        }
    }

    private function callOpenAI($message, $apiKey)
    {
        $model = Setting::where('key', 'ai_model')->value('value') ?? 'gpt-3.5-turbo';
        $systemPrompt = Setting::where('key', 'ai_system_prompt')->value('value') ?? 'You are a helpful assistant for FarmVax, an agricultural vaccination platform. Help users with questions about livestock health, vaccinations, and farm management.';

        $url = 'https://api.openai.com/v1/chat/completions';

        $data = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $message]
            ],
            'temperature' => (float) (Setting::where('key', 'ai_temperature')->value('value') ?? 0.7),
            'max_tokens' => (int) (Setting::where('key', 'ai_max_tokens')->value('value') ?? 500)
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            throw new \Exception('OpenAI API request failed');
        }

        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? 'Unable to generate response.';
    }

    private function callAnthropic($message, $apiKey)
    {
        $model = Setting::where('key', 'ai_model')->value('value') ?? 'claude-3-sonnet-20240229';
        $systemPrompt = Setting::where('key', 'ai_system_prompt')->value('value') ?? 'You are a helpful assistant for FarmVax.';

        $url = 'https://api.anthropic.com/v1/messages';

        $data = [
            'model' => $model,
            'max_tokens' => (int) (Setting::where('key', 'ai_max_tokens')->value('value') ?? 500),
            'messages' => [
                ['role' => 'user', 'content' => $message]
            ],
            'system' => $systemPrompt
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'x-api-key: ' . $apiKey,
            'anthropic-version: 2023-06-01'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            throw new \Exception('Anthropic API request failed');
        }

        $result = json_decode($response, true);
        return $result['content'][0]['text'] ?? 'Unable to generate response.';
    }
}
