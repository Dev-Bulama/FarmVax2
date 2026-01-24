@extends('layouts.farmer')

@section('title', 'Farmer Dashboard')

@section('content')

<div class="p-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 👋</h1>
        <p class="text-gray-600 mt-1">Here's what's happening with your farm today</p>
    </div>

    @php
        $user = auth()->user();
          // ✅ ADD THIS: Initialize Ad Service
        $adService = new \App\Services\AdService();
        $bannerAds = $adService->getBannerAds($user);
        $sidebarAds = $adService->getSidebarAds($user);
        $inlineAds = $adService->getInlineAds($user);
        // Livestock Stats
        $totalLivestock = \App\Models\Livestock::where('user_id', $user->id)->count();
        $healthyLivestock = \App\Models\Livestock::where('user_id', $user->id)->where('health_status', 'healthy')->count();
        $sickLivestock = \App\Models\Livestock::where('user_id', $user->id)->whereIn('health_status', ['sick', 'under_treatment'])->count();
        $healthScore = $totalLivestock > 0 ? round(($healthyLivestock / $totalLivestock) * 100) : 0;
        
        // Vaccination Stats
        $upcomingVaccinations = \App\Models\VaccinationHistory::whereHas('livestock', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('next_booster_due_date', '>=', now())->where('next_booster_due_date', '<=', now()->addDays(30))->count();
        
        $overdueVaccinations = \App\Models\VaccinationHistory::whereHas('livestock', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('next_booster_due_date', '<', now())->count();
        
        // Service Requests Stats
        $activeServiceRequests = \App\Models\ServiceRequest::where('user_id', $user->id)->whereIn('status', ['pending', 'in_progress'])->count();
        $completedServiceRequests = \App\Models\ServiceRequest::where('user_id', $user->id)->where('status', 'completed')->count();
        
        // Outbreak Alerts
        $outbreakAlerts = \App\Models\OutbreakAlert::where("is_active", 1)->orderBy("created_at", "desc")->limit(5)->get();
        
        // Recent Messages
        $recentMessages = \App\Models\BulkMessage::where('status', 'sent')
            ->where(function($query) use ($user) {
                $query->where('target_audience', 'all')
                    ->orWhere(function($q) {
                        $q->where('target_audience', 'role')
                          ->where(function($sq) {
                              $sq->whereJsonContains('target_roles->target_roles', 'farmer')
                                 ->orWhereJsonContains('target_roles->target_roles', 'individual');
                          });
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Active Ads
        $activeAds = \App\Models\Ad::where('is_active', 1)
            ->where('start_date', '<=', now())
            ->where(function($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->where(function($query) use ($user) {
                $query->where('target_audience', 'all')
                    ->orWhere(function($q) {
                        $q->where('target_audience', 'role')
                          ->where(function($sq) {
                              $sq->whereJsonContains('target_roles->target_roles', 'farmer')
                                 ->orWhereJsonContains('target_roles->target_roles', 'individual');
                          });
                    });
            })
            ->orderBy('priority', 'desc')
            ->limit(3)
            ->get();
        
        // Recent Livestock
        $recentLivestock = \App\Models\Livestock::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    @endphp

    <!-- Critical Outbreak Alerts -->
    @if($outbreakAlerts->count() > 0)
        <div class="mb-6">
            @foreach($outbreakAlerts as $alert)
                <div class="mb-3 bg-{{ $alert->severity == 'critical' ? 'red' : ($alert->severity == 'high' ? 'orange' : 'yellow') }}-50 border-l-4 border-{{ $alert->severity == 'critical' ? 'red' : ($alert->severity == 'high' ? 'orange' : 'yellow') }}-500 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-{{ $alert->severity == 'critical' ? 'red' : ($alert->severity == 'high' ? 'orange' : 'yellow') }}-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-bold text-{{ $alert->severity == 'critical' ? 'red' : ($alert->severity == 'high' ? 'orange' : 'yellow') }}-800">
                                🚨 {{ strtoupper($alert->severity) }} ALERT: {{ $alert->disease_name }}
                            </h3>
                            <p class="text-sm text-{{ $alert->severity == 'critical' ? 'red' : ($alert->severity == 'high' ? 'orange' : 'yellow') }}-700 mt-1">
                                {{ $alert->description }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
<!-- ✅ LOCATION 1: BANNER AD (Full Width at Top) -->
  <!-- ✅ BANNER AD (Full Width at Top) -->
@if($bannerAds && $bannerAds->count() > 0)
    <div class="mb-6">
        @foreach($bannerAds as $ad)
            <div class="relative rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition bg-white">
                <span class="absolute top-3 right-3 bg-gray-900 bg-opacity-75 text-white text-xs px-3 py-1 rounded-full z-10">
                    Sponsored
                </span>
                <a href="{{ $ad->link_url ? route('ad.click', $ad->id) : '#' }}" 
                   target="{{ $ad->link_url ? '_blank' : '_self' }}"
                   class="block">
                    @if($ad->image)
                        <img src="{{ $ad->image }}" 
                             alt="{{ $ad->title }}" 
                             class="w-full h-40 md:h-56 object-cover"
                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22800%22 height=%22400%22%3E%3Crect fill=%22%234f46e5%22 width=%22800%22 height=%22400%22/%3E%3Ctext fill=%22white%22 font-size=%2240%22 font-family=%22Arial%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22%3E{{ $ad->title }}%3C/text%3E%3C/svg%3E';">
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
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Livestock -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Total Livestock</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalLivestock }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        <span class="text-green-600 font-semibold">{{ $healthyLivestock }} healthy</span> • 
                        <span class="text-red-600 font-semibold">{{ $sickLivestock }} sick</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Health Score -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Health Score</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $healthScore }}%</h3>
                    <p class="text-xs text-gray-500 mt-1">Overall herd health</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Vaccinations Due -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Vaccinations</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingVaccinations }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Due in 30 days • 
                        <span class="text-red-600 font-semibold">{{ $overdueVaccinations }} overdue</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Service Requests -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Service Requests</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $activeServiceRequests }}</h3>
                    <p class="text-xs text-gray-500 mt-1">
                        Active • 
                        <span class="text-green-600 font-semibold">{{ $completedServiceRequests }} completed</span>
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Recent Messages -->
            @if($recentMessages->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">📨 Recent Messages</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($recentMessages as $message)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <h4 class="text-sm font-semibold text-gray-900">{{ $message->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($message->content, 100) }}</p>
                                <p class="text-xs text-gray-500 mt-2">{{ $message->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
<!-- ✅ LOCATION 2: INLINE AD (Between Content Sections) -->
   <!-- ✅ INLINE AD (Between Content) -->
@if($inlineAds && $inlineAds->count() > 0)
    <div class="bg-gradient-to-r from-blue-50 to-purple-50 border-l-4 border-blue-500 rounded-lg p-5">
        @foreach($inlineAds->take(1) as $ad)
            <div class="flex items-start mb-2">
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">Sponsored</span>
            </div>
            <div class="flex items-center">
                @if($ad->image)
                    <img src="{{ $ad->image }}" 
                         alt="{{ $ad->title }}" 
                         class="w-24 h-24 rounded-lg object-cover mr-4 flex-shrink-0"
                         onerror="this.style.display='none';">
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
            <!-- Recent Livestock -->
            @if($recentLivestock->count() > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">🐄 Recent Livestock</h3>
                        <a href="{{ route('farmer.livestock.index') }}" class="text-sm text-green-600 hover:text-green-700 font-semibold">View All →</a>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($recentLivestock as $animal)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <span class="text-green-600 font-bold text-sm">{{ strtoupper(substr($animal->livestock_type ?? 'L', 0, 2)) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-semibold text-gray-900">{{ $animal->tag_number ?? 'No Tag' }}</h4>
                                            <p class="text-xs text-gray-600 capitalize">{{ $animal->livestock_type ?? 'Unknown' }}</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 {{ $animal->health_status == 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold rounded-full">
                                        {{ ucfirst($animal->health_status ?? 'Unknown') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="space-y-6">
            <!-- ✅ LOCATION 3: SIDEBAR ADS (Replace old active ads section) -->
       <!-- ✅ SIDEBAR ADS -->
@if($sidebarAds && $sidebarAds->count() > 0)
    <div>
        <h3 class="text-sm font-semibold text-gray-700 mb-3">📢 Featured Services</h3>
        @foreach($sidebarAds as $ad)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition mb-4">
                <div class="relative">
                    <span class="absolute top-2 right-2 bg-gray-900 bg-opacity-75 text-white text-xs px-2 py-1 rounded-full z-10">
                        Sponsored
                    </span>
                    @if($ad->image)
                        <img src="{{ $ad->image }}" 
                             alt="{{ $ad->title }}" 
                             class="w-full h-40 object-cover"
                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-40 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center\'><span class=\'text-white text-4xl\'>📢</span></div>';">
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
            <!-- Active Ads -->
            @if($activeAds->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600">
                        <h3 class="text-sm font-bold text-white">📢 Featured Offers</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($activeAds as $ad)
                            <div class="p-4">
                                @if($ad->image_path)
                                    <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="w-full h-32 object-cover rounded-lg mb-3">
                                @endif
                                <h4 class="text-sm font-bold text-gray-900">{{ $ad->title }}</h4>
                                @if($ad->content)
                                    <p class="text-xs text-gray-600 mt-1">{{ Str::limit($ad->content, 60) }}</p>
                                @endif
                                @if($ad->link_url)
                                    <a href="{{ route('farmer.track-ad-click', $ad->id) }}" target="_blank" class="inline-block mt-2 text-xs text-purple-600 hover:text-purple-700 font-semibold">
                                        Learn More →
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('farmer.livestock.create') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
                        <svg class="h-5 w-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Add Livestock</span>
                    </a>
                    <a href="{{ route('farmer.service-requests.create') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                        <svg class="h-5 w-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-900">Request Service</span>
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