@extends('layouts.admin')

@section('title', 'Livestock Details')
@section('page-title', 'Livestock Details')

@section('content')

<!-- Breadcrumb -->
<div class="mb-6">
    <a href="{{ route('admin.livestock.index') }}" class="text-[#11455B] hover:underline text-sm">
        ← Back to Livestock
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-6">
                <div class="text-5xl mr-4">
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
                    <h2 class="text-2xl font-bold text-gray-900">{{ $animal->name ?: 'Unnamed Animal' }}</h2>
                    <p class="text-gray-600">Tag: {{ $animal->tag_number ?: 'No tag assigned' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Type</p>
                    <p class="text-gray-900 capitalize mt-1">{{ $animal->livestock_type }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Breed</p>
                    <p class="text-gray-900 mt-1">{{ $animal->breed ?: 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Gender</p>
                    <p class="text-gray-900 capitalize mt-1">{{ $animal->gender }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Age</p>
                    <p class="text-gray-900 mt-1">
                        @if($animal->age_years || $animal->age_months)
                            {{ $animal->age_years ? $animal->age_years . 'y ' : '' }}{{ $animal->age_months ? $animal->age_months . 'm' : '' }}
                        @else
                            Unknown
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Weight</p>
                    <p class="text-gray-900 mt-1">{{ $animal->weight ? $animal->weight . ' kg' : 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Color</p>
                    <p class="text-gray-900 mt-1">{{ $animal->color ?: 'Not specified' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Health Status</p>
                    <p class="mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full capitalize
                            {{ $animal->health_status === 'healthy' ? 'bg-green-100 text-green-800' :
                               ($animal->health_status === 'sick' ? 'bg-red-100 text-red-800' :
                               ($animal->health_status === 'recovering' ? 'bg-blue-100 text-blue-800' :
                               'bg-gray-100 text-gray-800')) }}">
                            {{ $animal->health_status }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Status</p>
                    <p class="mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full capitalize
                            {{ $animal->status === 'active' ? 'bg-green-100 text-green-800' :
                               ($animal->status === 'sold' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-red-100 text-red-800') }}">
                            {{ $animal->status ?: 'active' }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Vaccinated</p>
                    <p class="text-gray-900 mt-1">{{ $animal->is_vaccinated ? '✅ Yes' : '❌ No' }}</p>
                </div>
            </div>

            @if($animal->notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Notes</p>
                    <p class="text-gray-700 text-sm">{{ $animal->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Side Info -->
    <div class="space-y-6">
        <!-- Owner Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Owner Information</h3>
            @if($animal->owner)
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Name</p>
                        <p class="text-gray-900 mt-1">{{ $animal->owner->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                        <p class="text-gray-700 mt-1 text-sm">{{ $animal->owner->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Phone</p>
                        <p class="text-gray-700 mt-1 text-sm">{{ $animal->owner->phone ?? 'N/A' }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-sm italic">No owner assigned</p>
            @endif
        </div>

        <!-- Record Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Record Details</h3>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Added</p>
                    <p class="text-gray-900 mt-1 text-sm">{{ $animal->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Last Updated</p>
                    <p class="text-gray-900 mt-1 text-sm">{{ $animal->updated_at->format('M d, Y H:i') }}</p>
                </div>
                @if($animal->date_of_birth)
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Date of Birth</p>
                    <p class="text-gray-900 mt-1 text-sm">{{ $animal->date_of_birth->format('M d, Y') }}</p>
                </div>
                @endif
                @if($animal->acquisition_date)
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Acquisition Date</p>
                    <p class="text-gray-900 mt-1 text-sm">{{ $animal->acquisition_date->format('M d, Y') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
