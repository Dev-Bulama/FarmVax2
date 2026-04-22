@extends('layouts.professional')
@section('title', 'Telemedicine')
@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold mb-6" style="color:#11455b;">🎥 Telemedicine Dashboard</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    {{-- Active / Assigned --}}
    @if($myActive->count())
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-3" style="color:#11455b;">My Active Calls</h2>
        <div class="space-y-3">
            @foreach($myActive as $req)
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-400 flex justify-between items-center">
                <div>
                    <p class="font-bold text-gray-800">{{ $req->requester->name }}</p>
                    <p class="text-sm text-gray-500">{{ Str::limit($req->reason, 100) }}</p>
                    @if($req->livestock) <p class="text-xs text-gray-400">Animal: {{ $req->livestock->tag_number ?? ucfirst($req->livestock->livestock_type) }}</p> @endif
                </div>
                <div class="flex gap-2 ml-4">
                    <a href="{{ route('professional.telemedicine.join', $req->id) }}"
                       class="px-4 py-2 bg-green-600 text-white font-bold rounded-lg">🎥 Join</a>
                    <form action="{{ route('professional.telemedicine.complete', $req->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg">Mark Complete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Pending (available to accept) --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-3" style="color:#11455b;">Pending Requests
            <span class="text-base font-normal text-gray-500">({{ $pending->count() }} waiting)</span>
        </h2>
        @if($pending->count())
        <div class="space-y-3">
            @foreach($pending as $req)
            <div class="bg-white rounded-xl shadow p-5 border border-gray-100 flex justify-between items-start">
                <div>
                    @if($req->priority === 'urgent')
                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 mr-2">URGENT</span>
                    @endif
                    <span class="text-xs text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                    <p class="font-bold text-gray-800 mt-1">{{ $req->requester->name }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($req->reason, 150) }}</p>
                    @if($req->livestock) <p class="text-xs text-gray-400 mt-1">Animal: {{ $req->livestock->tag_number ?? ucfirst($req->livestock->livestock_type) }}</p> @endif
                    @if($req->scheduled_at) <p class="text-xs text-gray-400">Preferred: {{ $req->scheduled_at->format('d M Y H:i') }}</p> @endif
                </div>
                <form action="{{ route('professional.telemedicine.accept', $req->id) }}" method="POST" class="ml-4 shrink-0">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-white font-bold rounded-lg" style="background:#2fcb6e;">Accept</button>
                </form>
            </div>
            @endforeach
        </div>
        @else
            <div class="bg-white rounded-xl shadow p-8 text-center text-gray-400">No pending requests right now.</div>
        @endif
    </div>

    {{-- History --}}
    @if($myHistory->count())
    <div>
        <h2 class="text-xl font-bold mb-3" style="color:#11455b;">Past Consultations</h2>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-bold text-gray-600">Patient</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600">Status</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600">Duration</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-600">Date</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($myHistory as $req)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $req->requester->name }}</td>
                        <td class="px-4 py-3">{!! $req->status_badge !!}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $req->duration_minutes ? $req->duration_minutes.' min' : '—' }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $req->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('professional.telemedicine.show', $req->id) }}" class="text-blue-600 font-semibold">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $myHistory->links() }}</div>
    </div>
    @endif
</div>
@endsection
