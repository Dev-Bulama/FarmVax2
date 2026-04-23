@extends('layouts.professional')
@section('title', 'Telemedicine')
@section('content')
<div class="p-6 max-w-6xl mx-auto">

    {{-- ===== INCOMING CALL BANNER (hidden by default, shown by JS) ===== --}}
    <div id="incoming-call-banner"
         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center animate-bounce-slow border-4 border-green-400">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 ring-4 ring-green-400 ring-offset-2 animate-pulse">
                <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <p id="incoming-priority-badge" class="hidden mb-2">
                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full uppercase">Urgent</span>
            </p>
            <h2 class="text-2xl font-black text-gray-900 mb-1">Incoming Call</h2>
            <p class="text-lg font-semibold text-green-700 mb-1" id="incoming-farmer-name"></p>
            <p class="text-sm text-gray-500 mb-6" id="incoming-reason"></p>
            <div class="flex gap-3 justify-center">
                <a id="incoming-join-btn" href="#"
                   class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-lg transition">
                    Accept &amp; Join
                </a>
                <button id="incoming-decline-btn"
                        class="flex-1 py-3 bg-red-100 hover:bg-red-200 text-red-700 font-bold rounded-xl text-lg transition">
                    Decline
                </button>
            </div>
        </div>
    </div>

    <h1 class="text-3xl font-bold mb-6" style="color:#11455b;">🎥 Telemedicine</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Active / Assigned calls --}}
    @if($myActive->count())
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-3" style="color:#11455b;">My Active Calls</h2>
        <div class="space-y-3">
            @foreach($myActive as $req)
            <div class="bg-white rounded-xl shadow p-5 border-l-4 border-green-400 flex justify-between items-center">
                <div>
                    @if($req->priority === 'urgent')
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700 mr-1">URGENT</span>
                    @endif
                    <p class="font-bold text-gray-800 mt-1">{{ $req->requester->name }}</p>
                    <p class="text-sm text-gray-500">{{ Str::limit($req->reason, 100) }}</p>
                    @if($req->livestock)
                        <p class="text-xs text-gray-400">Animal: {{ $req->livestock->tag_number ?? ucfirst($req->livestock->livestock_type) }}</p>
                    @endif
                </div>
                <div class="flex gap-2 ml-4 shrink-0">
                    <a href="{{ route('professional.telemedicine.join', $req->id) }}"
                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition">
                        🎥 Join
                    </a>
                    <form action="{{ route('professional.telemedicine.complete', $req->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                            Complete
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Pending requests (all vets can accept) --}}
    <div class="mb-8">
        <h2 class="text-xl font-bold mb-3" style="color:#11455b;">
            Pending Requests
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
                    @if($req->livestock)
                        <p class="text-xs text-gray-400 mt-1">Animal: {{ $req->livestock->tag_number ?? ucfirst($req->livestock->livestock_type) }}</p>
                    @endif
                </div>
                <form action="{{ route('professional.telemedicine.accept', $req->id) }}" method="POST" class="ml-4 shrink-0">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-white font-bold rounded-lg transition"
                            style="background:#2fcb6e;">
                        Accept
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @else
            <div class="bg-white rounded-xl shadow p-8 text-center text-gray-400">
                No pending requests right now.
            </div>
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
                            <a href="{{ route('professional.telemedicine.show', $req->id) }}"
                               class="text-blue-600 font-semibold hover:underline">View</a>
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

<style>
@keyframes bounce-slow {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-6px); }
}
.animate-bounce-slow { animation: bounce-slow 1.5s ease-in-out infinite; }
</style>

<script>
(function () {
    const CSRF      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const POLL_URL  = '{{ route("professional.telemedicine.poll") }}';
    const banner    = document.getElementById('incoming-call-banner');
    const joinBtn   = document.getElementById('incoming-join-btn');
    const declineBtn= document.getElementById('incoming-decline-btn');
    const namEl     = document.getElementById('incoming-farmer-name');
    const reasonEl  = document.getElementById('incoming-reason');
    const priBadge  = document.getElementById('incoming-priority-badge');

    // Start tracking from the highest active call ID we already know about
    let lastId   = {{ $myActive->max('id') ?? 0 }};
    let shownIds = new Set([{{ $myActive->pluck('id')->join(',') }}]);
    let currentCallId = null;

    function showBanner(call) {
        currentCallId = call.id;
        namEl.textContent    = call.farmer;
        reasonEl.textContent = call.reason;
        joinBtn.href         = call.join_url;
        priBadge.classList.toggle('hidden', call.priority !== 'urgent');
        banner.classList.remove('hidden');

        // Attempt a subtle notification sound via Web Audio API
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            [440, 550, 660].forEach((freq, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain); gain.connect(ctx.destination);
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.15, ctx.currentTime + i * 0.18);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.18 + 0.25);
                osc.start(ctx.currentTime + i * 0.18);
                osc.stop(ctx.currentTime + i * 0.18 + 0.3);
            });
        } catch (_) {}
    }

    declineBtn.addEventListener('click', function () {
        if (!currentCallId) return;
        fetch('/professional/telemedicine/' + currentCallId + '/decline', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        }).catch(() => {});
        banner.classList.add('hidden');
        shownIds.add(currentCallId);
        currentCallId = null;
    });

    function poll() {
        fetch(POLL_URL + '?after=' + lastId, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        })
        .then(r => r.json())
        .then(data => {
            (data.calls || []).forEach(call => {
                if (shownIds.has(call.id)) return;
                shownIds.add(call.id);
                lastId = Math.max(lastId, call.id);
                showBanner(call);
            });
        })
        .catch(() => {});
    }

    // Poll immediately, then every 5 seconds
    poll();
    setInterval(poll, 5000);
})();
</script>
@endsection
