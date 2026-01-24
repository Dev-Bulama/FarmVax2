@extends('layouts.admin')

@section('title', 'Outbreak Alert Details')

@section('content')

<!-- Page Header -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Outbreak Alert Details</h2>
        <p class="text-gray-600 mt-1">{{ $alert->disease_name }}</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.outbreak-alerts.edit', $alert->id) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
            Edit Alert
        </a>
        <a href="{{ route('admin.outbreak-alerts.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition">
            Back to List
        </a>
    </div>
</div>

<!-- Alert Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Alert Information</h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Disease Name</p>
                    <p class="text-base text-gray-900 mt-1">{{ $alert->disease_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Severity</p>
                    <p class="text-base text-gray-900 mt-1">
                        @if($alert->severity == 'critical')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Critical</span>
                        @elseif($alert->severity == 'high')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">High</span>
                        @elseif($alert->severity == 'medium')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Low</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Status</p>
                    <p class="text-base text-gray-900 mt-1">
                        @if($alert->is_active)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Affected Species</p>
                    <p class="text-base text-gray-900 mt-1">{{ $alert->affected_species ?? 'N/A' }}</p>
                </div>
                @if($alert->outbreak_date)
                <div>
                    <p class="text-sm font-semibold text-gray-600">Outbreak Date</p>
                    <p class="text-base text-gray-900 mt-1">{{ \Carbon\Carbon::parse($alert->outbreak_date)->format('M d, Y') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-sm font-semibold text-gray-600">Confirmed Cases</p>
                    <p class="text-base text-gray-900 mt-1">{{ $alert->confirmed_cases ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Deaths</p>
                    <p class="text-base text-red-600 mt-1 font-bold">{{ $alert->deaths ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-600">Reported By</p>
                    <p class="text-base text-gray-900 mt-1">{{ $alert->reporter->name ?? 'N/A' }}</p>
                </div>
            </div>
            
            <div class="mt-6">
                <p class="text-sm font-semibold text-gray-600 mb-2">Description</p>
                <p class="text-base text-gray-900">{{ $alert->description }}</p>
            </div>
            
            @if($alert->symptoms)
            <div class="mt-4">
                <p class="text-sm font-semibold text-gray-600 mb-2">Symptoms</p>
                <p class="text-base text-gray-900">{{ $alert->symptoms }}</p>
            </div>
            @endif
            
            @if($alert->preventive_measures)
            <div class="mt-4">
                <p class="text-sm font-semibold text-gray-600 mb-2">Preventive Measures</p>
                <p class="text-base text-gray-900">{{ $alert->preventive_measures }}</p>
            </div>
            @endif
        </div>
        
        <!-- Location Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Location Information</h3>
            
            <div class="grid grid-cols-2 gap-4">
                @if($alert->country)
                <div>
                    <p class="text-sm font-semibold text-gray-600">Country</p>
                    <p class="text-base text-gray-900 mt-1">{{ $alert->country->name ?? 'N/A' }}</p>
                </div>
                @endif
                @if($alert->state)
                <div>
                    <p class="text-sm font-semibold text-gray-600">State</p>
                    <p class="text-base text-gray-900 mt-1">{{ $alert->state->name ?? 'N/A' }}</p>
                </div>
                @endif
                @if($alert->lga)
                <div>
                    <p class="text-sm font-semibold text-gray-600">LGA</p>
                    <p class="text-base text-gray-900 mt-1">{{ $alert->lga->name ?? 'N/A' }}</p>
                </div>
                @endif
                @if($alert->radius_km)
                <div>
                    <p class="text-sm font-semibold text-gray-600">Alert Radius</p>
                    <p class="text-base text-gray-900 mt-1">{{ $alert->radius_km }} km</p>
                </div>
                @endif
            </div>
            
            @if($alert->location)
            <div class="mt-4">
                <p class="text-sm font-semibold text-gray-600 mb-2">Additional Location Details</p>
                <p class="text-base text-gray-900">{{ $alert->location }}</p>
            </div>
            @endif
        </div>
        
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        
        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
            
            <div class="space-y-3">
                <!-- Toggle Status -->
                <form action="{{ route('admin.outbreak-alerts.toggle', $alert->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 {{ $alert->is_active ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg font-semibold transition">
                        {{ $alert->is_active ? 'Deactivate Alert' : 'Activate Alert' }}
                    </button>
                </form>
                
                <!-- Resend Notifications -->
                <form action="{{ route('admin.outbreak-alerts.resend', $alert->id) }}" method="POST" onsubmit="return confirm('Resend notifications to all affected farmers?');">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold transition">
                        Resend Notifications
                    </button>
                </form>
                
                <!-- Delete -->
                <form action="{{ route('admin.outbreak-alerts.destroy', $alert->id) }}" method="POST" onsubmit="return confirm('Delete this alert permanently?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition">
                        Delete Alert
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Created</p>
                    <p class="text-gray-900 font-semibold">{{ $alert->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Last Updated</p>
                    <p class="text-gray-900 font-semibold">{{ $alert->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @if($alert->reported_at)
                <div>
                    <p class="text-gray-600">Reported</p>
                    <p class="text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($alert->reported_at)->format('M d, Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
        
    </div>
    
</div>

@endsection