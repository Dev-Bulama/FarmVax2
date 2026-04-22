@extends('layouts.farmer')
@section('title', 'Video Consultations')
@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color:#11455b;">🎥 Video Consultations</h1>
            <p class="text-gray-500 mt-1">Request real-time video calls with veterinarians</p>
        </div>
        <a href="{{ route('farmer.telemedicine.create') }}"
           class="px-5 py-3 text-white font-bold rounded-xl shadow" style="background:#2fcb6e;">
            + New Request
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    @if($requests->count())
        <div class="space-y-4">
            @foreach($requests as $req)
            <div class="bg-white rounded-xl shadow p-5 border border-gray-100">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            {!! $req->status_badge !!}
                            @if($req->priority === 'urgent')
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">URGENT</span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="font-semibold text-gray-800">{{ Str::limit($req->reason, 120) }}</p>
                        @if($req->livestock)
                            <p class="text-sm text-gray-500 mt-1">Animal: {{ $req->livestock->tag_number ?? $req->livestock->name ?? ucfirst($req->livestock->livestock_type) }}</p>
                        @endif
                        @if($req->professional)
                            <p class="text-sm text-gray-500">Vet: {{ $req->professional->name }}</p>
                        @endif
                        @if($req->scheduled_at)
                            <p class="text-sm text-gray-500">Scheduled: {{ $req->scheduled_at->format('d M Y H:i') }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2 ml-4">
                        @if(in_array($req->status, ['assigned', 'in_progress']))
                            <a href="{{ route('farmer.telemedicine.join', $req->id) }}"
                               class="px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-lg text-center">
                                🎥 Join Call
                            </a>
                        @endif
                        <a href="{{ route('farmer.telemedicine.show', $req->id) }}"
                           class="px-4 py-2 border-2 text-sm font-semibold rounded-lg text-center"
                           style="border-color:#11455b;color:#11455b;">View</a>
                        @if(in_array($req->status, ['pending', 'assigned']))
                            <form action="{{ route('farmer.telemedicine.cancel', $req->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Cancel this request?')"
                                        class="w-full px-4 py-2 text-sm font-semibold text-red-600 border border-red-300 rounded-lg">
                                    Cancel
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $requests->links() }}</div>
    @else
        <div class="text-center py-16 bg-white rounded-xl shadow">
            <div class="text-6xl mb-4">🎥</div>
            <h3 class="text-xl font-bold text-gray-700">No consultations yet</h3>
            <p class="text-gray-500 mt-2">Request a video call with a veterinarian to get expert advice on your animals.</p>
            <a href="{{ route('farmer.telemedicine.create') }}"
               class="mt-6 inline-block px-6 py-3 text-white font-bold rounded-xl" style="background:#2fcb6e;">
                Request Your First Consultation
            </a>
        </div>
    @endif
</div>
@endsection
