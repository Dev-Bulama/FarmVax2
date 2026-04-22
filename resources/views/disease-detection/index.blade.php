@extends('layouts.farmer')
@section('title', 'AI Disease Scans')
@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold" style="color:#11455b;">🔬 AI Disease Detection</h1>
            <p class="text-gray-500 mt-1">Scan your animals for disease using AI-powered image analysis</p>
        </div>
        <a href="{{ route('disease-detection.create') }}"
           class="px-5 py-3 text-white font-bold rounded-xl shadow" style="background:#2fcb6e;">
            + New Scan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    @if($scans->count())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($scans as $scan)
            <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
                <div class="flex">
                    {{-- Thumbnail --}}
                    <div class="w-32 shrink-0 bg-gray-100">
                        <img src="{{ asset('storage/' . $scan->image_path) }}" alt="scan"
                             class="w-full h-full object-cover" style="min-height:100px;"
                             onerror="this.src='{{ asset('images/default-livestock.png') }}'">
                    </div>
                    <div class="p-4 flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            @if($scan->status === 'completed')
                                @if($scan->is_sick)
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">⚠️ Issues Detected</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">✅ Healthy</span>
                                @endif
                                {!! $scan->urgency_badge !!}
                            @elseif($scan->status === 'processing')
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">⏳ Processing</span>
                            @else
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600">Failed</span>
                            @endif
                        </div>
                        <p class="font-bold text-gray-800">{{ ucfirst($scan->animal_type) }}</p>
                        @if($scan->livestock)
                            <p class="text-xs text-gray-500">{{ $scan->livestock->tag_number ?? ucfirst($scan->livestock->livestock_type) }}</p>
                        @endif
                        @if($scan->confidence_score)
                            <p class="text-xs text-gray-400 mt-1">AI Confidence: {{ $scan->confidence_score }}%</p>
                        @endif
                        <p class="text-xs text-gray-400">{{ $scan->created_at->diffForHumans() }}</p>
                        <a href="{{ route('disease-detection.show', $scan->id) }}"
                           class="mt-2 inline-block text-sm font-semibold" style="color:#11455b;">View Results →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $scans->links() }}</div>
    @else
        <div class="text-center py-16 bg-white rounded-xl shadow">
            <div class="text-6xl mb-4">🔬</div>
            <h3 class="text-xl font-bold text-gray-700">No scans yet</h3>
            <p class="text-gray-500 mt-2">Upload a photo of any animal to detect diseases instantly with AI.</p>
            <a href="{{ route('disease-detection.create') }}"
               class="mt-6 inline-block px-6 py-3 text-white font-bold rounded-xl" style="background:#2fcb6e;">
                Start Your First Scan
            </a>
        </div>
    @endif
</div>
@endsection
