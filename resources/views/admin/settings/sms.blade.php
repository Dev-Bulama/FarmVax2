@extends('layouts.admin')

@section('title', 'SMS Settings')
@section('page-title', 'SMS Settings')

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
    <form action="{{ route('admin.settings.sms.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <!-- SMS Provider -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SMS Provider</label>
                <select name="sms_provider" id="sms_provider"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                    <option value="kudi" {{ (old('sms_provider', $settingsArray['sms_provider'] ?? 'kudi') == 'kudi') ? 'selected' : '' }}>Kudi SMS (Recommended for Nigeria)</option>
                    <option value="termii" {{ (old('sms_provider', $settingsArray['sms_provider'] ?? '') == 'termii') ? 'selected' : '' }}>Termii</option>
                    <option value="africastalking" {{ (old('sms_provider', $settingsArray['sms_provider'] ?? '') == 'africastalking') ? 'selected' : '' }}>Africa's Talking</option>
                    <option value="bulksms" {{ (old('sms_provider', $settingsArray['sms_provider'] ?? '') == 'bulksms') ? 'selected' : '' }}>BulkSMS Nigeria</option>
                    <option value="twilio" {{ (old('sms_provider', $settingsArray['sms_provider'] ?? '') == 'twilio') ? 'selected' : '' }}>Twilio</option>
                </select>
            </div>

            <!-- Kudi SMS Settings -->
            <div id="kudi-settings" class="space-y-4 border-t pt-4">
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="text-blue-800 font-semibold">Kudi SMS</h4>
                            <p class="text-sm text-blue-700 mt-1">Get your API credentials from <a href="https://kudisms.net" target="_blank" class="underline">kudisms.net</a></p>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-800">Kudi SMS Configuration</h3>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded mb-4">
                    <p class="text-sm text-yellow-800">
                        <strong>Note:</strong> Kudi SMS supports both API Key and Username/Password authentication.
                        Fill in whichever method you have from your Kudi SMS account.
                    </p>
                </div>

                <!-- Authentication Method Toggle -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Authentication Method</label>
                    <select id="kudi-auth-method" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                        <option value="username" {{ !empty($settingsArray['kudi_username']) ? 'selected' : '' }}>Username & Password</option>
                        <option value="api_key" {{ !empty($settingsArray['kudi_api_key']) && empty($settingsArray['kudi_username']) ? 'selected' : '' }}>API Key</option>
                    </select>
                </div>

                <!-- API Key Method -->
                <div id="kudi-api-key-section" class="hidden space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            API Key <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kudi_api_key" value="{{ old('kudi_api_key', $settingsArray['kudi_api_key'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent font-mono text-sm"
                               placeholder="B5Kk9*******Dy16XRTbA***********qPmzQj2rF70YefZN4nwaG">
                        <p class="text-xs text-gray-500 mt-1">Your Kudi SMS API key from account dashboard</p>
                    </div>
                </div>

                <!-- Username/Password Method -->
                <div id="kudi-username-section" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kudi_username" value="{{ old('kudi_username', $settingsArray['kudi_username'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                               placeholder="your-kudi-username">
                        <p class="text-xs text-gray-500 mt-1">Your Kudi SMS account username</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="kudi_password" value="{{ old('kudi_password', $settingsArray['kudi_password'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent font-mono text-sm"
                               placeholder="••••••••••••••••">
                        <p class="text-xs text-gray-500 mt-1">Your Kudi SMS account password</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sender ID <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kudi_sender_id" value="{{ old('kudi_sender_id', $settingsArray['kudi_sender_id'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="FarmVax" maxlength="11">
                    <p class="text-xs text-gray-500 mt-1">Maximum 11 characters (alphanumeric) - must be registered with Kudi SMS</p>
                </div>
            </div>

            <!-- Termii Settings -->
            <div id="termii-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">Termii Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Termii API Key</label>
                    <input type="text" name="termii_api_key" value="{{ old('termii_api_key', $settingsArray['termii_api_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="TLxxxxxxxxxxxxxxxxxxxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                    <input type="text" name="termii_sender_id" value="{{ old('termii_sender_id', $settingsArray['termii_sender_id'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="FarmVax">
                </div>
            </div>

            <!-- Africa's Talking Settings -->
            <div id="africastalking-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">Africa's Talking Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" name="africastalking_username" value="{{ old('africastalking_username', $settingsArray['africastalking_username'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="sandbox or your username">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="text" name="africastalking_api_key" value="{{ old('africastalking_api_key', $settingsArray['africastalking_api_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="Your API Key">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                    <input type="text" name="africastalking_sender_id" value="{{ old('africastalking_sender_id', $settingsArray['africastalking_sender_id'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="FarmVax">
                </div>
            </div>

            <!-- BulkSMS Nigeria Settings -->
            <div id="bulksms-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">BulkSMS Nigeria Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Token</label>
                    <input type="text" name="bulksms_api_token" value="{{ old('bulksms_api_token', $settingsArray['bulksms_api_token'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="Your API Token">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                    <input type="text" name="bulksms_sender_id" value="{{ old('bulksms_sender_id', $settingsArray['bulksms_sender_id'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="FarmVax">
                </div>
            </div>

            <!-- Twilio Settings -->
            <div id="twilio-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">Twilio Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account SID</label>
                    <input type="text" name="twilio_account_sid" value="{{ old('twilio_account_sid', $settingsArray['twilio_account_sid'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="ACxxxxxxxxxxxxxxxxxxxxx">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Auth Token</label>
                    <input type="password" name="twilio_auth_token" value="{{ old('twilio_auth_token', $settingsArray['twilio_auth_token'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Number</label>
                    <input type="text" name="twilio_from_number" value="{{ old('twilio_from_number', $settingsArray['twilio_from_number'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="+1234567890">
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <button type="button" onclick="testSmsConnection()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Test SMS
            </button>
            <button type="submit" class="px-6 py-2 bg-[#2FCB6E] text-white rounded-lg hover:bg-[#25a356] transition font-semibold">
                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
    const providerSelect = document.getElementById('sms_provider');
    const kudiSettings = document.getElementById('kudi-settings');
    const termiiSettings = document.getElementById('termii-settings');
    const africastalkingSettings = document.getElementById('africastalking-settings');
    const bulksmsSettings = document.getElementById('bulksms-settings');
    const twilioSettings = document.getElementById('twilio-settings');

    providerSelect.addEventListener('change', function() {
        kudiSettings.classList.add('hidden');
        termiiSettings.classList.add('hidden');
        africastalkingSettings.classList.add('hidden');
        bulksmsSettings.classList.add('hidden');
        twilioSettings.classList.add('hidden');

        switch(this.value) {
            case 'kudi':
                kudiSettings.classList.remove('hidden');
                break;
            case 'termii':
                termiiSettings.classList.remove('hidden');
                break;
            case 'africastalking':
                africastalkingSettings.classList.remove('hidden');
                break;
            case 'bulksms':
                bulksmsSettings.classList.remove('hidden');
                break;
            case 'twilio':
                twilioSettings.classList.remove('hidden');
                break;
        }
    });

    // Trigger on page load
    providerSelect.dispatchEvent(new Event('change'));

    // Kudi SMS auth method toggle
    const kudiAuthMethod = document.getElementById('kudi-auth-method');
    const kudiApiKeySection = document.getElementById('kudi-api-key-section');
    const kudiUsernameSection = document.getElementById('kudi-username-section');

    if (kudiAuthMethod) {
        kudiAuthMethod.addEventListener('change', function() {
            if (this.value === 'api_key') {
                kudiApiKeySection.classList.remove('hidden');
                kudiUsernameSection.classList.add('hidden');
            } else {
                kudiApiKeySection.classList.add('hidden');
                kudiUsernameSection.classList.remove('hidden');
            }
        });

        // Trigger on page load
        kudiAuthMethod.dispatchEvent(new Event('change'));
    }

    function testSmsConnection() {
        const phone = prompt('Enter phone number to receive test SMS (with country code, e.g., +2348012345678):');

        if (!phone) {
            return;
        }

        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';

        fetch('{{ route("admin.settings.sms.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                test_phone: phone
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;

            if (data.success) {
                alert('✅ Success!\n\n' + data.message + '\nProvider: ' + (data.provider || 'N/A'));
            } else {
                alert('❌ Error!\n\n' + data.message);
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            alert('❌ Error!\n\nFailed to send test SMS. Please check your configuration and try again.\n\nError: ' + error.message);
        });
    }
</script>

@endsection