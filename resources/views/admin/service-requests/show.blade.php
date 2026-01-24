@extends('layouts.admin')

@section('title', 'Service Request Details')
@section('page-title', 'Service Request Details')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.service-requests.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Service Requests
    </a>
</div>

<!-- Service Request Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Main Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Request Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Request Information</h3>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    @if($serviceRequest->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($serviceRequest->status == 'assigned') bg-blue-100 text-blue-800
                    @elseif($serviceRequest->status == 'in_progress') bg-purple-100 text-purple-800
                    @elseif($serviceRequest->status == 'completed') bg-green-100 text-green-800
                    @elseif($serviceRequest->status == 'cancelled') bg-gray-100 text-gray-800
                    @elseif($serviceRequest->status == 'rejected') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($serviceRequest->status) }}
                </span>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Reference Number</p>
                        <p class="text-sm font-mono text-gray-900">{{ $serviceRequest->reference_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Service Type</p>
                        <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $serviceRequest->service_type)) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Priority</p>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($serviceRequest->priority == 'critical' || $serviceRequest->priority == 'high') bg-red-100 text-red-800
                            @elseif($serviceRequest->priority == 'important' || $serviceRequest->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($serviceRequest->priority) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Urgency Level</p>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($serviceRequest->urgency_level == 'critical' || $serviceRequest->urgency_level == 'high') bg-red-100 text-red-800
                            @elseif($serviceRequest->urgency_level == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($serviceRequest->urgency_level) }}
                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Service Title</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->service_title ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Description</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->service_description ?? $serviceRequest->description ?? 'No description provided' }}</p>
                </div>

                @if($serviceRequest->livestock_type || $serviceRequest->number_of_animals)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($serviceRequest->livestock_type)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Livestock Type</p>
                        <p class="text-sm text-gray-900">{{ ucfirst($serviceRequest->livestock_type) }}</p>
                    </div>
                    @endif
                    @if($serviceRequest->number_of_animals)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Number of Animals</p>
                        <p class="text-sm text-gray-900">{{ $serviceRequest->number_of_animals }}</p>
                    </div>
                    @endif
                </div>
                @endif

                @if($serviceRequest->preferred_date)
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Preferred Date</p>
                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($serviceRequest->preferred_date)->format('M d, Y') }}</p>
                </div>
                @endif

                @if($serviceRequest->location)
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Location</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->location }}</p>
                </div>
                @endif

                @if($serviceRequest->notes)
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Notes</p>
                    <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded">{{ $serviceRequest->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Farmer Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Farmer Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Name</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->user->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Phone</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->user->phone ?? $serviceRequest->contact_phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Address</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->user->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        @if($serviceRequest->livestock)
        <!-- Livestock Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Livestock Information</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Tag Number</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->livestock->tag_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Type</p>
                    <p class="text-sm text-gray-900">{{ ucfirst($serviceRequest->livestock->livestock_type ?? 'N/A') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Breed</p>
                    <p class="text-sm text-gray-900">{{ $serviceRequest->livestock->breed ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Health Status</p>
                    <p class="text-sm text-gray-900">{{ ucfirst($serviceRequest->livestock->health_status ?? 'N/A') }}</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Right Column - Actions & Status -->
    <div class="space-y-6">
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-3">
                
                <!-- Update Status Form -->
                <form action="{{ route('admin.service-requests.update-status', $serviceRequest->id) }}" method="POST">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="pending" {{ $serviceRequest->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="assigned" {{ $serviceRequest->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="in_progress" {{ $serviceRequest->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $serviceRequest->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $serviceRequest->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="rejected" {{ $serviceRequest->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Add notes..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Update Status
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- Request Timeline -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Timeline</h3>
            </div>
            <div class="p-6 space-y-3">
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Request Created</p>
                        <p class="text-xs text-gray-500">{{ $serviceRequest->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                @if($serviceRequest->assignedProvider)
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-purple-600 rounded-full mt-2 mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Assigned to Professional</p>
                        <p class="text-xs text-gray-500">{{ $serviceRequest->assignedProvider->name }}</p>
                    </div>
                </div>
                @endif

                @if($serviceRequest->status == 'completed' && $serviceRequest->completion_date)
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-green-600 rounded-full mt-2 mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Completed</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($serviceRequest->completion_date)->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-start">
                    <div class="w-2 h-2 bg-gray-400 rounded-full mt-2 mr-3"></div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">Last Updated</p>
                        <p class="text-xs text-gray-500">{{ $serviceRequest->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($serviceRequest->assignedProvider)
        <!-- Assigned Professional -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Assigned Professional</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">{{ substr($serviceRequest->assignedProvider->name, 0, 1) }}</span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-semibold text-gray-900">{{ $serviceRequest->assignedProvider->name }}</p>
                        <p class="text-xs text-gray-500">{{ $serviceRequest->assignedProvider->email }}</p>
                    </div>
                </div>
                @if($serviceRequest->assignedProvider->phone)
                <p class="text-xs text-gray-600">📞 {{ $serviceRequest->assignedProvider->phone }}</p>
                @endif
            </div>
        </div>
        @endif

    </div>
</div>

@endsection