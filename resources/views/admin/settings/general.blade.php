@extends('layouts.admin')

@section('title', 'General Settings')
@section('page-title', 'General Settings')

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

@if ($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <!-- Site Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Site Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Site Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settingsArray['site_name'] ?? 'FarmVax') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="FarmVax">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Site Tagline
                        </label>
                        <input type="text" name="site_tagline" value="{{ old('site_tagline', $settingsArray['site_tagline'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="Your livestock vaccination partner">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Site Description
                        </label>
                        <textarea name="site_description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                                  placeholder="Brief description of your platform...">{{ old('site_description', $settingsArray['site_description'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Branding -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Branding</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Site Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                        @if(isset($settingsArray['site_logo']) && $settingsArray['site_logo'])
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-2">Current Logo:</p>
                                <img src="{{ asset($settingsArray['site_logo']) }}" alt="Current Logo" class="h-16 border rounded">
                            </div>
                        @endif
                        <input type="file" name="site_logo" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Recommended: 200x60px (PNG with transparency)</p>
                    </div>

                    <!-- Site Favicon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                        @if(isset($settingsArray['site_favicon']) && $settingsArray['site_favicon'])
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-2">Current Favicon:</p>
                                <img src="{{ asset($settingsArray['site_favicon']) }}" alt="Current Favicon" class="h-8 border rounded">
                            </div>
                        @endif
                        <input type="file" name="site_favicon" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Recommended: 32x32px or 64x64px (PNG or ICO)</p>
                    </div>
                </div>

                <!-- Brand Colors -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Primary Color</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="primary_color" value="{{ old('primary_color', $settingsArray['primary_color'] ?? '#11455B') }}"
                                   class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                            <input type="text" value="{{ old('primary_color', $settingsArray['primary_color'] ?? '#11455B') }}"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                                   readonly>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Secondary Color</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="secondary_color" value="{{ old('secondary_color', $settingsArray['secondary_color'] ?? '#2FCB6E') }}"
                                   class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                            <input type="text" value="{{ old('secondary_color', $settingsArray['secondary_color'] ?? '#2FCB6E') }}"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                                   readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Contact Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settingsArray['contact_email'] ?? '') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="contact@farmvax.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone
                        </label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settingsArray['contact_phone'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="+234 XXX XXX XXXX">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Office Address
                        </label>
                        <textarea name="office_address" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                                  placeholder="Enter office address...">{{ old('office_address', $settingsArray['office_address'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Social Media Links -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Social Media Links</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                        <input type="url" name="facebook_url" value="{{ old('facebook_url', $settingsArray['facebook_url'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="https://facebook.com/farmvax">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Twitter URL</label>
                        <input type="url" name="twitter_url" value="{{ old('twitter_url', $settingsArray['twitter_url'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="https://twitter.com/farmvax">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ old('instagram_url', $settingsArray['instagram_url'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="https://instagram.com/farmvax">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $settingsArray['linkedin_url'] ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="https://linkedin.com/company/farmvax">
                    </div>
                </div>
            </div>

            <!-- System Preferences -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">System Preferences</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-semibold">Maintenance Mode</h4>
                            <p class="text-sm text-gray-600">Temporarily disable public access</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="maintenance_mode" value="1"
                                   {{ (old('maintenance_mode', $settingsArray['maintenance_mode'] ?? '0') == '1') ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#11455B]/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#11455B]"></div>
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select name="timezone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent">
                            <option value="Africa/Lagos" {{ (old('timezone', $settingsArray['timezone'] ?? 'Africa/Lagos') == 'Africa/Lagos') ? 'selected' : '' }}>Africa/Lagos (WAT)</option>
                            <option value="UTC" {{ (old('timezone', $settingsArray['timezone'] ?? '') == 'UTC') ? 'selected' : '' }}>UTC</option>
                            <option value="America/New_York" {{ (old('timezone', $settingsArray['timezone'] ?? '') == 'America/New_York') ? 'selected' : '' }}>America/New_York (EST)</option>
                            <option value="Europe/London" {{ (old('timezone', $settingsArray['timezone'] ?? '') == 'Europe/London') ? 'selected' : '' }}>Europe/London (GMT)</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <button type="reset" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Reset
            </button>
            <button type="submit" class="px-6 py-2 bg-[#11455B] text-white rounded-lg hover:bg-[#0d3345] transition font-semibold">
                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

<script>
    // Sync color picker with text input
    document.querySelectorAll('input[type="color"]').forEach(colorInput => {
        const textInput = colorInput.nextElementSibling.nextElementSibling;
        
        colorInput.addEventListener('input', function() {
            textInput.value = this.value;
            colorInput.nextElementSibling.nextElementSibling.value = this.value;
        });
    });
</script>

@endsection