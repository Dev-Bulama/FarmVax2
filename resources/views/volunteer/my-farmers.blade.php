@extends('layouts.volunteer')

@section('title', 'My Farmers')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-3xl font-bold" style="color: #11455b;">My Farmers</h1>
                <p class="text-gray-600 mt-1">Farmers you've enrolled into FarmVax</p>
            </div>
            <a href="{{ route('volunteer.enroll.farmer') }}" 
               class="inline-flex items-center px-6 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
               style="background-color: #2fcb6e;">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Enroll New Farmer
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        
        <div class="bg-white rounded-lg shadow p-6 border-l-4" style="border-color: #2fcb6e;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Total Farmers</p>
                    <h3 class="text-3xl font-bold mt-1" style="color: #11455b;">{{ $farmers->total() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(47, 203, 110, 0.1);">
                    <i class="fas fa-users text-2xl" style="color: #2fcb6e;"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">This Month</p>
                    <h3 class="text-3xl font-bold text-blue-600 mt-1">
                        {{ $farmers->filter(function($f) { 
                            return $f->created_at->month == now()->month; 
                        })->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Points Earned</p>
                    <h3 class="text-3xl font-bold text-yellow-600 mt-1">{{ $farmers->total() * 10 }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- Farmers List --}}
    @if($farmers->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b" style="background-color: rgba(17, 69, 91, 0.03);">
                <h2 class="text-lg font-bold" style="color: #11455b;">📋 Enrolled Farmers ({{ $farmers->total() }})</h2>
            </div>

            {{-- Desktop Table View --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Farmer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($farmers as $enrollment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: #2fcb6e;">
                                            <span class="text-white font-bold">{{ substr($enrollment->farmer->name ?? 'F', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold" style="color: #11455b;">
                                                {{ $enrollment->farmer->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $enrollment->farmer->email ?? 'No email' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $enrollment->farmer->phone ?? 'Not provided' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $enrollment->location ?? $enrollment->farmer->address ?? 'Not specified' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $enrollment->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $enrollment->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold" style="color: #2fcb6e;">+10</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($farmers as $enrollment)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-3" style="background-color: #2fcb6e;">
                                    <span class="text-white font-bold text-lg">{{ substr($enrollment->farmer->name ?? 'F', 0, 1) }}</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold" style="color: #11455b;">{{ $enrollment->farmer->name ?? 'N/A' }}</h4>
                                    <p class="text-xs text-gray-500">{{ $enrollment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                        <div class="space-y-1 text-sm">
                            <p class="text-gray-600">
                                <i class="fas fa-envelope mr-2" style="color: #2fcb6e;"></i>
                                {{ $enrollment->farmer->email ?? 'No email' }}
                            </p>
                            <p class="text-gray-600">
                                <i class="fas fa-phone mr-2" style="color: #2fcb6e;"></i>
                                {{ $enrollment->farmer->phone ?? 'Not provided' }}
                            </p>
                            <p class="text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2" style="color: #2fcb6e;"></i>
                                {{ $enrollment->location ?? 'Not specified' }}
                            </p>
                        </div>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Enrolled: {{ $enrollment->created_at->format('M d, Y') }}</span>
                            <span class="text-sm font-bold" style="color: #2fcb6e;">+10 pts</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $farmers->links() }}
            </div>
        </div>

    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <h3 class="text-xl font-bold mb-2" style="color: #11455b;">No Farmers Enrolled Yet</h3>
            <p class="text-gray-600 mb-6">Start making an impact by enrolling your first farmer</p>
            <a href="{{ route('volunteer.enroll.farmer') }}" 
               class="inline-flex items-center px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
               style="background-color: #2fcb6e;">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Enroll Your First Farmer
            </a>
        </div>
    @endif

</div>

@endsection