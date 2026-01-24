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
                                  placeholder="Describe the outbreak situation, how it started, and current status...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Severity & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Severity Level <span class="text-red-500">*</span>
                            </label>
                            <select name="severity" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">-- Select Severity --</option>
                                <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('severity')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                <option value="">-- Select Status --</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="contained" {{ old('status') == 'contained' ? 'selected' : '' }}>Contained</option>
                                <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Outbreak Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Outbreak Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="outbreak_date" value="{{ old('outbreak_date', date('Y-m-d')) }}" required
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        @error('outbreak_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cases & Deaths -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmed Cases</label>
                            <input type="number" name="confirmed_cases" value="{{ old('confirmed_cases', 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deaths</label>
                            <input type="number" name="deaths" value="{{ old('deaths', 0) }}" min="0"
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
                    <!-- State, LGA, Village -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">State</label>
                            <input type="text" name="location_state" value="{{ old('location_state') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="e.g., Lagos">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">LGA/District</label>
                            <input type="text" name="location_lga" value="{{ old('location_lga') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="e.g., Ikeja">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Village/Town</label>
                            <input type="text" name="location_village" value="{{ old('location_village') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="e.g., Allen Avenue">
                        </div>
                    </div>

                    <!-- GPS Coordinates & Radius -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Latitude</label>
                            <input type="number" step="any" name="latitude" value="{{ old('latitude') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="6.5244">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Longitude</label>
                            <input type="number" step="any" name="longitude" value="{{ old('longitude') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="3.3792">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alert Radius (km) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="radius_km" value="{{ old('radius_km', 50) }}" required min="1" max="500"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="50">
                        </div>
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
                                  placeholder="List the symptoms observed in affected animals...">{{ old('symptoms') }}</textarea>
                    </div>

                    <!-- Precautions -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Precautions & Recommendations</label>
                        <textarea name="precautions" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                  placeholder="What should farmers do to prevent or control the outbreak?">{{ old('precautions') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Affected Animals -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Affected Animals</h3>
                
                <div class="space-y-2">
                    @php
                        $animalTypes = ['cattle', 'goat', 'sheep', 'poultry', 'pig', 'other'];
                    @endphp
                    
                    @foreach($animalTypes as $animal)
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="affected_animals[]" value="{{ $animal }}"
                               {{ in_array($animal, old('affected_animals', [])) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm font-medium text-gray-700">{{ ucfirst($animal) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Notification Settings</h3>
                
                <div class="space-y-3">
                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="send_notifications" value="1" checked
                               class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-700">Send Notifications Now</span>
                            <p class="text-xs text-gray-500 mt-1">Alert all affected farmers immediately via SMS and Email</p>
                        </div>
                    </label>
                </div>

                <!-- Info -->
                <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                    <p class="text-xs text-blue-800">
                        <strong>Note:</strong> Farmers within the specified radius and location will receive this alert automatically.
                    </p>
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