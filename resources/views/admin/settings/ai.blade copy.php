@extends('layouts.admin')

@section('title', 'AI Chatbot Settings')
@section('page-title', 'AI Chatbot Settings')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.settings.ai.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Enable AI -->
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <h3 class="font-semibold">Enable AI Chatbot</h3>
                    <p class="text-sm text-gray-600">Allow users to interact with AI-powered chatbot</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="ai_enabled" value="1"
                           {{ (old('ai_enabled', $settings['ai_enabled'] ?? '0') == '1') ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                </label>
            </div>

            <!-- AI Provider -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">AI Provider</label>
                <select name="ai_provider"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="openai" {{ (old('ai_provider', $settings['ai_provider'] ?? 'openai') == 'openai') ? 'selected' : '' }}>OpenAI (ChatGPT)</option>
                    <option value="anthropic" {{ (old('ai_provider', $settings['ai_provider'] ?? '') == 'anthropic') ? 'selected' : '' }}>Anthropic (Claude)</option>
                    <option value="google" {{ (old('ai_provider', $settings['ai_provider'] ?? '') == 'google') ? 'selected' : '' }}>Google (Gemini)</option>
                </select>
            </div>

            <!-- API Key -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                <input type="password" name="ai_api_key" value="{{ old('ai_api_key', $settings['ai_api_key'] ?? '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Model -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                <input type="text" name="ai_model" value="{{ old('ai_model', $settings['ai_model'] ?? 'gpt-3.5-turbo') }}"
                       placeholder="gpt-3.5-turbo, gpt-4, claude-3-opus, etc."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Specify the model ID to use</p>
            </div>

            <!-- Advanced Settings -->
            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold mb-4">Advanced Settings</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Temperature</label>
                        <input type="number" name="ai_temperature" step="0.1" min="0" max="2"
                               value="{{ old('ai_temperature', $settings['ai_temperature'] ?? '0.7') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Controls randomness (0-2)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Tokens</label>
                        <input type="number" name="ai_max_tokens" min="50" max="4000"
                               value="{{ old('ai_max_tokens', $settings['ai_max_tokens'] ?? '500') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">Maximum response length</p>
                    </div>
                </div>
            </div>

            <!-- Training Data / System Prompt -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">System Prompt</label>
                <textarea name="ai_system_prompt" rows="5"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="You are a helpful assistant for FarmVax, an agricultural vaccination platform...">{{ old('ai_system_prompt', $settings['ai_system_prompt'] ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Define the AI's behavior and knowledge</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" onclick="testAi()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-flask mr-2"></i> Test AI
            </button>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-save mr-2"></i> Save Settings
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function testAi() {
        alert('Testing AI connection...');
    }
</script>
@endpush
@endsection
