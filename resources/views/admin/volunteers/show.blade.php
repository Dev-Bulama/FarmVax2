@extends('layouts.admin')

@section('title', 'Volunteer Details')
@section('page-title', 'Volunteer Details')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.volunteers.index') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Volunteers
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Profile Header -->
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full mx-auto flex items-center justify-center mb-4">
                    <span class="text-white font-bold text-3xl">{{ substr($volunteer->user->name, 0, 1) }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ $volunteer->user->name }}</h3>
                <p class="text-gray-500 text-sm">{{ $volunteer->user->email }}</p>
                
                <!-- Status Badge -->
                <div class="mt-3">
                    @if($volunteer->is_active)
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </div>
            </div>

            <hr class="my-6">

            <!-- Contact Information -->
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Phone</p>
                    <p class="text-sm text-gray-900">{{ $volunteer->user->phone ?? 'Not provided' }}</p>
                </div>

                <div>
    <p class="text-xs text-gray-500 uppercase font-semibold">Location</p>
    <p class="text-sm text-gray-900">
        @if(is_object($volunteer->user->country) && isset($volunteer->user->country->name))
            {{ $volunteer->user->country->name }}
            @if(is_object($volunteer->user->state) && isset($volunteer->user->state->name)), {{ $volunteer->user->state->name }}@endif
            @if(is_object($volunteer->user->lga) && isset($volunteer->user->lga->name)), {{ $volunteer->user->lga->name }}@endif
        @elseif($volunteer->user->country_id)
            <span class="text-gray-400">Location ID: {{ $volunteer->user->country_id }}</span>
        @else
            Not provided
        @endif
    </p>
</div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Address</p>
                    <p class="text-sm text-gray-900">{{ $volunteer->user->address ?? 'Not provided' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Joined Date</p>
                    <p class="text-sm text-gray-900">
                        {{ $volunteer->joined_at ? $volunteer->joined_at->format('M d, Y') : $volunteer->created_at->format('M d, Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Points Earned</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $volunteer->points ?? 0 }}</p>
                </div>
            </div>

            <hr class="my-6">

            <!-- Actions -->
            <div class="space-y-2">
                @if($volunteer->is_active)
                    <form action="{{ route('admin.volunteers.deactivate', $volunteer->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Deactivate this volunteer?')" 
                                class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                            Deactivate Volunteer
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.volunteers.activate', $volunteer->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Activate this volunteer?')" 
                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Activate Volunteer
                        </button>
                    </form>
                @endif

                <form action="{{ route('admin.users.destroy', $volunteer->user_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Delete this volunteer permanently? This action cannot be undone.')" 
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Delete Volunteer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Statistics & Activities -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Farmers Enrolled</p>
                <p class="text-2xl font-bold text-gray-900">{{ $volunteer->enrolledFarmers->count() ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                <p class="text-sm text-gray-600">Total Points</p>
                <p class="text-2xl font-bold text-gray-900">{{ $volunteer->points ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Status</p>
                <p class="text-2xl font-bold text-gray-900">{{ $volunteer->is_active ? 'Active' : 'Inactive' }}</p>
            </div>
        </div>

        <!-- Enrolled Farmers -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Enrolled Farmers</h3>
            </div>
            <div class="p-6">
                @if($volunteer->enrolledFarmers && $volunteer->enrolledFarmers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Farmer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrolled Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($volunteer->enrolledFarmers as $enrollment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->farmer->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $enrollment->farmer->phone ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $enrollment->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $enrollment->status ?? 'Active' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="mt-4 text-gray-500">No farmers enrolled yet</p>
                        <p class="text-sm text-gray-400 mt-2">This volunteer hasn't enrolled any farmers</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activity Timeline (Optional - if you have activity tracking) -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-4 text-gray-500">No recent activity</p>
                    <p class="text-sm text-gray-400 mt-2">Activity tracking coming soon</p>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection