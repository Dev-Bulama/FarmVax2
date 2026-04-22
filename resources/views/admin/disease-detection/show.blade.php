@extends('layouts.admin')
@section('title', 'Scan Detail')
@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.disease-detection.index') }}" class="text-gray-500 hover:text-gray-800 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to All Scans
        </a>
        <h1 class="text-2xl font-bold" style="color:#11455b;">🔬 Scan #{{ $scan->id }}</h1>
        {!! $scan->urgency_badge !!}
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left column --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <img src="{{ asset('storage/' . $scan->image_path) }}" alt="Scanned animal"
                     class="w-full object-cover" style="max-height:260px;"
                     onerror="this.src='{{ asset('images/default-livestock.png') }}'">
                <div class="p-4 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-gray-700">{{ ucfirst($scan->animal_type) }}</span>
                        @if($scan->status === 'completed')
                            @if($scan->is_sick)
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">⚠️ Sick</span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">✅ Healthy</span>
                            @endif
                        @elseif($scan->status === 'processing')
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">⏳ Processing</span>
                        @else
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600">Failed</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400">Submitted {{ $scan->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            {{-- User info --}}
            <div class="bg-white rounded-xl shadow p-4 space-y-2">
                <h3 class="font-bold text-gray-700 mb-3">Submitted By</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-lg">
                        {{ strtoupper(substr($scan->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $scan->user->name ?? '—' }}</p>
                        <p class="text-xs text-gray-500">{{ $scan->user->email ?? '' }}</p>
                    </div>
                </div>
                @if($scan->livestock)
                <div class="pt-2 border-t border-gray-100 mt-2">
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">Linked Animal</p>
                    <p class="text-sm text-gray-700">{{ $scan->livestock->tag_number ?? ucfirst($scan->livestock->livestock_type) }}</p>
                </div>
                @endif
            </div>

            {{-- AI Confidence --}}
            @if($scan->status === 'completed' && $scan->confidence_score)
            <div class="bg-white rounded-xl shadow p-4">
                <p class="font-semibold text-gray-700 mb-3">AI Confidence</p>
                <div class="flex items-center gap-3">
                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full {{ $scan->confidence_score >= 85 ? 'bg-green-500' : ($scan->confidence_score >= 65 ? 'bg-yellow-500' : 'bg-red-500') }}"
                             style="width:{{ $scan->confidence_score }}%"></div>
                    </div>
                    <span class="font-black text-gray-800">{{ $scan->confidence_score }}%</span>
                </div>
                @if($scan->ai_model)
                    <p class="text-xs text-gray-400 mt-2">Model: {{ $scan->ai_model }}</p>
                @endif
            </div>
            @endif
        </div>

        {{-- Right column --}}
        <div class="lg:col-span-2 space-y-6">

            @if($scan->status === 'processing')
            <div class="bg-blue-50 rounded-xl p-8 text-center">
                <div class="text-5xl mb-3">⏳</div>
                <p class="font-bold text-blue-800 text-xl">Analysis in Progress</p>
                <p class="text-blue-600 mt-2">This scan is still being processed by the AI engine.</p>
            </div>

            @elseif($scan->status === 'failed')
            <div class="bg-red-50 rounded-xl p-8 text-center">
                <div class="text-5xl mb-3">❌</div>
                <p class="font-bold text-red-800 text-xl">Analysis Failed</p>
                <p class="text-red-600 mt-2">{{ $scan->analysis_result }}</p>
            </div>

            @else
            {{-- Verdict --}}
            @if($scan->is_sick)
            <div class="rounded-xl p-6 border-l-4 {{ $scan->urgency_level === 'critical' ? 'bg-red-50 border-red-600' : ($scan->urgency_level === 'high' ? 'bg-orange-50 border-orange-500' : 'bg-yellow-50 border-yellow-500') }}">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl">⚠️</span>
                    <div>
                        <p class="font-bold text-xl text-gray-900">Health Issues Detected</p>
                        <div class="flex items-center gap-2 mt-1">{!! $scan->urgency_badge !!}</div>
                    </div>
                </div>
                <p class="text-gray-800 mt-2">{{ $scan->analysis_result }}</p>
            </div>
            @else
            <div class="bg-green-50 rounded-xl p-6 border-l-4 border-green-500">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-3xl">✅</span>
                    <p class="font-bold text-xl text-green-800">Animal Appears Healthy</p>
                </div>
                <p class="text-gray-700 mt-2">{{ $scan->analysis_result }}</p>
            </div>
            @endif

            {{-- Symptoms reported by user --}}
            @if($scan->symptoms_reported)
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-800 mb-2">Owner-Reported Symptoms</h3>
                <p class="text-gray-700 text-sm">{{ $scan->symptoms_reported }}</p>
            </div>
            @endif

            {{-- Detected conditions --}}
            @if($scan->detected_conditions && count($scan->detected_conditions))
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-800 mb-4">Detected Conditions</h3>
                <div class="space-y-3">
                    @foreach($scan->detected_conditions as $condition)
                    <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-bold text-gray-800">{{ $condition['name'] }}</p>
                            @if(!empty($condition['description']))
                                <p class="text-sm text-gray-600 mt-1">{{ $condition['description'] }}</p>
                            @endif
                            @php
                                $sev = $condition['severity'] ?? 'mild';
                                $sevColor = $sev === 'severe' ? 'red' : ($sev === 'moderate' ? 'yellow' : 'green');
                            @endphp
                            <span class="mt-1 inline-block px-2 py-0.5 text-xs font-semibold rounded-full bg-{{ $sevColor }}-100 text-{{ $sevColor }}-800">{{ ucfirst($sev) }}</span>
                        </div>
                        <div class="ml-4 text-right shrink-0">
                            <div class="text-2xl font-black" style="color:#11455b;">{{ $condition['probability'] ?? 0 }}%</div>
                            <p class="text-xs text-gray-400">probability</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Recommendations --}}
            @if($scan->recommendations)
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-800 mb-4">Recommended Actions</h3>
                <ul class="space-y-2">
                    @foreach(explode("\n", $scan->recommendations) as $rec)
                        @if(trim($rec))
                        <li class="flex items-start gap-2">
                            <span class="text-green-500 font-bold mt-0.5">→</span>
                            <span class="text-gray-700 text-sm">{{ trim($rec) }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @endif

            {{-- Admin notes / raw JSON (debug) --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-bold text-gray-700 mb-3 text-sm uppercase tracking-wide">Scan Metadata</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div><span class="text-gray-500">Status:</span> <span class="font-semibold text-gray-800">{{ ucfirst($scan->status) }}</span></div>
                    <div><span class="text-gray-500">Urgency:</span> <span class="font-semibold text-gray-800">{{ ucfirst($scan->urgency_level ?? '—') }}</span></div>
                    <div><span class="text-gray-500">AI Model:</span> <span class="font-semibold text-gray-800">{{ $scan->ai_model ?? '—' }}</span></div>
                    <div><span class="text-gray-500">Confidence:</span> <span class="font-semibold text-gray-800">{{ $scan->confidence_score ? $scan->confidence_score.'%' : '—' }}</span></div>
                    <div><span class="text-gray-500">Scan ID:</span> <span class="font-semibold text-gray-800">#{{ $scan->id }}</span></div>
                    <div><span class="text-gray-500">Updated:</span> <span class="font-semibold text-gray-800">{{ $scan->updated_at->format('d M Y H:i') }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
