@extends('layouts.admin')

@section('title', 'Livestock Management')
@section('page-title', 'Livestock Management')

@section('content')

<!-- Page Header -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Livestock Management</h2>
        <p class="text-gray-600 mt-1">View and manage all registered livestock</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.livestock.import.template') }}"
           class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-semibold">
            📥 Download Template
        </a>
        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="px-4 py-2 bg-[#2FCB6E] text-white rounded-lg hover:bg-[#25a356] transition text-sm font-semibold">
            📤 Import Livestock
        </button>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <p class="text-sm text-red-700">{{ session('error') }}</p>
    </div>
@endif

<!-- Statistics Cards -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-600">
        <p class="text-xs text-gray-600">Total Livestock</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-amber-600">
        <p class="text-xs text-gray-600">🐄 Cattle</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['cattle']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-600">
        <p class="text-xs text-gray-600">🐐🐑 Goat/Sheep</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['goat'] + $stats['sheep']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-orange-600">
        <p class="text-xs text-gray-600">🐔🐷 Poultry/Pig</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['chicken'] + $stats['pig']) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-600">
        <p class="text-xs text-gray-600">✅ Healthy</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['healthy']) }}</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('admin.livestock.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tag, name, breed, owner..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                <option value="">All Types</option>
                <option value="cattle" {{ request('type') == 'cattle' ? 'selected' : '' }}>🐄 Cattle</option>
                <option value="goat" {{ request('type') == 'goat' ? 'selected' : '' }}>🐐 Goat</option>
                <option value="sheep" {{ request('type') == 'sheep' ? 'selected' : '' }}>🐑 Sheep</option>
                <option value="chicken" {{ request('type') == 'chicken' ? 'selected' : '' }}>🐔 Poultry</option>
                <option value="pig" {{ request('type') == 'pig' ? 'selected' : '' }}>🐷 Pig</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>🦙 Other</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Health Status</label>
            <select name="health_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                <option value="">All Statuses</option>
                <option value="healthy" {{ request('health_status') == 'healthy' ? 'selected' : '' }}>Healthy</option>
                <option value="sick" {{ request('health_status') == 'sick' ? 'selected' : '' }}>Sick</option>
                <option value="recovering" {{ request('health_status') == 'recovering' ? 'selected' : '' }}>Recovering</option>
                <option value="deceased" {{ request('health_status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                <option value="">All</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
                <option value="deceased" {{ request('status') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transferred</option>
            </select>
        </div>
        <div class="flex items-end space-x-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-[#11455B] text-white rounded-lg hover:bg-[#0d3345] transition text-sm font-semibold">
                Filter
            </button>
            <a href="{{ route('admin.livestock.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Livestock Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-900">
            All Livestock <span class="text-gray-500 text-sm font-normal">({{ $livestock->total() }} records)</span>
        </h3>
        <a href="{{ route('admin.export.livestock') }}" class="text-sm text-[#11455B] hover:underline">
            Export CSV
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Animal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type & Breed</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Health</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Added</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($livestock as $animal)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-2xl mr-3">
                                @switch($animal->livestock_type)
                                    @case('cattle') 🐄 @break
                                    @case('goat') 🐐 @break
                                    @case('sheep') 🐑 @break
                                    @case('chicken') 🐔 @break
                                    @case('pig') 🐷 @break
                                    @case('duck') 🦆 @break
                                    @case('horse') 🐴 @break
                                    @default 🦙
                                @endswitch
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $animal->name ?: 'Unnamed' }}</div>
                                <div class="text-xs text-gray-500">{{ $animal->tag_number ?: 'No tag' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 capitalize">{{ $animal->livestock_type }}</div>
                        <div class="text-xs text-gray-500">{{ $animal->breed ?: 'Unknown breed' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($animal->owner)
                            <div class="text-sm font-medium text-gray-900">{{ $animal->owner->name }}</div>
                            <div class="text-xs text-gray-500">{{ $animal->owner->email }}</div>
                        @else
                            <span class="text-xs text-gray-400 italic">No owner</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $healthColors = [
                                'healthy' => 'bg-green-100 text-green-800',
                                'sick' => 'bg-red-100 text-red-800',
                                'recovering' => 'bg-blue-100 text-blue-800',
                                'deceased' => 'bg-gray-100 text-gray-800',
                            ];
                            $healthColor = $healthColors[$animal->health_status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $healthColor }} capitalize">
                            {{ $animal->health_status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusColors = [
                                'active' => 'bg-green-100 text-green-800',
                                'sold' => 'bg-yellow-100 text-yellow-800',
                                'deceased' => 'bg-red-100 text-red-800',
                                'transferred' => 'bg-blue-100 text-blue-800',
                                'missing' => 'bg-orange-100 text-orange-800',
                            ];
                            $statusColor = $statusColors[$animal->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }} capitalize">
                            {{ $animal->status ?: 'active' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                        {{ $animal->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.livestock.show', $animal->id) }}" class="text-[#11455B] hover:underline font-medium">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="text-4xl mb-3">🐾</div>
                        <p class="text-lg font-medium">No livestock found</p>
                        <p class="text-sm mt-1">Try adjusting your filters or import livestock</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($livestock->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $livestock->links() }}
        </div>
    @endif
</div>

<!-- Import Modal -->
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Import Livestock from CSV</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="p-6">
            <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                <p class="text-sm text-blue-700">
                    <strong>CSV Format:</strong> owner_email, livestock_type, breed, tag_number, name, gender, health_status, age_years, notes<br>
                    <a href="{{ route('admin.livestock.import.template') }}" class="underline font-medium">Download template CSV</a>
                </p>
            </div>

            <form method="POST" action="{{ route('admin.livestock.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select CSV File</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] text-sm">
                    <p class="text-xs text-gray-500 mt-1">Maximum file size: 5MB</p>
                </div>

                <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                    <p class="text-xs text-yellow-700">
                        <strong>Valid livestock types:</strong> cattle, goat, sheep, pig, chicken, duck, turkey, rabbit, horse, donkey, other<br>
                        <strong>Valid health statuses:</strong> healthy, sick, recovering, deceased<br>
                        <strong>Valid genders:</strong> male, female, unknown
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-semibold">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-[#2FCB6E] text-white rounded-lg hover:bg-[#25a356] transition text-sm font-semibold">
                        Import Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
