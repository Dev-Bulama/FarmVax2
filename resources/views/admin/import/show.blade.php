@extends('layouts.admin')

@section('title', 'Import Details')
@section('page-title', 'Import Details')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.import.index') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Import History
    </a>
</div>

<!-- Import Summary Card -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $import->original_filename }}</h2>
            <p class="text-sm text-gray-600 mt-1">
                Imported by <strong>{{ $import->importedBy->name }}</strong> on {{ $import->created_at->format('M d, Y \a\t h:i A') }}
            </p>
        </div>
        <span class="px-4 py-2 text-sm font-semibold rounded-full
            {{ $import->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
            {{ $import->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : '' }}
            {{ $import->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
            {{ $import->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
            {{ ucfirst($import->status) }}
        </span>
    </div>

    <!-- Import Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6">
        <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
            <p class="text-xs text-blue-600 font-medium mb-1">Total Records</p>
            <p class="text-2xl font-bold text-blue-900">{{ number_format($import->total_records) }}</p>
        </div>

        <div class="bg-green-50 rounded-lg p-4 border-l-4 border-green-500">
            <p class="text-xs text-green-600 font-medium mb-1">Successful</p>
            <p class="text-2xl font-bold text-green-900">{{ number_format($import->successful_imports) }}</p>
        </div>

        <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-500">
            <p class="text-xs text-red-600 font-medium mb-1">Failed</p>
            <p class="text-2xl font-bold text-red-900">{{ number_format($import->failed_imports) }}</p>
        </div>

        <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-500">
            <p class="text-xs text-yellow-600 font-medium mb-1">Duplicates</p>
            <p class="text-2xl font-bold text-yellow-900">{{ number_format($import->duplicate_emails) }}</p>
        </div>

        <div class="bg-purple-50 rounded-lg p-4 border-l-4 border-purple-500">
            <p class="text-xs text-purple-600 font-medium mb-1">Success Rate</p>
            <p class="text-2xl font-bold text-purple-900">{{ number_format($import->success_rate, 1) }}%</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mt-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Import Progress</span>
            <span class="text-sm font-medium text-gray-700">{{ number_format($import->success_rate, 1) }}% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-[#2FCB6E] h-3 rounded-full transition-all" style="width: {{ $import->success_rate }}%"></div>
        </div>
    </div>

    <!-- Import Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 pt-6 border-t border-gray-200">
        <div>
            <p class="text-xs text-gray-500">User Type</p>
            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $import->user_type_display }}</p>
        </div>
        @if($import->started_at)
            <div>
                <p class="text-xs text-gray-500">Duration</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $import->human_duration }}</p>
            </div>
        @endif
        @if($import->completed_at)
            <div>
                <p class="text-xs text-gray-500">Completed At</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $import->completed_at->format('M d, Y h:i A') }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Errors Section (if any) -->
@if($import->errors && count($import->errors) > 0)
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-red-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            Import Errors ({{ count($import->errors) }})
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-red-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-red-900">Row</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-red-900">Field</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-red-900">Error Message</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-red-200">
                    @foreach(array_slice($import->errors, 0, 20) as $error)
                        <tr>
                            <td class="px-4 py-2 text-red-900 font-medium">{{ $error['row'] ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-red-800">{{ $error['field'] ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-red-700">{{ $error['message'] ?? 'Unknown error' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if(count($import->errors) > 20)
                <p class="text-sm text-red-600 mt-3">Showing first 20 errors of {{ count($import->errors) }} total</p>
            @endif
        </div>
    </div>
@endif

<!-- Imported Users -->
@if($import->importedUsers->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Imported Users ({{ number_format($import->importedUsers->count()) }})</h3>
            
            @if($import->pendingEmails()->count() > 0)
                <form action="{{ route('admin.import.resend-batch', $import->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-[#2FCB6E] text-white rounded-lg hover:bg-[#25a356] transition flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Resend All Pending ({{ $import->pendingEmails()->count() }})
                    </button>
                </form>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Email Sent</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($import->importedUsers as $importedUser)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-700 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-sm">{{ substr($importedUser->user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $importedUser->user->name }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $importedUser->user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $importedUser->user->email }}</div>
                                <div class="text-xs text-gray-500">{{ $importedUser->user->phone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $importedUser->email_status_color === 'red' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $importedUser->email_status_color === 'orange' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $importedUser->email_status_color === 'green' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $importedUser->email_status_text }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $importedUser->time_since_last_email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($importedUser->canResendEmail())
                                    <form action="{{ route('admin.import.resend-email', $importedUser->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-[#2FCB6E] hover:text-[#25a356] font-medium text-sm" title="Resend Welcome Email">
                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">
                                        @if($importedUser->hasReachedMaxResends())
                                            Max resends reached
                                        @else
                                            Wait 1 hour
                                        @endif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Delete Import -->
<div class="mt-6 flex justify-end">
    <form action="{{ route('admin.import.destroy', $import->id) }}" method="POST" 
          onsubmit="return confirm('Are you sure you want to delete this import record? This will not delete the imported users.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Delete Import Record
        </button>
    </form>
</div>

@endsection