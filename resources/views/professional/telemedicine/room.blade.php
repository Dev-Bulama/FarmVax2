@extends('layouts.professional')
@section('title', 'Video Call')
@section('content')
<div class="p-4 max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold" style="color:#11455b;">🎥 Consultation with {{ $req->requester->name }}</h1>
            @if($req->livestock)
                <p class="text-gray-500 text-sm">Animal: {{ $req->livestock->tag_number ?? ucfirst($req->livestock->livestock_type) }}</p>
            @endif
        </div>
        <a href="{{ route('professional.telemedicine.index') }}"
           class="px-4 py-2 border-2 font-semibold rounded-lg" style="border-color:#11455b;color:#11455b;">
            Back to Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        <div class="lg:col-span-3 bg-white rounded-xl shadow overflow-hidden" style="height:70vh;">
            <iframe
                src="https://meet.jit.si/{{ $req->room_code }}#userInfo.displayName={{ urlencode(auth()->user()->name . ' (Vet)') }}&config.prejoinPageEnabled=false"
                style="width:100%;height:100%;border:0;"
                allow="camera; microphone; fullscreen; display-capture; autoplay"
                allowfullscreen>
            </iframe>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow p-4">
                <p class="font-bold text-gray-700 mb-2">Patient Info</p>
                <p class="text-sm text-gray-800 font-semibold">{{ $req->requester->name }}</p>
                <p class="text-sm text-gray-500">{{ $req->requester->phone ?? '—' }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $req->requester->state?->name ?? '' }}</p>
            </div>

            <div class="bg-blue-50 rounded-xl p-4">
                <p class="font-bold text-blue-800 mb-2">Concern</p>
                <p class="text-sm text-blue-900">{{ $req->reason }}</p>
                @if($req->notes) <p class="text-xs text-blue-700 mt-1">{{ $req->notes }}</p> @endif
            </div>

            <form action="{{ route('professional.telemedicine.complete', $req->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Consultation Notes</label>
                    <textarea name="professional_notes" rows="5"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                              placeholder="Diagnosis, recommendations, prescriptions..."></textarea>
                </div>
                <button type="submit"
                        onclick="return confirm('Mark this consultation as complete?')"
                        class="w-full py-3 bg-gray-700 text-white font-bold rounded-xl hover:bg-gray-800">
                    ✅ End & Complete Call
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
