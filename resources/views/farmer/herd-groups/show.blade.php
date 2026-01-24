@extends('layouts.farmer')

@section('title', $herdGroup->name)

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('farmer.herd-groups.index') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Herd Groups
        </a>
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center">
                <div class="w-4 h-12 rounded mr-4" style="background-color: {{ $herdGroup->color_code }};"></div>
                <div>
                    <h1 class="text-3xl font-bold" style="color: #11455b;">{{ $herdGroup->name }}</h1>
                    <p class="text-gray-600 mt-1">
                        @if($herdGroup->type)
                            {{ ucfirst($herdGroup->type) }}
                        @endif
                        @if($herdGroup->purpose)
                            · {{ ucfirst($herdGroup->purpose) }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('farmer.herd-groups.edit', $herdGroup->id) }}" 
                   class="px-4 py-2 border-2 font-semibold rounded-lg hover:bg-gray-50"
                   style="border-color: #11455b; color: #11455b;">
                    Edit Herd
                </a>
                <form action="{{ route('farmer.herd-groups.toggle-status', $herdGroup->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 font-semibold rounded-lg"
                            style="background-color: {{ $herdGroup->is_active ? '#6b7280' : '#2fcb6e' }}; color: white;">
                        {{ $herdGroup->is_active ? 'Deactivate' : 'Activate' }}
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

    {{-- Status Alert --}}
    @if(!$herdGroup->is_active)
        <div class="mb-6 bg-gray-50 border-l-4 border-gray-400 p-4 rounded-lg">
            <p class="text-gray-800 font-semibold">⚠️ This herd is currently inactive</p>
        </div>
    @endif

    @if($herdGroup->sick_count > 0)
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
            <p class="text-red-800 font-semibold">⚠️ This herd needs attention - {{ $herdGroup->sick_count }} sick animal(s)</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600 font-semibold mb-1">Total Animals</p>
                    <h3 class="text-3xl font-bold" style="color: #11455b;">{{ $stats['total'] }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600 font-semibold mb-1">Healthy</p>
                    <h3 class="text-3xl font-bold text-green-600">{{ $stats['healthy'] }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600 font-semibold mb-1">Sick</p>
                    <h3 class="text-3xl font-bold text-red-600">{{ $stats['sick'] }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600 font-semibold mb-1">Avg Age</p>
                    <h3 class="text-3xl font-bold text-purple-600">{{ $stats['average_age'] }}<span class="text-sm">mo</span></h3>
                </div>

            </div>

            {{-- Health & Vaccination Overview --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4" style="color: #11455b;">📊 Health Overview</h2>
                
                <div class="space-y-4">
                    {{-- Health Score --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700">Health Score</span>
                            <span class="text-lg font-bold" style="color: {{ $stats['health_percentage'] >= 80 ? '#2fcb6e' : '#ef4444' }};">
                                {{ $stats['health_percentage'] }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="h-4 rounded-full transition-all" 
                                 style="width: {{ $stats['health_percentage'] }}%; background-color: {{ $stats['health_percentage'] >= 80 ? '#2fcb6e' : ($stats['health_percentage'] >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                        </div>
                    </div>

                    {{-- Vaccination Coverage --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-semibold text-gray-700">Vaccination Coverage</span>
                            <span class="text-lg font-bold text-blue-600">{{ $stats['vaccination_coverage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-blue-500 h-4 rounded-full transition-all" 
                                 style="width: {{ $stats['vaccination_coverage'] }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Livestock List --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h2 class="text-xl font-bold" style="color: #11455b;">🐄 Livestock in this Herd</h2>
                    @if($unassignedLivestock->count() > 0)
                        <button onclick="document.getElementById('add-livestock-modal').classList.remove('hidden')"
                                class="px-4 py-2 text-sm font-semibold rounded-lg"
                                style="background-color: #2fcb6e; color: white;">
                            + Add Livestock
                        </button>
                    @endif
                </div>

                <div class="p-6">
                    @if($herdGroup->livestock->count() > 0)
                        <div class="space-y-3">
                            @foreach($herdGroup->livestock as $animal)
                                <div class="flex items-center justify-between p-4 border-2 rounded-lg hover:shadow transition">
                                    <div class="flex items-center flex-1">
                                        <div class="w-2 h-12 rounded mr-4" style="background-color: {{ $herdGroup->color_code }};"></div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900">
                                                {{ $animal->tag_number ?? $animal->name ?? 'Animal #' . $animal->id }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                {{ ucfirst($animal->livestock_type) }}
                                                @if($animal->breed)
                                                    · {{ $animal->breed }}
                                                @endif
                                                · {{ ucfirst($animal->gender) }}
                                                @if($animal->age_years || $animal->age_months)
                                                    · {{ $animal->age_years }}y {{ $animal->age_months }}m
                                                @endif
                                            </p>
                                            <div class="flex gap-2 mt-1">
                                                <span class="px-2 py-1 text-xs font-semibold rounded"
                                                      style="background-color: {{ $animal->health_status == 'healthy' ? '#d1fae5' : '#fee2e2' }}; color: {{ $animal->health_status == 'healthy' ? '#065f46' : '#991b1b' }};">
                                                    {{ ucfirst($animal->health_status) }}
                                                </span>
                                                @if($animal->is_vaccinated)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                                        Vaccinated
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('farmer.livestock.show', $animal->id) }}" 
                                           class="px-3 py-2 text-sm font-semibold rounded hover:bg-gray-100">
                                            View
                                        </a>
                                        <form action="{{ route('farmer.herd-groups.remove-livestock', $herdGroup->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="livestock_id" value="{{ $animal->id }}">
                                            <button type="submit" 
                                                    onclick="return confirm('Remove this animal from the herd?')"
                                                    class="px-3 py-2 text-sm font-semibold text-red-600 rounded hover:bg-red-50">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="text-gray-500 mb-4">No livestock in this herd yet</p>
                            @if($unassignedLivestock->count() > 0)
                                <button onclick="document.getElementById('add-livestock-modal').classList.remove('hidden')"
                                        class="px-6 py-3 font-semibold rounded-lg"
                                        style="background-color: #2fcb6e; color: white;">
                                    Add Livestock Now
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Recent Vaccinations --}}
            @if($recentVaccinations->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4" style="color: #11455b;">💉 Recent Vaccinations (Last 30 Days)</h2>
                    <div class="space-y-3">
                        @foreach($recentVaccinations->take(5) as $vaccination)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $vaccination->vaccine_name }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $vaccination->livestock->tag_number ?? 'Animal #' . $vaccination->livestock_id }}
                                        · {{ $vaccination->vaccination_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                    Completed
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Herd Details --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #11455b;">Herd Details</h3>
                
                <div class="space-y-3">
                    @if($herdGroup->description)
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Description</p>
                            <p class="text-gray-900">{{ $herdGroup->description }}</p>
                        </div>
                    @endif

                    @if($herdGroup->location)
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Location</p>
                            <p class="text-gray-900">📍 {{ $herdGroup->location }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Created</p>
                        <p class="text-gray-900">{{ $herdGroup->created_at->format('M d, Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Last Updated</p>
                        <p class="text-gray-900">{{ $herdGroup->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #11455b;">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('farmer.herd-groups.edit', $herdGroup->id) }}" 
                       class="block px-4 py-3 text-center font-semibold rounded-lg transition"
                       style="background-color: #e8f5e9; color: #11455b;">
                        ✏️ Edit Herd
                    </a>
                    <form action="{{ route('farmer.herd-groups.destroy', $herdGroup->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure? All livestock will be unassigned.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-3 text-center font-semibold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                            🗑️ Delete Herd
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>

    {{-- Add Livestock Modal --}}
    @if($unassignedLivestock->count() > 0)
        <div id="add-livestock-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-hidden">
                <div class="p-6 border-b">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold" style="color: #11455b;">Add Livestock to Herd</h3>
                        <button onclick="document.getElementById('add-livestock-modal').classList.add('hidden')"
                                class="text-gray-500 hover:text-gray-700">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <form action="{{ route('farmer.herd-groups.add-livestock', $herdGroup->id) }}" method="POST">
                    @csrf
                    <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">
                        <div class="space-y-2">
                            @foreach($unassignedLivestock as $animal)
                                <label class="flex items-center p-3 hover:bg-gray-50 rounded-lg cursor-pointer">
                                    <input type="checkbox" name="livestock_ids[]" value="{{ $animal->id }}"
                                           class="h-5 w-5 rounded" style="color: #2fcb6e;">
                                    <div class="ml-3 flex-1">
                                        <p class="font-semibold text-gray-900">
                                            {{ $animal->tag_number ?? $animal->name ?? 'Animal #' . $animal->id }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            {{ ucfirst($animal->livestock_type) }}
                                            @if($animal->breed) · {{ $animal->breed }} @endif
                                            · {{ ucfirst($animal->health_status) }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6 border-t flex justify-end gap-3">
                        <button type="button" 
                                onclick="document.getElementById('add-livestock-modal').classList.add('hidden')"
                                class="px-6 py-2 border-2 border-gray-300 font-semibold rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 font-semibold rounded-lg"
                                style="background-color: #2fcb6e; color: white;">
                            Add Selected
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>

@endsection