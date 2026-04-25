@extends('layouts.farmer')
@section('title', 'Video Call')
@php
    $provider    = \App\Models\Setting::get('telemedicine_provider', 'custom');
    $jitsiDomain = \App\Models\Setting::get('jitsi_domain', 'meet.jit.si');
    $vetName     = $req->professional->name ?? 'Veterinarian';
@endphp
@section('content')

<div id="call-room" class="fixed inset-0 flex flex-col select-none" style="z-index:9999;background:#0a1520;">

@if($provider === 'jitsi')
{{-- ── JITSI ──────────────────────────────────────────────────────────────── --}}
<div class="flex flex-col h-full">
    <div class="flex items-center justify-between px-4 py-3 safe-top" style="background:rgba(0,0,0,0.55);backdrop-filter:blur(8px);">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center font-bold text-white">{{ strtoupper(substr($vetName,0,1)) }}</div>
            <div>
                <p class="text-white font-semibold text-sm">{{ $vetName }}</p>
                <p id="call-timer" class="text-green-400 text-xs tabular-nums">00:00</p>
            </div>
        </div>
        <a href="{{ route('farmer.telemedicine.show', $req->id) }}" class="text-gray-400 text-xs border border-gray-600 px-3 py-1.5 rounded-full">← Details</a>
    </div>
    <iframe src="https://{{ $jitsiDomain }}/farmvax-{{ $req->room_code }}#config.requireDisplayName=false&config.prejoinPageEnabled=false&config.startWithoutInteraction=true&config.enableWelcomePage=false&config.disableDeepLinking=true&config.startAudioOnly=false&userInfo.displayName={{ urlencode(auth()->user()->name) }}&interfaceConfig.SHOW_JITSI_WATERMARK=false&interfaceConfig.MOBILE_APP_PROMO=false&interfaceConfig.SHOW_WATERMARK_FOR_GUESTS=false"
            allow="camera;microphone;fullscreen;display-capture;autoplay"
            class="flex-1 w-full border-0" style="min-height:0;"></iframe>
    <div class="flex justify-center py-4 safe-bottom" style="background:rgba(0,0,0,0.55);backdrop-filter:blur(8px);">
        <button onclick="endJitsiCall()" class="flex flex-col items-center gap-1">
            <span class="w-16 h-16 rounded-full bg-red-600 flex items-center justify-center shadow-xl">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
            </span>
            <span class="text-red-400 text-xs font-semibold">End</span>
        </button>
    </div>
</div>

@else
{{-- ── CUSTOM WEBRTC ──────────────────────────────────────────────────────── --}}

{{-- Remote video (full-screen) --}}
<video id="remote-video" autoplay playsinline
       class="absolute inset-0 w-full h-full object-cover"
       style="display:none;z-index:1;"></video>

{{-- Waiting overlay --}}
<div id="waiting-overlay" class="absolute inset-0 flex flex-col items-center justify-center"
     style="z-index:2;background:radial-gradient(ellipse at center,#1a3040 0%,#0a1520 100%);">
    <div class="w-28 h-28 rounded-full border-4 border-green-500 flex items-center justify-center text-5xl mb-5 shadow-2xl" style="background:rgba(47,203,110,0.15);">👨‍⚕️</div>
    <p class="text-white text-2xl font-bold">{{ $vetName }}</p>
    <p class="text-green-400 text-sm mt-1 animate-pulse">Calling…</p>
    <div class="mt-6 rounded-2xl overflow-hidden border-2 border-white/20 shadow-2xl" style="width:200px;height:148px;background:#0d1e2a;">
        <video id="local-preview-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
    </div>
    <p class="text-gray-500 text-xs mt-2">Your preview</p>
    <p id="setup-status" class="text-yellow-400 text-xs mt-3"></p>
</div>

