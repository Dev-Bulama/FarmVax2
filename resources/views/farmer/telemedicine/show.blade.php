@extends('layouts.farmer')
@section('title', 'Consultation Details')
@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('farmer.telemedicine.index') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-2">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>Back to Consultations
        </a>
        <h1 class="text-3xl font-bold" style="color:#11455b;">Consultation #{{ $req->id }}</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-3 mb-4">
                    {!! $req->status_badge !!}
                    @if($req->priority === 'urgent')
                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">URGENT</span>
                    @endif
                </div>

                <div class="space-y-3 text-sm">
                    <div><span class="font-semibold text-gray-600">Submitted:</span> {{ $req->created_at->format('d M Y H:i') }}</div>
                    @if($req->scheduled_at)
                    <div><span class="font-semibold text-gray-600">Scheduled:</span> {{ $req->scheduled_at->format('d M Y H:i') }}</div>
                    @endif
                    @if($req->started_at)
                    <div><span class="font-semibold text-gray-600">Call started:</span> {{ $req->started_at->format('d M Y H:i') }}</div>
                    @endif
                    @if($req->ended_at)
                    <div><span class="font-semibold text-gray-600">Call ended:</span> {{ $req->ended_at->format('d M Y H:i') }} ({{ $req->duration_minutes }} min)</div>
                    @endif
                    @if($req->livestock)
                    <div><span class="font-semibold text-gray-600">Animal:</span> {{ $req->livestock->tag_number ?? $req->livestock->name ?? ucfirst($req->livestock->livestock_type) }}</div>
                    @endif
                </div>

                <div class="mt-4 border-t pt-4">
                    <p class="font-semibold text-gray-700 mb-2">Your concern:</p>
                    <p class="text-gray-800">{{ $req->reason }}</p>
                    @if($req->notes)
                        <p class="text-gray-600 text-sm mt-2">{{ $req->notes }}</p>
                    @endif
                </div>

                @if($req->professional_notes)
                <div class="mt-4 border-t pt-4 bg-blue-50 rounded-lg p-4">
                    <p class="font-semibold text-blue-800 mb-2">Vet's Notes:</p>
                    <p class="text-blue-900">{{ $req->professional_notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            @if(in_array($req->status, ['assigned', 'in_progress']))
            <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-center">
                <div class="text-4xl mb-2">🎥</div>
                <p class="font-bold text-green-800 mb-3">Your vet is ready!</p>
                <a href="{{ route('farmer.telemedicine.join', $req->id) }}"
                   class="block w-full py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700">
                    Join Video Call
                </a>
            </div>
            @endif

            <div class="bg-white rounded-xl shadow p-5">
                <p class="font-semibold text-gray-700 mb-2">Assigned Vet</p>
                @if($req->professional)
                    <p class="font-bold text-gray-900">{{ $req->professional->name }}</p>
                    <p class="text-sm text-gray-500">{{ $req->professional->phone ?? '' }}</p>
                @else
                    <p class="text-gray-500 text-sm">Awaiting vet assignment…</p>
                @endif
            </div>

            @if(in_array($req->status, ['pending', 'assigned']))
            <form action="{{ route('farmer.telemedicine.cancel', $req->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Cancel this request?')"
                        class="w-full py-3 text-red-600 border-2 border-red-300 font-semibold rounded-xl hover:bg-red-50">
                    Cancel Request
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
