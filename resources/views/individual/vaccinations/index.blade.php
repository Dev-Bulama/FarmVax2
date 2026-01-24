@extends('layouts.farmer')

@section('title', 'Vaccinations')
@section('page-title', 'Vaccination Records')
@section('page-subtitle', 'Track all vaccination history for your livestock')

@section('content')
<div class="p-6">

    @php
        $allVaccinations = \App\Models\VaccinationHistory::whereHas('livestock', function($q) {
            $q->where('user_id', auth()->id());
        })->with('livestock')->orderBy('vaccination_date', 'desc')->paginate(15);
        
        $upcomingCount = \App\Models\VaccinationHistory::whereHas('livestock', function($q) {
            $q->where('user_id', auth()->id());
        })->where('next_booster_due_date', '>=', now())
        ->where('next_booster_due_date', '<=', now()->addDays(30))->count();
        
        $overdueCount = \App\Models\VaccinationHistory::whereHas('livestock', function($q) {
            $q->where('user_id', auth()->id());
        })->where('next_booster_due_date', '<', now())->count();
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Total Vaccinations</p>
                    <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $allVaccinations->total() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Due Soon (30 days)</p>
                    <h3 class="text-3xl font-bold text-yellow-600 mt-2">{{ $upcomingCount }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-semibold">Overdue</p>
                    <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $overdueCount }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Vaccination List -->
    @if($allVaccinations->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Animal</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Vaccine</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date Given</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Next Due</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($allVaccinations as $vaccination)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-green-600 font-bold text-sm">{{ strtoupper(substr($vaccination->livestock->livestock_type ?? 'L', 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $vaccination->livestock->tag_number ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-600 capitalize">{{ $vaccination->livestock->livestock_type ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $vaccination->vaccine_name }}</p>
                                @if($vaccination->batch_number)
                                    <p class="text-xs text-gray-600">Batch: {{ $vaccination->batch_number }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($vaccination->vaccination_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if(($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date))
                                    <p class="font-semibold {{ \Carbon\Carbon::parse(($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date))->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ \Carbon\Carbon::parse(($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date))->format('M d, Y') }}
                                    </p>
                                    <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse(($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date))->diffForHumans() }}</p>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if(($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date))
                                    @if(\Carbon\Carbon::parse(($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date))->isPast())
                                        <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Overdue</span>
                                    @elseif(\Carbon\Carbon::parse(($vaccination->next_booster_due_date ?? $vaccination->next_dose_due_date))->diffInDays() <= 30)
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Due Soon</span>
                                    @else
                                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Up to Date</span>
                                    @endif
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">Completed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $allVaccinations->links() }}</div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-gray-900">No Vaccination Records Yet</h3>
            <p class="mt-2 text-gray-600">Vaccination records will appear here once you add them.</p>
        </div>
    @endif

</div>
@endsection