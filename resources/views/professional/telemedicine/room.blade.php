@extends('layouts.professional')
@section('title', 'Video Call')
@section('content')

{{-- Full-height dark video room --}}
<div id="call-room" class="fixed inset-0 bg-gray-950 flex" style="z-index:50;">

    {{-- Video panel (left) --}}
    <div class="flex-1 flex flex-col">

        {{-- Top bar --}}
        <div class="flex items-center justify-between px-5 py-3 bg-black/40 backdrop-blur">
            <div>
                <p class="text-white font-bold text-lg">
                    Consultation — {{ $req->requester->name }}
                </p>
                @if($req->livestock)
                    <p class="text-gray-400 text-sm">
                        Animal: {{ $req->livestock->tag_number ?? ucfirst($req->livestock->livestock_type) }}
                    </p>
                @endif
            </div>
            <span id="conn-status" class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-black">⏳ Waiting…</span>
        </div>

        {{-- Video --}}
        <div class="flex-1 relative overflow-hidden">
            <video id="remote-video" autoplay playsinline
                   class="w-full h-full object-cover bg-gray-900"
                   style="display:none;"></video>

            {{-- Waiting overlay --}}
            <div id="waiting-overlay" class="absolute inset-0 flex flex-col items-center justify-center text-center">
                <div class="w-24 h-24 rounded-full bg-gray-700 flex items-center justify-center text-4xl mb-4 animate-pulse">
                    🧑‍🌾
                </div>
                <p class="text-white text-xl font-bold">Waiting for {{ $req->requester->name }}…</p>
                <p class="text-gray-400 mt-2 text-sm">The farmer will join shortly. Ensure your devices are ready.</p>
                <div class="mt-6 rounded-xl overflow-hidden shadow-2xl" style="width:280px;height:200px;background:#111;">
                    <video id="local-preview-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                </div>
            </div>

            {{-- Local PiP --}}
            <div id="local-pip"
                 class="absolute bottom-4 right-4 rounded-xl overflow-hidden shadow-2xl border-2 border-gray-700"
                 style="width:160px;height:120px;display:none;cursor:move;z-index:10;">
                <video id="local-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
                <div class="absolute inset-0 flex items-end p-1">
                    <span class="text-xs text-white bg-black/60 px-1.5 py-0.5 rounded">You (Vet)</span>
                </div>
            </div>
        </div>

        {{-- Control bar --}}
        <div class="flex items-center justify-center gap-6 py-4 bg-black/40 backdrop-blur">
            <button id="btn-mic" onclick="toggleMic()" title="Toggle microphone"
                    class="w-14 h-14 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-2xl transition">
                🎤
            </button>
            <button id="btn-cam" onclick="toggleCam()" title="Toggle camera"
                    class="w-14 h-14 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-2xl transition">
                📷
            </button>
            <button onclick="hangUp()" title="End call"
                    class="w-16 h-16 rounded-full bg-red-600 hover:bg-red-700 flex items-center justify-center text-3xl shadow-lg transition">
                📵
            </button>
        </div>
    </div>

    {{-- Side panel (right) --}}
    <div class="w-72 flex flex-col bg-gray-900 border-l border-gray-800 overflow-y-auto">
        <div class="p-4 border-b border-gray-800">
            <p class="text-white font-bold mb-1">{{ $req->requester->name }}</p>
            <p class="text-gray-400 text-sm">{{ $req->requester->phone ?? '—' }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $req->requester->state?->name ?? '' }}</p>
        </div>

        <div class="p-4 border-b border-gray-800">
            <p class="text-xs text-gray-500 uppercase font-bold mb-1">Concern</p>
            <p class="text-gray-200 text-sm">{{ $req->reason }}</p>
            @if($req->notes)
                <p class="text-gray-400 text-xs mt-2">{{ $req->notes }}</p>
            @endif
        </div>

        <div class="p-4 flex-1">
            <form action="{{ route('professional.telemedicine.complete', $req->id) }}" method="POST">
                @csrf
                <label class="block text-xs text-gray-400 uppercase font-bold mb-2">Consultation Notes</label>
                <textarea name="professional_notes" rows="8"
                          class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-green-500 resize-none"
                          placeholder="Diagnosis, treatment prescribed, follow-up instructions…"></textarea>

                <button type="submit"
                        onclick="return confirmComplete()"
                        class="mt-4 w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition">
                    ✅ End &amp; Complete Consultation
                </button>
            </form>
        </div>

        <div class="p-4 border-t border-gray-800">
            <a href="{{ route('professional.telemedicine.index') }}"
               class="block text-center text-xs text-gray-500 hover:text-gray-300">
                ← Back to dashboard (without completing)
            </a>
        </div>
    </div>
</div>

<script>
const ROOM_CODE  = @json($req->room_code);
const SIGNAL_URL = '/api/telemedicine/' + ROOM_CODE;
const MY_ROLE    = 'callee';    // professional always receives offer
const CSRF_TOKEN = @json(csrf_token());

const iceConfig = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' },
        { urls: 'stun:stun.cloudflare.com:3478' },
    ]
};

