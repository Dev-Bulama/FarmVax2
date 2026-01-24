@extends('layouts.admin')

@section('title', 'SMS Settings')
@section('page-title', 'SMS Settings')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.settings.sms.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- SMS Provider -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">SMS Provider</label>
                <select name="sms_provider" id="sms_provider"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="twilio" {{ (old('sms_provider', $settings['sms_provider'] ?? 'twilio') == 'twilio') ? 'selected' : '' }}>Twilio</option>
                    <option value="kudi" {{ (old('sms_provider', $settings['sms_provider'] ?? '') == 'kudi') ? 'selected' : '' }}>Kudi SMS</option>
                    <option value="termii" {{ (old('sms_provider', $settings['sms_provider'] ?? '') == 'termii') ? 'selected' : '' }}>Termii</option>
                    <option value="africastalking" {{ (old('sms_provider', $settings['sms_provider'] ?? '') == 'africastalking') ? 'selected' : '' }}>Africa's Talking</option>
                    <option value="bulksms" {{ (old('sms_provider', $settings['sms_provider'] ?? '') == 'bulksms') ? 'selected' : '' }}>BulkSMS Nigeria</option>
                </select>
            </div>

            <!-- Twilio Settings -->
            <div id="twilio-settings" class="space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold">Twilio Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account SID</label>
                    <input type="text" name="sms_api_key" value="{{ old('sms_api_key', $settings['sms_api_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Auth Token</label>
                    <input type="password" name="sms_api_secret" value="{{ old('sms_api_secret', $settings['sms_api_secret'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Number</label>
                    <input type="text" name="sms_from_number" value="{{ old('sms_from_number', $settings['sms_from_number'] ?? '') }}"
                           placeholder="+1234567890"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <!-- Other Providers (Kudi, Termii, etc) -->
            <div id="other-providers-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold">SMS Provider Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="text" name="sms_api_key_other" value="{{ old('sms_api_key', $settings['sms_api_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sender ID</label>
                    <input type="text" name="sms_sender_id" value="{{ old('sms_sender_id', $settings['sms_sender_id'] ?? 'FarmVax') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-1">This is the name that will appear as the sender</p>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" onclick="testSmsConnection()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-flask mr-2"></i> Test Connection
            </button>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-save mr-2"></i> Save Settings
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const providerSelect = document.getElementById('sms_provider');
    const twilioSettings = document.getElementById('twilio-settings');
    const otherProviderSettings = document.getElementById('other-providers-settings');

    providerSelect.addEventListener('change', function() {
        twilioSettings.classList.add('hidden');
        otherProviderSettings.classList.add('hidden');

        if(this.value === 'twilio') {
            twilioSettings.classList.remove('hidden');
        } else {
            otherProviderSettings.classList.remove('hidden');
        }
    });

    // Trigger on page load
    providerSelect.dispatchEvent(new Event('change'));

    function testSmsConnection() {
        alert('Testing SMS connection...');
    }
</script>
@endpush
@endsection