{{-- Local PiP --}}
<div id="local-pip" class="absolute rounded-2xl overflow-hidden border-2 border-white/25 shadow-2xl"
     style="width:100px;height:148px;bottom:130px;right:14px;display:none;z-index:5;touch-action:none;cursor:move;">
    <video id="local-video" autoplay muted playsinline class="w-full h-full object-cover"></video>
    <div class="absolute bottom-1.5 left-2"><span class="text-xs text-white bg-black/50 px-1.5 py-0.5 rounded-full">You</span></div>
</div>

{{-- Top bar --}}
<div class="absolute top-0 left-0 right-0 safe-top flex items-center justify-between px-4 py-3"
     style="z-index:10;background:linear-gradient(180deg,rgba(0,0,0,0.75) 0%,transparent 100%);">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center font-bold text-white">{{ strtoupper(substr($vetName,0,1)) }}</div>
        <div>
            <p class="text-white font-semibold text-sm leading-tight">{{ $vetName }}</p>
            <p id="call-timer" class="text-green-400 text-xs tabular-nums">Calling…</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <span id="conn-badge" class="px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-500 text-black">⏳ Connecting</span>
        <span id="quality-badge" class="hidden px-2.5 py-1 rounded-full text-xs font-bold bg-green-600 text-white">HD</span>
    </div>
</div>

{{-- Bottom controls --}}
<div class="absolute bottom-0 left-0 right-0 safe-bottom pb-5 pt-8"
     style="z-index:10;background:linear-gradient(0deg,rgba(0,0,0,0.85) 0%,transparent 100%);">
    <div class="flex items-end justify-center gap-8">
        <button id="btn-mic" onclick="toggleMic()" class="flex flex-col items-center gap-1.5">
            <span id="mic-icon" class="w-14 h-14 rounded-full flex items-center justify-center transition-all" style="background:rgba(255,255,255,0.18);backdrop-filter:blur(6px);">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3zm5 11a5 5 0 0 1-10 0H5a7 7 0 0 0 6 6.93V21H9v2h6v-2h-2v-2.07A7 7 0 0 0 19 12h-2z"/></svg>
            </span>
            <span id="mic-label" class="text-white text-xs">Mute</span>
        </button>
        <button onclick="hangUp()" class="flex flex-col items-center gap-1.5">
            <span class="w-16 h-16 rounded-full bg-red-600 flex items-center justify-center shadow-2xl">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
            </span>
            <span class="text-red-400 text-xs font-semibold">End Call</span>
        </button>
        <button id="btn-cam" onclick="toggleCam()" class="flex flex-col items-center gap-1.5">
            <span id="cam-icon" class="w-14 h-14 rounded-full flex items-center justify-center transition-all" style="background:rgba(255,255,255,0.18);backdrop-filter:blur(6px);">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-3.5l4 4v-11l-4 4z"/></svg>
            </span>
            <span id="cam-label" class="text-white text-xs">Camera</span>
        </button>
    </div>
</div>

@endif
</div>

@if($provider === 'jitsi')
<script>
let timerSec=0;
const timerEl=document.getElementById('call-timer');
const tid=setInterval(()=>{timerSec++;timerEl.textContent=fmt(Math.floor(timerSec/60))+':'+fmt(timerSec%60);},1000);
function fmt(n){return String(n).padStart(2,'0');}
function endJitsiCall(){clearInterval(tid);window.location.href=@json(route('farmer.telemedicine.show',$req->id));}
</script>
@else
<script>
const ROOM_CODE  = @json($req->room_code);
const JOINED_AT  = @json($joinedAt ?? now()->timestamp);
const SIGNAL_URL = '/api/telemedicine/' + ROOM_CODE;
const MY_ROLE    = 'caller';
const CSRF       = @json(csrf_token());
const BACK_URL   = @json(route('farmer.telemedicine.show', $req->id));

const ICE_CFG = { iceServers:[
    {urls:'stun:stun.l.google.com:19302'},
    {urls:'stun:stun1.l.google.com:19302'},
    {urls:'stun:stun2.l.google.com:19302'},
    {urls:'stun:stun.cloudflare.com:3478'},
]};

