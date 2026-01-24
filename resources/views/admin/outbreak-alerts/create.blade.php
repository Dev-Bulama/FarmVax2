@extends('layouts.admin')

@section('title', 'Create Outbreak Alert')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Create Outbreak Alert</h2>
            <p class="text-gray-600 mt-1">Send urgent disease outbreak notifications to affected farmers</p>
        </div>
        <a href="{{ route('admin.outbreak-alerts.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition">
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
<form action="{{ route('admin.outbreak-alerts.store') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>
                
                <div class="space-y-4">
                    <!-- Disease Name -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Disease Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="disease_name" value="{{ old('disease_name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="e.g., Foot and Mouth Disease (FMD)">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Describe the outbreak situation...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Severity -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Severity Level <span class="text-red-500">*</span>
                        </label>
                        <select name="severity" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">-- Select Severity --</option>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>

                    <!-- Affected Species -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Affected Species</label>
                        <input type="text" name="affected_species" value="{{ old('affected_species') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="e.g., Cattle, Goats, Sheep">
                    </div>

                    <!-- Cases & Deaths -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmed Cases</label>
                            <input type="number" name="confirmed_cases" value="{{ old('confirmed_cases', 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deaths</label>
                            <input type="number" name="deaths" value="{{ old('deaths', 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location & Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Location & Additional Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location Details</label>
                        <textarea name="location" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Describe the location...">{{ old('location') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Symptoms</label>
                        <textarea name="symptoms" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="List symptoms...">{{ old('symptoms') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preventive Measures</label>
                        <textarea name="preventive_measures" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="What should farmers do?">{{ old('preventive_measures') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Alert Settings</h3>
                
                <div class="space-y-3">
                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-700">Activate Alert</span>
                            <p class="text-xs text-gray-500 mt-1">Make this alert active immediately</p>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="send_notifications" value="1" checked
                               class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-700">Send Notifications</span>
                            <p class="text-xs text-gray-500 mt-1">Alert affected farmers via SMS & Email</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full px-6 py-4 bg-red-600 text-white rounded-lg hover:bg-red-700 font-bold transition flex items-center justify-center shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Create Alert & Notify Farmers
            </button>

        </div>

    </div>

</form>
@endsection