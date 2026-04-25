@extends('layouts.professional')
@section('title', 'Video Consultation')
@php
    $provider   = \App\Models\Setting::get('telemedicine_provider', 'custom');
    $jitsiDomain = \App\Models\Setting::get('jitsi_domain', 'meet.jit.si');
    $farmerName = $req->requester->name ?? 'Farmer';
@endphp
@section('content')

{{-- WhatsApp-style full-screen call room --}}
<div id="call-room" class="fixed inset-0 flex flex-col" style="z-index:9999; background:#0b141a;">

    @if($provider === 'jitsi')
    {{-- ── JITSI MODE ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col h-full">
        {{-- Top bar --}}
        <div class="flex items-center justify-between px-4 py-3 safe-top" style="background:rgba(0,0,0,0.6);">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr($farmerName, 0, 1)) }}
                </div>
                <div>
                    <p class="text-white font-semibold text-sm leading-tight">{{ $farmerName }}</p>
                    <p id="call-timer" class="text-green-400 text-xs">Connecting…</p>
                </div>
            </div>
            <a href="{{ route('professional.telemedicine.index') }}" class="text-gray-400 text-xs border border-gray-600 px-3 py-1 rounded-full">← Dashboard</a>
        </div>

        {{-- Jitsi iframe --}}
        <iframe id="jitsi-frame"
                src="https://{{ $jitsiDomain }}/farmvax-{{ $req->room_code }}#config.startWithAudioMuted=false&config.startWithVideoMuted=false&config.prejoinPageEnabled=false&config.disableDeepLinking=true&userInfo.displayName={{ urlencode('Dr. '.auth()->user()->name) }}&interfaceConfig.SHOW_JITSI_WATERMARK=false"
                allow="camera; microphone; fullscreen; display-capture; autoplay"
                class="flex-1 w-full border-0"
                style="min-height:0;"></iframe>

        {{-- Notes / Complete bottom sheet --}}
        <div class="safe-bottom px-4 pb-4 pt-3" style="background:rgba(0,0,0,0.85);">
            <form action="{{ route('professional.telemedicine.complete', $req->id) }}" method="POST" class="space-y-2">
                @csrf
                <textarea name="professional_notes" rows="2"
                          class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-xl text-sm text-white placeholder-gray-500 focus:ring-2 focus:ring-green-500 resize-none"
                          placeholder="Consultation notes (diagnosis, treatment, follow-up)…"></textarea>
                <div class="flex gap-3">
                    <button type="submit" onclick="endJitsiCall(); return true;"
                            class="flex-1 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition text-sm">
                        ✅ Complete Consultation
                    </button>
                    <button type="button" onclick="endJitsiCall()"
                            class="w-14 h-12 rounded-xl bg-red-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @else
    {{-- ── CUSTOM WEBRTC MODE ──────────────────────────────────────────── --}}

    {{-- Remote video --}}
    <video id="remote-video" autoplay playsinline
           class="absolute inset-0 w-full h-full object-cover"
           style="display:none; z-index:1;"></video>

    {{-- Waiting overlay --}}
    <div id="waiting-overlay" class="absolute inset-0 flex flex-col items-center justify-center"
         style="z-index:2; background:linear-gradient(180deg,#1a2d35 0%,#0b1a20 100%);">
        <div class="w-28 h-28 rounded-full bg-green-700/30 border-4 border-green-600 flex items-center justify-center text-5xl mb-4 animate-pulse">
            🧑‍🌾
        </div>
        <p class="text-white text-2xl font-semibold">{{ $farmerName }}</p>
        <p class="text-green-400 text-sm mt-1">Waiting for patient…</p>
        @if($req->livestock)
            <p class="text-gray-400 text-xs mt-2">Animal: {{ $req->livestock->tag_number ?? ucfirst($req->livestock->livestock_type) }}</p>
        @endif
        <div class="mt-6 rounded-2xl overflow-hidden border-2 border-white/20 shadow-2xl" style="width:200px;height:140px;background:#111;">
            <video id="local-preview-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
        </div>
        <p class="text-gray-400 text-xs mt-2">Your camera preview</p>
    </div>

    {{-- Local PiP --}}
    <div id="local-pip"
         class="absolute rounded-2xl overflow-hidden border-2 border-white/30 shadow-2xl"
         style="width:100px;height:145px;bottom:220px;right:12px;display:none;cursor:move;z-index:5;touch-action:none;">
        <video id="local-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
        <div class="absolute inset-0 flex items-end p-1.5">
            <span class="text-xs text-white bg-black/50 px-2 py-0.5 rounded-full">You</span>
        </div>
    </div>

    {{-- Top bar --}}
    <div class="absolute top-0 left-0 right-0 safe-top flex items-center justify-between px-4 py-3"
         style="z-index:10; background:linear-gradient(180deg,rgba(0,0,0,0.7) 0%,transparent 100%);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-base">
                {{ strtoupper(substr($farmerName, 0, 1)) }}
            </div>
            <div>
                <p class="text-white font-semibold text-sm leading-tight">{{ $farmerName }}</p>
                <p id="call-timer" class="text-green-400 text-xs">Waiting…</p>
            </div>
        </div>
        <span id="conn-badge" class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-black">⏳</span>
    </div>

    {{-- Notes bottom sheet --}}
    <div id="notes-sheet" class="absolute bottom-0 left-0 right-0 safe-bottom px-4 pb-3"
         style="z-index:10; background:linear-gradient(0deg,rgba(0,0,0,0.9) 50%,transparent 100%);">

        {{-- Controls row --}}
        <div class="flex items-end justify-center gap-8 mb-4">
            <button id="btn-mic" onclick="toggleMic()" class="flex flex-col items-center gap-1.5">
                <span id="mic-icon" class="w-14 h-14 rounded-full bg-white/20 backdrop-blur flex items-center justify-center">
                    <svg id="mic-svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3zm5 11a5 5 0 0 1-10 0H5a7 7 0 0 0 6 6.93V21H9v2h6v-2h-2v-2.07A7 7 0 0 0 19 12h-2z"/>
                    </svg>
                </span>
                <span class="text-white text-xs">Mute</span>
            </button>

            <button onclick="hangUp()" class="flex flex-col items-center gap-1.5">
                <span class="w-16 h-16 rounded-full bg-red-600 flex items-center justify-center shadow-2xl">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
                    </svg>
                </span>
                <span class="text-red-400 text-xs font-semibold">End</span>
            </button>

            <button id="btn-cam" onclick="toggleCam()" class="flex flex-col items-center gap-1.5">
                <span id="cam-icon" class="w-14 h-14 rounded-full bg-white/20 backdrop-blur flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 10.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-3.5l4 4v-11l-4 4z"/>
                    </svg>
                </span>
                <span class="text-white text-xs">Camera</span>
            </button>
        </div>

        {{-- Consultation notes (visible during call) --}}
        <form action="{{ route('professional.telemedicine.complete', $req->id) }}" method="POST">
            @csrf
            <div class="bg-gray-900/90 rounded-2xl p-3 border border-gray-700">
                <p class="text-xs text-gray-400 mb-1.5 font-semibold uppercase tracking-wide">Consultation Notes</p>
                <textarea name="professional_notes" rows="2"
                          class="w-full bg-transparent text-sm text-white placeholder-gray-500 border-0 focus:ring-0 resize-none outline-none"
                          placeholder="Type notes during the call…"></textarea>
                <div class="flex justify-end mt-2">
                    <button type="submit" onclick="return confirmComplete()"
                            class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm transition">
                        ✅ Complete
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>

@if($provider === 'jitsi')
<script>
let timerSec = 0;
const timerEl = document.getElementById('call-timer');
const timerId = setInterval(() => {
    timerSec++;
    timerEl.textContent = String(Math.floor(timerSec/60)).padStart(2,'0') + ':' + String(timerSec%60).padStart(2,'0');
}, 1000);
function endJitsiCall() { clearInterval(timerId); }
</script>
@else
<script>
const ROOM_CODE  = @json($req->room_code);
const SIGNAL_URL = '/api/telemedicine/' + ROOM_CODE;
const MY_ROLE    = 'callee';
const CSRF_TOKEN = @json(csrf_token());

const iceConfig = { iceServers: [
    { urls: 'stun:stun.l.google.com:19302' },
    { urls: 'stun:stun1.l.google.com:19302' },
    { urls: 'stun:stun.cloudflare.com:3478' },
]};

let pc, localStream, micOn = true, camOn = true;
let lastSignalId = 0, hasOffer = false;
let pollTimer, timerInterval, timerSec = 0;

async function start() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        document.getElementById('local-preview-video').srcObject = localStream;
    } catch (err) {
        setStatus('⚠️ No camera/mic', 'red');
        alert('Camera and microphone access is required.\n' + err.message);
        return;
    }

    pc = new RTCPeerConnection(iceConfig);
    localStream.getTracks().forEach(t => pc.addTrack(t, localStream));

    pc.ontrack = e => {
        document.getElementById('remote-video').srcObject = e.streams[0];
        document.getElementById('remote-video').style.display = 'block';
        document.getElementById('waiting-overlay').style.display = 'none';
        document.getElementById('local-pip').style.display = 'block';
        document.getElementById('local-video').srcObject = localStream;
        setStatus('🟢 Connected', 'green');
        startTimer();
    };

    pc.onicecandidate = e => { if (e.candidate) postSignal('ice', JSON.stringify(e.candidate)); };
    pc.onconnectionstatechange = () => {
        if (['disconnected','failed'].includes(pc.connectionState)) setStatus('🔴 Disconnected', 'red');
    };

    setStatus('⏳ Waiting…', 'yellow');
    pollTimer = setInterval(poll, 1500);
}

