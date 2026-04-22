@extends('layouts.farmer')
@section('title', 'Video Call')
@section('content')
<div class="p-4 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold" style="color:#11455b;">🎥 Video Consultation</h1>
            <p class="text-gray-500 text-sm">With {{ $req->professional->name ?? 'Veterinarian' }}</p>
        </div>
        <a href="{{ route('farmer.telemedicine.show', $req->id) }}"
           class="px-4 py-2 border-2 font-semibold rounded-lg" style="border-color:#11455b;color:#11455b;">
            Back to Details
        </a>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden" style="height:75vh;">
        <iframe
            src="https://meet.jit.si/{{ $req->room_code }}#userInfo.displayName={{ urlencode(auth()->user()->name) }}&config.prejoinPageEnabled=false&config.startWithAudioMuted=false&config.startWithVideoMuted=false"
            style="width:100%;height:100%;border:0;"
            allow="camera; microphone; fullscreen; display-capture; autoplay"
            allowfullscreen>
        </iframe>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="font-bold text-blue-800">Room Code</p>
            <p class="text-blue-700 font-mono">{{ $req->room_code }}</p>
        </div>
        @if($req->livestock)
        <div class="bg-green-50 rounded-lg p-4">
            <p class="font-bold text-green-800">Animal</p>
            <p class="text-green-700">{{ $req->livestock->tag_number ?? $req->livestock->name ?? ucfirst($req->livestock->livestock_type) }}</p>
        </div>
        @endif
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="font-bold text-gray-700">Your concern</p>
            <p class="text-gray-600">{{ Str::limit($req->reason, 80) }}</p>
        </div>
    </div>

    <p class="text-xs text-gray-400 mt-3 text-center">
        Powered by Jitsi Meet — end-to-end encrypted, no account required. Allow camera & microphone access when prompted.
    </p>
</div>
@endsection
