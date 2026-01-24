@extends('layouts.farmer')

@section('title', 'Add New Livestock')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('farmer.livestock.index') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Livestock
        </a>
        <h1 class="text-3xl font-bold mt-2" style="color: #11455b;">Add New Livestock</h1>
        <p class="text-gray-600 mt-1">Register a new animal to your farm</p>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
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

    <form action="{{ route('farmer.livestock.store') }}" method="POST">
        @csrf

        {{-- Basic Information --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">📋 Basic Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Tag Number --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tag Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tag_number" value="{{ old('tag_number') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="e.g., COW-001">
                </div>

                {{-- Name (Optional) --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name (Optional)</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="e.g., Bessie">
                </div>

                {{-- Livestock Type --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Livestock Type <span class="text-red-500">*</span>
                    </label>
                    <select name="livestock_type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        <option value="">Select Type</option>
                        <option value="cattle" {{ old('livestock_type') == 'cattle' ? 'selected' : '' }}>🐄 Cattle</option>
                        <option value="goats" {{ old('livestock_type') == 'goats' ? 'selected' : '' }}>🐐 Goats</option>
                        <option value="sheep" {{ old('livestock_type') == 'sheep' ? 'selected' : '' }}>🐑 Sheep</option>
                        <option value="pigs" {{ old('livestock_type') == 'pigs' ? 'selected' : '' }}>🐷 Pigs</option>
                        <option value="poultry" {{ old('livestock_type') == 'poultry' ? 'selected' : '' }}>🐔 Poultry</option>
                        <option value="fish" {{ old('livestock_type') == 'fish' ? 'selected' : '' }}>🐟 Fish</option>
                    </select>
                </div>

                {{-- Breed --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Breed</label>
                    <input type="text" name="breed" value="{{ old('breed') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="e.g., Holstein, Boer">
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Gender <span class="text-red-500">*</span>
                    </label>
                    <select name="gender" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                {{-- Date of Birth --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                </div>

                {{-- Age Years --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Age (Years)</label>
                    <input type="number" name="age_years" value="{{ old('age_years') }}" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                </div>

                {{-- Age Months --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Age (Months)</label>
                    <input type="number" name="age_months" value="{{ old('age_months') }}" min="0" max="11"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                </div>

            </div>
        </div>

        {{-- Health Information --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🏥 Health Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Health Status --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Health Status <span class="text-red-500">*</span>
                    </label>
                    <select name="health_status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        <option value="healthy" {{ old('health_status') == 'healthy' ? 'selected' : '' }}>Healthy</option>
                        <option value="sick" {{ old('health_status') == 'sick' ? 'selected' : '' }}>Sick</option>
                        <option value="under_treatment" {{ old('health_status') == 'under_treatment' ? 'selected' : '' }}>Under Treatment</option>
                        <option value="deceased" {{ old('health_status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                </div>

                {{-- Weight --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" name="weight_kg" value="{{ old('weight_kg') }}" min="0" step="0.1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                </div>

                {{-- Color/Markings --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Color/Markings</label>
                    <input type="text" name="color_markings" value="{{ old('color_markings') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="e.g., Black and white spotted">
                </div>

                {{-- Vaccination Status --}}
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_vaccinated" value="1" {{ old('is_vaccinated') ? 'checked' : '' }}
                               class="h-5 w-5 rounded" style="color: #2fcb6e;">
                        <span class="ml-2 text-sm font-semibold text-gray-700">This animal has been vaccinated</span>
                    </label>
                </div>

            </div>
        </div>

        {{-- Additional Information --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">📝 Additional Information</h2>
            
            {{-- Herd Group --}}
            @if(isset($herdGroups) && $herdGroups->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Assign to Herd Group (Optional)</label>
                    <select name="herd_group_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        <option value="">No Herd Group</option>
                        @foreach($herdGroups as $herd)
                            <option value="{{ $herd->id }}" {{ old('herd_group_id') == $herd->id ? 'selected' : '' }}>
                                {{ $herd->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                          placeholder="Any additional information about this animal...">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-between bg-white rounded-lg shadow p-6">
            <a href="{{ route('farmer.livestock.index') }}" 
               class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
                    style="background-color: #2fcb6e;">
                Add Livestock
            </button>
        </div>

    </form>

</div>

@endsection