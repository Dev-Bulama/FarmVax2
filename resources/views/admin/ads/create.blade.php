@extends('layouts.admin')

@section('title', 'Create Advertisement')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Create Advertisement</h2>
            <p class="text-gray-600 mt-1">Create a new ad to display to users</p>
        </div>
        <a href="{{ route('admin.ads.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition">
            Cancel
        </a>
    </div>
</div>

<!-- Error Messages -->
@if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex">
            <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<!-- Form -->
<form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>
                
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Advertisement Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Premium Livestock Feed">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Describe your product or service...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Advertisement Image</label>
                        <input type="file" name="image" accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Recommended: 1200x628px for banners, 300x250px for sidebar</p>
                    </div>

                    <!-- Link URL -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Link URL</label>
                        <input type="url" name="link_url" value="{{ old('link_url') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://example.com/product">
                        <p class="text-xs text-gray-500 mt-1">Where users go when they click the ad</p>
                    </div>

                    <!-- Ad Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Advertisement Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Select Type --</option>
                            <option value="banner" {{ old('type') == 'banner' ? 'selected' : '' }}>Banner (Top/Bottom)</option>
                            <option value="sidebar" {{ old('type') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                            <option value="inline" {{ old('type') == 'inline' ? 'selected' : '' }}>Inline (Within Content)</option>
                            <option value="popup" {{ old('type') == 'popup' ? 'selected' : '' }}>Popup</option>
                        </select>
                    </div>
                    <div class="mb-4">
    <label class="flex items-center cursor-pointer">
        <input type="checkbox" name="send_notification" value="1" class="h-4 w-4 text-blue-600 rounded">
        <span class="ml-2 text-sm text-gray-700">Send email notification to targeted users</span>
    </label>
</div>
                </div>
            </div>

            <!-- Schedule & Budget -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Schedule & Budget</h3>
                
                <div class="space-y-4">
                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Budget & CPC -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Budget</label>
                            <input type="number" step="0.01" name="budget" value="{{ old('budget') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                            <p class="text-xs text-gray-500 mt-1">Optional: Set a total budget</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Cost Per Click</label>
                            <input type="number" step="0.01" name="cost_per_click" value="{{ old('cost_per_click') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                            <p class="text-xs text-gray-500 mt-1">Optional: Track cost per click</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Target Audience -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Target Audience</h3>
                
                <div class="space-y-2">
                    @php
                        $audiences = [
                            'farmer' => 'Farmers',
                            'animal_health_professional' => 'Animal Health Professionals',
                            'volunteer' => 'Volunteers',
                            'data_collector' => 'Data Collectors',
                            'all' => 'All Users'
                        ];
                    @endphp
                    
                    @foreach($audiences as $value => $label)
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="target_audience[]" value="{{ $value }}"
                               {{ in_array($value, old('target_audience', [])) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm font-medium text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
                
                <label class="flex items-center p-4 border-2 border-green-200 bg-green-50 rounded-lg cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                           class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <div class="ml-3">
                        <span class="text-sm font-semibold text-gray-900">Activate Advertisement</span>
                        <p class="text-xs text-gray-600 mt-1">Make this ad visible to users immediately</p>
                    </div>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full px-6 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition flex items-center justify-center shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Create Advertisement
            </button>

        </div>

    </div>

</form>

@endsection