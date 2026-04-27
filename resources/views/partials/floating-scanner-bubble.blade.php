{{-- ================================================================
     FLOATING DISEASE SCANNER BUBBLE + SCANNER MODAL / BOTTOM SHEET
     ================================================================ --}}

{{-- ── Floating Bubble ─────────────────────────────────────────── --}}
<div id="scanner-bubble-container"
     class="fixed bottom-20 right-4 md:bottom-24 md:right-6 z-40 flex flex-col items-end gap-1.5 select-none">

    <div class="scanner-tooltip-wrap relative flex items-center justify-end">

        {{-- Desktop hover tooltip --}}
        <span id="scanner-tooltip"
              class="hidden md:block absolute right-[4.5rem] whitespace-nowrap text-xs font-semibold
                     text-white bg-[#11455B]/90 backdrop-blur-sm px-3 py-1.5 rounded-full shadow-md
                     opacity-0 translate-x-2 transition-all duration-200 pointer-events-none">
            Detect Disease
        </span>

        {{-- Bubble button (opens modal instead of navigating) --}}
        <button type="button"
                id="scanner-bubble"
                onclick="openScannerModal()"
                aria-label="Scan for animal disease"
                class="relative w-14 h-14 md:w-16 md:h-16 flex items-center justify-center rounded-full
                       bg-gradient-to-br from-[#2FCB6E] to-[#11455B]
                       shadow-lg hover:scale-110 active:scale-95
                       transition-all duration-300 touch-manipulation scanner-glow">

            <span class="absolute inset-0 rounded-full bg-[#2FCB6E]/25 animate-scanner-ping pointer-events-none"></span>

            <svg class="relative w-7 h-7 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
        </button>
    </div>

    {{-- Mobile always-visible label --}}
    <span class="md:hidden text-[10px] font-bold tracking-wide text-[#11455B] bg-white/90
                 backdrop-blur-sm px-2 py-0.5 rounded-full shadow-sm text-center">
        Scan
    </span>
</div>

{{-- ── Modal Backdrop ───────────────────────────────────────────── --}}
<div id="scanner-backdrop"
     class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[60]"
     onclick="closeScannerModal()">
</div>

{{-- ── Scanner Modal / Bottom Sheet ────────────────────────────── --}}
<div id="scanner-modal"
     role="dialog"
     aria-modal="true"
     aria-labelledby="scanner-modal-title"
     class="hidden fixed z-[70] bg-white overflow-hidden">

    {{-- Drag handle (mobile only) --}}
    <div class="md:hidden flex justify-center pt-3 pb-1">
        <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
    </div>

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#2FCB6E] to-[#11455B]
                        flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
            </div>
            <div>
                <h2 id="scanner-modal-title" class="text-lg font-bold text-[#11455B] leading-tight">
                    Animal Disease Scanner
                </h2>
                <p class="text-xs text-gray-500">AI-powered livestock health analysis</p>
            </div>
        </div>

        <button type="button"
                onclick="closeScannerModal()"
                aria-label="Close scanner"
                class="w-9 h-9 flex items-center justify-center rounded-full text-gray-500
                       hover:bg-gray-100 hover:text-gray-700 transition-colors duration-150 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ── Tab Switcher ─────────────────────────────────────────── --}}
    <div class="flex gap-2 px-5 py-3 border-b border-gray-100 bg-gray-50/50">
        <button type="button"
                id="tab-upload"
                onclick="switchScannerTab('upload')"
                class="scanner-tab flex-1 flex items-center justify-center gap-2 px-4 py-2.5
                       rounded-xl text-sm font-semibold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            Upload Image
        </button>

        <button type="button"
                id="tab-camera"
                onclick="switchScannerTab('camera')"
                class="scanner-tab flex-1 flex items-center justify-center gap-2 px-4 py-2.5
                       rounded-xl text-sm font-semibold transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
            Scan with Camera
        </button>
    </div>

    {{-- ── Tab Content ──────────────────────────────────────────── --}}
    <div class="scanner-modal-body overflow-y-auto px-5 py-5">

        {{-- Upload Tab --}}
        <div id="scanner-content-upload">

            {{-- Drop zone --}}
            <div id="scanner-drop-zone"
                 onclick="triggerScannerFileInput()"
                 ondragover="scannerDragOver(event)"
                 ondragleave="scannerDragLeave(event)"
                 ondrop="scannerDrop(event)"
                 class="flex flex-col items-center justify-center gap-3 min-h-[200px] border-2
                        border-dashed border-gray-300 rounded-2xl cursor-pointer
                        bg-gray-50 hover:bg-[#2FCB6E]/5 hover:border-[#2FCB6E]
                        transition-all duration-200 p-6">

                <div class="w-16 h-16 rounded-full bg-[#2FCB6E]/10 flex items-center justify-center">
                    <svg class="w-8 h-8 text-[#2FCB6E]" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                </div>

                <div class="text-center">
                    <p class="text-sm font-semibold text-gray-700">Drop your image here</p>
                    <p class="text-xs text-gray-400 mt-1">or <span class="text-[#2FCB6E] font-semibold underline underline-offset-2">browse files</span></p>
                    <p class="text-xs text-gray-400 mt-2">Supports JPG, PNG, WEBP · Max 10 MB</p>
                </div>

                <input type="file"
                       id="scanner-file-input"
                       accept="image/*"
                       class="hidden"
                       onchange="scannerFileSelected(event)">
            </div>

            {{-- Upload preview --}}
            <div id="scanner-upload-preview" class="hidden">
                <div class="relative rounded-2xl overflow-hidden bg-gray-100 aspect-video">
                    <img id="scanner-preview-img"
                         class="w-full h-full object-contain"
                         alt="Selected image preview">
                    <button type="button"
                            onclick="clearScannerUpload()"
                            class="absolute top-2 right-2 w-8 h-8 flex items-center justify-center
                                   bg-black/50 hover:bg-black/70 text-white rounded-full transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <p id="scanner-file-name" class="text-xs text-gray-500 mt-2 text-center truncate"></p>
            </div>
        </div>

        {{-- Camera Tab --}}
        <div id="scanner-content-camera" class="hidden">

            {{-- Camera initialising state --}}
            <div id="scanner-camera-loading"
                 class="flex flex-col items-center justify-center gap-3 min-h-[200px]">
                <div class="w-12 h-12 border-4 border-[#2FCB6E]/30 border-t-[#2FCB6E] rounded-full animate-spin"></div>
                <p class="text-sm text-gray-500">Starting camera…</p>
            </div>

            {{-- Camera permission error state --}}
            <div id="scanner-camera-error"
                 class="hidden flex flex-col items-center justify-center gap-3 min-h-[200px] text-center">
                <div class="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Camera access denied</p>
                    <p class="text-xs text-gray-500 mt-1">Allow camera access in your browser settings and try again.</p>
                </div>
                <button type="button"
                        onclick="startScannerCamera()"
                        class="text-sm font-semibold text-[#2FCB6E] hover:underline">
                    Try again
                </button>
            </div>

            {{-- Live camera feed --}}
            <div id="scanner-camera-live" class="hidden">
                <div class="relative rounded-2xl overflow-hidden bg-black aspect-video">
                    <video id="scanner-video"
                           autoplay
                           playsinline
                           muted
                           class="w-full h-full object-cover"></video>

                    {{-- Scan guide overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="w-3/4 aspect-square rounded-2xl border-2 border-[#2FCB6E]/60"
                             style="box-shadow: 0 0 0 9999px rgba(0,0,0,0.35)"></div>
                    </div>
                </div>

                {{-- Capture button --}}
                <div class="flex justify-center mt-4">
                    <button type="button"
                            id="scanner-capture-btn"
                            onclick="scannerCapturePhoto()"
                            class="w-16 h-16 rounded-full bg-white border-4 border-[#2FCB6E]
                                   shadow-lg hover:scale-105 active:scale-95 transition-transform duration-150
                                   flex items-center justify-center">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#2FCB6E] to-[#11455B]"></div>
                    </button>
                </div>
                <p class="text-xs text-center text-gray-500 mt-2">Tap to capture</p>
            </div>

            {{-- Captured photo preview --}}
            <div id="scanner-captured-preview" class="hidden">
                <div class="relative rounded-2xl overflow-hidden bg-gray-100 aspect-video">
                    <img id="scanner-captured-img"
                         class="w-full h-full object-contain"
                         alt="Captured photo">
                </div>
                <div class="flex justify-center mt-3">
                    <button type="button"
                            onclick="scannerRetakePhoto()"
                            class="flex items-center gap-1.5 text-sm font-semibold text-gray-500
                                   hover:text-[#11455B] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"/>
                            <path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>
                        </svg>
                        Retake
                    </button>
                </div>
            </div>

            {{-- Hidden canvas for capture --}}
            <canvas id="scanner-canvas" class="hidden"></canvas>
        </div>
    </div>

    {{-- ── Footer Action ────────────────────────────────────────── --}}
    <div class="px-5 py-4 border-t border-gray-100 bg-white">
        <button type="button"
                id="scanner-analyze-btn"
                onclick="runScannerAnalysis()"
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl
                       bg-gradient-to-r from-[#2FCB6E] to-[#11455B]
                       text-white text-sm font-bold tracking-wide
                       shadow-md hover:opacity-90 active:scale-[0.98]
                       transition-all duration-200
                       opacity-50 pointer-events-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
            <span id="scanner-analyze-label">Analyze Disease</span>
        </button>
        <p id="scanner-footer-hint" class="text-[11px] text-center text-gray-400 mt-2">
            Select or capture an image of the affected animal to continue
        </p>
    </div>
</div>

{{-- ── Styles ───────────────────────────────────────────────────── --}}
<style>
    /* Bubble glow pulse */
    @keyframes scannerGlow {
        0%, 100% { box-shadow: 0 4px 20px rgba(47,203,110,0.40); }
        50%       { box-shadow: 0 4px 32px rgba(47,203,110,0.70); }
    }
    .scanner-glow { animation: scannerGlow 2.4s ease-in-out infinite; }

    /* Bubble ring ping */
    @keyframes scannerPing {
        0%   { transform: scale(1);   opacity: 0.6; }
        70%  { transform: scale(1.5); opacity: 0;   }
        100% { transform: scale(1.5); opacity: 0;   }
    }
    .animate-scanner-ping { animation: scannerPing 2.4s cubic-bezier(0,0,0.2,1) infinite; }

    /* Desktop hover tooltip */
    .scanner-tooltip-wrap:hover #scanner-tooltip {
        opacity: 1;
        transform: translateX(0);
    }
    #scanner-bubble { -webkit-tap-highlight-color: transparent; }

    /* ── Backdrop ── */
    #scanner-backdrop {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    #scanner-backdrop.scanner-open { opacity: 1; }

    /* ── Modal: mobile bottom-sheet ── */
    #scanner-modal {
        bottom: 0; left: 0; right: 0;
        border-radius: 1.5rem 1.5rem 0 0;
        max-height: 92vh;
        transform: translateY(100%);
        transition: transform 0.38s cubic-bezier(0.32, 0.72, 0, 1);
    }
    #scanner-modal.scanner-open {
        transform: translateY(0);
    }

    /* ── Modal: desktop centered card ── */
    @media (min-width: 768px) {
        #scanner-modal {
            top: 50%; left: 50%;
            right: auto; bottom: auto;
            width: 100%; max-width: 520px;
            border-radius: 1.25rem;
            max-height: 88vh;
            transform: translate(-50%, -50%) scale(0.95);
            opacity: 0;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }
        #scanner-modal.scanner-open {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }
    }

    /* ── Modal scrollable body: fills remaining height ── */
    .scanner-modal-body {
        max-height: calc(92vh - 230px);
    }
    @media (min-width: 768px) {
        .scanner-modal-body { max-height: calc(88vh - 230px); }
    }

    /* ── Tabs ── */
    .scanner-tab {
        background: #f3f4f6;
        color: #6b7280;
    }
    .scanner-tab.scanner-tab-active {
        background: linear-gradient(135deg, #2FCB6E, #11455B);
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(47,203,110,0.35);
    }
</style>

{{-- ── Script ───────────────────────────────────────────────────── --}}
<script>
(function () {
    let scannerCurrentTab  = 'upload';
    let scannerStream      = null;
    let scannerHasImage    = false;
    let scannerImageFile   = null;   // the actual File/Blob ready to POST

    /* ── open / close ──────────────────────────────────────────── */
    window.openScannerModal = function () {
        const backdrop = document.getElementById('scanner-backdrop');
        const modal    = document.getElementById('scanner-modal');

        backdrop.classList.remove('hidden');
        modal.classList.remove('hidden');
        // Double rAF ensures the display:block is painted before transition runs
        requestAnimationFrame(() => requestAnimationFrame(() => {
            backdrop.classList.add('scanner-open');
            modal.classList.add('scanner-open');
        }));

        document.body.style.overflow = 'hidden';
        // Initialise to upload tab
        switchScannerTab('upload');
    };

    window.closeScannerModal = function () {
        const backdrop = document.getElementById('scanner-backdrop');
        const modal    = document.getElementById('scanner-modal');

        backdrop.classList.remove('scanner-open');
        modal.classList.remove('scanner-open');

        setTimeout(() => {
            backdrop.classList.add('hidden');
            modal.classList.add('hidden');
            stopScannerCamera();
        }, 380);

        document.body.style.overflow = '';
    };

    /* ── tabs ───────────────────────────────────────────────────── */
    window.switchScannerTab = function (tab) {
        scannerCurrentTab = tab;

        document.querySelectorAll('.scanner-tab').forEach(el => {
            el.classList.remove('scanner-tab-active');
        });
        document.getElementById('tab-' + tab).classList.add('scanner-tab-active');

        document.getElementById('scanner-content-upload').classList.toggle('hidden', tab !== 'upload');
        document.getElementById('scanner-content-camera').classList.toggle('hidden', tab !== 'camera');

        if (tab === 'camera') {
            startScannerCamera();
        } else {
            stopScannerCamera();
        }
    };

    /* ── camera ─────────────────────────────────────────────────── */
    window.startScannerCamera = function () {
        const loading  = document.getElementById('scanner-camera-loading');
        const errEl    = document.getElementById('scanner-camera-error');
        const liveEl   = document.getElementById('scanner-camera-live');
        const captured = document.getElementById('scanner-captured-preview');

        loading.classList.remove('hidden');
        errEl.classList.add('hidden');
        liveEl.classList.add('hidden');
        captured.classList.add('hidden');

        if (!navigator.mediaDevices?.getUserMedia) {
            loading.classList.add('hidden');
            errEl.classList.remove('hidden');
            return;
        }

        navigator.mediaDevices.getUserMedia({
            video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }
        }).then(stream => {
            scannerStream = stream;
            const video = document.getElementById('scanner-video');
            video.srcObject = stream;
            loading.classList.add('hidden');
            liveEl.classList.remove('hidden');
        }).catch(() => {
            loading.classList.add('hidden');
            errEl.classList.remove('hidden');
        });
    };

    function stopScannerCamera() {
        if (scannerStream) {
            scannerStream.getTracks().forEach(t => t.stop());
            scannerStream = null;
        }
        const video = document.getElementById('scanner-video');
        if (video) video.srcObject = null;
    }
    window.stopScannerCamera = stopScannerCamera;

    window.scannerCapturePhoto = function () {
        const video  = document.getElementById('scanner-video');
        const canvas = document.getElementById('scanner-canvas');
        const ctx    = canvas.getContext('2d');

        canvas.width  = video.videoWidth  || 1280;
        canvas.height = video.videoHeight || 720;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        document.getElementById('scanner-captured-img').src = canvas.toDataURL('image/jpeg', 0.85);
        document.getElementById('scanner-camera-live').classList.add('hidden');
        document.getElementById('scanner-captured-preview').classList.remove('hidden');
        stopScannerCamera();

        // Convert canvas frame to a real File for POST submission
        canvas.toBlob(blob => {
            scannerImageFile = new File([blob], 'camera-capture.jpg', { type: 'image/jpeg' });
            setScannerImageReady(true);
        }, 'image/jpeg', 0.85);
    };

    window.scannerRetakePhoto = function () {
        document.getElementById('scanner-captured-preview').classList.add('hidden');
        scannerImageFile = null;
        setScannerImageReady(false);
        startScannerCamera();
    };

    /* ── upload ─────────────────────────────────────────────────── */
    window.triggerScannerFileInput = function () {
        document.getElementById('scanner-file-input').click();
    };

    window.scannerFileSelected = function (event) {
        const file = event.target.files[0];
        if (file) readScannerFile(file);
    };

    function readScannerFile(file) {
        if (!file.type.startsWith('image/')) return;
        scannerImageFile = file;   // keep the real File for POST
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('scanner-preview-img').src = e.target.result;
            document.getElementById('scanner-file-name').textContent = file.name;
            document.getElementById('scanner-drop-zone').classList.add('hidden');
            document.getElementById('scanner-upload-preview').classList.remove('hidden');
            setScannerImageReady(true);
        };
        reader.readAsDataURL(file);
    }

    window.clearScannerUpload = function () {
        document.getElementById('scanner-preview-img').src = '';
        document.getElementById('scanner-file-input').value = '';
        document.getElementById('scanner-upload-preview').classList.add('hidden');
        document.getElementById('scanner-drop-zone').classList.remove('hidden');
        scannerImageFile = null;
        setScannerImageReady(false);
    };

    /* ── drag & drop ────────────────────────────────────────────── */
    window.scannerDragOver = function (e) {
        e.preventDefault();
        document.getElementById('scanner-drop-zone').classList.add('border-[#2FCB6E]', 'bg-[#2FCB6E]/5');
    };
    window.scannerDragLeave = function () {
        document.getElementById('scanner-drop-zone').classList.remove('border-[#2FCB6E]', 'bg-[#2FCB6E]/5');
    };
    window.scannerDrop = function (e) {
        e.preventDefault();
        scannerDragLeave();
        const file = e.dataTransfer.files[0];
        if (file) readScannerFile(file);
    };

    /* ── analyze button state ───────────────────────────────────── */
    function setScannerImageReady(ready) {
        scannerHasImage = ready;
        const btn = document.getElementById('scanner-analyze-btn');
        if (ready) {
            btn.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            btn.classList.add('opacity-50', 'pointer-events-none');
        }
    }

    /* ── analyze: submit image to public endpoint via fetch ──────── */
    window.runScannerAnalysis = async function () {
        if (!scannerImageFile) return;

        const body     = document.querySelector('#scanner-modal .scanner-modal-body');
        const footer   = document.querySelector('#scanner-modal .px-5.py-4.border-t');
        const closeBtn = document.querySelector('#scanner-modal [aria-label="Close scanner"]');

        // Save full UI state so the user can retry without re-selecting the image
        const savedBodyHTML   = body.innerHTML;
        const savedFooterHTML = footer.innerHTML;
        const savedFile       = scannerImageFile;

        // ── Analyzing overlay ───────────────────────────────────────
        body.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10 text-center gap-5">
                <div class="relative w-24 h-24">
                    <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-t-[#2FCB6E]
                                border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
                    <div class="absolute inset-2 rounded-full border-4 border-t-transparent
                                border-r-[#11455B]/40 border-b-transparent border-l-transparent animate-spin"
                         style="animation-duration:1.6s"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-3xl">🔬</div>
                </div>
                <div>
                    <p class="text-lg font-bold text-[#11455B]" id="sc-title">Analyzing...</p>
                    <p class="text-sm text-gray-500 mt-1" id="sc-sub">Please wait while our AI examines the photo</p>
                </div>
                <div class="w-full max-w-[240px] space-y-1.5 text-sm">
                    <div id="sc-s1" class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-[#2FCB6E]/10 text-gray-700">
                        <svg class="w-4 h-4 animate-spin text-[#2FCB6E] shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        <span>Processing image</span>
                    </div>
                    <div id="sc-s2" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-400 opacity-40">
                        <div class="w-4 h-4 rounded-full border-2 border-gray-300 shrink-0"></div>
                        <span>Running AI analysis</span>
                    </div>
                    <div id="sc-s3" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-400 opacity-40">
                        <div class="w-4 h-4 rounded-full border-2 border-gray-300 shrink-0"></div>
                        <span>Generating report</span>
                    </div>
                </div>
            </div>`;
        footer.innerHTML = '';
        if (closeBtn) closeBtn.setAttribute('disabled', 'true');

        // Step icons
        const spinSvg  = '<svg class="w-4 h-4 animate-spin text-[#2FCB6E] shrink-0" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg>';
        const checkSvg = '<svg class="w-4 h-4 text-[#2FCB6E] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';

        function activateStep(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('opacity-40');
            el.classList.add('bg-[#2FCB6E]/10', 'text-gray-700');
            const icon = el.querySelector('div, svg');
            if (icon) icon.outerHTML = spinSvg;
        }
        function completeStep(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const icon = el.querySelector('svg');
            if (icon) icon.outerHTML = checkSvg;
        }

        const t1 = setTimeout(() => { completeStep('sc-s1'); activateStep('sc-s2'); }, 900);
        const t2 = setTimeout(() => { completeStep('sc-s2'); activateStep('sc-s3'); }, 2600);

        // ── POST to public scan endpoint ────────────────────────────
        try {
            const fd = new FormData();
            fd.append('image', savedFile, savedFile.name || 'scan.jpg');
            fd.append('_token', '{{ csrf_token() }}');

            const resp = await fetch('{{ route("disease-detection.public-store") }}', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd,
            });

            clearTimeout(t1);
            clearTimeout(t2);

            let data;
            try { data = await resp.json(); } catch { data = {}; }

            if (resp.ok && data.success && data.redirect) {
                // Mark all steps done, then navigate
                ['sc-s1','sc-s2','sc-s3'].forEach(id => {
                    completeStep(id);
                    const el = document.getElementById(id);
                    if (el) { el.classList.remove('opacity-40'); el.classList.add('bg-[#2FCB6E]/10','text-gray-700'); }
                });
                const t = document.getElementById('sc-title');
                const s = document.getElementById('sc-sub');
                if (t) t.textContent = 'Done! ✓';
                if (s) s.textContent = 'Redirecting to your results…';
                setTimeout(() => { window.location.href = data.redirect; }, 350);
            } else {
                const msg = data.message
                    || (data.errors && Object.values(data.errors).flat()[0])
                    || 'Analysis failed. Please try again.';
                throw new Error(msg);
            }
        } catch (err) {
            clearTimeout(t1);
            clearTimeout(t2);

            // ── Restore the full scanner UI so the user can try again ──
            body.innerHTML   = savedBodyHTML;
            footer.innerHTML = savedFooterHTML;
            scannerImageFile = savedFile;       // keep the image ready
            setScannerImageReady(true);         // re-enable the Analyze button
            if (closeBtn) closeBtn.removeAttribute('disabled');

            // Show a dismissible error banner above the footer
            const toast = document.createElement('div');
            toast.className = 'px-5 pb-2';
            toast.innerHTML = `
                <div class="flex items-center justify-between gap-2 px-3 py-2.5
                             bg-red-50 border border-red-200 rounded-xl text-xs text-red-700">
                    <span>⚠️ ${err.message}</span>
                    <button onclick="this.closest('.px-5').remove()"
                            class="shrink-0 text-red-400 hover:text-red-600 font-bold text-base leading-none">×</button>
                </div>`;
            footer.insertAdjacentElement('beforebegin', toast);
            setTimeout(() => toast.remove(), 8000);
        }
    };

    /* ── keyboard close ─────────────────────────────────────────── */
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('scanner-modal');
            if (modal && !modal.classList.contains('hidden')) closeScannerModal();
        }
    });
})();
</script>
