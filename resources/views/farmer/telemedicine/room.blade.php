@extends('layouts.farmer')
@section('title', 'Video Call')
@php
    $provider = \App\Models\Setting::get('telemedicine_provider', 'custom');
    $jitsiDomain = \App\Models\Setting::get('jitsi_domain', 'meet.jit.si');
    $vetName = $req->professional->name ?? 'Veterinarian';
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
                    {{ strtoupper(substr($vetName, 0, 1)) }}
                </div>
                <div>
                    <p class="text-white font-semibold text-sm leading-tight">{{ $vetName }}</p>
                    <p id="call-timer" class="text-green-400 text-xs">Connecting…</p>
                </div>
            </div>
            <a href="{{ route('farmer.telemedicine.show', $req->id) }}" class="text-gray-400 text-xs border border-gray-600 px-3 py-1 rounded-full">← Details</a>
        </div>

        {{-- Jitsi iframe --}}
        <iframe id="jitsi-frame"
                src="https://{{ $jitsiDomain }}/farmvax-{{ $req->room_code }}#config.startWithAudioMuted=false&config.startWithVideoMuted=false&config.prejoinPageEnabled=false&config.disableDeepLinking=true&userInfo.displayName={{ urlencode(auth()->user()->name) }}&interfaceConfig.SHOW_JITSI_WATERMARK=false"
                allow="camera; microphone; fullscreen; display-capture; autoplay"
                class="flex-1 w-full border-0"
                style="min-height:0;"></iframe>

        <div class="flex items-center justify-center gap-6 py-4 safe-bottom" style="background:rgba(0,0,0,0.6);">
            <button onclick="endJitsiCall()" class="flex flex-col items-center gap-1">
                <span class="w-14 h-14 rounded-full bg-red-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
                    </svg>
                </span>
                <span class="text-white text-xs">End</span>
            </button>
        </div>
    </div>

    @else
    {{-- ── CUSTOM WEBRTC MODE ──────────────────────────────────────────── --}}

    {{-- Remote video (fills screen) --}}
    <video id="remote-video" autoplay playsinline
           class="absolute inset-0 w-full h-full object-cover"
           style="display:none; z-index:1;"></video>

    {{-- Waiting / preview overlay --}}
    <div id="waiting-overlay" class="absolute inset-0 flex flex-col items-center justify-center" style="z-index:2; background:linear-gradient(180deg,#1a2d35 0%,#0b1a20 100%);">
        <div class="w-28 h-28 rounded-full bg-green-700/30 border-4 border-green-600 flex items-center justify-center text-5xl mb-4 animate-pulse">
            👨‍⚕️
        </div>
        <p class="text-white text-2xl font-semibold">{{ $vetName }}</p>
        <p class="text-green-400 text-sm mt-1">Calling…</p>

        {{-- Self preview --}}
        <div class="mt-6 rounded-2xl overflow-hidden border-2 border-white/20 shadow-2xl" style="width:200px;height:140px;background:#111;">
            <video id="local-preview-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
        </div>
        <p class="text-gray-400 text-xs mt-2">Your camera preview</p>
    </div>

    {{-- Local PiP (visible once connected) --}}
    <div id="local-pip"
         class="absolute rounded-2xl overflow-hidden border-2 border-white/30 shadow-2xl"
         style="width:100px;height:145px;bottom:120px;right:12px;display:none;cursor:move;z-index:5; touch-action:none;">
        <video id="local-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
        <div class="absolute inset-0 flex items-end p-1.5">
            <span class="text-xs text-white bg-black/50 px-2 py-0.5 rounded-full">You</span>
        </div>
    </div>

    {{-- Top bar (always on top) --}}
    <div class="absolute top-0 left-0 right-0 safe-top flex items-center justify-between px-4 py-3"
         style="z-index:10; background:linear-gradient(180deg,rgba(0,0,0,0.7) 0%,transparent 100%);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-base">
                {{ strtoupper(substr($vetName, 0, 1)) }}
            </div>
            <div>
                <p class="text-white font-semibold text-sm leading-tight">{{ $vetName }}</p>
                <p id="call-timer" class="text-green-400 text-xs">Calling…</p>
            </div>
        </div>
        <span id="conn-badge" class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-black">⏳</span>
    </div>

    {{-- WhatsApp-style bottom control bar --}}
    <div class="absolute bottom-0 left-0 right-0 safe-bottom pb-4 pt-6"
         style="z-index:10; background:linear-gradient(0deg,rgba(0,0,0,0.85) 0%,transparent 100%);">

        <div class="flex items-end justify-center gap-8 px-8">

            {{-- Mic --}}
            <button id="btn-mic" onclick="toggleMic()" class="flex flex-col items-center gap-1.5">
                <span id="mic-icon" class="w-14 h-14 rounded-full bg-white/20 backdrop-blur flex items-center justify-center transition-all">
                    <svg id="mic-svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3zm5 11a5 5 0 0 1-10 0H5a7 7 0 0 0 6 6.93V21H9v2h6v-2h-2v-2.07A7 7 0 0 0 19 12h-2z"/>
                    </svg>
                </span>
                <span class="text-white text-xs">Mute</span>
            </button>

            {{-- End Call (centre, bigger) --}}
            <button onclick="hangUp()" class="flex flex-col items-center gap-1.5">
                <span class="w-16 h-16 rounded-full bg-red-600 flex items-center justify-center shadow-2xl">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
                    </svg>
                </span>
                <span class="text-red-400 text-xs font-semibold">End Call</span>
            </button>

            {{-- Camera --}}
            <button id="btn-cam" onclick="toggleCam()" class="flex flex-col items-center gap-1.5">
                <span id="cam-icon" class="w-14 h-14 rounded-full bg-white/20 backdrop-blur flex items-center justify-center transition-all">
                    <svg id="cam-svg" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 10.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-3.5l4 4v-11l-4 4z"/>
                    </svg>
                </span>
                <span class="text-white text-xs">Camera</span>
            </button>
        </div>
    </div>
    @endif
</div>

@if($provider === 'jitsi')
<script>
let timerSec = 0;
const timerEl = document.getElementById('call-timer');
const timerId = setInterval(() => {
    timerSec++;
    const m = String(Math.floor(timerSec/60)).padStart(2,'0');
    const s = String(timerSec % 60).padStart(2,'0');
    timerEl.textContent = m + ':' + s;
}, 1000);

function endJitsiCall() {
    clearInterval(timerId);
    window.location.href = @json(route('farmer.telemedicine.show', $req->id));
}
</script>
@else
<script>
const ROOM_CODE  = @json($req->room_code);
const SIGNAL_URL = '/api/telemedicine/' + ROOM_CODE;
const MY_ROLE    = 'caller';
const CSRF_TOKEN = @json(csrf_token());

const iceConfig = { iceServers: [
    { urls: 'stun:stun.l.google.com:19302' },
    { urls: 'stun:stun1.l.google.com:19302' },
    { urls: 'stun:stun.cloudflare.com:3478' },
]};

let pc, localStream, micOn = true, camOn = true;
let lastSignalId = 0, hasAnswer = false;
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

    const offer = await pc.createOffer({ offerToReceiveAudio: true, offerToReceiveVideo: true });
    await pc.setLocalDescription(offer);
    await postSignal('offer', JSON.stringify(offer));

    setStatus('⏳ Calling…', 'yellow');
    pollTimer = setInterval(poll, 1500);
}

async function poll() {
    try {
        const res  = await fetch(`${SIGNAL_URL}/signals?for_role=${MY_ROLE}&last_id=${lastSignalId}`);
        const data = await res.json();
        for (const sig of (data.signals || [])) {
            lastSignalId = sig.id;
            if (sig.type === 'answer' && !hasAnswer) {
                hasAnswer = true;
                await pc.setRemoteDescription(JSON.parse(sig.payload));
                setStatus('🟡 Connecting…', 'yellow');
            } else if (sig.type === 'ice' && pc.remoteDescription) {
                try { await pc.addIceCandidate(JSON.parse(sig.payload)); } catch(_) {}
            } else if (sig.type === 'hangup') {
                clearInterval(pollTimer); clearInterval(timerInterval);
                setStatus('🔴 Call ended', 'red');
                alert('The veterinarian has ended the call.');
                window.location.href = @json(route('farmer.telemedicine.show', $req->id));
            }
        }
    } catch (_) {}
}

function startTimer() {
    const el = document.getElementById('call-timer');
    timerInterval = setInterval(() => {
        timerSec++;
        const m = String(Math.floor(timerSec/60)).padStart(2,'0');
        const s = String(timerSec % 60).padStart(2,'0');
        el.textContent = m + ':' + s;
    }, 1000);
}

function toggleMic() {
    micOn = !micOn;
    localStream.getAudioTracks().forEach(t => t.enabled = micOn);
    const icon = document.getElementById('mic-icon');
    const svg  = document.getElementById('mic-svg');
    icon.classList.toggle('bg-white/20', micOn);
    icon.classList.toggle('bg-red-600', !micOn);
    svg.innerHTML = micOn
        ? '<path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3zm5 11a5 5 0 0 1-10 0H5a7 7 0 0 0 6 6.93V21H9v2h6v-2h-2v-2.07A7 7 0 0 0 19 12h-2z"/>'
        : '<path d="M19 11a7 7 0 0 1-7 7m0 0a7 7 0 0 1-7-7m7 7v3m0 0H9m3 0h3M12 1a3 3 0 0 0-3 3v4m6-4a3 3 0 0 1 3 3v1M3 3l18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/>';
    document.querySelector('#btn-mic span:last-child').textContent = micOn ? 'Mute' : 'Unmute';
}

function toggleCam() {
    camOn = !camOn;
    localStream.getVideoTracks().forEach(t => t.enabled = camOn);
    const icon = document.getElementById('cam-icon');
    icon.classList.toggle('bg-white/20', camOn);
    icon.classList.toggle('bg-red-600', !camOn);
    document.querySelector('#btn-cam span:last-child').textContent = camOn ? 'Camera' : 'Camera Off';
}

async function hangUp() {
    if (!confirm('End this call?')) return;
    clearInterval(pollTimer); clearInterval(timerInterval);
    await postSignal('hangup', 'ended');
    cleanup();
    window.location.href = @json(route('farmer.telemedicine.show', $req->id));
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

// Touch-draggable PiP
(function() {
    const pip = document.getElementById('local-pip');
    let startX, startY, startLeft, startBottom;
    pip.addEventListener('touchstart', e => {
        const t = e.touches[0];
        startX = t.clientX; startY = t.clientY;
        startLeft = pip.offsetLeft;
        startBottom = parseInt(pip.style.bottom) || 120;
    }, { passive: true });
    pip.addEventListener('touchmove', e => {
        e.preventDefault();
        const t = e.touches[0];
        pip.style.left   = (startLeft + (t.clientX - startX)) + 'px';
        pip.style.bottom = (startBottom - (t.clientY - startY)) + 'px';
        pip.style.right  = 'auto';
    }, { passive: false });
})();

window.addEventListener('beforeunload', cleanup);
start();
</script>
@endif

<style>
.safe-top  { padding-top: max(12px, env(safe-area-inset-top)); }
.safe-bottom { padding-bottom: max(16px, env(safe-area-inset-bottom)); }
#call-room { -webkit-touch-callout: none; user-select: none; }
</style>
@endsection
