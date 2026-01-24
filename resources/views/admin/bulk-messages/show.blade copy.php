@extends('layouts.admin')

@section('title', 'Message Details')
@section('page-title', 'Message Details')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.bulk-messages.index') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Bulk Messages
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column - Message Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <div class="text-center pb-6 border-b">
                <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-3
                    @if($message->type == 'email') bg-blue-100
                    @elseif($message->type == 'sms') bg-green-100
                    @else bg-purple-100 @endif">
                    @if($message->type == 'email')
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    @elseif($message->type == 'sms')
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    @else
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ $message->title }}</h3>
                
                <!-- Status Badge -->
                <div class="mt-3">
                    @if($message->status == 'sent')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Sent</span>
                    @elseif($message->status == 'draft')
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($message->status) }}</span>
                    @endif
                </div>
            </div>

            <!-- Message Info -->
            <div class="space-y-3 mt-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Message Type</p>
                    <p class="text-sm text-gray-900">{{ strtoupper($message->type) }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Total Recipients</p>
                    <p class="text-sm text-gray-900">{{ number_format($message->total_recipients) }}</p>
                </div>

                @if($message->success_count > 0)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Successfully Sent</p>
                        <p class="text-sm text-green-600 font-semibold">{{ number_format($message->success_count) }}</p>
                    </div>
                @endif

                @if($message->failed_count > 0)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Failed</p>
                        <p class="text-sm text-red-600 font-semibold">{{ number_format($message->failed_count) }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Created</p>
                    <p class="text-sm text-gray-900">{{ $message->created_at->format('M d, Y h:i A') }}</p>
                </div>

                @if($message->sent_at)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Sent</p>
                        <p class="text-sm text-gray-900">{{ $message->sent_at->format('M d, Y h:i A') }}</p>
                    </div>
                @endif
            </div>

            <hr class="my-6">

            <!-- Actions -->
            @if($message->status == 'draft')
                <form action="{{ route('admin.bulk-messages.send', $message->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Send this message to {{ $message->total_recipients }} recipients?')"
                            class="w-full px-4 py-3 bg-[#2FCB6E] text-white rounded-lg hover:bg-[#25a356] transition font-semibold">
                        Send Now
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Right Column - Message Content & Logs -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Message Content -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Message Content</h3>
            </div>
            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $message->message }}</p>
                </div>
            </div>
        </div>

        <!-- Delivery Logs -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Delivery Logs</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recipient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sent At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $log->user->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->status == 'sent')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sent</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->sent_at ? $log->sent_at->format('M d, Y h:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                    No delivery logs yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>

</div>

@endsection