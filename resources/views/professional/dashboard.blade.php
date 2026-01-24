@extends('layouts.professional')

@section('title', 'Professional Dashboard')

@section('content')

@php
    $user = auth()->user();
    
    // ✅ Ad Service for displaying ads
    $adService = new \App\Services\AdService();
    $bannerAds = $adService->getBannerAds($user);
    $sidebarAds = $adService->getSidebarAds($user);
    $inlineAds = $adService->getInlineAds($user);
@endphp

<div class="p-6">

    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="text-gray-600 mt-1">Your professional dashboard</p>
        
        @if(isset($profile) && $profile->approval_status === 'pending')
            <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-700 font-medium">
                        Your account is pending approval. You'll receive full access once verified by our team.
                    </p>
                </div>
            </div>
        @endif
    </div>

    {{-- ✅ BANNER AD (Full Width at Top) --}}
    @if($bannerAds && $bannerAds->count() > 0)
        <div class="mb-6">
            @foreach($bannerAds as $ad)
                <div class="relative rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition bg-white">
                    <span class="absolute top-3 right-3 bg-gray-900 bg-opacity-75 text-white text-xs px-3 py-1 rounded-full z-10">
                        Sponsored
                    </span>
                    <a href="{{ $ad->link_url ? route('ad.click', $ad->id) : '#' }}" 
                       target="{{ $ad->link_url ? '_blank' : '_self' }}">
                        @if($ad->image_url)
                            <img src="{{ asset('storage/' . $ad->image_url) }}" 
                                 alt="{{ $ad->title }}" 
                                 class="w-full h-40 md:h-56 object-cover">
                        @else
                            <div class="w-full h-40 md:h-56 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <div class="text-center text-white p-6">
                                    <h3 class="text-2xl font-bold mb-2">{{ $ad->title }}</h3>
                                    <p class="text-sm">{{ Str::limit($ad->description, 150) }}</p>
                                </div>
                            </div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    {{-- =================== MAIN CONTENT GRID =================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- LEFT COLUMN (2/3 width) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- =================== STATISTICS =================== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Pending Requests (Available to claim) -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Available</p>
                            <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending_requests'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">To claim</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Requests (In Progress) -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Active</p>
                            <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['active_requests'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">In progress</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Completed Requests -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Completed</p>
                            <h3 class="text-3xl font-bold text-green-600 mt-2">{{ $stats['completed_requests'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Total done</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Requests -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Total</p>
                            <h3 class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['total_requests'] ?? 0 }}</h3>
                            <p class="text-xs text-gray-500 mt-1">All time</p>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ✅ INLINE AD (Between Content Sections) --}}
            @if($inlineAds && $inlineAds->count() > 0)
                <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-l-4 border-blue-500 rounded-lg p-5">
                    @foreach($inlineAds->take(1) as $ad)
                        <div class="flex items-start mb-2">
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">Sponsored</span>
                        </div>
                        <div class="flex items-center">
                            @if($ad->image_url)
                                <img src="{{ asset('storage/' . $ad->image_url) }}" 
                                     alt="{{ $ad->title }}" 
                                     class="w-24 h-24 rounded-lg object-cover mr-4 flex-shrink-0">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $ad->title }}</h4>
                                <p class="text-sm text-gray-700 mb-3">{{ Str::limit($ad->description, 120) }}</p>
                                @if($ad->link_url)
                                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                                        Learn More
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- =================== RECENT SERVICE REQUESTS =================== --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-lg font-bold text-blue-700">🔧 Service Requests</h2>
                    <a href="{{ route('professional.service-requests.index') }}" class="text-sm text-blue-700 font-semibold">View All →</a>
                </div>

                <div class="p-6">
                    @if(isset($recentRequests) && $recentRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentRequests as $request)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'assigned' => 'bg-blue-100 text-blue-800',
                                        'in_progress' => 'bg-indigo-100 text-indigo-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $badge = $statusColors[$request->status] ?? 'bg-gray-100 text-gray-700';
                                    
                                    $isAvailable = $request->status === 'pending' && !$request->assigned_to;
                                    $isMine = $request->assigned_to == $user->id;
                                @endphp

                                <div class="p-4 bg-gray-50 rounded hover:bg-blue-50 transition {{ $isAvailable ? 'border-2 border-yellow-300' : '' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            @if($isAvailable)
                                                <span class="inline-block px-2 py-1 bg-yellow-200 text-yellow-900 text-xs font-bold rounded mb-2">
                                                    🆕 NEW - AVAILABLE TO CLAIM
                                                </span>
                                            @endif
                                            <p class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $request->service_type)) }}</p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                Farmer: <strong>{{ $request->user->name ?? 'Unknown' }}</strong>
                                            </p>
                                            @if($request->user && $request->user->phone)
                                                <p class="text-xs text-gray-500">📞 {{ $request->user->phone }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                        </span>
                                    </div>
                                    
                                    <div class="mt-3 flex gap-2">
                                        @if($isAvailable)
                                            <a href="{{ route('professional.service-requests.show', $request->id) }}" 
                                               class="inline-block px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded hover:bg-green-700">
                                                ✅ Accept Request
                                            </a>
                                        @elseif($isMine && $request->status === 'in_progress')
                                            <a href="{{ route('professional.service-requests.show', $request->id) }}" 
                                               class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded hover:bg-blue-700">
                                                📝 View Details
                                            </a>
                                        @else
                                            <a href="{{ route('professional.service-requests.show', $request->id) }}" 
                                               class="inline-block text-sm text-blue-600 hover:text-blue-700 font-semibold">
                                                View Details →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-sm text-gray-500 py-8">No service requests available.</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (1/3 width - SIDEBAR) --}}
        <div class="space-y-6">

            {{-- ✅ SIDEBAR ADS --}}
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

            {{-- =================== QUICK ACTIONS =================== --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('professional.service-requests.index') }}" 
                       class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">View All Requests</p>
                            <p class="text-xs text-gray-600">See pending & active</p>
                        </div>
                    </a>

                    <a href="{{ route('professional.profile') }}" 
                       class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">My Profile</p>
                            <p class="text-xs text-gray-600">Update info & credentials</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Profile Status --}}
            @if(isset($profile))
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Profile Status</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Approval</span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $profile->approval_status === 'approved' ? 'bg-green-100 text-green-800' : ($profile->approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($profile->approval_status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Type</span>
                            <span class="text-sm font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $profile->professional_type)) }}</span>
                        </div>
                        @if($profile->experience_years)
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Experience</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $profile->experience_years }} years</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection