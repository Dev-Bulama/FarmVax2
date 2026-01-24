@extends('layouts.admin')

@section('title', 'Edit Outbreak Alert')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Edit Outbreak Alert</h2>
            <p class="text-gray-600 mt-1">Update disease outbreak information</p>
        </div>
        <a href="{{ route('admin.outbreak-alerts.show', $alert->id) }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition">
            Cancel
        </a>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
@endif

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
<form action="{{ route('admin.outbreak-alerts.update', $alert->id) }}" method="POST">
    @csrf
    @method('PUT')

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
                        <input type="text" name="disease_name" value="{{ old('disease_name', $alert->disease_name) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="e.g., Foot and Mouth Disease (FMD)">
                        @error('disease_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Describe the outbreak situation...">{{ old('description', $alert->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Severity -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Severity Level <span class="text-red-500">*</span>
                        </label>
                        <select name="severity" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">-- Select Severity --</option>
                            <option value="low" {{ old('severity', $alert->severity) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('severity', $alert->severity) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('severity', $alert->severity) == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('severity', $alert->severity) == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('severity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Affected Species -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Affected Species</label>
                        <input type="text" name="affected_species" value="{{ old('affected_species', $alert->affected_species) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="e.g., Cattle, Goats, Sheep">
                        @error('affected_species')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cases & Deaths -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmed Cases</label>
                            <input type="number" name="confirmed_cases" value="{{ old('confirmed_cases', $alert->confirmed_cases ?? 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deaths</label>
                            <input type="number" name="deaths" value="{{ old('deaths', $alert->deaths ?? 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Location Information</h3>
                
                <div class="space-y-4">
                    <!-- Location Text -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location Details</label>
                        <textarea name="location" rows="2"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="Specific location description...">{{ old('location', $alert->location) }}</textarea>
                    </div>

                    <!-- Alert Radius -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alert Radius (km)</label>
                        <input type="number" name="radius_km" value="{{ old('radius_km', $alert->radius_km ?? 50) }}" min="1" max="500"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                               placeholder="50">
                        <p class="text-xs text-gray-500 mt-1">Farmers within this radius will be notified</p>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Additional Details</h3>
                
                <div class="space-y-4">
                    <!-- Symptoms -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Symptoms</label>
                        <textarea name="symptoms" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="List the symptoms observed in affected animals...">{{ old('symptoms', $alert->symptoms) }}</textarea>
                    </div>

                    <!-- Preventive Measures -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preventive Measures</label>
                        <textarea name="preventive_measures" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="What should farmers do to prevent or control the outbreak?">{{ old('preventive_measures', $alert->preventive_measures) }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Alert Status</h3>
                
                <div class="space-y-3">
                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $alert->is_active) ? 'checked' : '' }}
                               class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-700">Alert is Active</span>
                            <p class="text-xs text-gray-500 mt-1">Uncheck to deactivate this alert</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Alert Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="font-bold text-blue-900 mb-3">ℹ️ Alert Information</h4>
                <div class="space-y-2 text-sm text-blue-800">
                    <p><strong>Created:</strong> {{ $alert->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $alert->updated_at->format('M d, Y H:i') }}</p>
                    <p><strong>Created By:</strong> {{ $alert->reporter->name ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full px-6 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition flex items-center justify-center shadow-lg">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Outbreak Alert
            </button>

        </div>

    </div>

</form>

@endsection