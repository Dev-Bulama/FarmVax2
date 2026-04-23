@extends('layouts.farmer')

@section('title', 'My Livestock')
@section('page-title', 'My Livestock')
@section('page-subtitle', 'Manage your animals and track their health')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center">
    <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center">
    <svg class="h-5 w-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    {{ session('error') }}
</div>
@endif

<!-- Action Button (desktop) -->
<div class="hidden sm:flex justify-end mb-6">
    <a href="{{ route('farmer.livestock.create') }}"
       class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition shadow-sm">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Livestock
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Livestock</p>
        <p class="text-3xl font-black text-green-600">{{ $livestock->total() }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Healthy</p>
        <p class="text-3xl font-black text-teal-600">{{ $stats['healthy'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Needs Attention</p>
        <p class="text-3xl font-black text-red-600">{{ $stats['sick'] ?? 0 }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Vaccination Due</p>
        <p class="text-3xl font-black text-yellow-600">{{ $stats['due_vaccination'] ?? 0 }}</p>
    </div>
</div>

<!-- Mobile Add Button -->
<div class="sm:hidden mb-6">
    <a href="{{ route('farmer.livestock.create') }}"
       class="flex items-center justify-center w-full px-4 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add New Livestock
    </a>
</div>

<!-- Livestock Table / Cards -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100">
    @if($livestock->count() > 0)

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Animal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Breed</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Health Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($livestock as $animal)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-700 font-black text-sm">
                                        {{ strtoupper(substr($animal->livestock_type ?? $animal->type ?? 'AN', 0, 2)) }}
                                    </span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">{{ $animal->tag_number ?? 'No Tag' }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $animal->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ ucfirst($animal->livestock_type ?? $animal->type ?? 'Unknown') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $animal->quantity ?? 1 }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst($animal->breed ?? 'Unknown') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $animal->age ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status = $animal->health_status ?? 'unknown';
                                $colors = [
                                    'healthy'    => 'bg-green-100 text-green-800',
                                    'sick'       => 'bg-red-100 text-red-800',
                                    'recovering' => 'bg-yellow-100 text-yellow-800',
                                    'deceased'   => 'bg-gray-100 text-gray-600',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-bold rounded-full {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('farmer.livestock.show', $animal->id) }}"
                               class="text-green-600 font-semibold hover:text-green-800">View →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden p-4 space-y-4">
            @foreach($livestock as $animal)
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-700 font-black text-sm">
                                {{ strtoupper(substr($animal->livestock_type ?? $animal->type ?? 'AN', 0, 2)) }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-gray-900">{{ $animal->tag_number ?? 'No Tag' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ ucfirst($animal->livestock_type ?? $animal->type ?? 'Unknown') }}
                                @if($animal->quantity > 1) · {{ $animal->quantity }} animals @endif
                                · {{ ucfirst($animal->breed ?? 'Unknown') }}
                            </p>
                        </div>
                    </div>
                    @php
                        $status = $animal->health_status ?? 'unknown';
                        $colors = [
                            'healthy'    => 'bg-green-100 text-green-800',
                            'sick'       => 'bg-red-100 text-red-800',
                            'recovering' => 'bg-yellow-100 text-yellow-800',
                            'deceased'   => 'bg-gray-100 text-gray-600',
                        ];
                    @endphp
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
                <a href="{{ route('farmer.livestock.show', $animal->id) }}"
                   class="block w-full text-center py-2 bg-white text-green-700 font-bold rounded-lg border-2 border-green-200 hover:bg-green-600 hover:text-white transition">
                    View Details
                </a>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($livestock->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $livestock->links() }}
        </div>
        @endif

    @else
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-4 text-sm font-bold text-gray-900">No livestock registered yet</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by adding your first animal.</p>
            <div class="mt-6">
                <a href="{{ route('farmer.livestock.create') }}"
                   class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Your First Livestock
                </a>
            </div>
        </div>
    @endif
</div>

@endsection