async function poll() {
    try {
        const res  = await fetch(`${SIGNAL_URL}/signals?for_role=${MY_ROLE}&last_id=${lastSignalId}`);
        const data = await res.json();
        for (const sig of (data.signals || [])) {
            lastSignalId = sig.id;
            if (sig.type === 'offer' && !hasOffer) {
                hasOffer = true;
                setStatus('🟡 Farmer connected…', 'yellow');
                await pc.setRemoteDescription(JSON.parse(sig.payload));
                const answer = await pc.createAnswer();
                await pc.setLocalDescription(answer);
                await postSignal('answer', JSON.stringify(answer));
            } else if (sig.type === 'ice' && pc.remoteDescription) {
                try { await pc.addIceCandidate(JSON.parse(sig.payload)); } catch(_) {}
            } else if (sig.type === 'hangup') {
                clearInterval(pollTimer); clearInterval(timerInterval);
                setStatus('🔴 Farmer ended call', 'red');
                alert('The farmer has ended the call. Please complete your consultation notes.');
            }
        }
    } catch (_) {}
}

function startTimer() {
    const el = document.getElementById('call-timer');
    timerInterval = setInterval(() => {
        timerSec++;
        el.textContent = String(Math.floor(timerSec/60)).padStart(2,'0') + ':' + String(timerSec%60).padStart(2,'0');
    }, 1000);
}

function toggleMic() {
    micOn = !micOn;
    localStream.getAudioTracks().forEach(t => t.enabled = micOn);
    document.getElementById('mic-icon').classList.toggle('bg-white/20', micOn);
    document.getElementById('mic-icon').classList.toggle('bg-red-600', !micOn);
    document.querySelector('#btn-mic span:last-child').textContent = micOn ? 'Mute' : 'Unmute';
}

function toggleCam() {
    camOn = !camOn;
    localStream.getVideoTracks().forEach(t => t.enabled = camOn);
    document.getElementById('cam-icon').classList.toggle('bg-white/20', camOn);
    document.getElementById('cam-icon').classList.toggle('bg-red-600', !camOn);
    document.querySelector('#btn-cam span:last-child').textContent = camOn ? 'Camera' : 'Camera Off';
}

async function hangUp() {
    await postSignal('hangup', 'ended');
    clearInterval(pollTimer); clearInterval(timerInterval);
    cleanup();
    window.location.href = @json(route('professional.telemedicine.index'));
}

function confirmComplete() {
    clearInterval(pollTimer); clearInterval(timerInterval);
    postSignal('hangup', 'ended');
    cleanup();
    return true;
}

function cleanup() {
    if (pc) { pc.close(); pc = null; }
    if (localStream) localStream.getTracks().forEach(t => t.stop());
}

function setStatus(text, color) {
    const el = document.getElementById('conn-badge');
    el.textContent = text;
    el.className = 'px-3 py-1 rounded-full text-xs font-bold ' + ({
        yellow: 'bg-yellow-500 text-black',
        green:  'bg-green-500 text-black',
        red:    'bg-red-600 text-white',
    }[color] || '');
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

(function() {
    const pip = document.getElementById('local-pip');
    let startX, startY, startLeft, startBottom;
    pip.addEventListener('touchstart', e => {
        const t = e.touches[0];
        startX = t.clientX; startY = t.clientY;
        startLeft = pip.offsetLeft; startBottom = parseInt(pip.style.bottom) || 220;
    }, { passive: true });
    pip.addEventListener('touchmove', e => {
        e.preventDefault();
        const t = e.touches[0];
        pip.style.left   = (startLeft + (t.clientX - startX)) + 'px';
        pip.style.bottom = (startBottom - (t.clientY - startY)) + 'px';
        pip.style.right  = 'auto';
    }, { passive: false });
})();

window.addEventListener('beforeunload', () => { postSignal('hangup','ended'); cleanup(); });
start();
</script>
@endif

<style>
.safe-top    { padding-top: max(12px, env(safe-area-inset-top)); }
.safe-bottom { padding-bottom: max(16px, env(safe-area-inset-bottom)); }
#call-room   { -webkit-touch-callout: none; user-select: none; }
</style>
@endsection
