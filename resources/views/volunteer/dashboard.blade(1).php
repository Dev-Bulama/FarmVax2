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
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-bold" style="color: #11455b;">🏆 Leaderboard</h3>
                    <p class="text-xs text-gray-500 mt-1">Top Volunteers</p>
                </div>
                <div class="p-6">
                    @if(isset($leaderboard) && $leaderboard->count() > 0)
                        <div class="space-y-3">
                            @foreach($leaderboard as $index => $volunteer)
                                <div class="flex items-center justify-between p-3 rounded-lg {{ $volunteer->id == auth()->id() ? 'border-2' : 'bg-gray-50' }}" 
                                     style="{{ $volunteer->id == auth()->id() ? 'border-color: #2fcb6e; background-color: rgba(47, 203, 110, 0.05);' : '' }}">
                                    <div class="flex items-center">
                                        <span class="w-8 h-8 flex items-center justify-center font-bold text-lg {{ $index < 3 ? 'text-yellow-600' : 'text-gray-600' }}">
                                            #{{ $index + 1 }}
                                        </span>
                                        <p class="ml-3 font-semibold" style="color: #11455b;">{{ $volunteer->name }}</p>
                                    </div>
                                    <span class="font-semibold" style="color: #2fcb6e;">{{ $volunteer->total_points ?? 0 }} pts</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4 text-sm">No data available</p>
                    @endif
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