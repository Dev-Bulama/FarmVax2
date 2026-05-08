<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disease Scan Results — FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#11455b', secondary: '#2fcb6e' } } }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }

        .locked-blur { filter: blur(5px); pointer-events: none; user-select: none; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease-out both; }
        .fade-up-2 { animation: fadeUp 0.5s 0.1s ease-out both; }
        .fade-up-3 { animation: fadeUp 0.5s 0.2s ease-out both; }
        .fade-up-4 { animation: fadeUp 0.5s 0.3s ease-out both; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

{{-- ===== TOP NAV ===== --}}
<nav class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-black text-sm"
                 style="background:linear-gradient(135deg,#2fcb6e,#11455b);">F</div>
            <span class="text-xl font-black" style="color:#11455b;">FarmVax</span>
        </a>
        <div class="flex items-center gap-3">
            @if(auth()->check())
                <a href="{{ route('disease-detection.index') }}"
                   class="text-sm font-semibold text-gray-600 hover:text-primary transition">
                    My Scans
                </a>
                <a href="{{ route('disease-detection.create') }}"
                   class="px-4 py-2 text-sm font-bold text-white rounded-xl transition"
                   style="background:#2fcb6e;">
                    + New Scan
                </a>
            @else
                <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                   class="px-4 py-2 text-sm font-semibold border-2 border-primary text-primary rounded-xl hover:bg-primary hover:text-white transition">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-sm font-bold text-white rounded-xl transition"
                   style="background:#2fcb6e;">
                    Register
                </a>
            @endif
        </div>
    </div>
</nav>

<main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-6">

    {{-- Breadcrumb --}}
    <div class="fade-up text-sm text-gray-500 flex items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">Scan Results</span>
    </div>

    {{-- ===== HEADER ===== --}}
    <div class="fade-up-2">
        <h1 class="text-3xl font-black" style="color:#11455b;">🔬 Disease Scan Results</h1>
        <p class="text-gray-500 mt-1 text-sm">AI-powered livestock health analysis · {{ $scan->created_at->format('d M Y, H:i') }}</p>
    </div>

    @php
        // Parse poor-image prefix stored in analysis_result
        $rawAnalysis  = $scan->analysis_result ?? '';
        $imageWarning = null;
        $analysisText = $rawAnalysis;
        if (str_starts_with($rawAnalysis, '[POOR_IMAGE:')) {
            preg_match('/\[POOR_IMAGE: (.*?)\]/', $rawAnalysis, $pwm);
            $imageWarning = $pwm[1] ?? 'Image quality is low — results may be less accurate.';
            $analysisText = trim(substr($rawAnalysis, strpos($rawAnalysis, '] ') + 2));
        }
        $isNotAnimal = ($scan->animal_type === 'not_animal');
    @endphp

    @if($scan->status === 'processing')
    {{-- Processing state --}}
    <div class="fade-up bg-blue-50 border border-blue-200 rounded-2xl p-10 text-center">
        <div class="text-5xl mb-4">⏳</div>
        <h2 class="text-xl font-bold text-blue-800 mb-2">Analysis in Progress</h2>
        <p class="text-blue-600 mb-5">The AI is still examining the image. Please wait a moment.</p>
        <a href="{{ url()->current() }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Refresh Results
        </a>
    </div>

    @elseif($scan->status === 'failed')
    {{-- Failed state --}}
    <div class="fade-up bg-red-50 border border-red-200 rounded-2xl p-10 text-center">
        <div class="text-5xl mb-4">❌</div>
        <h2 class="text-xl font-bold text-red-800 mb-2">Analysis Could Not Be Completed</h2>
        <p class="text-red-600 mb-2">{{ $analysisText }}</p>
        <p class="text-sm text-gray-500 mb-5">Please try again with a clear, well-lit photo of the animal.</p>
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-[#11455b] text-[#11455b] font-bold rounded-xl hover:bg-[#11455b] hover:text-white transition text-sm">
            Try Again
        </a>
    </div>

    @elseif($isNotAnimal)
    {{-- ── Not an animal ───────────────────────────────────────────── --}}
    <div class="fade-up-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col sm:flex-row">
            <div class="sm:w-48 sm:shrink-0">
                <img src="{{ asset('storage/' . $scan->image_path) }}"
                     alt="Uploaded image"
                     class="w-full h-48 sm:h-full object-cover"
                     onerror="this.src='https://placehold.co/192x192/e5e7eb/9ca3af?text=No+Image'">
            </div>
            <div class="flex-1 p-5 flex flex-col justify-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 w-fit rounded-full text-sm font-bold bg-amber-100 text-amber-700 border border-amber-200">
                    ⚠️ No Animal Detected
                </span>
                <p class="text-xs text-gray-400">Scanned {{ $scan->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>

    <div class="fade-up-3 bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center space-y-4">
        <div class="text-5xl">🐄</div>
        <h2 class="text-xl font-bold text-amber-800">No Animal Found in This Image</h2>
        <p class="text-amber-700 text-sm leading-relaxed max-w-md mx-auto">{{ $analysisText }}</p>
        <div class="bg-white rounded-xl p-4 text-left max-w-sm mx-auto border border-amber-100 space-y-2 text-sm text-gray-600">
            <p class="font-semibold text-gray-800 mb-1">Tips for a good scan:</p>
            <p>📸 Take a close-up photo of the animal</p>
            <p>💡 Ensure good lighting — avoid shadows</p>
            <p>🔍 The animal should fill most of the frame</p>
            <p>🐾 Works best with: cattle, goat, sheep, pig, poultry, horse</p>
        </div>
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#11455b] text-white font-bold rounded-xl hover:opacity-90 transition text-sm">
            Try Again with a Better Photo
        </a>
    </div>

    @else
    {{-- ================================================================
         COMPLETED — show results
         ================================================================ --}}

    {{-- Poor image quality banner --}}
    @if($imageWarning)
    <div class="fade-up bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
        <span class="text-2xl shrink-0">📷</span>
        <div>
            <p class="font-bold text-amber-800 text-sm">Low Image Quality Detected</p>
            <p class="text-amber-700 text-sm mt-0.5">{{ $imageWarning }}</p>
            <p class="text-amber-600 text-xs mt-1">For best results, use a clear, well-lit close-up photo where the animal fills the frame.</p>
        </div>
    </div>
    @endif

    {{-- Animal summary card --}}
    <div class="fade-up-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col sm:flex-row">
            <div class="sm:w-48 sm:shrink-0">
                <img src="{{ asset('storage/' . $scan->image_path) }}"
                     alt="Scanned animal"
                     class="w-full h-48 sm:h-full object-cover"
                     onerror="this.src='https://placehold.co/192x192/e5e7eb/9ca3af?text=No+Image'">
            </div>
            <div class="flex-1 p-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gray-400 mb-0.5">Animal Type</p>
                            <p class="text-2xl font-black" style="color:#11455b;">{{ ucwords(str_replace('_', ' ', $scan->animal_type ?? 'Unknown')) }}</p>
                        </div>
                        @if($scan->is_sick)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold bg-red-100 text-red-700 border border-red-200">
                                ⚠️ Disease Detected
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold bg-green-100 text-green-700 border border-green-200">
                                ✅ Appears Healthy
                            </span>
                        @endif
                    </div>
                    @if($scan->livestock)
                        <p class="text-sm text-gray-500 mt-1">
                            Animal ID: {{ $scan->livestock->tag_number ?? $scan->livestock->name ?? '#'.$scan->livestock->id }}
                        </p>
                    @endif
                </div>
                <div class="mt-3 flex items-center gap-4 text-sm text-gray-400">
                    <span>Scanned {{ $scan->created_at->diffForHumans() }}</span>
                    @if($scan->ai_model)
                        <span class="px-2 py-0.5 bg-gray-100 rounded text-xs">{{ $scan->ai_model }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         A. PUBLIC PREVIEW — visible to everyone
         ================================================================ --}}
    <div class="fade-up-3 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-black"
                 style="background:#2fcb6e;">✓</div>
            <h2 class="text-lg font-bold" style="color:#11455b;">Scan Preview</h2>
            <span class="ml-auto text-xs px-2 py-1 bg-green-50 text-green-700 font-semibold rounded-full border border-green-200">
                Public
            </span>
        </div>

        @php
            $conditions   = $scan->detected_conditions ?? [];
            $topCondition = count($conditions) > 0 ? $conditions[0] : null;
            $conditionName = $topCondition['name'] ?? ($scan->is_sick ? 'Disease Detected' : 'No Disease Detected');
            $severity      = $topCondition['severity'] ?? $scan->urgency_level ?? 'low';
            $urgency       = $scan->urgency_level ?? 'low';

            $urgencyColors = [
                'low'      => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'label' => 'Low'],
                'medium'   => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Medium'],
                'high'     => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'High'],
                'critical' => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'label' => 'Critical'],
            ];
            $uc = $urgencyColors[$urgency] ?? $urgencyColors['low'];

            $symptomsRaw = trim($scan->symptoms_reported ?? '');
            $symptomList = array_filter(array_map('trim', explode(',', $symptomsRaw)));
            $symptomPreview = implode(', ', array_slice($symptomList, 0, 2));
            $symptomHasMore = count($symptomList) > 2;
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            {{-- Detected disease --}}
            <div class="md:col-span-2 bg-gray-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">Detected Disease</p>
                <p class="text-lg font-bold text-gray-900 leading-tight">{{ $conditionName }}</p>
                @if($topCondition && isset($topCondition['probability']))
                    <p class="text-sm text-gray-500 mt-0.5">{{ $topCondition['probability'] }}% probability</p>
                @endif
            </div>

            {{-- Confidence --}}
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">Confidence</p>
                <p class="text-3xl font-black" style="color:#2fcb6e;">{{ number_format($scan->confidence_score ?? 0, 0) }}%</p>
                <div class="mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all"
                         style="width:{{ min(100, $scan->confidence_score ?? 0) }}%;
                                background:{{ ($scan->confidence_score ?? 0) >= 75 ? '#2fcb6e' : (($scan->confidence_score ?? 0) >= 50 ? '#f59e0b' : '#ef4444') }}">
                    </div>
                </div>
            </div>

            {{-- Severity --}}
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-xs uppercase tracking-widest text-gray-400 mb-2">Severity</p>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold {{ $uc['bg'] }} {{ $uc['text'] }}">
                    {{ $uc['label'] }}
                </span>
            </div>
        </div>

        {{-- Symptom summary --}}
        @if($symptomsRaw)
        <div class="mt-4 flex items-start gap-3 p-4 bg-blue-50 rounded-xl">
            <div class="mt-0.5 w-5 h-5 rounded-full bg-blue-200 flex items-center justify-center shrink-0">
                <svg class="h-3 w-3 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-0.5">Reported Symptoms</p>
                <p class="text-gray-800 text-sm">
                    {{ $symptomPreview }}{{ $symptomHasMore ? '…' : '' }}
                </p>
            </div>
        </div>
        @endif
    </div>

    {{-- ================================================================
         B. LOCKED FULL RESULT
         ================================================================ --}}
    @if(auth()->check())
    {{-- ---- LOGGED IN: full results ---- --}}
    <div class="fade-up-4 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">

        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-black"
                 style="background:#11455b;">📋</div>
            <h2 class="text-lg font-bold" style="color:#11455b;">Full Diagnosis Report</h2>
            <span class="ml-auto text-xs px-2 py-1 bg-blue-50 text-blue-700 font-semibold rounded-full border border-blue-200">
                Full Access
            </span>
        </div>

        {{-- AI Summary --}}
        @if($analysisText)
        <div class="p-4 {{ $scan->is_sick ? 'bg-red-50 border border-red-100' : 'bg-green-50 border border-green-100' }} rounded-xl">
            <p class="text-sm font-semibold {{ $scan->is_sick ? 'text-red-700' : 'text-green-700' }} mb-1">AI Summary</p>
            <p class="text-gray-800 text-sm leading-relaxed">{{ $analysisText }}</p>
        </div>
        @endif

        {{-- All conditions --}}
        @if(count($conditions) > 0)
        <div>
            <h3 class="font-bold text-gray-800 mb-3">Full Symptoms Breakdown</h3>
            <div class="space-y-3">
                @foreach($conditions as $cond)
                @php
                    $sev = $cond['severity'] ?? 'mild';
                    $sevMap = ['severe' => ['bg-red-100','text-red-700'], 'moderate' => ['bg-yellow-100','text-yellow-700'], 'mild' => ['bg-green-100','text-green-700']];
                    [$sevBg, $sevText] = $sevMap[$sev] ?? ['bg-gray-100','text-gray-700'];
                @endphp
                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-gray-900">{{ $cond['name'] ?? 'Unknown' }}</p>
                        @if(!empty($cond['description']))
                            <p class="text-sm text-gray-600 mt-1">{{ $cond['description'] }}</p>
                        @endif
                        <span class="mt-2 inline-block px-2 py-0.5 text-xs font-semibold rounded-full {{ $sevBg }} {{ $sevText }}">
                            {{ ucfirst($sev) }}
                        </span>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-2xl font-black" style="color:#11455b;">{{ $cond['probability'] ?? 0 }}%</div>
                        <p class="text-xs text-gray-400">probability</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Treatment plan / recommendations --}}
        @if($scan->recommendations)
        <div>
            <h3 class="font-bold text-gray-800 mb-3">Treatment Plan & Recommendations</h3>
            <ul class="space-y-2">
                @foreach(array_filter(explode("\n", $scan->recommendations)) as $rec)
                <li class="flex items-start gap-2.5">
                    <span class="mt-0.5 w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-white text-xs font-bold"
                          style="background:#2fcb6e;">→</span>
                    <span class="text-gray-700 text-sm">{{ trim($rec) }}</span>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Vet & isolation flags --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="flex items-center gap-3 p-4 rounded-xl {{ $scan->urgency_level === 'critical' || $scan->urgency_level === 'high' ? 'bg-red-50 border border-red-200' : 'bg-gray-50' }}">
                <span class="text-2xl">🏥</span>
                <div>
                    <p class="text-sm font-bold text-gray-800">Veterinary Visit</p>
                    <p class="text-xs text-gray-500">
                        {{ in_array($scan->urgency_level, ['high','critical']) ? 'Strongly recommended' : 'Recommended if symptoms persist' }}
                    </p>
                </div>
            </div>
            @if($scan->is_sick)
            <div class="flex items-center gap-3 p-4 rounded-xl bg-orange-50 border border-orange-200">
                <span class="text-2xl">🔒</span>
                <div>
                    <p class="text-sm font-bold text-gray-800">Isolation</p>
                    <p class="text-xs text-gray-500">Consider isolating to prevent spread</p>
                </div>
            </div>
            @endif
        </div>

        {{-- CTA to book vet --}}
        <div class="flex items-center justify-between bg-blue-50 border border-blue-100 rounded-xl p-4">
            <div>
                <p class="font-bold text-blue-800 text-sm">Need expert advice?</p>
                <p class="text-xs text-blue-600">Request a live video consultation with a veterinarian.</p>
            </div>
            @php
                $role = auth()->user()->role ?? 'farmer';
            @endphp
            @if(\Illuminate\Support\Facades\Route::has($role . '.telemedicine.create'))
                <a href="{{ route($role . '.telemedicine.create') }}"
                   class="ml-4 px-4 py-2 bg-blue-600 text-white font-bold rounded-lg text-sm whitespace-nowrap">
                    📹 Book Vet Call
                </a>
            @elseif(\Illuminate\Support\Facades\Route::has('farmer.telemedicine.create'))
                <a href="{{ route('farmer.telemedicine.create') }}"
                   class="ml-4 px-4 py-2 bg-blue-600 text-white font-bold rounded-lg text-sm whitespace-nowrap">
                    📹 Book Vet Call
                </a>
            @endif
        </div>

        {{-- Back link --}}
        <div class="text-right">
            <a href="{{ route('disease-detection.index') }}"
               class="text-sm font-semibold text-primary hover:underline">
                ← Back to My Scans
            </a>
        </div>
    </div>

    @else
    {{-- ---- GUEST: locked section ---- --}}
    <div class="fade-up-4 relative">

        {{-- Blurred placeholder content --}}
        <div class="locked-blur bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4"
             aria-hidden="true">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-7 h-7 rounded-full bg-gray-200"></div>
                <div class="h-5 bg-gray-200 rounded w-48"></div>
            </div>
            <div class="p-4 bg-gray-100 rounded-xl space-y-2">
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                <div class="h-4 bg-gray-200 rounded w-full"></div>
                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
            </div>
            <div class="space-y-3">
                <div class="h-5 bg-gray-200 rounded w-40"></div>
                @for($i = 0; $i < 3; $i++)
                <div class="p-4 bg-gray-50 rounded-xl flex justify-between">
                    <div class="space-y-1.5 flex-1">
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                        <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-3 bg-gray-100 rounded w-16 mt-1"></div>
                    </div>
                    <div class="h-8 w-12 bg-gray-200 rounded ml-4 shrink-0"></div>
                </div>
                @endfor
            </div>
            <div class="space-y-2">
                <div class="h-5 bg-gray-200 rounded w-48"></div>
                @for($i = 0; $i < 4; $i++)
                <div class="flex gap-2">
                    <div class="w-5 h-5 rounded-full bg-gray-200 shrink-0"></div>
                    <div class="h-4 bg-gray-200 rounded flex-1"></div>
                </div>
                @endfor
            </div>
        </div>

        {{-- Lock overlay --}}
        <div class="absolute inset-0 flex items-center justify-center rounded-2xl"
             style="background:rgba(255,255,255,0.7);backdrop-filter:blur(2px);">
            <div class="text-center px-6 py-8 max-w-sm mx-auto">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"
                     style="background:rgba(17,69,91,0.08);">
                    <span class="text-3xl">🔒</span>
                </div>
                <h3 class="text-xl font-black mb-2" style="color:#11455b;">Full Diagnosis Locked</h3>
                <p class="text-gray-500 text-sm mb-2">
                    Login or create an account to view the full diagnosis and treatment plan.
                </p>
                <div class="text-xs text-gray-400 mb-6 space-y-1">
                    <p>🩺 Full symptoms breakdown</p>
                    <p>💊 Medication recommendations</p>
                    <p>🛡️ Prevention guide</p>
                    <p>📄 Downloadable veterinary report</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
                       class="flex-1 py-3 text-white font-bold rounded-xl text-sm text-center transition hover:opacity-90"
                       style="background:#11455b;">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex-1 py-3 font-bold rounded-xl text-sm text-center border-2 transition hover:bg-gray-50"
                       style="border-color:#11455b; color:#11455b;">
                        Create Account
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @endif {{-- end status/type check --}}

    {{-- ================================================================
         RATING & FEEDBACK SECTION
         ================================================================ --}}
    @if($scan->status === 'completed' && !$isNotAnimal)
    <div class="fade-up bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center gap-2 mb-5">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-black"
                 style="background:#2fcb6e;">★</div>
            <h2 class="text-lg font-bold" style="color:#11455b;">Rate AI Accuracy</h2>
        </div>

        @if(session('feedback_success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700 font-medium">
            ✓ {{ session('feedback_success') }}
        </div>
        @endif

        @if($scan->user_rating)
        {{-- Already rated: show existing rating --}}
        <div class="mb-5">
            <p class="text-xs text-gray-500 mb-1 uppercase tracking-wide font-semibold">Current Rating</p>
            <div class="flex items-center gap-1">
                @for($i = 1; $i <= 5; $i++)
                <svg class="w-7 h-7 {{ $i <= $scan->user_rating ? 'text-amber-400' : 'text-gray-200' }}"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                @endfor
                <span class="ml-2 text-sm font-bold text-gray-700">{{ $scan->user_rating }}/5</span>
            </div>
            @if($scan->user_comment)
            <p class="mt-3 text-sm text-gray-600 italic">"{{ $scan->user_comment }}"</p>
            @endif
        </div>
        @endif

        @if(auth()->check())
        <form action="{{ route('disease-detection.rate', $scan->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-2">
                    {{ $scan->user_rating ? 'Update your rating:' : 'How accurate was the AI diagnosis?' }}
                </p>
                <div class="flex items-center gap-2" id="star-container">
                    @for($i = 1; $i <= 5; $i++)
                    <label class="cursor-pointer star-label" data-val="{{ $i }}">
                        <input type="radio" name="rating" value="{{ $i }}"
                               class="sr-only"
                               {{ $scan->user_rating == $i ? 'checked' : '' }}>
                        <svg class="w-9 h-9 transition-colors duration-100 star-icon
                                   {{ $scan->user_rating && $scan->user_rating >= $i ? 'text-amber-400' : 'text-gray-200' }}"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </label>
                    @endfor
                    <span id="star-label-text" class="text-sm text-gray-400 ml-2"></span>
                </div>
                @error('rating')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Comment / Suggestion <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <textarea name="comment" rows="3" maxlength="1000"
                          placeholder="Share your feedback or suggest improvements to the AI…"
                          class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm text-gray-700
                                 focus:ring-2 focus:ring-[#2fcb6e] focus:border-[#2fcb6e] resize-none">{{ old('comment', $scan->user_comment) }}</textarea>
                @error('comment')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="px-6 py-2.5 text-white text-sm font-bold rounded-xl transition hover:opacity-90"
                    style="background:linear-gradient(135deg,#2fcb6e,#11455b);">
                {{ $scan->user_rating ? 'Update Feedback' : 'Submit Feedback' }}
            </button>
        </form>

        <script>
        (function () {
            const labels = document.querySelectorAll('.star-label');
            const stars  = document.querySelectorAll('.star-icon');
            const hint   = document.getElementById('star-label-text');
            const texts  = ['','Poor','Fair','Good','Very Good','Excellent'];

            function highlight(n) {
                stars.forEach((s, i) => {
                    s.classList.toggle('text-amber-400', i < n);
                    s.classList.toggle('text-gray-200',  i >= n);
                });
                hint.textContent = texts[n] || '';
            }

            labels.forEach(lbl => {
                const val = parseInt(lbl.dataset.val);
                lbl.addEventListener('mouseenter', () => highlight(val));
                lbl.addEventListener('mouseleave', () => {
                    const checked = document.querySelector('input[name="rating"]:checked');
                    highlight(checked ? parseInt(checked.value) : 0);
                });
                lbl.addEventListener('click', () => highlight(val));
            });

            // Initialise if already rated
            const checkedInit = document.querySelector('input[name="rating"]:checked');
            if (checkedInit) highlight(parseInt(checkedInit.value));
        })();
        </script>

        @else
        {{-- Guest: prompt to login --}}
        <div class="flex items-center justify-between gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
            <div>
                <p class="text-sm font-semibold text-gray-700">Want to rate this result?</p>
                <p class="text-xs text-gray-500 mt-0.5">Login to rate the AI accuracy and leave a comment.</p>
            </div>
            <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}"
               class="shrink-0 px-4 py-2 text-white text-sm font-bold rounded-xl transition hover:opacity-90"
               style="background:#11455b;">
                Login to Rate
            </a>
        </div>
        @endif
    </div>
    @endif

</main>

{{-- Footer --}}
<footer class="mt-12 border-t border-gray-100 py-6 text-center text-xs text-gray-400">
    &copy; {{ date('Y') }} FarmVax · AI-powered livestock disease detection
</footer>

</body>
</html>
