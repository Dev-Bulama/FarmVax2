@extends('layouts.farmer')

@section('title', 'Farmer Dashboard')
@section('page-title', 'Farmer Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name . '!')

@section('content')
<div class="p-6">

    {{-- =================== STATISTICS =================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

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


    {{-- =================== QUICK ACTIONS =================== --}}
    <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <a href="{{ route('individual.livestock.create') }}" class="bg-white rounded-lg shadow p-6 border hover:border-green-500 hover:shadow-md transition">
            <h3 class="font-semibold text-gray-800">Add New Livestock</h3>
            <p class="text-sm text-gray-600 mt-1">Register a new animal.</p>
        </a>

        <a href="{{ route('individual.service-requests.create') }}" class="bg-white rounded-lg shadow p-6 border hover:border-blue-500 hover:shadow-md transition">
            <h3 class="font-semibold text-gray-800">Request Service</h3>
            <p class="text-sm text-gray-600 mt-1">Vaccination, treatment, etc.</p>
        </a>

        <a href="{{ route('individual.farm-records.step1') }}" class="bg-white rounded-lg shadow p-6 border hover:border-indigo-500 hover:shadow-md transition">
            <h3 class="font-semibold text-gray-800">Submit Farm Record</h3>
            <p class="text-sm text-gray-600 mt-1">Update farm information.</p>
        </a>
    </div>


    {{-- =================== RECENT LIVESTOCK =================== --}}
    <div class="bg-white rounded-lg shadow mb-10">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold text-green-700">Recent Livestock</h2>
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
            <h2 class="text-lg font-bold text-blue-700">Service Requests</h2>
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
@endsection
