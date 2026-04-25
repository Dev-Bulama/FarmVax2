@extends('layouts.admin')
@section('title', 'Telemedicine Settings')
@section('page-title', 'Telemedicine Settings')
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

<div class="max-w-2xl">
<form action="{{ route('admin.settings.telemedicine.update') }}" method="POST">
    @csrf @method('PUT')

    <div class="bg-white rounded-xl shadow p-6 space-y-6">

        <div>
            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Video Call Provider</h3>

            {{-- Toggle card --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Custom WebRTC --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="telemedicine_provider" value="custom"
                           {{ $provider === 'custom' ? 'checked' : '' }}
                           class="sr-only peer" onchange="toggleJitsiFields()">
                    <div class="peer-checked:border-[#11455b] peer-checked:bg-[#11455b]/5 border-2 border-gray-200 rounded-xl p-5 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-[#11455b]/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#11455b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Built-in WebRTC</p>
                                <p class="text-xs text-gray-500">Self-hosted, no third-party</p>
                            </div>
                        </div>
                        <ul class="text-xs text-gray-600 space-y-1 list-disc list-inside">
                            <li>Direct peer-to-peer video</li>
                            <li>No external account needed</li>
                            <li>WhatsApp-style mobile UI</li>
                        </ul>
                        <div class="mt-3 peer-checked:block hidden">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-[#11455b] text-white text-xs font-bold rounded-full">
                                ✓ Currently Active
                            </span>
                        </div>
                    </div>
                </label>

                {{-- Jitsi --}}
                <label class="relative cursor-pointer">
                    <input type="radio" name="telemedicine_provider" value="jitsi"
                           {{ $provider === 'jitsi' ? 'checked' : '' }}
                           class="sr-only peer" onchange="toggleJitsiFields()">
                    <div class="peer-checked:border-blue-600 peer-checked:bg-blue-50 border-2 border-gray-200 rounded-xl p-5 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8zm-1-13v6l5 3-1 1.73-6-3.46V7h2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Jitsi Meet</p>
                                <p class="text-xs text-gray-500">Open-source, enterprise-grade</p>
                            </div>
                        </div>
                        <ul class="text-xs text-gray-600 space-y-1 list-disc list-inside">
                            <li>Highly reliable & scalable</li>
                            <li>Works on all devices / browsers</li>
                            <li>Use meet.jit.si or own server</li>
                        </ul>
                    </div>
                </label>
            </div>
        </div>

        {{-- Jitsi domain field (shown only when Jitsi selected) --}}
        <div id="jitsi-config" class="{{ $provider === 'jitsi' ? '' : 'hidden' }}">
            <h3 class="text-base font-semibold text-gray-700 border-b pb-2 mb-4">Jitsi Configuration</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jitsi Server Domain</label>
                <input type="text" name="jitsi_domain" value="{{ $jitsiDomain }}"
                       placeholder="meet.jit.si"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-gray-500 mt-1">
                    Use <strong>meet.jit.si</strong> for the free public server, or your own self-hosted Jitsi domain.
                    Rooms are automatically named <code>farmvax-{room_code}</code>.
                </p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-2.5 bg-[#11455b] hover:bg-[#0d3345] text-white font-bold rounded-xl transition">
                Save Telemedicine Settings
            </button>
        </div>
    </div>
</form>
</div>

<script>
function toggleJitsiFields() {
    const isJitsi = document.querySelector('input[name="telemedicine_provider"][value="jitsi"]').checked;
    document.getElementById('jitsi-config').classList.toggle('hidden', !isJitsi);
}
</script>
@endsection
