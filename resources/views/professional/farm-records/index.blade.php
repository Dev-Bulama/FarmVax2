@extends('layouts.professional')

@section('title', 'Farm Records')

@section('content')

@php
    $user = auth()->user();
    $adService = new \App\Services\AdService();
    $sidebarAds = $adService->getSidebarAds($user);
    
    // Get farm records from farmers in the professional's area
    $farmRecords = \App\Models\FarmRecord::with(['user:id,name,email,phone'])
        ->where('status', 'submitted')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
@endphp

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Farm Records</h1>
        <p class="text-gray-600 mt-1">View farm records from farmers in your area</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2">

            {{-- Statistics --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600 font-semibold">Total Records</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $farmRecords->total() }}</h3>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                    <p class="text-sm text-gray-600 font-semibold">This Month</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">
                        {{ \App\Models\FarmRecord::where('created_at', '>=', now()->startOfMonth())->count() }}
                    </h3>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                    <p class="text-sm text-gray-600 font-semibold">This Week</p>
                    <h3 class="text-2xl font-bold text-purple-600 mt-1">
                        {{ \App\Models\FarmRecord::where('created_at', '>=', now()->startOfWeek())->count() }}
                    </h3>
                </div>
            </div>

            {{-- Farm Records List --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-bold text-gray-900">📋 Farm Records</h2>
                </div>

                <div class="overflow-x-auto">
                    @if($farmRecords->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farm</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Livestock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($farmRecords as $record)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $record->farmer_name }}</p>
                                                <p class="text-xs text-gray-500">{{ $record->farmer_phone }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <p class="text-sm text-gray-900">{{ $record->farm_name ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">{{ $record->farm_size ?? 0 }} {{ $record->farm_size_unit ?? 'ha' }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-900">{{ $record->total_livestock_count ?? 0 }} animals</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm text-gray-900">{{ $record->farmer_lga ?? '' }}</p>
                                            <p class="text-xs text-gray-500">{{ $record->farmer_state ?? '' }}</p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $record->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('professional.farm-records.show', $record->id) }}" 
                                               class="text-blue-600 hover:text-blue-700 font-semibold">
                                                View →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Pagination --}}
                        <div class="px-6 py-4 border-t">
                            {{ $farmRecords->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Farm Records Yet</h3>
                            <p class="text-gray-600">Farm records will appear here once farmers submit them.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Sidebar Ads --}}
            @if($sidebarAds && $sidebarAds->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">📢 Sponsored</h3>
                    @foreach($sidebarAds as $ad)
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition mb-4">
                            <div class="relative">
                                <span class="absolute top-2 right-2 bg-gray-900 bg-opacity-75 text-white text-xs px-2 py-1 rounded-full z-10">
                                    Sponsored
                                </span>
                                @if($ad->image_url)
                                    <img src="{{ asset('storage/' . $ad->image_url) }}" 
                                         alt="{{ $ad->title }}" 
                                         class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center">
                                        <span class="text-white text-4xl">📢</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $ad->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($ad->description, 80) }}</p>
                                @if($ad->link_url)
                                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                                       class="text-blue-600 text-sm font-semibold hover:text-blue-700">
                                        Learn More →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Quick Filters --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">🔍 Quick Filters</h3>
                <div class="space-y-2">
                    <a href="{{ route('professional.farm-records.index') }}" 
                       class="block text-sm text-gray-700 hover:text-blue-600">
                        All Records
                    </a>
                    <a href="{{ route('professional.farm-records.index') }}?period=today" 
                       class="block text-sm text-gray-700 hover:text-blue-600">
                        Today
                    </a>
                    <a href="{{ route('professional.farm-records.index') }}?period=week" 
                       class="block text-sm text-gray-700 hover:text-blue-600">
                        This Week
                    </a>
                    <a href="{{ route('professional.farm-records.index') }}?period=month" 
                       class="block text-sm text-gray-700 hover:text-blue-600">
                        This Month
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection