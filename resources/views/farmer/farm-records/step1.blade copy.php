@extends('layouts.farmer')

@section('title', 'Farm Record - Step 1')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <!-- Progress Bar -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-green-600">Step 1 of 3</span>
            <span class="text-sm text-gray-500">33% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: 33%"></div>
        </div>
        
        <!-- Step Indicators -->
        <div class="mt-4 grid grid-cols-3 gap-2">
            <div class="text-center">
                <div class="w-10 h-10 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Basic Info</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                <p class="text-xs mt-1 text-gray-500">Livestock</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                <p class="text-xs mt-1 text-gray-500">Health</p>
            </div>
        </div>
    </div>

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
                    <input type="text" name="farm_name" value="{{ old('farm_name', $farmRecordData['step1']['farm_name'] ?? '') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="Enter your farm name">
                    @error('farm_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Farm Size & Unit -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Farm Size <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="farm_size" value="{{ old('farm_size', $farmRecordData['step1']['farm_size'] ?? '') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., 5">
                        @error('farm_size')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Unit <span class="text-red-500">*</span>
                        </label>
                        <select name="farm_size_unit" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">-- Select Unit --</option>
                            <option value="acres" {{ old('farm_size_unit', $farmRecordData['step1']['farm_size_unit'] ?? '') == 'acres' ? 'selected' : '' }}>Acres</option>
                            <option value="hectares" {{ old('farm_size_unit', $farmRecordData['step1']['farm_size_unit'] ?? '') == 'hectares' ? 'selected' : '' }}>Hectares</option>
                        </select>
                        @error('farm_size_unit')
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
                        <option value="">-- Select Farm Type --</option>
                        <option value="commercial" {{ old('farm_type', $farmRecordData['step1']['farm_type'] ?? '') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                        <option value="subsistence" {{ old('farm_type', $farmRecordData['step1']['farm_type'] ?? '') == 'subsistence' ? 'selected' : '' }}>Subsistence</option>
                        <option value="mixed" {{ old('farm_type', $farmRecordData['step1']['farm_type'] ?? '') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                    </select>
                    @error('farm_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Farm Address -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Farm Address</label>
                    <textarea name="farmer_address" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Enter farm address or location">{{ old('farmer_address', $farmRecordData['step1']['farmer_address'] ?? '') }}</textarea>
                    @error('farmer_address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- State & LGA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">State</label>
                        <input type="text" name="farmer_state" value="{{ old('farmer_state', $farmRecordData['step1']['farmer_state'] ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., Lagos">
                        @error('farmer_state')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">LGA/District</label>
                        <input type="text" name="farmer_lga" value="{{ old('farmer_lga', $farmRecordData['step1']['farmer_lga'] ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., Ikeja">
                        @error('farmer_lga')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- GPS Coordinates (Optional) -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-900 mb-3">📍 GPS Coordinates (Optional)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Latitude</label>
                            <input type="number" step="any" name="latitude" value="{{ old('latitude', $farmRecordData['step1']['latitude'] ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="e.g., 6.5244">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Longitude</label>
                            <input type="number" step="any" name="longitude" value="{{ old('longitude', $farmRecordData['step1']['longitude'] ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="e.g., 3.3792">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('farmer.dashboard') }}" class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition">
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