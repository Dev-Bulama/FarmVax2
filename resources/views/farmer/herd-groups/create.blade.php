@extends('layouts.farmer')

@section('title', 'Create Herd Group')

@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('farmer.herd-groups.index') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Herd Groups
            </a>
            <h1 class="text-3xl font-bold mt-2" style="color: #11455b;">Create New Herd Group</h1>
            <p class="text-gray-600 mt-1">Organize your livestock into manageable groups</p>
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

        <form action="{{ route('farmer.herd-groups.store') }}" method="POST">
            @csrf

            {{-- Basic Information --}}
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4" style="color: #11455b;">📋 Basic Information</h2>
                
                <div class="space-y-4">
                    
                    {{-- Herd Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Herd Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                               style="focus:ring-color: #2fcb6e;"
                               placeholder="e.g., Dairy Cows A, Breeding Goats, Young Calves">
                        <p class="text-xs text-gray-500 mt-1">Give your herd a descriptive name</p>
                    </div>

                    {{-- Type and Purpose --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Livestock Type</label>
                            <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="cattle" {{ old('type') == 'cattle' ? 'selected' : '' }}>🐄 Cattle</option>
                                <option value="goats" {{ old('type') == 'goats' ? 'selected' : '' }}>🐐 Goats</option>
                                <option value="sheep" {{ old('type') == 'sheep' ? 'selected' : '' }}>🐑 Sheep</option>
                                <option value="pigs" {{ old('type') == 'pigs' ? 'selected' : '' }}>🐷 Pigs</option>
                                <option value="poultry" {{ old('type') == 'poultry' ? 'selected' : '' }}>🐔 Poultry</option>
                                <option value="mixed" {{ old('type') == 'mixed' ? 'selected' : '' }}>🦙 Mixed</option>
                            </select>
                        </div>

                        {{-- Purpose --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Purpose</label>
                            <select name="purpose" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                <option value="">Select Purpose</option>
                                <option value="dairy" {{ old('purpose') == 'dairy' ? 'selected' : '' }}>Dairy</option>
                                <option value="meat" {{ old('purpose') == 'meat' ? 'selected' : '' }}>Meat</option>
                                <option value="breeding" {{ old('purpose') == 'breeding' ? 'selected' : '' }}>Breeding</option>
                                <option value="eggs" {{ old('purpose') == 'eggs' ? 'selected' : '' }}>Eggs</option>
                                <option value="mixed" {{ old('purpose') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                        </div>

                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                  placeholder="Describe this herd group...">{{ old('description') }}</textarea>
                    </div>

                    {{-- Location --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                               placeholder="e.g., North Paddock, Barn 2, Section A">
                        <p class="text-xs text-gray-500 mt-1">Where is this herd located on your farm?</p>
                    </div>

                    {{-- Color Code --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Color Tag</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color_code" value="{{ old('color_code', '#2fcb6e') }}"
                                   class="h-12 w-20 border-2 border-gray-300 rounded cursor-pointer">
                            <span class="text-sm text-gray-600">Choose a color to easily identify this herd</span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Assign Livestock --}}
            @if($unassignedLivestock->count() > 0)
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🐄 Assign Livestock (Optional)</h2>
                    <p class="text-sm text-gray-600 mb-4">Select animals to add to this herd. You can also add them later.</p>
                    
                    <div class="space-y-2 max-h-96 overflow-y-auto p-2 border border-gray-200 rounded-lg">
                        @foreach($unassignedLivestock as $animal)
                            <label class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer transition">
                                <input type="checkbox" name="livestock_ids[]" value="{{ $animal->id }}"
                                       class="h-5 w-5 rounded"
                                       style="color: #2fcb6e;">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-gray-900">
                                            {{ $animal->tag_number ?? $animal->name ?? 'Animal #' . $animal->id }}
                                        </span>
                                        <span class="text-sm text-gray-600">
                                            {{ ucfirst($animal->livestock_type) }}
                                            @if($animal->breed)
                                                · {{ $animal->breed }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ ucfirst($animal->gender) }}
                                        @if($animal->age_years || $animal->age_months)
                                            · Age: {{ $animal->age_years }}y {{ $animal->age_months }}m
                                        @endif
                                        · Health: 
                                        <span class="{{ $animal->health_status == 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ ucfirst($animal->health_status) }}
                                        </span>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <p class="text-xs text-gray-500 mt-3">
                        {{ $unassignedLivestock->count() }} unassigned animal(s) available
                    </p>
                </div>
            @else
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
                    <p class="text-sm text-blue-800">
                        ℹ️ You don't have any unassigned livestock. All your animals are already in herds or you can add livestock later.
                    </p>
                </div>
            @endif

            {{-- Submit Buttons --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('farmer.herd-groups.index') }}" 
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
                            style="background-color: #2fcb6e;">
                        Create Herd Group
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection