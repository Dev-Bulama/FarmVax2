@extends('layouts.professional')

@section('title', 'Service Requests')

@section('content')

@php
    $user = auth()->user();
    $adService = new \App\Services\AdService();
    $sidebarAds = $adService->getSidebarAds($user);
@endphp

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Service Requests</h1>
        <p class="text-gray-600 mt-1">Accept and manage service requests from farmers</p>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
                    <p class="text-sm text-gray-600 font-semibold">Available</p>
                    <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['available'] ?? 0 }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-600 font-semibold">In Progress</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['in_progress'] ?? 0 }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                    <p class="text-sm text-gray-600 font-semibold">Completed</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $stats['completed'] ?? 0 }}</h3>
                </div>

                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-indigo-500">
                    <p class="text-sm text-gray-600 font-semibold">Total</p>
                    <h3 class="text-2xl font-bold text-indigo-600 mt-1">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>

            {{-- Available Requests (Pending) --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b bg-yellow-50">
                    <h2 class="text-lg font-bold text-yellow-900">🆕 Available Requests ({{ $pendingRequests->count() }})</h2>
                    <p class="text-sm text-yellow-700 mt-1">These requests are waiting to be accepted by a professional</p>
                </div>

                <div class="p-6">
                    @if($pendingRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($pendingRequests as $request)
                                @php
                                    $priorityColors = [
                                        'critical' => 'bg-red-100 text-red-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'important' => 'bg-yellow-100 text-yellow-800',
                                        'medium' => 'bg-blue-100 text-blue-800',
                                        'routine' => 'bg-gray-100 text-gray-800',
                                        'low' => 'bg-green-100 text-green-800',
                                    ];
                                    $priorityBadge = $priorityColors[$request->priority] ?? 'bg-gray-100 text-gray-700';
                                @endphp

                                <div class="p-4 bg-yellow-50 border-2 border-yellow-300 rounded-lg hover:bg-yellow-100 transition">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h3 class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $request->service_type)) }}</h3>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $priorityBadge }}">
                                                    {{ ucfirst($request->priority) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-700">
                                                <strong>Farmer:</strong> {{ $request->user->name ?? 'Unknown' }}
                                            </p>
                                            @if($request->user && $request->user->phone)
                                                <p class="text-sm text-gray-600">📞 {{ $request->user->phone }}</p>
                                            @endif
                                            @if($request->livestock_type)
                                                <p class="text-sm text-gray-600">🐄 {{ ucfirst($request->livestock_type) }} ({{ $request->number_of_animals }} animals)</p>
                                            @endif
                                            @if($request->preferred_date)
                                                <p class="text-sm text-gray-600">📅 Preferred: {{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    @if($request->description || $request->service_description)
                                        <p class="text-sm text-gray-700 mb-3 line-clamp-2">{{ $request->description ?? $request->service_description }}</p>
                                    @endif

                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</span>
                                        <a href="{{ route('professional.service-requests.show', $request->id) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                                            View & Accept
                                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Available Requests</h3>
                            <p class="text-gray-600">All current requests have been assigned. Check back later for new requests.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- My Requests --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b bg-blue-50">
                    <h2 class="text-lg font-bold text-blue-900">📋 My Requests ({{ $myRequests->count() }})</h2>
                    <p class="text-sm text-blue-700 mt-1">Service requests you've accepted or completed</p>
                </div>

                <div class="p-6">
                    @if($myRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($myRequests as $request)
                                @php
                                    $statusColors = [
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusBadge = $statusColors[$request->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp

                                <div class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 transition border {{ $request->status === 'in_progress' ? 'border-blue-300' : 'border-gray-200' }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <h3 class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $request->service_type)) }}</h3>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusBadge }}">
                                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-700">
                                                <strong>Farmer:</strong> {{ $request->user->name ?? 'Unknown' }}
                                            </p>
                                            @if($request->livestock_type)
                                                <p class="text-sm text-gray-600">🐄 {{ ucfirst($request->livestock_type) }} ({{ $request->number_of_animals }} animals)</p>
                                            @endif
                                            @if($request->assigned_at)
                                                <p class="text-sm text-gray-600">✅ Accepted: {{ \Carbon\Carbon::parse($request->assigned_at)->format('M d, Y') }}</p>
                                            @endif
                                            @if($request->completed_at)
                                                <p class="text-sm text-gray-600">🎉 Completed: {{ \Carbon\Carbon::parse($request->completed_at)->format('M d, Y') }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500">Created {{ $request->created_at->diffForHumans() }}</span>
                                        <a href="{{ route('professional.service-requests.show', $request->id) }}" 
                                           class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700">
                                            {{ $request->status === 'in_progress' ? 'Continue Working' : 'View Details' }}
                                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Requests Yet</h3>
                            <p class="text-gray-600">You haven't accepted any service requests. Check the "Available Requests" section above to get started.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Quick Stats --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Performance</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Acceptance Rate</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $stats['total'] > 0 ? round(($stats['total'] / max($stats['total'] + $stats['available'], 1)) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Completion Rate</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Active Tasks</span>
                        <span class="text-sm font-bold text-blue-600">{{ $stats['in_progress'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Sidebar Ads --}}
            @if($sidebarAds && $sidebarAds->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">📢 For Professionals</h3>
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
                                       class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700 transition">
                                        Learn More
                                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Help & Support --}}
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-3">💡 Need Help?</h3>
                <p class="text-sm text-gray-700 mb-4">
                    Contact our support team if you have questions about service requests or the platform.
                </p>
                <a href="mailto:support@farmvax.com" 
                   class="inline-flex items-center text-blue-600 text-sm font-semibold hover:text-blue-700">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Email Support
                </a>
            </div>

        </div>

    </div>

</div>

@endsection