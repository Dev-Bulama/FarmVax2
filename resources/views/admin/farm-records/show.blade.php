@extends('layouts.admin')

@section('title', 'Farm Record Details')
@section('page-title', 'Farm Record Details')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.farm-records.index') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Farm Records
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Record Info Card -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <!-- Record Header -->
            <div class="text-center pb-6 border-b">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-full mx-auto flex items-center justify-center mb-3">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Record #{{ $record->id }}</h3>
                <p class="text-sm text-gray-500">{{ ucfirst($record->record_type ?? 'General') }}</p>
                
                <!-- Status Badge -->
                <div class="mt-3">
                    @if(($record->status ?? '') == 'approved')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                    @elseif(in_array($record->status ?? '', ['submitted', 'pending']))
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                    @elseif(($record->status ?? '') == 'rejected')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($record->status ?? 'Unknown') }}</span>
                    @endif
                </div>
            </div>

            <!-- Farmer Information -->
            <div class="space-y-3 mt-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Submitted By</p>
                    <p class="text-sm font-medium text-gray-900">{{ $record->creator_name ?? 'Unknown' }}</p>
                    <p class="text-sm text-gray-500">{{ $record->creator_email ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Submitted Date</p>
                    <p class="text-sm text-gray-900">{{ isset($record->created_at) ? \Carbon\Carbon::parse($record->created_at)->format('M d, Y h:i A') : 'N/A' }}</p>
                </div>

                @if(isset($record->approved_at) && $record->approved_at)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">{{ ($record->status ?? '') == 'approved' ? 'Approved' : 'Reviewed' }} Date</p>
                        <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->approved_at)->format('M d, Y h:i A') }}</p>
                    </div>
                @endif

                @if(isset($record->admin_notes) && $record->admin_notes)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Admin Notes</p>
                        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $record->admin_notes }}</p>
                    </div>
                @endif
            </div>

            <hr class="my-6">

            <!-- Actions -->
            @if(in_array($record->status ?? '', ['submitted', 'pending']))
                <div class="space-y-2">
                    <form action="{{ route('admin.farm-records.approve', $record->id) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Approve this farm record?')" 
                                class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                            ✓ Approve Record
                        </button>
                    </form>

                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" 
                            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                        ✗ Reject Record
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column - Record Details -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Record Information -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Record Information</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Record Type</p>
                        <p class="text-base text-gray-900 mt-1">{{ ucfirst($record->record_type ?? 'Not specified') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Farm Name</p>
                        <p class="text-base text-gray-900 mt-1">{{ $record->farm_name ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Location</p>
                        <p class="text-base text-gray-900 mt-1">{{ $record->location ?? 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Farm Size</p>
                        <p class="text-base text-gray-900 mt-1">{{ $record->farm_size ?? 'Not specified' }}</p>
                    </div>
                </div>

                @if(isset($record->description) && $record->description)
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 font-semibold">Description / Details</p>
                        <p class="text-base text-gray-900 mt-2 whitespace-pre-wrap">{{ $record->description }}</p>
                    </div>
                @endif

                @if(isset($record->notes) && $record->notes)
                    <div class="mt-6">
                        <p class="text-sm text-gray-500 font-semibold">Additional Notes</p>
                        <p class="text-base text-gray-900 mt-2 whitespace-pre-wrap">{{ $record->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Livestock Information (if available) -->
        @if((isset($record->livestock_type) && $record->livestock_type) || (isset($record->livestock_count) && $record->livestock_count))
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Livestock Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if(isset($record->livestock_type) && $record->livestock_type)
                            <div>
                                <p class="text-sm text-gray-500 font-semibold">Livestock Type</p>
                                <p class="text-base text-gray-900 mt-1">{{ ucfirst($record->livestock_type) }}</p>
                            </div>
                        @endif
                        @if(isset($record->livestock_count) && $record->livestock_count)
                            <div>
                                <p class="text-sm text-gray-500 font-semibold">Number of Animals</p>
                                <p class="text-base text-gray-900 mt-1">{{ $record->livestock_count }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Attached Files/Images -->
        @if((isset($record->attachments) && $record->attachments) || (isset($record->images) && $record->images))
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Attachments</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Placeholder for attachments -->
                        <div class="text-center py-8 col-span-full">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <p class="mt-4 text-gray-500">No attachments</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Activity Log -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Activity Log</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Created -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Record Created</p>
                            <p class="text-sm text-gray-500">{{ isset($record->created_at) ? \Carbon\Carbon::parse($record->created_at)->format('M d, Y h:i A') : 'N/A' }}</p>
                            <p class="text-xs text-gray-400">By {{ $record->creator_name ?? 'Unknown' }}</p>
                        </div>
                    </div>

                    <!-- Approved/Rejected -->
                    @if(isset($record->approved_at) && $record->approved_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                @if(($record->status ?? '') == 'approved')
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ($record->status ?? '') == 'approved' ? 'Record Approved' : 'Record Rejected' }}
                                </p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($record->approved_at)->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Reject Farm Record</h3>
        
        <form action="{{ route('admin.farm-records.reject', $record->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Rejection Reason <span class="text-red-500">*</span>
                </label>
                <textarea name="rejection_reason" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                          placeholder="Explain why this record is being rejected..."></textarea>
                <p class="text-xs text-gray-500 mt-1">This reason will be sent to the farmer</p>
            </div>

            <div class="flex space-x-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Reject Record
                </button>
            </div>
        </form>
    </div>
</div>

@endsection