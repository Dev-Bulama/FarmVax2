@extends('layouts.farmer')

@section('title', $livestock->tag_number ?? $livestock->name ?? 'Livestock Details')

@section('content')

<div class="p-6 max-w-6xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('individual.livestock.index') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Livestock
        </a>
        <div class="flex items-center justify-between mt-2">
            <div>
                <h1 class="text-3xl font-bold" style="color: #11455b;">
                    {{ $livestock->tag_number ?? $livestock->name ?? 'Animal #' . $livestock->id }}
                </h1>
                <p class="text-gray-600 mt-1">{{ ucfirst($livestock->livestock_type) }} · {{ ucfirst($livestock->gender) }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('individual.livestock.edit', $livestock->id) }}" 
                   class="px-4 py-2 border-2 font-semibold rounded-lg hover:bg-gray-50"
                   style="border-color: #11455b; color: #11455b;">
                    Edit
                </a>
                <form action="{{ route('individual.livestock.destroy', $livestock->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to delete this livestock?')"
                            class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Basic Details --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4" style="color: #11455b;">Basic Information</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Tag Number</p>
                        <p class="text-gray-900">{{ $livestock->tag_number ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Name</p>
                        <p class="text-gray-900">{{ $livestock->name ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Type</p>
                        <p class="text-gray-900">{{ ucfirst($livestock->livestock_type) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Breed</p>
                        <p class="text-gray-900">{{ $livestock->breed ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Gender</p>
                        <p class="text-gray-900">{{ ucfirst($livestock->gender) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Age</p>
                        <p class="text-gray-900">
                            @if($livestock->age_years || $livestock->age_months)
                                {{ $livestock->age_years }}y {{ $livestock->age_months }}m
                            @else
                                Not recorded
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Weight</p>
                        <p class="text-gray-900">{{ $livestock->weight_kg ? $livestock->weight_kg . ' kg' : 'Not recorded' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Health Status</p>
                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full 
                            {{ $livestock->health_status == 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($livestock->health_status) }}
                        </span>
                    </div>
                </div>

                @if($livestock->notes)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-sm text-gray-600 font-semibold mb-2">Notes</p>
                        <p class="text-gray-900">{{ $livestock->notes }}</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Herd Group --}}
            @if($livestock->herdGroup)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold mb-3" style="color: #11455b;">Herd Group</h3>
                    <div class="flex items-center">
                        <div class="w-4 h-12 rounded mr-3" style="background-color: {{ $livestock->herdGroup->color_code }};"></div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $livestock->herdGroup->name }}</p>
                            <p class="text-sm text-gray-600">{{ $livestock->herdGroup->livestock->count() }} animals</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Quick Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-3" style="color: #11455b;">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('individual.livestock.edit', $livestock->id) }}" 
                       class="block px-4 py-3 text-center font-semibold rounded-lg"
                       style="background-color: #e8f5e9; color: #11455b;">
                        ✏️ Edit Details
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection