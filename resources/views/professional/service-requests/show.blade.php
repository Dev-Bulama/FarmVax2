@extends('layouts.professional')

@section('title', 'Service Request Details')

@section('content')

@php
    $user = auth()->user();
    $adService = new \App\Services\AdService();
    $sidebarAds = $adService->getSidebarAds($user);
    
    $isPending = $request->status === 'pending' && !$request->assigned_to;
    $isInProgress = $request->status === 'in_progress' && $request->assigned_to == $user->id;
    $isCompleted = $request->status === 'completed';
    $isMine = $request->assigned_to == $user->id;
    
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800',
        'in_progress' => 'bg-blue-100 text-blue-800',
        'completed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
    ];
    $statusBadge = $statusColors[$request->status] ?? 'bg-gray-100 text-gray-700';
@endphp

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Service Request Details</h1>
                <p class="text-gray-600 mt-1">Reference: <strong>{{ $request->reference_number ?? 'SR-' . $request->id }}</strong></p>
            </div>
            <a href="{{ route('professional.service-requests.index') }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                ← Back to Requests
            </a>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <p class="text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Status & Action Buttons --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Request Status</h2>
                        <span class="inline-block mt-2 px-4 py-2 text-sm font-semibold rounded-full {{ $statusBadge }}">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </span>
                    </div>
                    
                    <div class="flex gap-3">
                        @if($isPending)
                            <form action="{{ route('professional.service-requests.accept', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">
                                    ✅ Accept Request
                                </button>
                            </form>
                        @endif
                        
                        @if($isInProgress)
                            <button onclick="document.getElementById('completeModal').classList.remove('hidden')" 
                                    class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                                ✓ Mark as Completed
                            </button>
                        @endif
                    </div>
                </div>

                @if($isPending)
                    <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <p class="text-sm text-yellow-800">
                            <strong>🆕 Available Request:</strong> Click "Accept Request" to claim this service request and start working on it.
                        </p>
                    </div>
                @endif

                @if($isInProgress && $isMine)
                    <div class="mt-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                        <p class="text-sm text-blue-800">
                            <strong>⚡ In Progress:</strong> You've accepted this request. Complete the service and click "Mark as Completed" when done.
                        </p>
                    </div>
                @endif

                @if($isCompleted)
                    <div class="mt-4 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                        <p class="text-sm text-green-800">
                            <strong>✅ Completed:</strong> This service request has been successfully completed.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Service Details --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Service Information</h2>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Service Type</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $request->service_type)) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Priority</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($request->priority) }}</p>
                    </div>
                    
                    @if($request->livestock_type)
                        <div>
                            <p class="text-sm text-gray-600">Livestock Type</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst($request->livestock_type) }}</p>
                        </div>
                    @endif
                    
                    @if($request->number_of_animals)
                        <div>
                            <p class="text-sm text-gray-600">Number of Animals</p>
                            <p class="font-semibold text-gray-900">{{ $request->number_of_animals }}</p>
                        </div>
                    @endif
                    
                    @if($request->preferred_date)
                        <div>
                            <p class="text-sm text-gray-600">Preferred Date</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}</p>
                        </div>
                    @endif
                    
                    @if($request->preferred_time)
                        <div>
                            <p class="text-sm text-gray-600">Preferred Time</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($request->preferred_time)->format('h:i A') }}</p>
                        </div>
                    @endif
                </div>

                @if($request->description || $request->service_description)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Description</p>
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-gray-900">{{ $request->description ?? $request->service_description }}</p>
                        </div>
                    </div>
                @endif

                @if($request->symptoms_description)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Symptoms</p>
                        <div class="bg-red-50 p-4 rounded border-l-4 border-red-400">
                            <p class="text-gray-900">{{ $request->symptoms_description }}</p>
                            @if($request->symptoms_start_date)
                                <p class="text-sm text-gray-600 mt-2">Started: {{ \Carbon\Carbon::parse($request->symptoms_start_date)->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                @if($request->urgency_reason)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 mb-2">Urgency Reason</p>
                        <div class="bg-yellow-50 p-4 rounded">
                            <p class="text-gray-900">{{ $request->urgency_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Location --}}
            @if($request->service_location || $request->location_instructions)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">📍 Location</h2>
                    
                    @if($request->service_location)
                        <p class="text-gray-900 mb-2">{{ $request->service_location }}</p>
                    @endif
                    
                    @if($request->location_instructions)
                        <div class="bg-blue-50 p-4 rounded">
                            <p class="text-sm font-semibold text-blue-900 mb-1">Directions:</p>
                            <p class="text-blue-800">{{ $request->location_instructions }}</p>
                        </div>
                    @endif
                    
                    @if($request->latitude && $request->longitude)
                        <a href="https://www.google.com/maps?q={{ $request->latitude }},{{ $request->longitude }}" 
                           target="_blank" 
                           class="inline-flex items-center mt-3 text-blue-600 hover:text-blue-700 font-semibold text-sm">
                            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            View on Google Maps
                        </a>
                    @endif
                </div>
            @endif

            {{-- Service Report (if completed) --}}
            @if($isCompleted && ($request->service_notes || $request->diagnosis))
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Service Report</h2>
                    
                    @if($request->actual_service_date)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Service Date</p>
                            <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($request->actual_service_date)->format('M d, Y') }}</p>
                        </div>
                    @endif

                    @if($request->service_notes)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Service Notes</p>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-900">{{ $request->service_notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($request->diagnosis)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Diagnosis</p>
                            <div class="bg-blue-50 p-4 rounded">
                                <p class="text-gray-900">{{ $request->diagnosis }}</p>
                            </div>
                        </div>
                    @endif

                    @if($request->treatment_provided)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Treatment Provided</p>
                            <div class="bg-green-50 p-4 rounded">
                                <p class="text-gray-900">{{ $request->treatment_provided }}</p>
                            </div>
                        </div>
                    @endif

                    @if($request->medications_prescribed)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Medications Prescribed</p>
                            <div class="bg-purple-50 p-4 rounded">
                                <p class="text-gray-900">{{ $request->medications_prescribed }}</p>
                            </div>
                        </div>
                    @endif

                    @if($request->recommendations)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Recommendations</p>
                            <div class="bg-yellow-50 p-4 rounded">
                                <p class="text-gray-900">{{ $request->recommendations }}</p>
                            </div>
                        </div>
                    @endif

                    @if($request->actual_cost)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">Service Cost</p>
                            <p class="font-semibold text-gray-900">₦{{ number_format($request->actual_cost, 2) }}</p>
                        </div>
                    @endif

                    @if($request->requires_followup)
                        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded">
                            <p class="text-sm font-semibold text-orange-900">Follow-up Required</p>
                            @if($request->followup_date)
                                <p class="text-sm text-orange-800 mt-1">Date: {{ \Carbon\Carbon::parse($request->followup_date)->format('M d, Y') }}</p>
                            @endif
                            @if($request->followup_instructions)
                                <p class="text-sm text-orange-800 mt-1">{{ $request->followup_instructions }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Farmer Information --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">👨‍🌾 Farmer Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Name</p>
                        <p class="font-semibold text-gray-900">{{ $request->user->name ?? 'Unknown' }}</p>
                    </div>
                    
                    @if($request->user && $request->user->phone)
                        <div>
                            <p class="text-sm text-gray-600">Phone</p>
                            <a href="tel:{{ $request->user->phone }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                {{ $request->user->phone }}
                            </a>
                        </div>
                    @endif
                    
                    @if($request->user && $request->user->email)
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <a href="mailto:{{ $request->user->email }}" class="font-semibold text-blue-600 hover:text-blue-700 text-sm break-all">
                                {{ $request->user->email }}
                            </a>
                        </div>
                    @endif

                    @if($isMine && $isInProgress)
                        <div class="mt-4 pt-4 border-t">
                            <a href="tel:{{ $request->user->phone ?? '' }}" 
                               class="block w-full text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                📞 Call Farmer
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Ads --}}
            @if($sidebarAds && $sidebarAds->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">📢 Sponsored</h3>
                    @foreach($sidebarAds as $ad)
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition mb-4">
                            <div class="relative">
                                <span class="absolute top-2 right-2 bg-gray-900 bg-opacity-75 text-white text-xs px-2 py-1 rounded-full z-10">
                                    Sponsored
                                </span>
                                @if($ad->image_url)
                                    <img src="{{ asset('storage/' . $ad->image_url) }}" 
                                         alt="{{ $ad->title }}" 
                                         class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center">
                                        <span class="text-white text-4xl">📢</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $ad->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($ad->description, 80) }}</p>
                                @if($ad->link_url)
                                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                                       class="text-blue-600 text-sm font-semibold hover:text-blue-700">
                                        Learn More →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Timeline --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📅 Timeline</h3>
                
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Request Created</p>
                            <p class="text-xs text-gray-600">{{ $request->created_at->format('M d, Y - h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($request->assigned_at)
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-green-600 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">Accepted</p>
                                <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($request->assigned_at)->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($request->completed_at)
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-purple-600 rounded-full mt-2 mr-3"></div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">Completed</p>
                                <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($request->completed_at)->format('M d, Y - h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

{{-- Complete Service Modal --}}
<div id="completeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-900">Complete Service Request</h3>
                <button onclick="document.getElementById('completeModal').classList.add('hidden')" 
                        class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('professional.service-requests.complete', $request->id) }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Actual Service Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="actual_service_date" required 
                               value="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Service Notes <span class="text-red-500">*</span>
                        </label>
                        <textarea name="service_notes" rows="4" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Describe what service was performed..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnosis</label>
                        <textarea name="diagnosis" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="What was diagnosed?"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Treatment Provided</label>
                        <textarea name="treatment_provided" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="What treatment was given?"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Medications Prescribed</label>
                        <textarea name="medications_prescribed" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="List medications and dosages..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Recommendations</label>
                        <textarea name="recommendations" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Follow-up care recommendations..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Service Cost (₦)</label>
                        <input type="number" name="actual_cost" step="0.01" min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               placeholder="0.00">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="requires_followup" id="requiresFollowup" 
                               class="h-4 w-4 text-blue-600 rounded"
                               onchange="document.getElementById('followupFields').classList.toggle('hidden')">
                        <label for="requiresFollowup" class="ml-2 text-sm text-gray-700">
                            Requires follow-up visit
                        </label>
                    </div>

                    <div id="followupFields" class="hidden space-y-4 pl-6 border-l-4 border-blue-200">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Date</label>
                            <input type="date" name="followup_date"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Follow-up Instructions</label>
                            <textarea name="followup_instructions" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                      placeholder="What should be done in the follow-up?"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">
                        ✓ Complete Service
                    </button>
                    <button type="button" 
                            onclick="document.getElementById('completeModal').classList.add('hidden')"
                            class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection