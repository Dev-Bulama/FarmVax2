@extends('layouts.professional')
@section('title', 'Consultation Details')
@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <a href="{{ route('professional.telemedicine.index') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-4">
        <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back
    </a>
    <div class="bg-white rounded-xl shadow p-6 space-y-4">
        <div class="flex items-center gap-3">{!! $req->status_badge !!}</div>
        <div><p class="text-sm font-semibold text-gray-500">Patient</p><p class="font-bold text-gray-900">{{ $req->requester->name }}</p></div>
        <div><p class="text-sm font-semibold text-gray-500">Concern</p><p class="text-gray-800">{{ $req->reason }}</p></div>
        @if($req->professional_notes)
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-sm font-semibold text-blue-700">Your Notes</p>
            <p class="text-blue-900">{{ $req->professional_notes }}</p>
        </div>
        @endif
        @if($req->duration_minutes)
        <div><p class="text-sm font-semibold text-gray-500">Duration</p><p class="text-gray-800">{{ $req->duration_minutes }} minutes</p></div>
        @endif
    </div>
</div>
@endsection
