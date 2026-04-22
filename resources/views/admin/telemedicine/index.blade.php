@extends('layouts.admin')
@section('title', 'Telemedicine Management')
@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold" style="color:#11455b;">🎥 Telemedicine Management</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 rounded text-green-800 font-semibold">{{ session('success') }}</div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        @foreach(['total'=>['Total','gray'],'pending'=>['Pending','yellow'],'in_progress'=>['In Progress','green'],'completed'=>['Completed','blue'],'cancelled'=>['Cancelled','red']] as $key => [$label, $color])
        <div class="bg-white rounded-xl shadow p-5 text-center">
            <p class="text-sm font-semibold text-gray-500">{{ $label }}</p>
            <p class="text-3xl font-black text-{{ $color }}-600">{{ $stats[$key] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-6 flex flex-wrap gap-3">
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">All Statuses</option>
            @foreach(['pending','assigned','in_progress','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
            @endforeach
        </select>
        <select name="priority" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">All Priorities</option>
            <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search patient name…"
               class="px-3 py-2 border border-gray-300 rounded-lg text-sm flex-1 min-w-40">
        <button type="submit" class="px-5 py-2 text-white font-semibold rounded-lg" style="background:#11455b;">Filter</button>
        <a href="{{ route('admin.telemedicine.index') }}" class="px-5 py-2 border border-gray-300 text-gray-600 font-semibold rounded-lg">Reset</a>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">#</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Patient</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Vet</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Priority</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Date</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $req)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $req->id }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-800">{{ $req->requester->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $req->professional->name ?? '<span class="text-yellow-600">Unassigned</span>' }}</td>
                    <td class="px-4 py-3">{!! $req->status_badge !!}</td>
                    <td class="px-4 py-3">
                        @if($req->priority === 'urgent')
                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700">URGENT</span>
                        @else
                            <span class="text-gray-400 text-xs">Normal</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $req->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.telemedicine.show', $req->id) }}" class="text-blue-600 font-semibold hover:underline">Manage</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-10 text-gray-400">No consultation requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $requests->links() }}</div>
</div>
@endsection
