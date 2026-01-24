@extends('layouts.farmer')

@section('title', 'Farmer Dashboard')
@section('page-title', 'Farmer Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name . '!')

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
                            <div class="w-full h-40 md:h-56 bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">My Livestock</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_livestock'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Healthy</p>
                            <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['healthy_livestock'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Vaccinations Due</p>
                            <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['vaccinations_due'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Active Requests</p>
                            <h3 class="text-3xl font-bold text-indigo-600 mt-2">{{ $stats['pending_requests'] ?? 0 }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
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

            {{-- =================== RECENT LIVESTOCK =================== --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-lg font-bold text-green-700">🐄 Recent Livestock</h2>
                    <a href="{{ route('individual.livestock.index') }}" class="text-sm text-green-700 font-semibold">View All →</a>
                </div>

                <div class="p-6">
                    @if(isset($recentLivestock) && $recentLivestock->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentLivestock as $animal)
                                <div class="flex items-center p-3 bg-gray-50 rounded hover:bg-green-50 transition">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <span class="text-green-600 font-bold text-sm">{{ strtoupper(substr($animal->type ?? '', 0, 2)) }}</span>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <p class="font-bold text-gray-900">{{ $animal->tag_number ?? 'No Tag' }}</p>
                                        <p class="text-xs text-gray-600">{{ ucfirst($animal->type) }} | {{ ucfirst($animal->breed ?? 'Unknown') }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $animal->health_status === 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($animal->health_status ?? 'Unknown') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-sm text-gray-500">No livestock added yet.</p>
                    @endif
                </div>
            </div>

            {{-- =================== SERVICE REQUESTS =================== --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="text-lg font-bold text-blue-700">🔧 Service Requests</h2>
                    <a href="{{ route('individual.service-requests.index') }}" class="text-sm text-blue-700 font-semibold">View All →</a>
                </div>

                <div class="p-6">
                    @if(isset($recentRequests) && $recentRequests->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentRequests as $request)
                                @php
                                    $status = $request->status ?? 'pending';
                                    $badge = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-blue-100 text-blue-800',
                                        'completed' => 'bg-green-100 text-green-800'
                                    ][$status] ?? 'bg-gray-100 text-gray-700';
                                @endphp

                                <div class="p-4 bg-gray-50 rounded hover:bg-blue-50 transition">
                                    <div class="flex justify-between">
                                        <p class="font-bold text-gray-900">{{ ucfirst($request->service_type) }}</p>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-sm text-gray-500">No service requests yet.</p>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (1/3 width - SIDEBAR) --}}
        <div class="space-y-6">

            {{-- ✅ SIDEBAR ADS --}}
            @if($sidebarAds && $sidebarAds->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">📢 Featured Services</h3>
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
                                    <div class="w-full h-40 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
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
                    <a href="{{ route('individual.livestock.create') }}" 
                       class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Add New Livestock</p>
                            <p class="text-xs text-gray-600">Register a new animal</p>
                        </div>
                    </a>

                    <a href="{{ route('individual.service-requests.create') }}" 
                       class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Request Service</p>
                            <p class="text-xs text-gray-600">Vaccination, treatment</p>
                        </div>
                    </a>

                    <a href="{{ route('individual.farm-records.step1') }}" 
                       class="flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                        <svg class="h-5 w-5 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Submit Farm Record</p>
                            <p class="text-xs text-gray-600">Update farm info</p>
                        </div>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
<script>
// Show popup ads after page load
document.addEventListener('DOMContentLoaded', function() {
    const popupAds = document.querySelectorAll('[id^="popup-ad-"]');
    
    if (popupAds.length > 0) {
        // Show first popup after 3 seconds
        setTimeout(() => {
            popupAds[0].classList.remove('hidden');
        }, 3000);
    }
});

// Close popup function
function closePopup(popupId) {
    document.getElementById(popupId).classList.add('hidden');
}

// Track ad impressions
document.querySelectorAll('.ad-item').forEach(adItem => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Ad is visible
                console.log('Ad viewed:', adItem.dataset.adId);
                observer.unobserve(entry.target);
            }
        });
    });
    
    observer.observe(adItem);
});
</script>
@endsection