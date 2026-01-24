<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $livestock->name ?: 'Livestock' }} - Details - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header with Back Button -->
            <div class="mb-6">
                <a href="{{ route('individual.livestock.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium inline-flex items-center">
                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to My Livestock
                </a>
            </div>

            <!-- Title Section -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-full bg-white shadow-md flex items-center justify-center">
                                    <span class="text-4xl">
                                        @switch($livestock->livestock_type)
                                            @case('cattle') 🐄 @break
                                            @case('goat') 🐐 @break
                                            @case('sheep') 🐑 @break
                                            @case('pig') 🐷 @break
                                            @case('chicken') 🐔 @break
                                            @case('duck') 🦆 @break
                                            @case('turkey') 🦃 @break
                                            @case('rabbit') 🐰 @break
                                            @case('horse') 🐴 @break
                                            @case('donkey') 🫏 @break
                                            @default 🐾
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $livestock->name ?: 'Unnamed Animal' }}</h1>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ ucfirst($livestock->livestock_type) }}
                                    @if($livestock->breed)
                                     • {{ $livestock->breed }}
                                    @endif
                                     • {{ ucfirst($livestock->gender) }}
                                </p>
                                <div class="mt-2 flex items-center space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        {{ $livestock->health_status === 'healthy' ? 'bg-green-100 text-green-800' : 
                                           ($livestock->health_status === 'sick' ? 'bg-red-100 text-red-800' : 
                                           ($livestock->health_status === 'recovering' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($livestock->health_status ?: 'Unknown') }}
                                    </span>
                                    @if($livestock->is_vaccinated)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Vaccinated
                                    </span>
                                    @endif
                                    @if($livestock->tag_number)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        Tag: {{ $livestock->tag_number }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('individual.livestock.edit', $livestock->id) }}" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('individual.livestock.delete', $livestock->id) }}" onsubmit="return confirm('Are you sure you want to delete this livestock record? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column - Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Section 1: Basic Information -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Basic Information
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Livestock Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($livestock->livestock_type) }}</dd>
                                </div>
                                @if($livestock->tag_number)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tag Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->tag_number }}</dd>
                                </div>
                                @endif
                                @if($livestock->name)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->name }}</dd>
                                </div>
                                @endif
                                @if($livestock->breed)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Breed</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->breed }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($livestock->gender) }}</dd>
                                </div>
                                @if($livestock->date_of_birth)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($livestock->date_of_birth)->format('M d, Y') }}</dd>
                                </div>
                                @endif
                                @if($livestock->age_years || $livestock->age_months)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Age</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($livestock->age_years) {{ $livestock->age_years }} years @endif
                                        @if($livestock->age_months) {{ $livestock->age_months }} months @endif
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Section 2: Physical Characteristics -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Physical Characteristics
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($livestock->weight)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Weight</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->weight }} {{ $livestock->weight_unit ?: 'kg' }}</dd>
                                </div>
                                @endif
                                @if($livestock->height)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Height</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->height }} cm</dd>
                                </div>
                                @endif
                                @if($livestock->color)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Color</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->color }}</dd>
                                </div>
                                @endif
                                @if($livestock->markings)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Distinctive Markings</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->markings }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Section 3: Health Status -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-yellow-50 border-b border-yellow-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Health Status
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $livestock->health_status === 'healthy' ? 'bg-green-100 text-green-800' : 
                                               ($livestock->health_status === 'sick' ? 'bg-red-100 text-red-800' : 
                                               ($livestock->health_status === 'recovering' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($livestock->health_status ?: 'Unknown') }}
                                        </span>
                                    </dd>
                                </div>
                                @if($livestock->last_health_check)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Health Check</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($livestock->last_health_check)->format('M d, Y') }}</dd>
                                </div>
                                @endif
                                @if($livestock->veterinarian_name)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Veterinarian</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->veterinarian_name }}</dd>
                                </div>
                                @endif
                                @if($livestock->veterinarian_phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vet Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->veterinarian_phone }}</dd>
                                </div>
                                @endif
                                @if($livestock->quarantine_status)
                                <div class="sm:col-span-2">
                                    <div class="rounded-md bg-yellow-50 p-4">
                                        <div class="flex">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-yellow-800">Under Quarantine</p>
                                                <p class="mt-1 text-xs text-yellow-700">This animal is currently isolated</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($livestock->current_conditions)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Current Conditions</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->current_conditions }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Section 4: Vaccination History -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-purple-50 border-b border-purple-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Vaccination History
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            @if($livestock->is_vaccinated)
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($livestock->last_vaccination_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Vaccination</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($livestock->last_vaccination_date)->format('M d, Y') }}</dd>
                                </div>
                                @endif
                                @if($livestock->total_vaccinations)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Vaccinations</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->total_vaccinations }}</dd>
                                </div>
                                @endif
                                @if($livestock->next_vaccination_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Next Vaccination Due</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($livestock->next_vaccination_date)->format('M d, Y') }}</dd>
                                </div>
                                @endif
                                @if($livestock->vaccination_notes)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Vaccination Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->vaccination_notes }}</dd>
                                </div>
                                @endif
                            </dl>
                            @else
                            <div class="text-center py-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No vaccination records</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Section 5: Production & Purpose -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Production & Purpose
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($livestock->production_purpose)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Purpose</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $livestock->production_purpose)) }}</dd>
                                </div>
                                @endif
                                @if($livestock->daily_milk_production)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Daily Milk Production</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->daily_milk_production }} liters</dd>
                                </div>
                                @endif
                                @if($livestock->monthly_egg_production)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Monthly Egg Production</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->monthly_egg_production }} eggs</dd>
                                </div>
                                @endif
                                @if($livestock->daily_feed_amount)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Daily Feed Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->daily_feed_amount }} kg</dd>
                                </div>
                                @endif
                                @if($livestock->feeding_schedule)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Feeding Schedule</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $livestock->feeding_schedule)) }}</dd>
                                </div>
                                @endif
                                @if($livestock->dietary_notes)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Dietary Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->dietary_notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Section 6: Origin & Documents -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Origin & Acquisition
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($livestock->acquisition_method)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Acquisition Method</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($livestock->acquisition_method) }}</dd>
                                </div>
                                @endif
                                @if($livestock->acquisition_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Acquisition Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($livestock->acquisition_date)->format('M d, Y') }}</dd>
                                </div>
                                @endif
                                @if($livestock->purchase_price)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Purchase Price</dt>
                                    <dd class="mt-1 text-sm text-gray-900">₦{{ number_format($livestock->purchase_price, 2) }}</dd>
                                </div>
                                @endif
                                @if($livestock->previous_owner)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Previous Owner</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->previous_owner }}</dd>
                                </div>
                                @endif
                                @if($livestock->notes)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Additional Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                </div>

                <!-- Right Column - Quick Actions & Metadata -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Quick Actions -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <a href="{{ route('individual.livestock.edit', $livestock->id) }}" 
                                class="block w-full text-center px-4 py-2 border border-green-600 rounded-md shadow-sm text-sm font-medium text-green-600 bg-white hover:bg-green-50">
                                Edit Livestock
                            </a>
                            <a href="{{ route('individual.service-requests.create') }}" 
                                class="block w-full text-center px-4 py-2 border border-blue-600 rounded-md shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-blue-50">
                                Request Service
                            </a>
                            <a href="{{ route('individual.vaccinations.index') }}" 
                                class="block w-full text-center px-4 py-2 border border-purple-600 rounded-md shadow-sm text-sm font-medium text-purple-600 bg-white hover:bg-purple-50">
                                View Vaccinations
                            </a>
                        </div>
                    </div>

                    <!-- Record Metadata -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Record Information</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->created_at->format('M d, Y h:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $livestock->updated_at->format('M d, Y h:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Record ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">#{{ $livestock->id }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</body>
</html>