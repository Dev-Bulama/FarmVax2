@extends('layouts.professional')

@section('title', 'Professional Dashboard')
@section('page-title', 'Professional Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Pending Requests</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['pending_requests'] ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Completed Today</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['completed_today'] ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Farm Records</p>
                <h3 class="text-2xl font-bold text-gray-800">{{ $stats['farm_records'] ?? 0 }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-file-alt text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Rating</p>
                <h3 class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-star text-yellow-500"></i> {{ $stats['rating'] ?? '4.8' }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-award text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold">Pending Service Requests</h3>
            <a href="{{ route('professional.service-requests.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All →</a>
        </div>
        <div class="p-6">
            @if(isset($pendingRequests) && count($pendingRequests) > 0)
                <div class="space-y-4">
                    @foreach($pendingRequests as $request)
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded">
                            <div>
                                <p class="font-semibold">{{ $request->service_type }}</p>
                                <p class="text-sm text-gray-600">{{ $request->farmer->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $request->created_at->diffForHumans() }}</p>
                            </div>
                            <a href="{{ route('professional.service-requests.show', $request->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">
                                View
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No pending requests</p>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Recent Activity</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <p class="text-gray-500 text-center py-8">No recent activity</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <a href="{{ route('professional.farm-records.create') }}" class="flex flex-col items-center p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mb-3">
            <i class="fas fa-plus text-blue-600 text-xl"></i>
        </div>
        <span class="font-semibold text-gray-700">New Record</span>
    </a>

    <a href="{{ route('professional.service-requests.index') }}" class="flex flex-col items-center p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mb-3">
            <i class="fas fa-tasks text-green-600 text-xl"></i>
        </div>
        <span class="font-semibold text-gray-700">My Requests</span>
    </a>

    <a href="{{ url('/chat') }}" class="flex flex-col items-center p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mb-3">
            <i class="fas fa-comments text-purple-600 text-xl"></i>
        </div>
        <span class="font-semibold text-gray-700">Messages</span>
    </a>

    <a href="{{ route('professional.profile') }}" class="flex flex-col items-center p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
            <i class="fas fa-user text-gray-600 text-xl"></i>
        </div>
        <span class="font-semibold text-gray-700">My Profile</span>
    </a>
</div>
@endsection