// HD video constraints
const VIDEO_CONSTRAINTS = {
    width:{ideal:1280,min:640},
    height:{ideal:720,min:480},
    frameRate:{ideal:30},
    facingMode:'user',
};

let pc, localStream;
let micOn=true, camOn=true;
let lastId=0, hasAnswer=false;
let pollTimer=null, timerInterval=null, timerSec=0;
let callConnected=false;

async function start() {
    setSetupStatus('Requesting camera & microphone…');
    try {
        localStream = await navigator.mediaDevices.getUserMedia({video:VIDEO_CONSTRAINTS, audio:{echoCancellation:true,noiseSuppression:true,autoGainControl:true}});
        document.getElementById('local-preview-video').srcObject = localStream;
        setSetupStatus('');
    } catch(err) {
        setSetupStatus('⚠ Camera/mic denied — ' + err.message);
        return;
    }

    pc = new RTCPeerConnection(ICE_CFG);
    localStream.getTracks().forEach(t => pc.addTrack(t, localStream));

    pc.ontrack = e => onRemoteTrack(e.streams[0]);

    pc.onicecandidate = e => { if(e.candidate) postSig('ice', JSON.stringify(e.candidate)); };

    pc.onconnectionstatechange = () => {
        const s = pc.connectionState;
        if(s === 'connected')       { setConn('🟢 Connected','green'); showQuality(); }
        else if(s === 'disconnected'){ setConn('🔴 Lost connection…','red'); }
        else if(s === 'failed')      { setConn('🔴 Connection failed','red'); tryReconnect(); }
    };

    pc.oniceconnectionstatechange = () => {
        if(pc.iceConnectionState === 'checking') setConn('🟡 Connecting…','yellow');
    };

    setSetupStatus('Creating call offer…');
    const offer = await pc.createOffer({offerToReceiveAudio:true, offerToReceiveVideo:true});
    await pc.setLocalDescription(offer);
    await postSig('offer', JSON.stringify(offer));
    setSetupStatus('');
    setConn('⏳ Waiting for vet…','yellow');

    pollTimer = setInterval(poll, 1500);
}

function onRemoteTrack(stream) {
    const vid = document.getElementById('remote-video');
    vid.srcObject = stream;
    vid.style.display = 'block';
    document.getElementById('waiting-overlay').style.display = 'none';
    document.getElementById('local-pip').style.display = 'block';
    document.getElementById('local-video').srcObject = localStream;
    callConnected = true;
    startTimer();
}

async function poll() {
    try {
        const res  = await fetch(`${SIGNAL_URL}/signals?for_role=${MY_ROLE}&last_id=${lastId}&joined_at=${JOINED_AT}`);
        if(!res.ok) return;
        const data = await res.json();
        for(const sig of (data.signals||[])) {
            lastId = sig.id;
            if(sig.type==='answer' && !hasAnswer) {
                hasAnswer=true;
                await pc.setRemoteDescription(JSON.parse(sig.payload));
                setConn('🟡 Establishing…','yellow');
            } else if(sig.type==='ice' && pc.remoteDescription) {
                try{ await pc.addIceCandidate(JSON.parse(sig.payload)); }catch(_){}
            } else if(sig.type==='hangup') {
                clearInterval(pollTimer);
                setConn('🔴 Call ended','red');
                // Only show alert if it's a genuine hangup (not a stale one from before we joined)
                showHangupAlert();
            }
        }
    } catch(_) {}
}

function showHangupAlert() {
    clearInterval(timerInterval);
    const div = document.createElement('div');
    div.className = 'absolute inset-0 flex flex-col items-center justify-center';
    div.style.cssText = 'z-index:20;background:rgba(0,0,0,0.85);';
    div.innerHTML = `
        <div class="text-5xl mb-4">📵</div>
        <p class="text-white text-xl font-bold mb-2">Call Ended</p>
        <p class="text-gray-400 text-sm mb-6">The veterinarian has ended the session.</p>
        <a href="${BACK_URL}" class="px-6 py-3 bg-green-600 text-white font-bold rounded-xl">Return to Details</a>
    `;
    document.getElementById('call-room').appendChild(div);
}

