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
@section('title', 'Scan Results')
@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('disease-detection.index') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-2">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Scans
        </a>
        <h1 class="text-3xl font-bold" style="color:#11455b;">🔬 AI Scan Results</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left — Photo + summary --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <img src="{{ asset('storage/' . $scan->image_path) }}" alt="Scanned animal"
                     class="w-full object-cover" style="max-height:280px;"
                     onerror="this.src='{{ asset('images/default-livestock.png') }}'">
                <div class="p-4">
                    <p class="font-semibold text-gray-700">{{ ucfirst($scan->animal_type) }}</p>
                    @if($scan->livestock)
                        <p class="text-sm text-gray-500">{{ $scan->livestock->tag_number ?? ucfirst($scan->livestock->livestock_type) }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-1">Scanned {{ $scan->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            @if($scan->status === 'completed')
            {{-- Confidence meter --}}
            <div class="bg-white rounded-xl shadow p-4">
                <p class="font-semibold text-gray-700 mb-2">AI Confidence</p>
                <div class="flex items-center gap-3">
                    <div class="flex-1 bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full {{ $scan->confidence_score >= 85 ? 'bg-green-500' : ($scan->confidence_score >= 65 ? 'bg-yellow-500' : 'bg-red-500') }}"
                             style="width:{{ $scan->confidence_score }}%"></div>
                    </div>
                    <span class="font-bold text-gray-800">{{ $scan->confidence_score }}%</span>
                </div>
                @if($scan->ai_model)
                    <p class="text-xs text-gray-400 mt-2">Model: {{ $scan->ai_model }}</p>
                @endif
            </div>
            @endif

            <form action="{{ route('disease-detection.destroy', $scan->id) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" onclick="return confirm('Delete this scan?')"
                        class="w-full py-2 text-sm text-red-600 border border-red-300 font-semibold rounded-xl hover:bg-red-50">
                    Delete Scan
                </button>
            </form>
        </div>

        {{-- Right — Results --}}
        <div class="lg:col-span-2 space-y-6">

            @if($scan->status === 'processing')
            <div class="bg-blue-50 rounded-xl shadow p-8 text-center">
                <div class="text-5xl mb-3">⏳</div>
                <p class="font-bold text-blue-800 text-xl">Analysis in progress…</p>
                <p class="text-blue-600 mt-2">Please wait a moment, then refresh the page.</p>
                <a href="{{ url()->current() }}" class="mt-4 inline-block px-5 py-2 bg-blue-600 text-white font-bold rounded-lg">Refresh</a>
            </div>

            @elseif($scan->status === 'failed')
            <div class="bg-red-50 rounded-xl shadow p-8 text-center">
                <div class="text-5xl mb-3">❌</div>
                <p class="font-bold text-red-800 text-xl">Analysis could not be completed</p>
                <p class="text-red-600 mt-2">{{ $scan->analysis_result }}</p>
                <p class="text-gray-500 text-sm mt-4">Please consult a veterinarian directly.</p>
            </div>

            @else
            {{-- Overall verdict --}}
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

            {{-- Detected conditions --}}
            @if($scan->detected_conditions && count($scan->detected_conditions))
            <div class="bg-white rounded-xl shadow p-6">
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
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-bold text-gray-800 mb-4">Recommended Actions</h3>
                <ul class="space-y-2">
                    @foreach(explode("\n", $scan->recommendations) as $rec)
                        @if(trim($rec))
                        <li class="flex items-start gap-2">
                            <span class="text-green-500 font-bold mt-0.5">→</span>
                            <span class="text-gray-700">{{ trim($rec) }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- CTA --}}
            <div class="bg-blue-50 rounded-xl p-5 flex items-center justify-between">
                <div>
                    <p class="font-bold text-blue-800">Need expert advice?</p>
                    <p class="text-sm text-blue-600">Request a live video consultation with a veterinarian.</p>
                </div>
                @if(\Illuminate\Support\Facades\Route::has($role . '.telemedicine.create'))
                <a href="{{ route($role . '.telemedicine.create') }}"
                   class="ml-4 px-4 py-2 bg-blue-600 text-white font-bold rounded-lg whitespace-nowrap">
                    📹 Book Vet Call
                </a>
                @elseif(\Illuminate\Support\Facades\Route::has('farmer.telemedicine.create'))
                <a href="{{ route('farmer.telemedicine.create') }}"
                   class="ml-4 px-4 py-2 bg-blue-600 text-white font-bold rounded-lg whitespace-nowrap">
                    📹 Book Vet Call
                </a>
                @endif
            </div>

            @endif
        </div>
    </div>
</div>
@endsection
