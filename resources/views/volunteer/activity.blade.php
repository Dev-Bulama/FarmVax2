@extends('layouts.volunteer')

@section('title', 'My Activity')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold" style="color: #11455b;">My Activity</h1>
        <p class="text-gray-600 mt-1">Track your contributions and impact over time</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        
        <div class="bg-white rounded-lg shadow p-4 border-l-4" style="border-color: #2fcb6e;">
            <p class="text-xs text-gray-600 font-semibold mb-1">Total Enrolled</p>
            <h3 class="text-2xl font-bold" style="color: #11455b;">{{ $totalEnrolled ?? 0 }}</h3>
            <p class="text-xs text-gray-500 mt-1">All time</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-600 font-semibold mb-1">This Month</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ $thisMonth ?? 0 }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ now()->format('F') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-xs text-gray-600 font-semibold mb-1">This Week</p>
            <h3 class="text-2xl font-bold text-purple-600">{{ $thisWeek ?? 0 }}</h3>
            <p class="text-xs text-gray-500 mt-1">Last 7 days</p>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-gray-600 font-semibold mb-1">Today</p>
            <h3 class="text-2xl font-bold text-yellow-600">{{ $today ?? 0 }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ now()->format('M d') }}</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Activity Timeline --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b" style="background-color: rgba(17, 69, 91, 0.03);">
                    <h2 class="text-lg font-bold" style="color: #11455b;">📅 Recent Activity</h2>
                    <p class="text-xs text-gray-500 mt-1">Your latest farmer enrollments</p>
                </div>

                <div class="p-6">
                    @if(isset($recentActivity) && $recentActivity->count() > 0)
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($recentActivity as $index => $enrollment)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-4 ring-white" style="background-color: #2fcb6e;">
                                                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm font-semibold" style="color: #11455b;">
                                                            Enrolled {{ $enrollment->farmer->name ?? 'Farmer' }}
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $enrollment->location ?? 'Location not specified' }}
                                                        </p>
                                                        @if($enrollment->notes)
                                                            <p class="text-xs text-gray-400 mt-1 italic">{{ $enrollment->notes }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right whitespace-nowrap">
                                                        <time class="text-xs text-gray-500">{{ $enrollment->created_at->diffForHumans() }}</time>
                                                        <p class="text-xs font-semibold mt-1" style="color: #2fcb6e;">+10 pts</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No recent activity</p>
                            <p class="text-xs text-gray-400 mt-1">Start by enrolling farmers to track your contributions</p>
                            <div class="mt-6">
                                <a href="{{ route('volunteer.enroll.farmer') }}" 
                                   class="inline-flex items-center px-6 py-2 text-white font-semibold rounded-lg"
                                   style="background-color: #2fcb6e;">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Enroll New Farmer
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Performance Summary --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4" style="color: #11455b;">📊 Performance</h3>
                
                <div class="space-y-4">
                    
                    {{-- Monthly Progress --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 font-semibold">Monthly Goal (10)</span>
                            <span class="text-sm font-bold" style="color: #2fcb6e;">{{ $thisMonth ?? 0 }}/10</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all" 
                                 style="width: {{ min((($thisMonth ?? 0) / 10) * 100, 100) }}%; background-color: #2fcb6e;"></div>
                        </div>
                    </div>

                    {{-- Weekly Progress --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600 font-semibold">Weekly Goal (3)</span>
                            <span class="text-sm font-bold text-purple-600">{{ $thisWeek ?? 0 }}/3</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-purple-500 h-3 rounded-full transition-all" 
                                 style="width: {{ min((($thisWeek ?? 0) / 3) * 100, 100) }}%;"></div>
                        </div>
                    </div>

                    {{-- Total Points --}}
                    <div class="mt-6 p-4 rounded-lg" style="background-color: rgba(47, 203, 110, 0.1);">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 font-semibold">Total Points Earned</p>
                                <p class="text-2xl font-bold mt-1" style="color: #2fcb6e;">{{ ($totalEnrolled ?? 0) * 10 }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-yellow-100">
                                <i class="fas fa-star text-yellow-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Milestones --}}
            <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg shadow p-6 border-l-4" style="border-color: #11455b;">
                <h3 class="text-lg font-bold mb-4" style="color: #11455b;">🏆 Milestones</h3>
                
                <div class="space-y-3">
                    @php
                        $milestones = [
                            ['count' => 5, 'badge' => 'Starter', 'icon' => 'seedling'],
                            ['count' => 10, 'badge' => 'Helper', 'icon' => 'hands-helping'],
                            ['count' => 25, 'badge' => 'Champion', 'icon' => 'medal'],
                            ['count' => 50, 'badge' => 'Hero', 'icon' => 'trophy'],
                            ['count' => 100, 'badge' => 'Legend', 'icon' => 'crown'],
                        ];
                    @endphp
                    
                    @foreach($milestones as $milestone)
                        <div class="flex items-center justify-between p-3 bg-white rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 {{ ($totalEnrolled ?? 0) >= $milestone['count'] ? 'bg-green-100' : 'bg-gray-100' }}">
                                    <i class="fas fa-{{ $milestone['icon'] }} {{ ($totalEnrolled ?? 0) >= $milestone['count'] ? 'text-green-600' : 'text-gray-400' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold" style="color: #11455b;">{{ $milestone['badge'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $milestone['count'] }} enrollments</p>
                                </div>
                            </div>
                            @if(($totalEnrolled ?? 0) >= $milestone['count'])
                                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <span class="text-xs text-gray-400">{{ $milestone['count'] - ($totalEnrolled ?? 0) }} more</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick Action --}}
            <div class="bg-white rounded-lg shadow p-6">
                <a href="{{ route('volunteer.enroll.farmer') }}" 
                   class="block w-full px-6 py-3 text-center text-white font-bold rounded-lg transition hover:shadow-lg"
                   style="background-color: #2fcb6e;">
                    + Enroll New Farmer
                </a>
            </div>

        </div>

    </div>

</div>

@endsection