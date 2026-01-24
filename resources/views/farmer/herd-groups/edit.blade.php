@extends('layouts.farmer')

@section('title', 'Edit Herd Group')

@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('farmer.herd-groups.show', $herdGroup->id) }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
                <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Herd Details
            </a>
            <h1 class="text-3xl font-bold mt-2" style="color: #11455b;">Edit Herd Group</h1>
            <p class="text-gray-600 mt-1">Update herd group information</p>
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

        <form action="{{ route('farmer.herd-groups.update', $herdGroup->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-bold mb-4" style="color: #11455b;">📋 Basic Information</h2>
                
                <div class="space-y-4">
                    
                    {{-- Herd Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Herd Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $herdGroup->name) }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                               style="focus:ring-color: #2fcb6e;"
                               placeholder="e.g., Dairy Cows A, Breeding Goats">
                    </div>

                    {{-- Type and Purpose --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Livestock Type</label>
                            <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="cattle" {{ old('type', $herdGroup->type) == 'cattle' ? 'selected' : '' }}>🐄 Cattle</option>
                                <option value="goats" {{ old('type', $herdGroup->type) == 'goats' ? 'selected' : '' }}>🐐 Goats</option>
                                <option value="sheep" {{ old('type', $herdGroup->type) == 'sheep' ? 'selected' : '' }}>🐑 Sheep</option>
                                <option value="pigs" {{ old('type', $herdGroup->type) == 'pigs' ? 'selected' : '' }}>🐷 Pigs</option>
                                <option value="poultry" {{ old('type', $herdGroup->type) == 'poultry' ? 'selected' : '' }}>🐔 Poultry</option>
                                <option value="mixed" {{ old('type', $herdGroup->type) == 'mixed' ? 'selected' : '' }}>🦙 Mixed</option>
                            </select>
                        </div>

                        {{-- Purpose --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Purpose</label>
                            <select name="purpose" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                <option value="">Select Purpose</option>
                                <option value="dairy" {{ old('purpose', $herdGroup->purpose) == 'dairy' ? 'selected' : '' }}>Dairy</option>
                                <option value="meat" {{ old('purpose', $herdGroup->purpose) == 'meat' ? 'selected' : '' }}>Meat</option>
                                <option value="breeding" {{ old('purpose', $herdGroup->purpose) == 'breeding' ? 'selected' : '' }}>Breeding</option>
                                <option value="eggs" {{ old('purpose', $herdGroup->purpose) == 'eggs' ? 'selected' : '' }}>Eggs</option>
                                <option value="mixed" {{ old('purpose', $herdGroup->purpose) == 'mixed' ? 'selected' : '' }}>Mixed</option>
                            </select>
                        </div>

                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                  placeholder="Describe this herd group...">{{ old('description', $herdGroup->description) }}</textarea>
                    </div>

                    {{-- Location --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <input type="text" name="location" value="{{ old('location', $herdGroup->location) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                               placeholder="e.g., North Paddock, Barn 2, Section A">
                    </div>

                    {{-- Color Code --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Color Tag</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color_code" value="{{ old('color_code', $herdGroup->color_code) }}"
                                   class="h-12 w-20 border-2 border-gray-300 rounded cursor-pointer">
                            <span class="text-sm text-gray-600">Choose a color to easily identify this herd</span>
                        </div>
                    </div>

                    {{-- Active Status --}}
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" 
                                   {{ old('is_active', $herdGroup->is_active) ? 'checked' : '' }}
                                   class="h-5 w-5 rounded" style="color: #2fcb6e;">
                            <span class="ml-2 text-sm font-semibold text-gray-700">Herd is Active</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1 ml-7">Inactive herds won't appear in livestock assignment</p>
                    </div>

                </div>
            </div>

            {{-- Current Livestock Info --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded mb-6">
                <p class="text-sm text-blue-800">
                    ℹ️ This herd currently has <strong>{{ $herdGroup->livestock->count() }}</strong> animal(s). 
                    You can add or remove animals from the herd details page.
                </p>
            </div>

            {{-- Submit Buttons --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <a href="{{ route('farmer.herd-groups.show', $herdGroup->id) }}" 
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
                            style="background-color: #2fcb6e;">
                        Update Herd Group
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection