@extends('layouts.farmer')

@section('title', 'My Herd Groups')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold" style="color: #11455b;">My Herd Groups</h1>
                <p class="text-gray-600 mt-1">Organize and manage your livestock in groups</p>
            </div>
            <a href="{{ route('farmer.herd-groups.create') }}" 
               class="inline-flex items-center px-6 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
               style="background-color: #2fcb6e;">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Herd
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4" style="border-color: #2fcb6e;">
            <p class="text-sm text-gray-600 font-semibold">Total Herds</p>
            <h3 class="text-2xl font-bold mt-1" style="color: #11455b;">{{ $stats['total_herds'] }}</h3>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600 font-semibold">Active Herds</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['active_herds'] }}</h3>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-sm text-gray-600 font-semibold">Total Animals</p>
            <h3 class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['total_animals'] }}</h3>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-orange-500">
            <p class="text-sm text-gray-600 font-semibold">Need Attention</p>
            <h3 class="text-2xl font-bold text-orange-600 mt-1">{{ $stats['needs_attention'] }}</h3>
        </div>

    </div>

    {{-- Herd Groups Grid --}}
    @if($herdGroups->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            @foreach($herdGroups as $herd)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    
                    {{-- Color Header --}}
                    <div class="h-3" style="background-color: {{ $herd->color_code }};"></div>
                    
                    {{-- Content --}}
                    <div class="p-5">
                        
                        {{-- Header --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold mb-1" style="color: #11455b;">
                                    {{ $herd->name }}
                                </h3>
                                @if($herd->type)
                                    <p class="text-sm text-gray-600">
                                        🐄 {{ ucfirst($herd->type) }}
                                        @if($herd->purpose)
                                            · {{ ucfirst($herd->purpose) }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            
                            {{-- Status Badge --}}
                            @if(!$herd->is_active)
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-700">
                                    Inactive
                                </span>
                            @elseif($herd->sick_count > 0)
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                    ⚠️ Attention
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                    ✓ Healthy
                                </span>
                            @endif
                        </div>

                        {{-- Description --}}
                        @if($herd->description)
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $herd->description }}</p>
                        @endif

                        {{-- Statistics --}}
                        <div class="grid grid-cols-3 gap-2 mb-4 p-3 rounded" style="background-color: #f5f5f5;">
                            <div class="text-center">
                                <p class="text-xs text-gray-600">Total</p>
                                <p class="text-lg font-bold" style="color: #11455b;">{{ $herd->total_count }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600">Healthy</p>
                                <p class="text-lg font-bold text-green-600">{{ $herd->healthy_count }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600">Sick</p>
                                <p class="text-lg font-bold text-red-600">{{ $herd->sick_count }}</p>
                            </div>
                        </div>

                        {{-- Health Bar --}}
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-gray-600">Health Score</span>
                                <span class="text-xs font-bold" style="color: #2fcb6e;">{{ $herd->health_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all" 
                                     style="width: {{ $herd->health_percentage }}%; background-color: {{ $herd->health_percentage >= 80 ? '#2fcb6e' : ($herd->health_percentage >= 50 ? '#f59e0b' : '#ef4444') }};"></div>
                            </div>
                        </div>

                        {{-- Location --}}
                        @if($herd->location)
                            <p class="text-sm text-gray-600 mb-4">
                                📍 {{ $herd->location }}
                            </p>
                        @endif

                        {{-- Actions --}}
                        <div class="flex gap-2">
                            <a href="{{ route('farmer.herd-groups.show', $herd->id) }}" 
                               class="flex-1 text-center px-4 py-2 font-semibold rounded-lg transition"
                               style="background-color: #2fcb6e; color: white;">
                                View Details
                            </a>
                            <a href="{{ route('farmer.herd-groups.edit', $herd->id) }}" 
                               class="px-4 py-2 border-2 font-semibold rounded-lg transition hover:bg-gray-50"
                               style="border-color: #11455b; color: #11455b;">
                                Edit
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $herdGroups->links() }}
        </div>

    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="text-xl font-bold mb-2" style="color: #11455b;">No Herd Groups Yet</h3>
            <p class="text-gray-600 mb-6">Start organizing your livestock by creating herd groups</p>
            <a href="{{ route('farmer.herd-groups.create') }}" 
               class="inline-flex items-center px-6 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
               style="background-color: #2fcb6e;">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Your First Herd
            </a>
        </div>
    @endif

</div>

@endsection