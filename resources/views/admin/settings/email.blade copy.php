@extends('layouts.admin')

@section('title', 'Email Settings')
@section('page-title', 'Email Settings')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.settings.email.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Email Provider -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Provider</label>
                <select name="email_provider" id="email_provider"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="smtp" {{ (old('email_provider', $settings['email_provider'] ?? 'smtp') == 'smtp') ? 'selected' : '' }}>SMTP</option>
                    <option value="sendgrid" {{ (old('email_provider', $settings['email_provider'] ?? '') == 'sendgrid') ? 'selected' : '' }}>SendGrid</option>
                    <option value="mailgun" {{ (old('email_provider', $settings['email_provider'] ?? '') == 'mailgun') ? 'selected' : '' }}>Mailgun</option>
                    <option value="ses" {{ (old('email_provider', $settings['email_provider'] ?? '') == 'ses') ? 'selected' : '' }}>Amazon SES</option>
                </select>
            </div>

            <!-- SMTP Settings -->
            <div id="smtp-settings" class="space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold">SMTP Configuration</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                        <input type="text" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port'] ?? '587') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                    <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                    <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings['smtp_password'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                    <select name="smtp_encryption" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        <option value="tls" {{ (old('smtp_encryption', $settings['smtp_encryption'] ?? 'tls') == 'tls') ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ (old('smtp_encryption', $settings['smtp_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>
            </div>

            <!-- SendGrid Settings -->
            <div id="sendgrid-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold">SendGrid Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SendGrid API Key</label>
                    <input type="text" name="sendgrid_api_key" value="{{ old('sendgrid_api_key', $settings['sendgrid_api_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <!-- Mailgun Settings -->
            <div id="mailgun-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold">Mailgun Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mailgun Domain</label>
                    <input type="text" name="mailgun_domain" value="{{ old('mailgun_domain', $settings['mailgun_domain'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mailgun API Key</label>
                    <input type="text" name="mailgun_api_key" value="{{ old('mailgun_api_key', $settings['mailgun_api_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <!-- Amazon SES Settings -->
            <div id="ses-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold">Amazon SES Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AWS Access Key</label>
                    <input type="text" name="ses_key" value="{{ old('ses_key', $settings['ses_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AWS Secret Key</label>
                    <input type="password" name="ses_secret" value="{{ old('ses_secret', $settings['ses_secret'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AWS Region</label>
                    <input type="text" name="ses_region" value="{{ old('ses_region', $settings['ses_region'] ?? 'us-east-1') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>

            <!-- From Email -->
            <div class="border-t pt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                    <input type="email" name="from_email" value="{{ old('from_email', $settings['from_email'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                    <input type="text" name="from_name" value="{{ old('from_name', $settings['from_name'] ?? 'FarmVax') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" onclick="testEmailConnection()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
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
    const providerSelect = document.getElementById('email_provider');
    const smtpSettings = document.getElementById('smtp-settings');
    const sendgridSettings = document.getElementById('sendgrid-settings');
    const mailgunSettings = document.getElementById('mailgun-settings');
    const sesSettings = document.getElementById('ses-settings');

    providerSelect.addEventListener('change', function() {
        smtpSettings.classList.add('hidden');
        sendgridSettings.classList.add('hidden');
        mailgunSettings.classList.add('hidden');
        sesSettings.classList.add('hidden');

        switch(this.value) {
            case 'smtp':
                smtpSettings.classList.remove('hidden');
                break;
            case 'sendgrid':
                sendgridSettings.classList.remove('hidden');
                break;
            case 'mailgun':
                mailgunSettings.classList.remove('hidden');
                break;
            case 'ses':
                sesSettings.classList.remove('hidden');
                break;
        }
    });

    // Trigger on page load
    providerSelect.dispatchEvent(new Event('change'));

    function testEmailConnection() {
        alert('Testing email connection...');
    }
</script>
@endpush
@endsection
