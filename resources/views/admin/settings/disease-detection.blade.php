@extends('layouts.admin')
@section('title', 'Disease Detection AI Settings')
@section('page-title', 'Disease Detection AI Settings')
@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
    <p class="text-green-700">{{ session('success') }}</p>
</div>
@endif

@if($errors->any())
<div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
    <ul class="text-sm text-red-700 list-disc list-inside">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="max-w-2xl space-y-6">

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
        <p class="font-semibold mb-1">How Disease Detection AI works</p>
        <p>When a user uploads an animal photo, FarmVax sends it to the selected AI provider's vision model.
           The model analyses the image and returns a structured diagnosis with conditions, confidence score, and recommendations.</p>
    </div>

    <form action="{{ route('admin.settings.disease-detection.update') }}" method="POST">
        @csrf @method('PUT')

        <div class="bg-white rounded-xl shadow p-6 space-y-6">

            {{-- Scanner Bubble Label --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 border-b pb-2 mb-4">Scanner Bubble Customisation</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Bubble Label
                        <span class="ml-1 text-xs text-gray-400 font-normal">(text shown below the scanner button)</span>
                    </label>
                    <input type="text" name="scanner_bubble_label"
                           value="{{ $bubbleLabel }}"
                           placeholder="AI DOCTA"
                           maxlength="20"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#2FCB6E] focus:border-[#2FCB6E]">
                    <p class="text-xs text-gray-500 mt-1">Max 20 characters. Default: <strong>AI DOCTA</strong></p>
                </div>
            </div>

            {{-- Provider toggle --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 border-b pb-2 mb-4">AI Vision Provider</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Anthropic --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="disease_detection_provider" value="anthropic"
                               {{ $provider === 'anthropic' ? 'checked' : '' }}
                               class="sr-only peer" onchange="showProvider('anthropic')">
                        <div class="peer-checked:border-orange-500 peer-checked:bg-orange-50 border-2 border-gray-200 rounded-xl p-5 transition-all h-full">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-black text-sm">Ai</div>
                                <div>
                                    <p class="font-bold text-gray-800">Anthropic Claude</p>
                                    <p class="text-xs text-gray-500">Claude Vision (claude-sonnet-4-6)</p>
                                </div>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1 list-disc list-inside">
                                <li>Excellent medical/veterinary reasoning</li>
                                <li>Detailed structured JSON output</li>
                                <li>Best overall accuracy</li>
                            </ul>
                        </div>
                    </label>

                    {{-- OpenAI --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="disease_detection_provider" value="openai"
                               {{ $provider === 'openai' ? 'checked' : '' }}
                               class="sr-only peer" onchange="showProvider('openai')">
                        <div class="peer-checked:border-green-600 peer-checked:bg-green-50 border-2 border-gray-200 rounded-xl p-5 transition-all h-full">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-black text-sm">GP</div>
                                <div>
                                    <p class="font-bold text-gray-800">OpenAI GPT-4</p>
                                    <p class="text-xs text-gray-500">GPT-4o Vision</p>
                                </div>
                            </div>
                            <ul class="text-xs text-gray-600 space-y-1 list-disc list-inside">
                                <li>Strong image understanding</li>
                                <li>Widely used & well-tested</li>
                                <li>Multiple speed/cost tiers</li>
                            </ul>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Anthropic fields --}}
            <div id="cfg-anthropic" class="{{ $provider !== 'anthropic' ? 'hidden' : '' }} space-y-4">
                <h3 class="text-base font-semibold text-gray-700 border-b pb-2">Anthropic Configuration</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Anthropic API Key <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="anthropic-key-input" name="disease_detection_anthropic_key"
                               value="{{ $anthropicKey }}"
                               placeholder="sk-ant-api03-…"
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                        <button type="button" onclick="toggleVis('anthropic-key-input', this)"
                                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 text-xs">Show</button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Get your key from <a href="https://console.anthropic.com/settings/keys" target="_blank" class="text-orange-600 underline">console.anthropic.com</a></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                    <select name="disease_detection_anthropic_model"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-400">
                        @foreach([
                            'claude-sonnet-4-6'        => 'Claude Sonnet 4.6 (Recommended)',
                            'claude-opus-4-7'          => 'Claude Opus 4.7 (Most Capable)',
                            'claude-haiku-4-5-20251001'=> 'Claude Haiku 4.5 (Fastest)',
                        ] as $val => $label)
                        <option value="{{ $val }}" {{ $anthropicModel === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- OpenAI fields --}}
            <div id="cfg-openai" class="{{ $provider !== 'openai' ? 'hidden' : '' }} space-y-4">
                <h3 class="text-base font-semibold text-gray-700 border-b pb-2">OpenAI Configuration</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        OpenAI API Key <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="openai-key-input" name="disease_detection_openai_key"
                               value="{{ $openaiKey }}"
                               placeholder="sk-…"
                               class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <button type="button" onclick="toggleVis('openai-key-input', this)"
                                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 text-xs">Show</button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Get your key from <a href="https://platform.openai.com/api-keys" target="_blank" class="text-green-700 underline">platform.openai.com</a></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                    <select name="disease_detection_openai_model"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500">
                        @foreach([
                            'gpt-4o'      => 'GPT-4o (Recommended – best vision)',
                            'gpt-4o-mini' => 'GPT-4o Mini (Faster & cheaper)',
                            'gpt-4-turbo' => 'GPT-4 Turbo',
                        ] as $val => $label)
                        <option value="{{ $val }}" {{ $openaiModel === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-[#11455b] hover:bg-[#0d3345] text-white font-bold rounded-xl transition">
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function showProvider(p) {
    document.getElementById('cfg-anthropic').classList.toggle('hidden', p !== 'anthropic');
    document.getElementById('cfg-openai').classList.toggle('hidden', p !== 'openai');
}

function toggleVis(id, btn) {
    const input = document.getElementById(id);
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    btn.textContent = isPass ? 'Hide' : 'Show';
}
</script>
@endsection
