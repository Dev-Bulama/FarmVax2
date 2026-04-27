@php
    $role = auth()->user()->role ?? 'farmer';
    $layout = match($role) {
        'admin'                      => 'layouts.admin',
        'volunteer'                  => 'layouts.volunteer',
        'animal_health_professional' => 'layouts.professional',
        'individual'                 => 'layouts.individual',
        default                      => 'layouts.farmer',
    };
@endphp
@extends($layout)
@section('title', 'AI Animal Disease Scan')
@section('content')
<div class="p-6 max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('disease-detection.index') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-2">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Scan History
        </a>
        <h1 class="text-3xl font-bold" style="color:#11455b;">🔬 AI Animal Disease Scan</h1>
        <p class="text-gray-500 mt-1">Upload a photo or use your camera. Our AI will analyse it for signs of disease or illness.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <ul class="text-sm text-red-700 list-disc list-inside">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Mode Selector --}}
    <div class="flex bg-gray-100 rounded-xl p-1 mb-6 gap-1">
        <button type="button" id="tab-upload"
                class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg font-semibold text-sm transition-all tab-btn tab-active"
                onclick="switchTab('upload')">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Upload Image
        </button>
        <button type="button" id="tab-camera"
                class="flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-lg font-semibold text-sm transition-all tab-btn text-gray-600 hover:bg-white"
                onclick="switchTab('camera')">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Scan with Camera
        </button>
    </div>

    <form id="scan-form" action="{{ route('disease-detection.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Shared hidden file input populated by both modes --}}
        <input type="file" id="image-input" name="image" accept="image/jpeg,image/jpg,image/png" class="hidden" required>

        {{-- ======================================================
             PHASE 3 — UPLOAD IMAGE SECTION
             ====================================================== --}}
        <div id="section-upload" class="bg-white rounded-xl shadow p-6 space-y-4">
            <h2 class="text-lg font-bold" style="color:#11455b;">Upload Animal Photo</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Animal Photo <span class="text-red-500">*</span>
                </label>

                {{-- Drag-and-drop zone --}}
                <div id="drop-zone"
                     class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-green-400 transition"
                     onclick="document.getElementById('upload-file-input').click()">

                    {{-- Trigger input (not submitted; we copy to #image-input) --}}
                    <input type="file" id="upload-file-input"
                           accept="image/jpeg,image/jpg,image/png"
                           class="hidden"
                           onchange="handleUploadSelect(this)">

                    <div id="upload-placeholder">
                        <div class="text-5xl mb-3">📸</div>
                        <p class="font-semibold text-gray-700">Click to upload or drag & drop</p>
                        <p class="text-sm text-gray-400 mt-1">JPG / JPEG / PNG — max 10 MB</p>
                        <button type="button"
                                class="mt-3 px-4 py-2 text-white font-semibold rounded-lg text-sm"
                                style="background:#2fcb6e;"
                                onclick="event.stopPropagation(); document.getElementById('upload-file-input').click()">
                            Choose Photo
                        </button>
                    </div>

                    <img id="upload-preview" src="" alt="Preview"
                         class="hidden max-h-64 mx-auto rounded-xl object-contain">

                    <button type="button" id="upload-change-btn"
                            class="hidden mt-3 px-3 py-1 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50"
                            onclick="event.stopPropagation(); resetUpload()">
                        Change Photo
                    </button>
                </div>

                <p class="text-xs text-gray-400 mt-2">
                    Tips: Good lighting, clear focus, include the full body or the affected area.
                </p>
            </div>
        </div>

        {{-- ======================================================
             PHASE 4 — CAMERA SCAN SECTION
             ====================================================== --}}
        <div id="section-camera" class="hidden bg-white rounded-xl shadow p-6 space-y-4">
            <h2 class="text-lg font-bold" style="color:#11455b;">Scan with Camera</h2>

            {{-- State: camera not yet started --}}
            <div id="camera-prompt" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center">
                <div class="text-5xl mb-3">📷</div>
                <p class="font-semibold text-gray-700 mb-1">Use your device camera</p>
                <p class="text-sm text-gray-400 mb-4">Point camera at the animal and capture a clear frame</p>
                <button type="button" onclick="startCamera()"
                        class="px-5 py-2 text-white font-semibold rounded-lg" style="background:#2fcb6e;">
                    Open Camera
                </button>
            </div>

            {{-- State: live preview --}}
            <div id="camera-live" class="hidden space-y-3">
                <div class="relative rounded-xl overflow-hidden bg-black">
                    <video id="camera-video" autoplay playsinline muted
                           class="w-full max-h-72 object-cover rounded-xl"></video>
                    <button type="button" onclick="stopCamera()"
                            class="absolute top-3 right-3 px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded-lg hover:bg-opacity-75">
                        ✕ Stop
                    </button>
                </div>
                <p class="text-sm text-gray-500 text-center">Position the animal in frame, then capture</p>
                <button type="button" id="btn-capture-analyze" onclick="captureAndAnalyze()"
                        class="w-full py-3 text-white font-bold rounded-xl text-base flex items-center justify-center gap-2"
                        style="background:#2fcb6e;">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Capture & Analyze
                </button>
            </div>

            {{-- State: captured, waiting for form submit (after canvas.toBlob) --}}
            <div id="camera-captured" class="hidden space-y-3">
                <div class="relative">
                    <img id="captured-preview" src="" alt="Captured"
                         class="w-full max-h-72 object-contain rounded-xl border border-gray-200">
                </div>
                <p class="text-sm text-center font-medium text-green-700">✓ Frame captured — submitting for analysis…</p>
                <div class="flex items-center justify-center gap-2 text-gray-500 text-sm">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Uploading…
                </div>
            </div>

            {{-- Hidden canvas for frame capture --}}
            <canvas id="capture-canvas" class="hidden"></canvas>

            {{-- State: permission denied --}}
            <div id="camera-denied" class="hidden rounded-xl bg-red-50 border border-red-200 p-4 text-center">
                <p class="text-red-700 font-semibold mb-1">Camera access denied</p>
                <p class="text-sm text-red-500">Allow camera access in your browser settings, then refresh the page.</p>
            </div>
        </div>

        {{-- Common fields: animal details --}}
        <div class="bg-white rounded-xl shadow p-6 space-y-4">
            <h2 class="text-lg font-semibold" style="color:#11455b;">Animal Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Animal Type <span class="text-red-500">*</span></label>
                    <select name="animal_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400">
                        <option value="">-- Select Type --</option>
                        @foreach(['cattle'=>'🐄 Cattle','goat'=>'🐐 Goat','sheep'=>'🐑 Sheep','pig'=>'🐷 Pig','chicken'=>'🐔 Poultry / Chicken','duck'=>'🦆 Duck','rabbit'=>'🐇 Rabbit','horse'=>'🐴 Horse','donkey'=>'🫏 Donkey','other'=>'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ old('animal_type') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Link to Registered Animal (Optional)</label>
                    <select name="livestock_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                        <option value="">-- No specific animal --</option>
                        @foreach($livestock as $animal)
                            <option value="{{ $animal->id }}" {{ old('livestock_id') == $animal->id ? 'selected' : '' }}>
                                {{ $animal->tag_number ?? $animal->name ?? '#'.$animal->id }} — {{ ucfirst($animal->livestock_type ?? $animal->type ?? '') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Describe what you've observed (Optional)</label>
                    <textarea name="symptoms_reported" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2"
                              placeholder="e.g. Not eating, limping, unusual discharge, swollen joints, weight loss…">{{ old('symptoms_reported') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-800">
            <strong>⚠️ Important:</strong> This AI scan is a screening tool, not a replacement for professional veterinary care.
            Always consult a licensed veterinarian for diagnosis and treatment.
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between bg-white rounded-xl shadow p-5">
            <a href="{{ route('disease-detection.index') }}"
               class="px-5 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" id="submit-btn"
                    class="px-8 py-3 text-white font-bold rounded-xl shadow flex items-center gap-2"
                    style="background:#2fcb6e;">
                <span id="btn-text">🔬 Analyze / Detect Disease</span>
                <svg id="btn-spinner" class="hidden h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

{{-- ======================================================
     PHASE 5 — FULL-PAGE ANALYZING OVERLAY
     Shown after form submit while the AI processes server-side.
     ====================================================== --}}
<div id="analyzing-overlay"
     class="hidden fixed inset-0 z-50 flex flex-col items-center justify-center"
     style="background:rgba(17,69,91,0.93);">
    <div class="text-center px-6 max-w-sm">
        <div class="relative w-28 h-28 mx-auto mb-8">
            <div class="absolute inset-0 rounded-full border-4 border-white/20"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-white border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
            <div class="absolute inset-3 rounded-full border-4 border-t-transparent border-r-white/50 border-b-transparent border-l-transparent animate-spin" style="animation-duration:1.6s;"></div>
            <div class="absolute inset-0 flex items-center justify-center text-4xl">🔬</div>
        </div>
        <h2 class="text-2xl font-black text-white mb-2">Analyzing...</h2>
        <p class="text-white/80 text-base mb-8">Please wait while our AI examines the photo</p>
        <div class="space-y-3 text-sm" id="analysis-steps">
            <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-2.5" id="step-1">
                <svg class="h-4 w-4 animate-spin text-white/70 shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span class="text-white/80">Processing image</span>
            </div>
            <div class="flex items-center gap-3 bg-white/5 rounded-xl px-4 py-2.5 opacity-50" id="step-2">
                <div class="h-4 w-4 rounded-full border-2 border-white/40 shrink-0"></div>
                <span class="text-white/60">Running AI analysis</span>
            </div>
            <div class="flex items-center gap-3 bg-white/5 rounded-xl px-4 py-2.5 opacity-50" id="step-3">
                <div class="h-4 w-4 rounded-full border-2 border-white/40 shrink-0"></div>
                <span class="text-white/60">Generating report</span>
            </div>
        </div>
    </div>
</div>

<style>
.tab-active {
    background: white;
    color: #11455b;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
}
</style>

<script>
let activeTab = 'upload';
let cameraStream = null;

/* ===== TAB SWITCHING ===== */
function switchTab(tab) {
    activeTab = tab;
    document.getElementById('tab-upload').classList.toggle('tab-active', tab === 'upload');
    document.getElementById('tab-upload').classList.toggle('text-gray-600', tab !== 'upload');
    document.getElementById('tab-camera').classList.toggle('tab-active', tab === 'camera');
    document.getElementById('tab-camera').classList.toggle('text-gray-600', tab !== 'camera');
    document.getElementById('section-upload').classList.toggle('hidden', tab !== 'upload');
    document.getElementById('section-camera').classList.toggle('hidden', tab !== 'camera');
    if (tab !== 'camera') stopCamera();
}

/* ===== PHASE 3 — UPLOAD ===== */
function handleUploadSelect(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
        alert('Only JPG, JPEG, and PNG images are accepted.');
        if (input.value !== undefined) input.value = '';
        return;
    }
    if (file.size > 10 * 1024 * 1024) {
        alert('Image must be smaller than 10 MB.');
        if (input.value !== undefined) input.value = '';
        return;
    }
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('image-input').files = dt.files;

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('upload-preview').src = e.target.result;
        document.getElementById('upload-preview').classList.remove('hidden');
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('upload-change-btn').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function resetUpload() {
    document.getElementById('upload-file-input').value = '';
    document.getElementById('image-input').value = '';
    document.getElementById('upload-preview').classList.add('hidden');
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('upload-change-btn').classList.add('hidden');
}

const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('border-green-400'); });
dropZone.addEventListener('dragleave', ()  => dropZone.classList.remove('border-green-400'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-green-400');
    if (e.dataTransfer.files[0]) handleUploadSelect({ files: e.dataTransfer.files });
});

/* ===== PHASE 4 — CAMERA ===== */
async function startCamera() {
    document.getElementById('camera-prompt').classList.add('hidden');
    document.getElementById('camera-denied').classList.add('hidden');
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
        });
        document.getElementById('camera-video').srcObject = cameraStream;
        document.getElementById('camera-live').classList.remove('hidden');
    } catch (err) {
        document.getElementById('camera-denied').classList.remove('hidden');
        document.getElementById('camera-prompt').classList.remove('hidden');
    }
}

function stopCamera() {
    if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }
    document.getElementById('camera-live').classList.add('hidden');
    document.getElementById('camera-captured').classList.add('hidden');
    document.getElementById('camera-prompt').classList.remove('hidden');
}

function captureAndAnalyze() {
    const video  = document.getElementById('camera-video');
    const canvas = document.getElementById('capture-canvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    document.getElementById('captured-preview').src = canvas.toDataURL('image/jpeg', 0.92);
    document.getElementById('camera-live').classList.add('hidden');
    document.getElementById('camera-captured').classList.remove('hidden');

    if (cameraStream) { cameraStream.getTracks().forEach(t => t.stop()); cameraStream = null; }

    canvas.toBlob(blob => {
        const dt = new DataTransfer();
        dt.items.add(new File([blob], 'camera-capture.jpg', { type: 'image/jpeg' }));
        document.getElementById('image-input').files = dt.files;
        showAnalyzingOverlay();
        document.getElementById('scan-form').submit();
    }, 'image/jpeg', 0.92);
}

/* ===== PHASE 5 — ANALYZING OVERLAY ===== */
function showAnalyzingOverlay() {
    document.getElementById('analyzing-overlay').classList.remove('hidden');
    document.getElementById('submit-btn').disabled = true;
    // Animate steps with delays
    setTimeout(() => {
        const s2 = document.getElementById('step-2');
        s2.classList.remove('opacity-50');
        s2.querySelector('div').outerHTML =
            '<svg class="h-4 w-4 animate-spin text-white/70 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>';
        s2.querySelector('span').classList.replace('text-white/60', 'text-white/80');
        s2.classList.replace('bg-white/5', 'bg-white/10');
    }, 1200);
    setTimeout(() => {
        const s3 = document.getElementById('step-3');
        s3.classList.remove('opacity-50');
        s3.querySelector('div').outerHTML =
            '<svg class="h-4 w-4 animate-spin text-white/70 shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>';
        s3.querySelector('span').classList.replace('text-white/60', 'text-white/80');
        s3.classList.replace('bg-white/5', 'bg-white/10');
    }, 2800);
}

/* ===== FORM SUBMIT ===== */
document.getElementById('scan-form').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('image-input');
    if (!fileInput.files || fileInput.files.length === 0) {
        e.preventDefault();
        alert(activeTab === 'camera' ? 'Please capture a photo first.' : 'Please select an image to upload.');
        return;
    }
    if (activeTab === 'upload') {
        showAnalyzingOverlay();
        document.getElementById('btn-text').textContent = 'Analysing…';
        document.getElementById('btn-spinner').classList.remove('hidden');
    }
});
</script>
@endsection
