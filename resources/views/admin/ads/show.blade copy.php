@extends('layouts.admin')

@section('title', 'Ad Details')
@section('page-title', 'Advertisement Details')

@section('content')

@php
    $stats = $stats ?? ['total_views' => 0, 'total_clicks' => 0, 'click_rate' => 0, 'unique_users' => 0];
    $targeting = json_decode($ad->targeting_data, true) ?? [];
@endphp

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.ads.index') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Advertisements
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Ad Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <!-- Ad Image -->
            @if($ad->image_path)
                <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" 
                     class="w-full rounded-lg mb-4 border">
            @else
                <div class="w-full h-48 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg mb-4 flex items-center justify-center">
                    <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $ad->title }}</h3>
            
            <!-- Status Badge -->
            <div class="mb-4">
                @if($ad->status == 'active')
                    @if($ad->end_date && \Carbon\Carbon::parse($ad->end_date)->isPast())
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @endif
                @else
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                @endif
            </div>

            <hr class="my-4">

            <!-- Ad Info -->
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Type</p>
                    <p class="text-sm text-gray-900">{{ ucfirst($ad->type) }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Category</p>
                    <p class="text-sm text-gray-900">{{ ucfirst($ad->category) }}</p>
                </div>

                @if($ad->link_url)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Link URL</p>
                        <a href="{{ $ad->link_url }}" target="_blank" class="text-sm text-indigo-600 hover:text-indigo-800 break-all">
                            {{ Str::limit($ad->link_url, 50) }}
                        </a>
                    </div>
                @endif

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Priority</p>
                    <p class="text-sm text-gray-900">{{ $ad->priority ?? 50 }}/100</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Start Date</p>
                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($ad->start_date)->format('M d, Y') }}</p>
                </div>

                @if($ad->end_date)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">End Date</p>
                        <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($ad->end_date)->format('M d, Y') }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Created By</p>
                    <p class="text-sm text-gray-900">{{ $ad->creator->name ?? 'Unknown' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Created</p>
                    <p class="text-sm text-gray-900">{{ $ad->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            <hr class="my-4">

            <!-- Actions -->
            <div class="space-y-2">
                <a href="{{ route('admin.ads.edit', $ad->id) }}" 
                   class="w-full px-4 py-2 bg-[#11455B] text-white rounded-lg hover:bg-[#0d3345] transition text-center block">
                    Edit Advertisement
                </a>

                <form action="{{ route('admin.ads.toggle-status', $ad->id) }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full px-4 py-2 {{ $ad->status == 'active' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg transition">
                        {{ $ad->status == 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Analytics & Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Performance Statistics -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Performance Analytics</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-blue-600 font-semibold">Total Views</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">{{ number_format($stats['total_views']) }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-green-600 font-semibold">Total Clicks</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">{{ number_format($stats['total_clicks']) }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-sm text-purple-600 font-semibold">Click Rate</p>
                        <p class="text-3xl font-bold text-purple-900 mt-2">{{ $stats['click_rate'] }}%</p>
                    </div>
                    <div class="bg-indigo-50 rounded-lg p-4">
                        <p class="text-sm text-indigo-600 font-semibold">Unique Users</p>
                        <p class="text-3xl font-bold text-indigo-900 mt-2">{{ number_format($stats['unique_users']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ad Content -->
        @if($ad->content)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Content</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $ad->content }}</p>
                </div>
            </div>
        @endif

        <!-- Targeting Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Targeting Settings</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Target Type</p>
                        <p class="text-base text-gray-900 mt-1">
                            @if(isset($targeting['target_type']))
                                @if($targeting['target_type'] == 'all')
                                    All Users
                                @elseif($targeting['target_type'] == 'role')
                                    By Role
                                @elseif($targeting['target_type'] == 'location')
                                    By Location
                                @else
                                    {{ ucfirst($targeting['target_type']) }}
                                @endif
                            @else
                                Not specified
                            @endif
                        </p>
                    </div>

                    @if(isset($targeting['target_type']) && $targeting['target_type'] == 'role' && !empty($targeting['target_roles']))
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Target Roles</p>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($targeting['target_roles'] as $role)
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-semibold rounded-full">
                                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(isset($targeting['target_type']) && $targeting['target_type'] == 'location')
                        <div>
                            <p class="text-sm text-gray-500 font-semibold">Target Location</p>
                            <p class="text-base text-gray-900 mt-1">
                                @if($ad->lga)
                                    {{ $ad->lga->name }}, {{ $ad->state->name }}, {{ $ad->country->name }}
                                @elseif($ad->state)
                                    {{ $ad->state->name }}, {{ $ad->country->name }}
                                @elseif($ad->country)
                                    {{ $ad->country->name }}
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

@endsection