let pc, localStream;
let lastSignalId = 0;
let hasOffer  = false;
let micOn = true, camOn = true;
let pollTimer;

// ── Start ────────────────────────────────────────────────────────────────────
async function start() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        document.getElementById('local-preview-video').srcObject = localStream;
    } catch (err) {
        setStatus('⚠️ Camera/Mic denied', 'red');
        alert('Camera and microphone access is required.\n' + err.message);
        return;
    }

    pc = new RTCPeerConnection(iceConfig);
    localStream.getTracks().forEach(track => pc.addTrack(track, localStream));

    pc.ontrack = e => {
        document.getElementById('remote-video').srcObject = e.streams[0];
        document.getElementById('remote-video').style.display = 'block';
        document.getElementById('waiting-overlay').style.display = 'none';
        document.getElementById('local-pip').style.display = 'block';
        document.getElementById('local-video').srcObject = localStream;
        setStatus('🟢 Connected', 'green');
    };

    pc.onicecandidate = e => {
        if (e.candidate) postSignal('ice', JSON.stringify(e.candidate));
    };

    pc.onconnectionstatechange = () => {
        if (pc.connectionState === 'disconnected' || pc.connectionState === 'failed') {
            setStatus('🔴 Disconnected', 'red');
        }
    };

    setStatus('⏳ Waiting for farmer…', 'yellow');
    pollTimer = setInterval(poll, 1500);
}

// ── Polling ──────────────────────────────────────────────────────────────────
async function poll() {
    try {
        const res  = await fetch(`${SIGNAL_URL}/signals?for_role=${MY_ROLE}&last_id=${lastSignalId}`);
        const data = await res.json();

        for (const sig of (data.signals || [])) {
            lastSignalId = sig.id;

            if (sig.type === 'offer' && !hasOffer) {
                hasOffer = true;
                setStatus('🟡 Farmer connected — answering…', 'yellow');
                await pc.setRemoteDescription(JSON.parse(sig.payload));
                const answer = await pc.createAnswer();
                await pc.setLocalDescription(answer);
                await postSignal('answer', JSON.stringify(answer));

            } else if (sig.type === 'ice' && pc.remoteDescription) {
                try { await pc.addIceCandidate(JSON.parse(sig.payload)); } catch(_) {}

            } else if (sig.type === 'hangup') {
                clearInterval(pollTimer);
                setStatus('🔴 Farmer ended call', 'red');
                alert('The farmer has ended the call. Please complete your consultation notes.');
            }
        }
    } catch (_) {}
}

// ── Controls ─────────────────────────────────────────────────────────────────
function toggleMic() {
    micOn = !micOn;
    localStream.getAudioTracks().forEach(t => t.enabled = micOn);
    document.getElementById('btn-mic').textContent = micOn ? '🎤' : '🔇';
    document.getElementById('btn-mic').classList.toggle('bg-red-700', !micOn);
    document.getElementById('btn-mic').classList.toggle('bg-gray-700', micOn);
}

function toggleCam() {
    camOn = !camOn;
    localStream.getVideoTracks().forEach(t => t.enabled = camOn);
    document.getElementById('btn-cam').textContent = camOn ? '📷' : '🚫';
    document.getElementById('btn-cam').classList.toggle('bg-red-700', !camOn);
    document.getElementById('btn-cam').classList.toggle('bg-gray-700', camOn);
}

async function hangUp() {
    await postSignal('hangup', 'ended');
    clearInterval(pollTimer);
    cleanup();
    window.location.href = @json(route('professional.telemedicine.index'));
}

function confirmComplete() {
    clearInterval(pollTimer);
    postSignal('hangup', 'ended');
    cleanup();
    return true;    // let the form submit
}

function cleanup() {
    if (pc) { pc.close(); pc = null; }
    if (localStream) localStream.getTracks().forEach(t => t.stop());
}

function setStatus(text, color) {
    const el = document.getElementById('conn-status');
    el.textContent = text;
    el.className = 'px-3 py-1 rounded-full text-xs font-bold ' + {
        yellow: 'bg-yellow-500 text-black',
        green:  'bg-green-500 text-black',
        red:    'bg-red-600 text-white',
    }[color];
}

async function postSignal(type, payload) {
    try {
        await fetch(`${SIGNAL_URL}/signal`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ from_role: MY_ROLE, type, payload }),
        });
    } catch(_) {}
}

// ── Draggable PiP ─────────────────────────────────────────────────────────────
(function makeDraggable() {
    const pip = document.getElementById('local-pip');
    let ox = 0, oy = 0;
    pip.addEventListener('mousedown', e => {
        e.preventDefault();
        ox = e.clientX - pip.offsetLeft;
        oy = e.clientY - pip.offsetTop;
        document.onmousemove = ev => {
            pip.style.left   = (ev.clientX - ox) + 'px';
            pip.style.top    = (ev.clientY - oy) + 'px';
            pip.style.right  = 'auto';
            pip.style.bottom = 'auto';
        };
        document.onmouseup = () => { document.onmousemove = null; };
    });
})();

window.addEventListener('beforeunload', () => {
    postSignal('hangup', 'ended');
    cleanup();
});

start();
</script>
@endsection
