@extends('layouts.admin')

@section('title', 'Email Settings')
@section('page-title', 'Email Settings')

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
    <form action="{{ route('admin.settings.email.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <!-- Email Provider -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Provider</label>
                <select name="email_provider" id="email_provider"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                    <option value="smtp" {{ (old('email_provider', $settingsArray['email_provider'] ?? 'smtp') == 'smtp') ? 'selected' : '' }}>SMTP</option>
                    <option value="sendgrid" {{ (old('email_provider', $settingsArray['email_provider'] ?? '') == 'sendgrid') ? 'selected' : '' }}>SendGrid</option>
                    <option value="mailgun" {{ (old('email_provider', $settingsArray['email_provider'] ?? '') == 'mailgun') ? 'selected' : '' }}>Mailgun</option>
                    <option value="ses" {{ (old('email_provider', $settingsArray['email_provider'] ?? '') == 'ses') ? 'selected' : '' }}>Amazon SES</option>
                </select>
            </div>

            <!-- SMTP Settings -->
            <div id="smtp-settings" class="space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">SMTP Configuration</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host', $settingsArray['smtp_host'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                               placeholder="smtp.gmail.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                        <input type="number" name="smtp_port" value="{{ old('smtp_port', $settingsArray['smtp_port'] ?? '587') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                               placeholder="587">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Username</label>
                    <input type="text" name="smtp_username" value="{{ old('smtp_username', $settingsArray['smtp_username'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="your-email@gmail.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Password</label>
                    <input type="password" name="smtp_password" value="{{ old('smtp_password', $settingsArray['smtp_password'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="••••••••">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to keep current password</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Encryption</label>
                    <select name="smtp_encryption" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                        <option value="tls" {{ (old('smtp_encryption', $settingsArray['smtp_encryption'] ?? 'tls') == 'tls') ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ (old('smtp_encryption', $settingsArray['smtp_encryption'] ?? '') == 'ssl') ? 'selected' : '' }}>SSL</option>
                    </select>
                </div>
            </div>

            <!-- SendGrid Settings -->
            <div id="sendgrid-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">SendGrid Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SendGrid API Key</label>
                    <input type="text" name="sendgrid_api_key" value="{{ old('sendgrid_api_key', $settingsArray['sendgrid_api_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="SG.xxxxxxxxxxxxxxxxxx">
                </div>
            </div>

            <!-- Mailgun Settings -->
            <div id="mailgun-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">Mailgun Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mailgun Domain</label>
                    <input type="text" name="mailgun_domain" value="{{ old('mailgun_domain', $settingsArray['mailgun_domain'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="mg.yourdomain.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mailgun API Key</label>
                    <input type="text" name="mailgun_api_key" value="{{ old('mailgun_api_key', $settingsArray['mailgun_api_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="key-xxxxxxxxxxxxxxxxxx">
                </div>
            </div>

            <!-- Amazon SES Settings -->
            <div id="ses-settings" class="hidden space-y-4 border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">Amazon SES Configuration</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AWS Access Key</label>
                    <input type="text" name="ses_key" value="{{ old('ses_key', $settingsArray['ses_key'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="AKIAIOSFODNN7EXAMPLE">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AWS Secret Key</label>
                    <input type="password" name="ses_secret" value="{{ old('ses_secret', $settingsArray['ses_secret'] ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="••••••••">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">AWS Region</label>
                    <input type="text" name="ses_region" value="{{ old('ses_region', $settingsArray['ses_region'] ?? 'us-east-1') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="us-east-1">
                </div>
            </div>

            <!-- From Email -->
            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Default Sender Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Email</label>
                        <input type="email" name="from_email" value="{{ old('from_email', $settingsArray['from_email'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                               placeholder="noreply@farmvax.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                        <input type="text" name="from_name" value="{{ old('from_name', $settingsArray['from_name'] ?? 'FarmVax') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                               placeholder="FarmVax">
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <button type="button" onclick="testEmailConnection()" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Test Connection
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
        const email = prompt('Enter email address to receive test email:');

        if (!email) {
            return;
        }

        // Validate email format
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('❌ Please enter a valid email address');
            return;
        }

        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';

        fetch('{{ route("admin.settings.email.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                test_email: email
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;

            if (data.success) {
                alert('✅ Success!\n\n' + data.message);
            } else {
                alert('❌ Error!\n\n' + data.message);
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            alert('❌ Error!\n\nFailed to send test email. Please check your configuration and try again.\n\nError: ' + error.message);
        });
    }
</script>

@endsection