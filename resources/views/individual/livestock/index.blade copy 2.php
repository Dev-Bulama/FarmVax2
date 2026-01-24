@extends('layouts.farmer')

@section('title', 'My Livestock')
@section('page-title', 'My Livestock')
@section('page-subtitle', 'Manage your animals and track their health')

@section('content')
<div class="p-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Total Livestock</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $livestock->total() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Healthy</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ $livestock->where('health_status', 'healthy')->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Under Treatment</p>
                    <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $livestock->whereIn('health_status', ['sick', 'under_treatment'])->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Quarantined</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $livestock->where('quarantine_status', true)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
            <div class="flex-1">
                <form action="{{ route('individual.livestock.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <input type="text" name="search" placeholder="Search by tag number, type, breed..." 
                           value="{{ request('search') }}"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    
                    <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="cattle" {{ request('type') == 'cattle' ? 'selected' : '' }}>Cattle</option>
                        <option value="goat" {{ request('type') == 'goat' ? 'selected' : '' }}>Goat</option>
                        <option value="sheep" {{ request('type') == 'sheep' ? 'selected' : '' }}>Sheep</option>
                        <option value="poultry" {{ request('type') == 'poultry' ? 'selected' : '' }}>Poultry</option>
                        <option value="pig" {{ request('type') == 'pig' ? 'selected' : '' }}>Pig</option>
                    </select>

                    <select name="health_status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="healthy" {{ request('health_status') == 'healthy' ? 'selected' : '' }}>Healthy</option>
                        <option value="sick" {{ request('health_status') == 'sick' ? 'selected' : '' }}>Sick</option>
                        <option value="under_treatment" {{ request('health_status') == 'under_treatment' ? 'selected' : '' }}>Under Treatment</option>
                    </select>
                    
                    <button type="submit" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Filter
                    </button>
                </form>
            </div>
            
            <a href="{{ route('individual.livestock.create') }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition flex items-center justify-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Livestock
            </a>
        </div>
    </div>

    <!-- Livestock List -->
    @if($livestock->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Animal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Breed</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Health Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($livestock as $animal)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-green-600 font-bold text-sm">{{ strtoupper(substr($animal->livestock_type ?? 'L', 0, 2)) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-gray-900">{{ $animal->tag_number ?? 'No Tag' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900 capitalize">{{ $animal->livestock_type ?? 'Unknown' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $animal->breed ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">
                                    @if($animal->date_of_birth)
                                        {{ \Carbon\Carbon::parse($animal->date_of_birth)->age }} yrs
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 capitalize">{{ $animal->gender ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($animal->health_status == 'healthy')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Healthy</span>
                                @elseif($animal->health_status == 'sick')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Sick</span>
                                @elseif($animal->health_status == 'under_treatment')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Under Treatment</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Unknown</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('individual.livestock.show', $animal->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('individual.livestock.edit', $animal->id) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('individual.livestock.destroy', $animal->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this animal?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4">
            @foreach($livestock as $animal)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-600 font-bold">{{ strtoupper(substr($animal->livestock_type ?? 'L', 0, 2)) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold text-gray-900">{{ $animal->tag_number ?? 'No Tag' }}</p>
                                <p class="text-xs text-gray-600 capitalize">{{ $animal->livestock_type ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        @if($animal->health_status == 'healthy')
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Healthy</span>
                        @elseif($animal->health_status == 'sick')
                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Sick</span>
                        @else
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Treatment</span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                        <div>
                            <span class="text-gray-600">Breed:</span>
                            <span class="font-semibold ml-1">{{ $animal->breed ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Gender:</span>
                            <span class="font-semibold ml-1 capitalize">{{ $animal->gender ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-2 pt-3 border-t">
                        <a href="{{ route('individual.livestock.show', $animal->id) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">View</a>
                        <a href="{{ route('individual.livestock.edit', $animal->id) }}" class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Edit</a>
                        <form action="{{ route('individual.livestock.destroy', $animal->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this animal?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($livestock->hasPages())
            <div class="mt-6">
                {{ $livestock->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">No Livestock Yet</h3>
            <p class="mt-2 text-gray-600">Get started by adding your first animal to track.</p>
            <a href="{{ route('individual.livestock.create') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Your First Livestock
            </a>
        </div>
    @endif

</div>
@endsection