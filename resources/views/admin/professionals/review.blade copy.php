@extends('layouts.admin')

@section('title', 'Review Professional Application')
@section('page-title', 'Review Professional Application')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.professionals.pending') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Pending Applications
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <!-- Profile Header -->
            <div class="text-center">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full mx-auto flex items-center justify-center mb-4">
                    <span class="text-white font-bold text-3xl">{{ substr($professional->user->name, 0, 1) }}</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900">{{ $professional->user->name }}</h3>
                <p class="text-gray-500 text-sm">{{ $professional->user->email }}</p>
                
                <!-- Status Badge -->
                <div class="mt-3">
                    @if($professional->approval_status == 'pending')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                    @elseif($professional->approval_status == 'approved')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                    @endif
                </div>
            </div>

            <hr class="my-6">

            <!-- Contact Information -->
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Phone</p>
                    <p class="text-sm text-gray-900">{{ $professional->user->phone ?? 'Not provided' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Email</p>
                    <p class="text-sm text-gray-900">{{ $professional->user->email }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Location</p>
                    <p class="text-sm text-gray-900">
                        @if(is_object($professional->user->country) && isset($professional->user->country->name))
                            {{ $professional->user->country->name }}
                            @if(is_object($professional->user->state) && isset($professional->user->state->name)), {{ $professional->user->state->name }}@endif
                            @if(is_object($professional->user->lga) && isset($professional->user->lga->name)), {{ $professional->user->lga->name }}@endif
                        @else
                            Not provided
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Address</p>
                    <p class="text-sm text-gray-900">{{ $professional->user->address ?? 'Not provided' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Submitted Date</p>
                    <p class="text-sm text-gray-900">
                        {{ $professional->submitted_at ? $professional->submitted_at->format('M d, Y h:i A') : $professional->created_at->format('M d, Y h:i A') }}
                    </p>
                </div>
            </div>

            <hr class="my-6">

            <!-- Quick Actions -->
            @if($professional->approval_status == 'pending')
                <div class="space-y-2">
                    <form action="{{ route('admin.professionals.approve', $professional->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Approve this professional application?')" 
                                class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                            ✓ Approve Application
                        </button>
                    </form>

                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                        ✗ Reject Application
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column - Details & Documents -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Professional Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Professional Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Professional Type</p>
                        <p class="text-base text-gray-900 mt-1">{{ $professional->professionalType->name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Specialization</p>
                        <p class="text-base text-gray-900 mt-1">{{ $professional->specialization->name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Service Area</p>
                        <p class="text-base text-gray-900 mt-1">{{ $professional->serviceArea->name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">License Number</p>
                        <p class="text-base text-gray-900 mt-1">{{ $professional->license_number ?? 'Not provided' }}</p>
                    </div>
                </div>

                @if($professional->bio)
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 font-semibold">Bio / Description</p>
                        <p class="text-base text-gray-900 mt-2 whitespace-pre-wrap">{{ $professional->bio }}</p>
                    </div>
                @endif

                @if($professional->years_of_experience)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 font-semibold">Years of Experience</p>
                        <p class="text-base text-gray-900 mt-1">{{ $professional->years_of_experience }} years</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Uploaded Documents -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Uploaded Documents</h3>
            </div>
            <div class="p-6">
                @if($professional->verificationDocuments && $professional->verificationDocuments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($professional->verificationDocuments as $document)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition">
                                <div class="flex items-start">
                                    <svg class="h-10 w-10 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $document->document_type ?? 'Document' }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $document->file_name ?? 'Uploaded file' }}</p>
                                        @if($document->file_path)
                                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" 
                                               class="text-blue-600 hover:text-blue-800 text-xs font-semibold mt-2 inline-block">
                                                View Document →
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-500">No documents uploaded</p>
                        <p class="text-sm text-gray-400 mt-2">This applicant did not upload any verification documents</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Approval History (if rejected or approved) -->
        @if($professional->approval_status != 'pending')
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Approval History</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start">
                        @if($professional->approval_status == 'approved')
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $professional->approval_status == 'approved' ? 'Application Approved' : 'Application Rejected' }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $professional->approved_at ? $professional->approved_at->format('M d, Y h:i A') : 'N/A' }}
                            </p>
                            @if($professional->rejection_reason)
                                <p class="text-sm text-gray-700 mt-2">
                                    <span class="font-semibold">Reason:</span> {{ $professional->rejection_reason }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Application</h3>
        
        <form action="{{ route('admin.professionals.reject', $professional->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rejection Reason <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Explain why this application is being rejected..."></textarea>
                <p class="text-xs text-gray-500 mt-1">This reason will be sent to the applicant</p>
            </div>

            <div class="flex space-x-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Reject Application
                </button>
            </div>
        </form>
    </div>
</div>

@endsection