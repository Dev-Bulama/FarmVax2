@extends('layouts.farmer')

@section('title', 'My Farm Records')

@section('content')

@php
    $user = auth()->user();
    $adService = new \App\Services\AdService();
    $sidebarAds = $adService->getSidebarAds($user);
@endphp

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold" style="color: #11455b;">My Farm Records</h1>
                <p class="text-gray-600 mt-1">Manage your farm and livestock information</p>
            </div>
            <a href="{{ route('farmer.farm-records.step1') }}" 
               class="inline-flex items-center px-6 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
               style="background-color: #2fcb6e;">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Farm Record
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                <div class="bg-white rounded-lg shadow p-4 border-l-4" style="border-color: #2fcb6e;">
                    <p class="text-sm text-gray-600 font-semibold">Total Records</p>
                    <h3 class="text-2xl font-bold mt-1" style="color: #11455b;">{{ $stats['total'] ?? 0 }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600 font-semibold">Submitted</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['submitted'] ?? 0 }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                    <p class="text-sm text-gray-600 font-semibold">Approved</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $stats['approved'] ?? 0 }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-600 font-semibold">Draft</p>
                    <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['draft'] ?? 0 }}</h3>
                </div>

            </div>

            {{-- Farm Records List --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b" style="background-color: #f8f9fa;">
                    <h2 class="text-lg font-bold" style="color: #11455b;">📋 All Farm Records</h2>
                </div>

                <div class="p-6">
                    @if($farmRecords->count() > 0)
                        <div class="space-y-4">
                            @foreach($farmRecords as $record)
                                @php
                                    $statusColors = [
                                        'submitted' => 'bg-blue-100 text-blue-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'under_review' => 'bg-yellow-100 text-yellow-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'draft' => 'bg-gray-100 text-gray-800',
                                    ];
                                    $statusBadge = $statusColors[$record->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp

                                <div class="p-5 border-2 rounded-lg hover:shadow-md transition" style="border-color: #e0e0e0;">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-3">
                                        <div class="flex-1 mb-3 md:mb-0">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h3 class="text-lg font-bold" style="color: #11455b;">
                                                    {{ $record->farm_name ?? 'Farm Record #' . $record->id }}
                                                </h3>
                                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusBadge }}">
                                                    {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                <strong>Farmer:</strong> {{ $record->farmer_name }}
                                            </p>
                                            <p class="text-sm text-gray-600">
                                                <strong>Location:</strong> {{ $record->farmer_city }}, {{ $record->farmer_state }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">Submitted</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $record->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-3 mb-3 p-3 rounded" style="background-color: #f5f5f5;">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-600">Livestock</p>
                                            <p class="text-lg font-bold" style="color: #11455b;">{{ $record->total_livestock_count }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-600">Farm Size</p>
                                            <p class="text-lg font-bold" style="color: #11455b;">{{ $record->farm_size ?? 'N/A' }} {{ $record->farm_size_unit }}</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-600">Type</p>
                                            <p class="text-sm font-bold" style="color: #11455b;">{{ ucfirst($record->farm_type) }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach(explode(', ', $record->livestock_types) as $type)
                                                <span class="px-2 py-1 text-xs font-semibold rounded" style="background-color: #e8f5e9; color: #11455b;">
                                                    {{ ucfirst($type) }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <a href="{{ route('farmer.farm-records.show', $record->id) }}" 
                                           class="inline-flex items-center px-4 py-2 font-semibold rounded-lg transition hover:shadow"
                                           style="background-color: #2fcb6e; color: white;">
                                            View Details
                                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $farmRecords->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-xl font-bold mb-2" style="color: #11455b;">No Farm Records Yet</h3>
                            <p class="text-gray-600 mb-6">Get started by creating your first farm record</p>
                            <a href="{{ route('farmer.farm-records.step1') }}" 
                               class="inline-flex items-center px-6 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
                               style="background-color: #2fcb6e;">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Create Your First Farm Record
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Quick Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #11455b;">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('farmer.farm-records.step1') }}" 
                       class="flex items-center p-3 rounded-lg transition hover:shadow"
                       style="background-color: #e8f5e9;">
                        <svg class="h-5 w-5 mr-3" style="color: #2fcb6e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <div>
                            <p class="font-semibold" style="color: #11455b;">New Farm Record</p>
                            <p class="text-xs text-gray-600">Start 3-step form</p>
                        </div>
                    </a>

                    <a href="{{ route('farmer.dashboard') }}" 
                       class="flex items-center p-3 bg-blue-50 rounded-lg transition hover:shadow">
                        <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-blue-900">Dashboard</p>
                            <p class="text-xs text-blue-700">View overview</p>
                        </div>
                    </a>
                </div>
            </div>

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
                                    <div class="w-full h-40 flex items-center justify-center"
                                         style="background: linear-gradient(135deg, #11455b 0%, #2fcb6e 100%);">
                                        <span class="text-white text-4xl">📢</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $ad->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($ad->description, 80) }}</p>
                                @if($ad->link_url)
                                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                                       class="text-sm font-semibold hover:underline"
                                       style="color: #2fcb6e;">
                                        Learn More →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Help & Info --}}
            <div class="rounded-lg shadow p-6" style="background: linear-gradient(135deg, #e8f5e9 0%, #e3f2fd 100%);">
                <h3 class="text-lg font-bold mb-3" style="color: #11455b;">💡 Need Help?</h3>
                <p class="text-sm text-gray-700 mb-4">
                    Farm records help us connect you with the right veterinary services and keep track of your livestock health.
                </p>
                <a href="mailto:support@farmvax.com" 
                   class="inline-flex items-center text-sm font-semibold hover:underline"
                   style="color: #2fcb6e;">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Contact Support
                </a>
            </div>

        </div>

    </div>

</div>

@endsection