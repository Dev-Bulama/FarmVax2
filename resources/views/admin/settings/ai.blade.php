@extends('layouts.admin')

@section('title', 'AI Chatbot Settings')
@section('page-title', 'AI Chatbot Settings')

@section('content')

@php
    $settingsArray = [];
    foreach($settings as $setting) {
        $settingsArray[$setting->key] = $setting->value;
    }
@endphp

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
@endif

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.settings.ai.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <!-- Enable AI -->
            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Enable AI Chatbot</h3>
                    <p class="text-sm text-gray-600 mt-1">Allow users to interact with AI-powered chatbot for livestock advice</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="hidden" name="ai_enabled" value="0">
                    <input type="checkbox" name="ai_enabled" value="1"
                           {{ (old('ai_enabled', $settingsArray['ai_enabled'] ?? '0') == '1') ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-purple-600"></div>
                </label>
            </div>

            <!-- AI Provider -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">AI Provider</label>
                <select name="ai_provider" id="ai_provider"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="openai" {{ (old('ai_provider', $settingsArray['ai_provider'] ?? 'openai') == 'openai') ? 'selected' : '' }}>OpenAI (ChatGPT)</option>
                    <option value="anthropic" {{ (old('ai_provider', $settingsArray['ai_provider'] ?? '') == 'anthropic') ? 'selected' : '' }}>Anthropic (Claude)</option>
                    <option value="google" {{ (old('ai_provider', $settingsArray['ai_provider'] ?? '') == 'google') ? 'selected' : '' }}>Google (Gemini)</option>
                </select>
            </div>

            <!-- Provider-Specific Settings -->
            <div id="openai-settings" class="border-t pt-4">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-blue-800 font-semibold">OpenAI</h4>
                            <p class="text-sm text-blue-700 mt-1">Get your API key from <a href="https://platform.openai.com/api-keys" target="_blank" class="underline">OpenAI Platform</a></p>
                        </div>
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-4">OpenAI Configuration</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            API Key <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="openai_api_key" value="{{ old('openai_api_key', $settingsArray['openai_api_key'] ?? $settingsArray['ai_api_key'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="sk-...">
                        <p class="text-xs text-gray-500 mt-1">Your OpenAI API key (starts with sk-)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                        <select name="openai_model" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="gpt-4o" {{ (old('openai_model', $settingsArray['openai_model'] ?? $settingsArray['ai_model'] ?? '') == 'gpt-4o') ? 'selected' : '' }}>GPT-4o (Recommended)</option>
                            <option value="gpt-4o-mini" {{ (old('openai_model', $settingsArray['openai_model'] ?? '') == 'gpt-4o-mini') ? 'selected' : '' }}>GPT-4o Mini (Faster & Cheaper)</option>
                            <option value="gpt-4-turbo" {{ (old('openai_model', $settingsArray['openai_model'] ?? '') == 'gpt-4-turbo') ? 'selected' : '' }}>GPT-4 Turbo</option>
                            <option value="gpt-3.5-turbo" {{ (old('openai_model', $settingsArray['openai_model'] ?? '') == 'gpt-3.5-turbo') ? 'selected' : '' }}>GPT-3.5 Turbo (Budget)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="anthropic-settings" class="hidden border-t pt-4">
                <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded mb-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-orange-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-orange-800 font-semibold">Anthropic Claude</h4>
                            <p class="text-sm text-orange-700 mt-1">Get your API key from <a href="https://console.anthropic.com" target="_blank" class="underline">Anthropic Console</a></p>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-4">Anthropic Configuration</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            API Key <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="anthropic_api_key" value="{{ old('anthropic_api_key', $settingsArray['anthropic_api_key'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="sk-ant-...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                        <select name="anthropic_model" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="claude-3-5-sonnet-20241022" {{ (old('anthropic_model', $settingsArray['anthropic_model'] ?? '') == 'claude-3-5-sonnet-20241022') ? 'selected' : '' }}>Claude 3.5 Sonnet (Recommended)</option>
                            <option value="claude-3-opus-20240229" {{ (old('anthropic_model', $settingsArray['anthropic_model'] ?? '') == 'claude-3-opus-20240229') ? 'selected' : '' }}>Claude 3 Opus</option>
                            <option value="claude-3-haiku-20240307" {{ (old('anthropic_model', $settingsArray['anthropic_model'] ?? '') == 'claude-3-haiku-20240307') ? 'selected' : '' }}>Claude 3 Haiku (Faster)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="google-settings" class="hidden border-t pt-4">
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-green-800 font-semibold">Google Gemini</h4>
                            <p class="text-sm text-green-700 mt-1">Get your API key from <a href="https://makersuite.google.com/app/apikey" target="_blank" class="underline">Google AI Studio</a></p>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-4">Google Gemini Configuration</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            API Key <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="google_api_key" value="{{ old('google_api_key', $settingsArray['google_api_key'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="AIza...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                        <select name="google_model" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="gemini-pro" {{ (old('google_model', $settingsArray['google_model'] ?? '') == 'gemini-pro') ? 'selected' : '' }}>Gemini Pro</option>
                            <option value="gemini-1.5-pro" {{ (old('google_model', $settingsArray['google_model'] ?? '') == 'gemini-1.5-pro') ? 'selected' : '' }}>Gemini 1.5 Pro</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Advanced Settings -->
            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Advanced Settings</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Temperature</label>
                        <input type="number" name="ai_temperature" step="0.1" min="0" max="2"
                               value="{{ old('ai_temperature', $settingsArray['ai_temperature'] ?? '0.7') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Controls creativity (0 = focused, 2 = creative)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Tokens</label>
                        <input type="number" name="ai_max_tokens" min="50" max="4000"
                               value="{{ old('ai_max_tokens', $settingsArray['ai_max_tokens'] ?? '1000') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Maximum response length</p>
                    </div>
                </div>
            </div>

            <!-- System Prompt -->
            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">System Prompt / Training Data</h3>
                <label class="block text-sm font-medium text-gray-700 mb-2">AI Instructions</label>
                <textarea name="ai_system_prompt" rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                          placeholder="You are a helpful agricultural assistant for FarmVax. You help farmers with livestock vaccination, disease prevention, and farm management...">{{ old('ai_system_prompt', $settingsArray['ai_system_prompt'] ?? 'You are a helpful agricultural assistant for FarmVax, a livestock vaccination and farm management platform in Nigeria. Help farmers with livestock health, vaccination schedules, disease prevention, and farm best practices. Be concise, practical, and culturally appropriate.') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Define the AI's behavior, knowledge, and personality</p>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <button type="button" onclick="testAi()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                Test AI
            </button>
            <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold">
                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
    const providerSelect = document.getElementById('ai_provider');
    const openaiSettings = document.getElementById('openai-settings');
    const anthropicSettings = document.getElementById('anthropic-settings');
    const googleSettings = document.getElementById('google-settings');

    providerSelect.addEventListener('change', function() {
        openaiSettings.classList.add('hidden');
        anthropicSettings.classList.add('hidden');
        googleSettings.classList.add('hidden');

        switch(this.value) {
            case 'openai':
                openaiSettings.classList.remove('hidden');
                break;
            case 'anthropic':
                anthropicSettings.classList.remove('hidden');
                break;
            case 'google':
                googleSettings.classList.remove('hidden');
                break;
        }
    });

    // Trigger on page load
    providerSelect.dispatchEvent(new Event('change'));

    function testAi() {
        alert('AI test feature will send a test message to verify the configuration works correctly.');
    }
</script>

@endsection