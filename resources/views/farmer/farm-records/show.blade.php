@extends('layouts.farmer')

@section('title', 'Farm Record Details')

@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                <div class="flex items-center">
                    <svg class="h-6 w-6 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold" style="color: #11455b;">Farm Record Submitted! 🎉</h1>
                    <p class="text-gray-600 mt-1">Your farm information has been recorded successfully</p>
                </div>
                <div class="text-right">
                    @php
                        $statusColors = [
                            'submitted' => 'bg-blue-100 text-blue-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'under_review' => 'bg-yellow-100 text-yellow-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                        $statusBadge = $statusColors[$farmRecord->status] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <span class="px-4 py-2 text-sm font-bold rounded-full {{ $statusBadge }}">
                        {{ ucfirst(str_replace('_', ' ', $farmRecord->status)) }}
                    </span>
                    <p class="text-xs text-gray-500 mt-2">ID: #{{ $farmRecord->id }}</p>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('farmer.farm-records.index') }}" 
                   class="px-6 py-3 bg-white border-2 font-semibold rounded-lg transition hover:bg-gray-50"
                   style="border-color: #11455b; color: #11455b;">
                    ← Back to All Records
                </a>
                <a href="{{ route('farmer.dashboard') }}" 
                   class="px-6 py-3 text-white font-semibold rounded-lg transition"
                   style="background-color: #2fcb6e;">
                    Go to Dashboard
                </a>
            </div>
        </div>

        {{-- Farmer Information --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">👨‍🌾 Farmer Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Full Name</p>
                    <p class="font-semibold text-gray-900">{{ $farmRecord->farmer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Phone Number</p>
                    <p class="font-semibold text-gray-900">{{ $farmRecord->farmer_phone }}</p>
                </div>
                @if($farmRecord->farmer_email)
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $farmRecord->farmer_email }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600">Location</p>
                    <p class="font-semibold text-gray-900">{{ $farmRecord->farmer_city }}, {{ $farmRecord->farmer_state }}</p>
                </div>
                @if($farmRecord->farmer_lga)
                    <div>
                        <p class="text-sm text-gray-600">LGA</p>
                        <p class="font-semibold text-gray-900">{{ $farmRecord->farmer_lga }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Farm Information --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🏡 Farm Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($farmRecord->farm_name)
                    <div>
                        <p class="text-sm text-gray-600">Farm Name</p>
                        <p class="font-semibold text-gray-900">{{ $farmRecord->farm_name }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600">Farm Size</p>
                    <p class="font-semibold text-gray-900">{{ $farmRecord->farm_size ?? 'N/A' }} {{ $farmRecord->farm_size_unit }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Farm Type</p>
                    <p class="font-semibold text-gray-900">{{ ucfirst($farmRecord->farm_type) }}</p>
                </div>
                @if($farmRecord->latitude && $farmRecord->longitude)
                    <div>
                        <p class="text-sm text-gray-600">GPS Coordinates</p>
                        <a href="https://www.google.com/maps?q={{ $farmRecord->latitude }},{{ $farmRecord->longitude }}" 
                           target="_blank"
                           class="font-semibold hover:underline"
                           style="color: #2fcb6e;">
                            View on Map →
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Livestock Information --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🐄 Livestock Information</h2>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Livestock Types</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(', ', $farmRecord->livestock_types) as $type)
                        <span class="px-3 py-1 rounded-full text-sm font-semibold" style="background-color: #e8f5e9; color: #11455b;">
                            {{ ucfirst($type) }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div class="text-center p-4 rounded-lg" style="background-color: #e3f2fd;">
                    <p class="text-2xl font-bold" style="color: #11455b;">{{ $farmRecord->total_livestock_count }}</p>
                    <p class="text-sm text-gray-600">Total Animals</p>
                </div>
                <div class="text-center p-4 rounded-lg" style="background-color: #fff3e0;">
                    <p class="text-2xl font-bold" style="color: #11455b;">{{ $farmRecord->young_count }}</p>
                    <p class="text-sm text-gray-600">Young</p>
                </div>
                <div class="text-center p-4 rounded-lg" style="background-color: #e8f5e9;">
                    <p class="text-2xl font-bold" style="color: #11455b;">{{ $farmRecord->adult_count }}</p>
                    <p class="text-sm text-gray-600">Adult</p>
                </div>
                <div class="text-center p-4 rounded-lg" style="background-color: #f3e5f5;">
                    <p class="text-2xl font-bold" style="color: #11455b;">{{ $farmRecord->old_count }}</p>
                    <p class="text-sm text-gray-600">Old</p>
                </div>
            </div>

            @if($farmRecord->breed_information)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Breed Information</p>
                    <p class="text-gray-900">{{ $farmRecord->breed_information }}</p>
                </div>
            @endif

            @if($farmRecord->livestock_details)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Additional Details</p>
                    <p class="text-gray-900">{{ $farmRecord->livestock_details }}</p>
                </div>
            @endif
        </div>

        {{-- Health & Vaccination --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">💉 Health & Vaccination</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                @if($farmRecord->last_vaccination_date)
                    <div>
                        <p class="text-sm text-gray-600">Last Vaccination</p>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($farmRecord->last_vaccination_date)->format('M d, Y') }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-600">Health Status</p>
                    <p class="font-semibold {{ $farmRecord->has_health_issues ? 'text-red-600' : 'text-green-600' }}">
                        {{ $farmRecord->has_health_issues ? '⚠️ Has Health Issues' : '✅ Healthy' }}
                    </p>
                </div>
            </div>

            @if($farmRecord->vaccination_history)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Vaccination History</p>
                    <p class="text-gray-900">{{ $farmRecord->vaccination_history }}</p>
                </div>
            @endif

            @if($farmRecord->has_health_issues && $farmRecord->current_health_issues)
                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-400 mb-4">
                    <p class="text-sm font-semibold text-red-900 mb-2">Current Health Issues</p>
                    <p class="text-sm text-red-800">{{ $farmRecord->current_health_issues }}</p>
                </div>
            @endif

            @if($farmRecord->veterinarian_name || $farmRecord->veterinarian_phone)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Veterinarian</p>
                    <p class="font-semibold text-gray-900">
                        {{ $farmRecord->veterinarian_name ?? 'N/A' }}
                        @if($farmRecord->veterinarian_phone)
                            - {{ $farmRecord->veterinarian_phone }}
                        @endif
                    </p>
                </div>
            @endif
        </div>

        {{-- Service Needs --}}
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🔧 Service Needs</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Urgency Level</p>
                    @php
                        $urgencyColors = [
                            'low' => 'text-green-600',
                            'medium' => 'text-yellow-600',
                            'high' => 'text-orange-600',
                            'emergency' => 'text-red-600',
                        ];
                        $urgencyColor = $urgencyColors[$farmRecord->urgency_level] ?? 'text-gray-600';
                    @endphp
                    <p class="font-bold {{ $urgencyColor }}">{{ ucfirst($farmRecord->urgency_level) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Immediate Attention</p>
                    <p class="font-semibold {{ $farmRecord->needs_immediate_attention ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $farmRecord->needs_immediate_attention ? '⚠️ Yes' : 'No' }}
                    </p>
                </div>
            </div>

            @if($farmRecord->service_needs)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Services Needed</p>
                    <p class="text-gray-900">{{ $farmRecord->service_needs }}</p>
                </div>
            @endif

            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Alert Preferences</p>
                <div class="flex gap-3">
                    @if($farmRecord->sms_alerts)
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">📱 SMS</span>
                    @endif
                    @if($farmRecord->email_alerts)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">📧 Email</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Next Steps --}}
        <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">📋 What's Next?</h2>
            <ul class="space-y-3">
                <li class="flex items-start">
                    <span class="text-2xl mr-3">1️⃣</span>
                    <div>
                        <p class="font-semibold text-gray-900">Record Review</p>
                        <p class="text-sm text-gray-600">Our team will review your farm record and verify the information</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <span class="text-2xl mr-3">2️⃣</span>
                    <div>
                        <p class="font-semibold text-gray-900">Professional Matching</p>
                        <p class="text-sm text-gray-600">We'll connect you with veterinary professionals in your area</p>
                    </div>
                </li>
                <li class="flex items-start">
                    <span class="text-2xl mr-3">3️⃣</span>
                    <div>
                        <p class="font-semibold text-gray-900">Service Delivery</p>
                        <p class="text-sm text-gray-600">Receive notifications when services are available for your livestock</p>
                    </div>
                </li>
            </ul>
        </div>

    </div>
</div>

@endsection