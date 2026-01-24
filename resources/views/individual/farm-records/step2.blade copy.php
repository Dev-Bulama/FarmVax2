<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Record Details - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- Header with Back Button -->
            <div class="mb-6">
                <a href="{{ route('individual.dashboard') }}" class="text-green-600 hover:text-green-700 text-sm font-medium inline-flex items-center">
                    <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Title Section -->
            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-green-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-full bg-white shadow-md flex items-center justify-center">
                                    <span class="text-4xl">📋</span>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h1 class="text-3xl font-bold text-gray-900">Farm Record Details</h1>
                                <p class="mt-1 text-sm text-gray-600">
                                    Submitted on {{ \Carbon\Carbon::parse($record->submitted_at)->format('M d, Y') }}
                                    @if($record->farmer_name)
                                     • {{ $record->farmer_name }}
                                    @endif
                                </p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                        {{ $record->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($record->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                           ($record->status === 'under_review' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($record->status === 'submitted' || $record->status === 'under_review')
                        <div class="flex space-x-3">
                            <a href="{{ route('individual.farm-records.edit', $record->id) }}" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column - Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Section 1: Personal Information -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Personal Information
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_phone ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_email ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Village/Town</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_village ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">State</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_state ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_country ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Household Size</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->household_size ?? 'N/A' }} people</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role in Sector</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $record->role_in_sector ?? 'N/A')) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Section 2: Livestock Information -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Livestock Data
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Types of Livestock</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($record->livestock_types)
                                            @foreach(json_decode($record->livestock_types, true) ?? [] as $type)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2 mb-1">
                                                    {{ ucfirst($type) }}
                                                </span>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Count</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->total_livestock_count ?? 'N/A' }} animals</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Vaccination Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $record->vaccination_status === 'up_to_date' ? 'bg-green-100 text-green-800' : 
                                               ($record->vaccination_status === 'partially_vaccinated' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $record->vaccination_status ?? 'Unknown')) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Common Diseases Experienced</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($record->common_diseases)
                                            @foreach(json_decode($record->common_diseases, true) ?? [] as $disease)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2 mb-1">
                                                    {{ strtoupper($disease) }}
                                                </span>
                                            @endforeach
                                        @else
                                            No diseases reported
                                        @endif
                                    </dd>
                                </div>
                                @if($record->other_diseases_text)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Other Diseases</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->other_diseases_text }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Section 3: Services & Preferences -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-purple-50 border-b border-purple-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Services & Alert Preferences
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preferred Veterinary Services</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($record->preferred_services)
                                            @foreach(json_decode($record->preferred_services, true) ?? [] as $service)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mr-2 mb-1">
                                                    {{ ucfirst(str_replace('_', ' ', $service)) }}
                                                </span>
                                            @endforeach
                                        @else
                                            Not specified
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cold Chain Access</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($record->has_cold_chain)
                                            <span class="text-green-600">✓ Yes, has access</span>
                                        @else
                                            <span class="text-red-600">✗ No access</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Training Needs</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($record->training_needs)
                                            @foreach(json_decode($record->training_needs, true) ?? [] as $need)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mr-2 mb-1">
                                                    {{ ucfirst(str_replace('_', ' ', $need)) }}
                                                </span>
                                            @endforeach
                                        @else
                                            None specified
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Alert Preferences</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <div class="space-y-1">
                                            @if($record->alerts_outbreak)
                                                <div class="flex items-center"><span class="text-green-600 mr-2">✓</span> Outbreak Alerts</div>
                                            @endif
                                            @if($record->alerts_vaccine)
                                                <div class="flex items-center"><span class="text-green-600 mr-2">✓</span> Vaccine Availability</div>
                                            @endif
                                            @if($record->alerts_awareness)
                                                <div class="flex items-center"><span class="text-green-600 mr-2">✓</span> Awareness Campaigns</div>
                                            @endif
                                            @if($record->alerts_public)
                                                <div class="flex items-center"><span class="text-green-600 mr-2">✓</span> Public Announcements</div>
                                            @endif
                                        </div>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preferred Language</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($record->preferred_language ?? 'English') }}</dd>
                                </div>
                                @if($record->feedback)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Feedback</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->feedback }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                </div>

                <!-- Right Column - Status & Metadata -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Status Card -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Status Information</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $record->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($record->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                               ($record->status === 'under_review' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Submitted At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->submitted_at)->format('M d, Y h:i A') }}</dd>
                                </div>
                                @if($record->approved_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">{{ $record->status === 'approved' ? 'Approved' : 'Reviewed' }} At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->approved_at)->format('M d, Y h:i A') }}</dd>
                                </div>
                                @endif
                                @if($record->admin_notes)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Admin Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ $record->admin_notes }}</dd>
                                </div>
                                @endif
                            </dl>
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
                                    <dt class="text-sm font-medium text-gray-500">Record ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">#{{ $record->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->created_at->format('M d, Y h:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $record->updated_at->format('M d, Y h:i A') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Consent Given</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($record->consent_data_use)
                                            <span class="text-green-600">✓ Yes</span>
                                        @else
                                            <span class="text-red-600">✗ No</span>
                                        @endif
                                    </dd>
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