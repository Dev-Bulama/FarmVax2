@extends('layouts.farmer')

@section('title', 'Farm Record - Step 2')
@section('page-title', 'Farm Record Submission')
@section('page-subtitle', 'Step 2 of 6: Livestock Inventory')

@section('content')
<div class="p-6">
    
    <!-- Progress Bar -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-green-600">Step 2 of 6</span>
            <span class="text-sm text-gray-500">33% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: 33%"></div>
        </div>
        
        <!-- Step Indicators -->
        <div class="mt-4 grid grid-cols-6 gap-2">
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Basic Info</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">2</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Livestock</p>
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
            <h2 class="text-xl font-bold text-gray-900">Livestock Inventory</h2>
            <p class="text-sm text-gray-600 mt-1">Please provide details about your livestock</p>
        </div>

<form method="POST" action="{{ route('farmer.farm-records.step2.store') }}" class="p-6">
                @csrf

            <div class="space-y-6">
                
                <!-- Cattle -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Cattle</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Cattle</label>
                            <input type="number" name="cattle_total" value="{{ old('cattle_total', session('farm_record.cattle_total', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Males</label>
                            <input type="number" name="cattle_male" value="{{ old('cattle_male', session('farm_record.cattle_male', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Females</label>
                            <input type="number" name="cattle_female" value="{{ old('cattle_female', session('farm_record.cattle_female', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Goats -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Goats</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Goats</label>
                            <input type="number" name="goat_total" value="{{ old('goat_total', session('farm_record.goat_total', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Males</label>
                            <input type="number" name="goat_male" value="{{ old('goat_male', session('farm_record.goat_male', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Females</label>
                            <input type="number" name="goat_female" value="{{ old('goat_female', session('farm_record.goat_female', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Sheep -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Sheep</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Sheep</label>
                            <input type="number" name="sheep_total" value="{{ old('sheep_total', session('farm_record.sheep_total', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Males</label>
                            <input type="number" name="sheep_male" value="{{ old('sheep_male', session('farm_record.sheep_male', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Females</label>
                            <input type="number" name="sheep_female" value="{{ old('sheep_female', session('farm_record.sheep_female', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Poultry -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Poultry</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Birds</label>
                            <input type="number" name="poultry_total" value="{{ old('poultry_total', session('farm_record.poultry_total', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                            <select name="poultry_type"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">Select type</option>
                                <option value="chicken" {{ old('poultry_type', session('farm_record.poultry_type')) == 'chicken' ? 'selected' : '' }}>Chicken</option>
                                <option value="turkey" {{ old('poultry_type', session('farm_record.poultry_type')) == 'turkey' ? 'selected' : '' }}>Turkey</option>
                                <option value="duck" {{ old('poultry_type', session('farm_record.poultry_type')) == 'duck' ? 'selected' : '' }}>Duck</option>
                                <option value="other" {{ old('poultry_type', session('farm_record.poultry_type')) == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Pigs -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">Pigs</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Pigs</label>
                            <input type="number" name="pig_total" value="{{ old('pig_total', session('farm_record.pig_total', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Males</label>
                            <input type="number" name="pig_male" value="{{ old('pig_male', session('farm_record.pig_male', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Females</label>
                            <input type="number" name="pig_female" value="{{ old('pig_female', session('farm_record.pig_female', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>
                </div>

                <!-- Other Livestock -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Other Livestock</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                            <input type="text" name="other_type" value="{{ old('other_type', session('farm_record.other_type')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="e.g., Fish, Rabbits">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Count</label>
                            <input type="number" name="other_total" value="{{ old('other_total', session('farm_record.other_total', 0)) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('individual.farm-records.step1') }}" class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Previous
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