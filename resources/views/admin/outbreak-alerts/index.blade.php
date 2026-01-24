@extends('layouts.admin')

@section('title', 'Outbreak Alerts')

@section('content')

<!-- Page Header -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Outbreak Alerts</h2>
        <p class="text-gray-600 mt-1">Manage disease outbreak notifications</p>
    </div>
    <a href="{{ route('admin.outbreak-alerts.create') }}" class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Create Alert
    </a>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <p class="text-sm text-gray-600">Total Alerts</p>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
    <p class="text-sm text-gray-600">Active</p>
    <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
</div>
<div class="bg-white rounded-lg shadow p-6 border-l-4 border-gray-500">
    <p class="text-sm text-gray-600">Inactive</p>
    <p class="text-3xl font-bold text-gray-600">{{ $stats['inactive'] }}</p>
</div>
<div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
    <p class="text-sm text-gray-600">High Severity</p>
    <p class="text-3xl font-bold text-orange-600">{{ $stats['high'] }}</p>
</div>
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <p class="text-sm text-gray-600">Critical</p>
        <p class="text-3xl font-bold text-purple-600">{{ $stats['critical'] }}</p>
    </div>
</div>

<!-- Outbreak Alerts Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Disease</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Severity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cases/Deaths</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($alerts as $alert)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center
                                    @if($alert->severity == 'critical') bg-red-100
                                    @elseif($alert->severity == 'high') bg-orange-100
                                    @elseif($alert->severity == 'medium') bg-yellow-100
                                    @else bg-blue-100 @endif">
                                    <svg class="h-6 w-6 
                                        @if($alert->severity == 'critical') text-red-600
                                        @elseif($alert->severity == 'high') text-orange-600
                                        @elseif($alert->severity == 'medium') text-yellow-600
                                        @else text-blue-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $alert->disease_name }}</div>
                                    <div class="text-sm text-gray-500">
                                        @if(is_array($alert->affected_animals))
                                            {{ implode(', ', array_map('ucfirst', $alert->affected_animals)) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $alert->location_state ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $alert->location_lga ?? '' }}</div>
                            @if($alert->radius_km)
                                <div class="text-xs text-gray-400">Radius: {{ $alert->radius_km }}km</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($alert->severity == 'critical')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Critical</span>
                            @elseif($alert->severity == 'high')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">High</span>
                            @elseif($alert->severity == 'medium')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Medium</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Low</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
    @if($alert->is_active)
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
    @else
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
    @endif
</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>Cases: {{ $alert->confirmed_cases ?? 0 }}</div>
                            <div class="text-red-600">Deaths: {{ $alert->deaths ?? 0 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($alert->outbreak_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- View -->
                                <a href="{{ route('admin.outbreak-alerts.show', $alert->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition" title="View">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <!-- Edit -->
                                <a href="{{ route('admin.outbreak-alerts.edit', $alert->id) }}" 
                                   class="text-green-600 hover:text-green-900 transition" title="Edit">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                <!-- Resend Notifications -->
                                <form action="{{ route('admin.outbreak-alerts.resend', $alert->id) }}" method="POST" 
                                      onsubmit="return confirm('Resend notifications to all affected users?');" class="inline">
                                    @csrf
                                    <button type="submit" class="text-purple-600 hover:text-purple-900 transition" title="Resend Notifications">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('admin.outbreak-alerts.destroy', $alert->id) }}" method="POST" 
                                      onsubmit="return confirm('Delete this outbreak alert permanently?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition" title="Delete">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="mt-4 text-gray-500">No outbreak alerts found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($alerts->hasPages())
    <div class="mt-6">
        {{ $alerts->links() }}
    </div>
@endif

@endsection