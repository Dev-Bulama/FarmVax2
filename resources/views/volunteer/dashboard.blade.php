@extends('layouts.volunteer')

@section('title', 'Volunteer Dashboard')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold" style="color: #11455b;">Volunteer Dashboard</h1>
        <p class="text-gray-600 mt-1">Track your impact and contributions</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4" style="border-color: #2fcb6e;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Farmers Enrolled</p>
                    <h3 class="text-3xl font-bold mt-1" style="color: #11455b;">{{ $stats['farmers_enrolled'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(47, 203, 110, 0.1);">
                    <i class="fas fa-users text-2xl" style="color: #2fcb6e;"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Total Points</p>
                    <h3 class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['total_points'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Badges Earned</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['badges'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-award text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Leaderboard Rank</p>
                    <h3 class="text-3xl font-bold text-purple-600 mt-1">#{{ $stats['rank'] ?? 'N/A' }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trophy text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

    </div>
<!-- Referral Code Card -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-bold mb-4" style="color: #11455b;">
        <i class="fas fa-share-alt mr-2"></i>Your Referral Code
    </h3>
    
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-6 border-2 border-purple-200">
        <div class="text-center">
            <p class="text-sm text-gray-600 mb-2">Share this code with farmers you recruit:</p>
            <div class="flex items-center justify-center mb-4">
                <div class="bg-white px-6 py-4 rounded-lg shadow-md border-2 border-purple-300">
                    <span class="text-3xl font-bold tracking-wider" style="color: #11455b;" id="referralCode">
                        {{ $volunteer->referral_code ?? 'FV-XXXXXXXX' }}
                    </span>
                </div>
                <button onclick="copyReferralCode()" 
                        class="ml-4 px-4 py-3 rounded-lg font-semibold transition"
                        style="background-color: #2fcb6e; color: white;">
                    <i class="fas fa-copy mr-2"></i>Copy
                </button>
            </div>
            
            <div class="grid grid-cols-3 gap-4 mt-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['farmers_enrolled'] }}</div>
                    <div class="text-xs text-gray-600">Farmers Enrolled</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_points'] }}</div>
                    <div class="text-xs text-gray-600">Total Points</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['badges'] }}</div>
                    <div class="text-xs text-gray-600">Badges Earned</div>
                </div>
            </div>
            
            <p class="text-xs text-gray-500 mt-4">
                <i class="fas fa-info-circle mr-1"></i>
                Farmers who register with your code will be credited to you. Earn 10 points per enrollment!
            </p>
        </div>
    </div>
</div>

<script>
function copyReferralCode() {
    const code = document.getElementById('referralCode').textContent.trim();
    navigator.clipboard.writeText(code).then(() => {
        alert('✓ Referral code copied: ' + code);
    });
}
</script>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Recent Enrollments --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h3 class="text-lg font-bold" style="color: #11455b;">Recent Enrollments</h3>
                    <a href="{{ route('volunteer.my-farmers') }}" class="text-sm font-semibold" style="color: #2fcb6e;">View All →</a>
                </div>
                <div class="p-6">
                    @if(isset($recentEnrollments) && $recentEnrollments->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentEnrollments as $enrollment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: #2fcb6e;">
                                            <span class="text-white font-bold">{{ substr($enrollment->farmer->name ?? 'F', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-semibold" style="color: #11455b;">{{ $enrollment->farmer->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $enrollment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-semibold" style="color: #2fcb6e;">+10 pts</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-gray-500 mt-2">No enrollments yet</p>
                            <a href="{{ route('volunteer.enroll.farmer') }}" 
                               class="inline-block mt-4 px-6 py-2 text-white font-semibold rounded-lg"
                               style="background-color: #2fcb6e;">
                                Enroll Your First Farmer
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #11455b;">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    
                    <a href="{{ route('volunteer.enroll.farmer') }}" 
                       class="flex flex-col items-center p-6 rounded-lg border-2 hover:shadow-lg transition"
                       style="border-color: #2fcb6e;">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mb-3" style="background-color: rgba(47, 203, 110, 0.1);">
                            <i class="fas fa-user-plus text-2xl" style="color: #2fcb6e;"></i>
                        </div>
                        <span class="font-semibold" style="color: #11455b;">Enroll Farmer</span>
                    </a>

                    <a href="{{ route('volunteer.my-farmers') }}" 
                       class="flex flex-col items-center p-6 bg-gray-50 rounded-lg border-2 border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                        <span class="font-semibold text-gray-700">My Farmers</span>
                    </a>

                    <a href="{{ route('volunteer.activity') }}" 
                       class="flex flex-col items-center p-6 bg-gray-50 rounded-lg border-2 border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                        </div>
                        <span class="font-semibold text-gray-700">My Activity</span>
                    </a>

                    <a href="{{ route('volunteer.profile') }}" 
                       class="flex flex-col items-center p-6 bg-gray-50 rounded-lg border-2 border-gray-200 hover:shadow-lg transition">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-user text-purple-600 text-2xl"></i>
                        </div>
                        <span class="font-semibold text-gray-700">My Profile</span>
                    </a>

                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Leaderboard --}}
            <!-- Leaderboard -->
<div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow sticky top-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold flex items-center" style="color: #11455b;">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Top Volunteers
            </h3>
        </div>
        <div class="p-6">
            @if(isset($leaderboard) && $leaderboard->count() > 0)
                <div class="space-y-3">
                    @foreach($leaderboard as $index => $leaderVolunteer)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ isset($leaderVolunteer->user_id) && $leaderVolunteer->user_id == auth()->id() ? 'border-2' : 'bg-gray-50' }}" 
                             style="{{ isset($leaderVolunteer->user_id) && $leaderVolunteer->user_id == auth()->id() ? 'border-color: #2fcb6e; background-color: rgba(47, 203, 110, 0.05);' : '' }}">
                            <div class="flex items-center">
                                <span class="w-8 h-8 flex items-center justify-center font-bold text-lg {{ $index < 3 ? 'text-yellow-600' : 'text-gray-600' }}">
                                    @if($index === 0)
                                        🥇
                                    @elseif($index === 1)
                                        🥈
                                    @elseif($index === 2)
                                        🥉
                                    @else
                                        #{{ $index + 1 }}
                                    @endif
                                </span>
                                <div class="ml-3">
                                    <p class="font-semibold" style="color: #11455b;">
                                        {{ $leaderVolunteer->name }}
                                        @if(isset($leaderVolunteer->user_id) && $leaderVolunteer->user_id == auth()->id())
                                            <span class="text-xs ml-1" style="color: #2fcb6e;">(You)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $leaderVolunteer->referral_code ?? '' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold" style="color: #2fcb6e;">{{ $leaderVolunteer->total_points ?? 0 }}</span>
                                <p class="text-xs text-gray-500">{{ $leaderVolunteer->total_enrollments ?? 0 }} farmers</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p class="mt-4 text-gray-500">No volunteers yet</p>
                </div>
            @endif
        </div>
    </div>
</div>
            {{-- Tips --}}
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg shadow p-6 border-l-4" style="border-color: #2fcb6e;">
                <h3 class="text-lg font-bold mb-3" style="color: #11455b;">💡 Tips</h3>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-start">
                        <span class="mr-2" style="color: #2fcb6e;">✓</span>
                        Earn 10 points for each farmer enrolled
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2" style="color: #2fcb6e;">✓</span>
                        Unlock badges at 5, 10, 25, 50 enrollments
                    </li>
                    <li class="flex items-start">
                        <span class="mr-2" style="color: #2fcb6e;">✓</span>
                        Track your impact in Activity section
                    </li>
                </ul>
            </div>

        </div>

    </div>

</div>

@endsection