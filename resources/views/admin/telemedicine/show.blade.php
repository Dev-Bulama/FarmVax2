@extends('layouts.admin')
@section('title', 'Telemedicine Request')
@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <a href="{{ route('admin.telemedicine.index') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-4">
        <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to List
    </a>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    <h1 class="text-3xl font-bold mb-6" style="color:#11455b;">Consultation #{{ $req->id }}</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Status & Details --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center gap-3 mb-4">{!! $req->status_badge !!}
                    @if($req->priority === 'urgent') <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">URGENT</span> @endif
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><p class="text-gray-500 font-semibold">Patient</p><p class="font-bold text-gray-900">{{ $req->requester->name ?? '—' }}</p><p class="text-gray-500">{{ $req->requester->phone ?? '' }}</p></div>
                    <div><p class="text-gray-500 font-semibold">Assigned Vet</p><p class="font-bold text-gray-900">{{ $req->professional->name ?? 'Unassigned' }}</p></div>
                    @if($req->livestock)
                    <div><p class="text-gray-500 font-semibold">Animal</p><p class="text-gray-800">{{ $req->livestock->tag_number ?? ucfirst($req->livestock->livestock_type) }}</p></div>
                    @endif
                    <div><p class="text-gray-500 font-semibold">Room Code</p><p class="font-mono text-blue-700">{{ $req->room_code }}</p></div>
                    @if($req->started_at)
                    <div><p class="text-gray-500 font-semibold">Started</p><p>{{ $req->started_at->format('d M Y H:i') }}</p></div>
                    @endif
                    @if($req->ended_at)
                    <div><p class="text-gray-500 font-semibold">Duration</p><p>{{ $req->duration_minutes }} min</p></div>
                    @endif
                </div>
                <div class="mt-4 border-t pt-4">
                    <p class="text-sm font-semibold text-gray-500">Patient's Concern</p>
                    <p class="text-gray-800 mt-1">{{ $req->reason }}</p>
                    @if($req->notes)<p class="text-gray-600 text-sm mt-1">{{ $req->notes }}</p>@endif
                </div>
                @if($req->professional_notes)
                <div class="mt-4 bg-blue-50 rounded-lg p-4">
                    <p class="text-sm font-semibold text-blue-700">Vet's Notes</p>
                    <p class="text-blue-900 mt-1">{{ $req->professional_notes }}</p>
                </div>
                @endif
            </div>

            {{-- Admin Notes --}}
            <form action="{{ route('admin.telemedicine.notes', $req->id) }}" method="POST" class="bg-white rounded-xl shadow p-6">
                @csrf @method('PATCH')
                <h3 class="font-bold text-gray-700 mb-3">Admin Notes</h3>
                <textarea name="admin_notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm mb-3" placeholder="Internal notes…">{{ $req->admin_notes }}</textarea>
                <button type="submit" class="px-5 py-2 text-white font-semibold rounded-lg" style="background:#11455b;">Save Notes</button>
            </form>
        </div>

        <div class="space-y-4">
            {{-- Assign Vet --}}
            @if(in_array($req->status, ['pending', 'assigned']))
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-700 mb-3">Assign Veterinarian</h3>
                <form action="{{ route('admin.telemedicine.assign', $req->id) }}" method="POST">
                    @csrf
                    <select name="professional_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-3 text-sm">
                        <option value="">Select Vet…</option>
                        @foreach($vets as $vet)
                            <option value="{{ $vet->id }}" {{ $req->professional_id == $vet->id ? 'selected' : '' }}>{{ $vet->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full py-2 text-white font-bold rounded-lg" style="background:#2fcb6e;">Assign</button>
                </form>
            </div>
            @endif

            {{-- Cancel --}}
            @if(!in_array($req->status, ['completed', 'cancelled']))
            <form action="{{ route('admin.telemedicine.cancel', $req->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Cancel this consultation?')"
                        class="w-full py-3 border-2 border-red-400 text-red-600 font-bold rounded-xl hover:bg-red-50">
                    Cancel Consultation
                </button>
            </form>
            @endif

            {{-- Jitsi Link --}}
            <div class="bg-gray-50 rounded-xl p-4 text-sm">
                <p class="font-semibold text-gray-600 mb-1">Video Room Link</p>
                <a href="{{ $req->jitsi_url }}" target="_blank" class="text-blue-600 break-all hover:underline">{{ $req->jitsi_url }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
