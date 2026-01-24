@extends('layouts.farmer')

@section('title', 'Add New Livestock')
@section('page-title', 'Add New Livestock')
@section('page-subtitle', 'Register a new animal to your herd')

@section('content')
<div class="p-6">

    <div class="max-w-4xl mx-auto">
        
        <!-- Info Alert -->
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex">
                <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-green-700">Keep accurate records of your livestock for better health management and traceability.</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Livestock Details</h2>
            </div>

            <form method="POST" action="{{ route('individual.livestock.store') }}" class="p-6">
                @csrf

                <div class="space-y-6">
                    
                    <!-- Livestock Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Livestock Type <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @php
                                $types = [
                                    'cattle' => ['name' => 'Cattle', 'icon' => '🐄'],
                                    'goat' => ['name' => 'Goat', 'icon' => '🐐'],
                                    'sheep' => ['name' => 'Sheep', 'icon' => '🐑'],
                                    'poultry' => ['name' => 'Poultry', 'icon' => '🐔'],
                                    'pig' => ['name' => 'Pig', 'icon' => '🐷'],
                                    'other' => ['name' => 'Other', 'icon' => '🦙'],
                                ];
                            @endphp
                            @foreach($types as $key => $type)
                                <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('livestock_type') == $key ? 'border-green-500 bg-green-50' : '' }}">
                                    <input type="radio" name="livestock_type" value="{{ $key }}" required
                                           {{ old('livestock_type') == $key ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500">
                                    <div class="ml-3">
                                        <p class="text-2xl">{{ $type['icon'] }}</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $type['name'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('livestock_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Basic Information -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tag Number -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tag/ID Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tag_number" value="{{ old('tag_number') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="e.g., CTL-001">
                                <p class="text-xs text-gray-500 mt-1">Unique identifier for this animal</p>
                                @error('tag_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Breed -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Breed
                                </label>
                                <input type="text" name="breed" value="{{ old('breed') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="e.g., Holstein, Boer">
                                @error('breed')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 flex-1 {{ old('gender') == 'male' ? 'border-green-500 bg-green-50' : '' }}">
                                        <input type="radio" name="gender" value="male" required
                                               {{ old('gender') == 'male' ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm font-semibold text-gray-900">Male</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 flex-1 {{ old('gender') == 'female' ? 'border-green-500 bg-green-50' : '' }}">
                                        <input type="radio" name="gender" value="female" required
                                               {{ old('gender') == 'female' ? 'checked' : '' }}
                                               class="h-4 w-4 text-green-600 focus:ring-green-500">
                                        <span class="ml-2 text-sm font-semibold text-gray-900">Female</span>
                                    </label>
                                </div>
                                @error('gender')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Date of Birth
                                </label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('date_of_birth')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Health Status -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Health Status</h3>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Current Health Status <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <label class="flex items-center justify-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('health_status') == 'healthy' ? 'border-green-500 bg-green-50' : '' }}">
                                    <input type="radio" name="health_status" value="healthy" required
                                           {{ old('health_status') == 'healthy' ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500">
                                    <span class="ml-2 text-sm font-semibold text-green-700">Healthy</span>
                                </label>

                                <label class="flex items-center justify-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('health_status') == 'sick' ? 'border-red-500 bg-red-50' : '' }}">
                                    <input type="radio" name="health_status" value="sick" required
                                           {{ old('health_status') == 'sick' ? 'checked' : '' }}
                                           class="h-4 w-4 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm font-semibold text-red-700">Sick</span>
                                </label>

                                <label class="flex items-center justify-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('health_status') == 'under_treatment' ? 'border-yellow-500 bg-yellow-50' : '' }}">
                                    <input type="radio" name="health_status" value="under_treatment" required
                                           {{ old('health_status') == 'under_treatment' ? 'checked' : '' }}
                                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500">
                                    <span class="ml-2 text-sm font-semibold text-yellow-700">Treatment</span>
                                </label>

                                <label class="flex items-center justify-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('health_status') == 'recovering' ? 'border-blue-500 bg-blue-50' : '' }}">
                                    <input type="radio" name="health_status" value="recovering" required
                                           {{ old('health_status') == 'recovering' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm font-semibold text-blue-700">Recovering</span>
                                </label>
                            </div>
                            @error('health_status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Acquisition Details -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Acquisition Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Acquisition Date -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Acquisition Date
                                </label>
                                <input type="date" name="acquisition_date" value="{{ old('acquisition_date') }}"
                                       max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('acquisition_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Acquisition Method -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    How Acquired
                                </label>
                                <select name="acquisition_method"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Select method</option>
                                    <option value="birth" {{ old('acquisition_method') == 'birth' ? 'selected' : '' }}>Born on farm</option>
                                    <option value="purchase" {{ old('acquisition_method') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                                    <option value="gift" {{ old('acquisition_method') == 'gift' ? 'selected' : '' }}>Gift</option>
                                    <option value="other" {{ old('acquisition_method') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('acquisition_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="border-t pt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Additional Notes
                        </label>
                        <textarea name="notes" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Any additional information about this animal (medical history, behavior, special needs, etc.)">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('individual.livestock.index') }}" class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Add Livestock
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection