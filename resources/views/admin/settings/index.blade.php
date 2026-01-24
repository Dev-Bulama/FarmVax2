@extends('layouts.admin')

@section('title', 'General Settings')
@section('page-title', 'General Settings')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Site Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'FarmVax') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Site Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                <textarea name="site_description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
            </div>

            <!-- Site Logo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                @if(isset($settings['site_logo']) && $settings['site_logo'])
                    <img src="{{ asset($settings['site_logo']) }}" alt="Current Logo" class="h-16 mb-2">
                @endif
                <input type="file" name="site_logo" accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>

            <!-- Contact Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Contact Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>

            <!-- Feature Toggles -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Feature Toggles</h3>
                
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="enable_ads" value="1" 
                               {{ (old('enable_ads', $settings['enable_ads'] ?? '1') == '1') ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Enable Advertisements</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="enable_outbreak_alerts" value="1"
                               {{ (old('enable_outbreak_alerts', $settings['enable_outbreak_alerts'] ?? '1') == '1') ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">Enable Outbreak Alerts</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fas fa-save mr-2"></i> Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
