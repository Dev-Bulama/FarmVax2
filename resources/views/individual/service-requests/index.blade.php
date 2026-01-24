@extends('layouts.farmer')

@section('title', 'Service Requests')
@section('page-title', 'Service Requests')
@section('page-subtitle', 'Track and manage your service requests')

@section('content')
<div class="p-6">

    @php
        $allRequests = \App\Models\ServiceRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Pending</p>
                    <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ \App\Models\ServiceRequest::where('user_id', auth()->id())->where('status', 'pending')->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">In Progress</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ \App\Models\ServiceRequest::where('user_id', auth()->id())->where('status', 'in_progress')->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Completed</p>
                    <h3 class="text-3xl font-bold text-green-600 mt-2">{{ \App\Models\ServiceRequest::where('user_id', auth()->id())->where('status', 'completed')->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Total</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\ServiceRequest::where('user_id', auth()->id())->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <a href="{{ route('individual.service-requests.create') }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition inline-flex items-center">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Request
        </a>
    </div>

    <!-- List -->
    @if($allRequests->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($allRequests as $req)
                        <tr>
                            <td class="px-6 py-4">#{{ $req->id }}</td>
                            <td class="px-6 py-4 capitalize">{{ $req->service_type }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-{{ $req->status == 'completed' ? 'green' : 'yellow' }}-100 text-{{ $req->status == 'completed' ? 'green' : 'yellow' }}-800 text-xs rounded-full">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $req->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">{{ $allRequests->links() }}</div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <h3 class="text-lg font-semibold text-gray-900">No Service Requests</h3>
            <a href="{{ route('individual.service-requests.create') }}" class="mt-4 inline-block px-6 py-3 bg-green-600 text-white rounded-lg">Create First Request</a>
        </div>
    @endif

</div>
@endsection