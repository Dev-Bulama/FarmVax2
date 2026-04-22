@extends('layouts.admin')
@section('title', 'AI Disease Detection Monitor')
@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold" style="color:#11455b;">🔬 AI Disease Detection Monitor</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow p-5 text-center"><p class="text-sm font-semibold text-gray-500">Total Scans</p><p class="text-3xl font-black text-gray-700">{{ $stats['total'] }}</p></div>
        <div class="bg-white rounded-xl shadow p-5 text-center"><p class="text-sm font-semibold text-gray-500">Issues Found</p><p class="text-3xl font-black text-red-600">{{ $stats['sick'] }}</p></div>
        <div class="bg-white rounded-xl shadow p-5 text-center"><p class="text-sm font-semibold text-gray-500">Healthy</p><p class="text-3xl font-black text-green-600">{{ $stats['healthy'] }}</p></div>
        <div class="bg-white rounded-xl shadow p-5 text-center"><p class="text-sm font-semibold text-gray-500">Critical</p><p class="text-3xl font-black text-red-800">{{ $stats['critical'] }}</p></div>
        <div class="bg-white rounded-xl shadow p-5 text-center"><p class="text-sm font-semibold text-gray-500">Pending</p><p class="text-3xl font-black text-blue-600">{{ $stats['pending'] }}</p></div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-6 flex flex-wrap gap-3">
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">All Statuses</option>
            @foreach(['processing','completed','failed'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="urgency" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">All Urgency</option>
            @foreach(['critical','high','medium','low'] as $u)
                <option value="{{ $u }}" {{ request('urgency') == $u ? 'selected' : '' }}>{{ ucfirst($u) }}</option>
            @endforeach
        </select>
        <select name="sick" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">Sick / Healthy</option>
            <option value="1" {{ request('sick') === '1' ? 'selected' : '' }}>Sick Only</option>
            <option value="0" {{ request('sick') === '0' ? 'selected' : '' }}>Healthy Only</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user name…"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm flex-1 min-w-40">
        <button type="submit" class="px-5 py-2 text-white font-semibold rounded-lg" style="background:#11455b;">Filter</button>
        <a href="{{ route('admin.disease-detection.index') }}" class="px-5 py-2 border border-gray-300 text-gray-600 font-semibold rounded-lg">Reset</a>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Image</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">User</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Animal</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Result</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Urgency</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Confidence</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Date</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($scans as $scan)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <img src="{{ asset('storage/' . $scan->image_path) }}" alt=""
                             class="w-14 h-14 rounded-lg object-cover"
                             onerror="this.src='{{ asset('images/default-livestock.png') }}'">
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $scan->user->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ ucfirst($scan->animal_type) }}</td>
                    <td class="px-4 py-3">
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
                    </td>
                    <td class="px-4 py-3">{!! $scan->urgency_badge !!}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $scan->confidence_score ? $scan->confidence_score.'%' : '—' }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $scan->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.disease-detection.show', $scan->id) }}" class="text-blue-600 font-semibold hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-gray-400">No scans found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $scans->links() }}</div>
</div>
@endsection