let reconnecting = false;
async function tryReconnect() {
    if(reconnecting) return;
    reconnecting = true;
    setConn('🟡 Reconnecting…','yellow');
    // simple restart ICE
    if(pc && pc.restartIce) {
        try {
            const offer = await pc.createOffer({iceRestart:true});
            await pc.setLocalDescription(offer);
            await postSig('offer', JSON.stringify(offer));
            hasAnswer = false;
        } catch(_) {}
    }
    setTimeout(()=>{reconnecting=false;},5000);
}

function startTimer() {
    const el = document.getElementById('call-timer');
    timerInterval = setInterval(()=>{
        timerSec++;
        el.textContent = fmt(Math.floor(timerSec/60))+':'+fmt(timerSec%60);
    },1000);
}

function showQuality() {
    const badge = document.getElementById('quality-badge');
    if(!badge) return;
    // Check video resolution
    const vTrack = localStream && localStream.getVideoTracks()[0];
    const settings = vTrack ? vTrack.getSettings() : {};
    const w = settings.width||0;
    badge.textContent = w >= 1280 ? 'HD' : (w >= 640 ? 'SD' : 'Low');
    badge.classList.remove('hidden');
}

function toggleMic() {
    micOn = !micOn;
    localStream.getAudioTracks().forEach(t=>t.enabled=micOn);
    const icon = document.getElementById('mic-icon');
    icon.style.background = micOn ? 'rgba(255,255,255,0.18)' : 'rgba(239,68,68,0.8)';
    document.getElementById('mic-label').textContent = micOn ? 'Mute' : 'Unmute';
}

function toggleCam() {
    camOn = !camOn;
    localStream.getVideoTracks().forEach(t=>t.enabled=camOn);
    const icon = document.getElementById('cam-icon');
    icon.style.background = camOn ? 'rgba(255,255,255,0.18)' : 'rgba(239,68,68,0.8)';
    document.getElementById('cam-label').textContent = camOn ? 'Camera' : 'Cam Off';
}

async function hangUp() {
    if(!confirm('End this call?')) return;
    await doHangup();
    window.location.href = BACK_URL;
}

async function doHangup() {
    clearInterval(pollTimer); clearInterval(timerInterval);
    await postSig('hangup','ended');
    cleanup();
}

function cleanup() {
    if(pc){pc.close();pc=null;}
    if(localStream) localStream.getTracks().forEach(t=>t.stop());
}

function setConn(text, color) {
    const el=document.getElementById('conn-badge');
    el.textContent=text;
    el.className='px-2.5 py-1 rounded-full text-xs font-bold '+({yellow:'bg-yellow-500 text-black',green:'bg-green-500 text-black',red:'bg-red-600 text-white'}[color]||'');
}

function setSetupStatus(msg) {
    const el=document.getElementById('setup-status');
    if(el) el.textContent=msg;
}

function fmt(n){return String(n).padStart(2,'0');}

// Touch draggable PiP
(function(){
    const pip=document.getElementById('local-pip');
    let ox=0,oy=0,sx=0,sy=0;
    pip.addEventListener('touchstart',e=>{const t=e.touches[0];ox=t.clientX;oy=t.clientY;sx=pip.offsetLeft;sy=parseInt(pip.style.bottom)||130;},{passive:true});
    pip.addEventListener('touchmove',e=>{e.preventDefault();const t=e.touches[0];pip.style.left=(sx+(t.clientX-ox))+'px';pip.style.bottom=(sy-(t.clientY-oy))+'px';pip.style.right='auto';},{passive:false});
})();

window.addEventListener('beforeunload', doHangup);
start();
</script>
@endif

<style>
.safe-top    { padding-top:    max(12px, env(safe-area-inset-top)); }
.safe-bottom { padding-bottom: max(20px, env(safe-area-inset-bottom)); }
</style>
@endsection
