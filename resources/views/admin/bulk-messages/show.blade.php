@extends('layouts.admin')

@section('title', 'Bulk Message Details')

@section('content')

<!-- Page Header -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $message->title }}</h2>
        <p class="text-gray-600 mt-1">Message Details & Delivery Status</p>
    </div>
    <div class="flex space-x-3">
        @if($message->status == 'draft')
        <form action="{{ route('admin.bulk-messages.send', $message->id) }}" method="POST" 
              onsubmit="return confirm('Send this message to {{ number_format($message->total_recipients) }} recipients?');">
            @csrf
            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition">
                Send Now
            </button>
        </form>
        @endif
        <a href="{{ route('admin.bulk-messages.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition">
            Back to List
        </a>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Message Content -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Message Content</h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Title</p>
                    <p class="text-base text-gray-900 mt-1">{{ $message->title }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Message Type</p>
                    <p class="text-base text-gray-900 mt-1">
                        @if($message->type == 'email')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Email</span>
                        @elseif($message->type == 'sms')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">SMS</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Both</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-2">Message</p>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-900 whitespace-pre-wrap">{{ $message->message }}</p>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">{{ strlen($message->message) }} characters</p>
                </div>
            </div>
        </div>

        <!-- Delivery Logs -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Delivery Logs</h3>
            </div>

            @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Channel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-semibold text-gray-600">{{ substr($log->user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->channel == 'email')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Email</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">SMS</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log->status == 'sent')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Sent</span>
                                @elseif($log->status == 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $log->sent_at ? $log->sent_at->format('M d, Y H:i') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $logs->links() }}
                </div>
            @endif
            @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="mt-4 text-gray-500">No delivery logs yet</p>
                <p class="text-sm text-gray-400 mt-2">Logs will appear after the message is sent</p>
            </div>
            @endif
        </div>

    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        
        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Statistics</h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Total Recipients</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($message->total_recipients) }}</p>
                </div>

                @if($message->status == 'sent')
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">Successfully Sent</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($message->success_count ?? 0) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Failed</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($message->failed_count ?? 0) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-2">Success Rate</p>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" style="width: {{ $message->success_rate }}%"></div>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $message->success_rate }}%</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Current Status</p>
                    <p class="text-base font-semibold text-gray-900 mt-1">
                        @if($message->status == 'sent')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Sent</span>
                        @elseif($message->status == 'sending')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">Sending...</span>
                        @elseif($message->status == 'draft')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                        @endif
                    </p>
                </div>

                @if($message->sent_at)
                <div>
                    <p class="text-sm text-gray-600">Sent At</p>
                    <p class="text-base text-gray-900 mt-1">{{ $message->sent_at->format('M d, Y H:i') }}</p>
                </div>
                @endif

                <div>
                    <p class="text-sm text-gray-600">Created By</p>
                    <p class="text-base text-gray-900 mt-1">{{ $message->creator->name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Created At</p>
                    <p class="text-base text-gray-900 mt-1">{{ $message->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
            
            <div class="space-y-3">
                @if($message->status == 'sent')
                <form action="{{ route('admin.bulk-messages.send', $message->id) }}" method="POST" 
                      onsubmit="return confirm('Resend this message to all recipients?');">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                        Resend Message
                    </button>
                </form>
                @endif

                <form action="{{ route('admin.bulk-messages.destroy', $message->id) }}" method="POST" 
                      onsubmit="return confirm('Delete this message permanently?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition">
                        Delete Message
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>

@endsection