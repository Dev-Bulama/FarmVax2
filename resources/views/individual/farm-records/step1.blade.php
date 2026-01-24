@extends('layouts.farmer')

@section('title', 'Farm Record - Step 1')
@section('page-title', 'Farm Record Submission')
@section('page-subtitle', 'Step 1 of 6: Basic Farm Information')

@section('content')
<div class="p-6">
    
    <!-- Progress Bar -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-green-600">Step 1 of 6</span>
            <span class="text-sm text-gray-500">17% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: 17%"></div>
        </div>
        
        <!-- Step Indicators -->
        <div class="mt-4 grid grid-cols-6 gap-2">
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Basic Info</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">2</div>
                <p class="text-xs mt-1 text-gray-500">Livestock</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">3</div>
                <p class="text-xs mt-1 text-gray-500">Health</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">4</div>
                <p class="text-xs mt-1 text-gray-500">Infrastructure</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">5</div>
                <p class="text-xs mt-1 text-gray-500">Alerts</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">6</div>
                <p class="text-xs mt-1 text-gray-500">Review</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Basic Farm Information</h2>
            <p class="text-sm text-gray-600 mt-1">Please provide your farm details</p>
        </div>

<form method="POST" action="{{ route('farmer.farm-records.step1.store') }}" class="p-6">
                @csrf

            <div class="space-y-6">
                
                <!-- Farm Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Farm Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="farm_name" value="{{ old('farm_name', session('farm_record.farm_name')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Enter your farm name">
                    @error('farm_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Farm Size -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Farm Size (Hectares) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="farm_size" value="{{ old('farm_size', session('farm_record.farm_size')) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="0.00">
                        @error('farm_size')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Years in Operation <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="years_operating" value="{{ old('years_operating', session('farm_record.years_operating')) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="0">
                        @error('years_operating')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Farm Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Farm Type <span class="text-red-500">*</span>
                    </label>
                    <select name="farm_type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">Select farm type</option>
                        <option value="commercial" {{ old('farm_type', session('farm_record.farm_type')) == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="subsistence" {{ old('farm_type', session('farm_record.farm_type')) == 'subsistence' ? 'selected' : '' }}>Subsistence</option>
                        <option value="mixed" {{ old('farm_type', session('farm_record.farm_type')) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                    @error('farm_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Primary Livestock -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Primary Livestock <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['cattle', 'goat', 'sheep', 'poultry', 'pig', 'fish', 'other'] as $type)
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="primary_livestock[]" value="{{ $type }}"
                                       {{ in_array($type, old('primary_livestock', session('farm_record.primary_livestock', []))) ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700 capitalize">{{ $type }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('primary_livestock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Farm Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Farm Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="farm_address" rows="3" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Enter complete farm address">{{ old('farm_address', session('farm_record.farm_address')) }}</textarea>
                    @error('farm_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- GPS Coordinates (Optional) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Latitude (Optional)</label>
                        <input type="text" name="latitude" value="{{ old('latitude', session('farm_record.latitude')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., 6.5244">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Longitude (Optional)</label>
                        <input type="text" name="longitude" value="{{ old('longitude', session('farm_record.longitude')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., 3.3792">
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('individual.dashboard') }}" class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition flex items-center">
                    Next Step
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection