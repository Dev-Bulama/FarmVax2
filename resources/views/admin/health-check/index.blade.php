@extends('layouts.admin')

@section('title', 'System Health & Diagnostics')
@section('page-title', 'System Health & Diagnostics')

@section('content')

<!-- Overall Status Banner -->
<div class="mb-6 p-6 rounded-lg shadow-lg {{ $overallStatus['color'] === 'green' ? 'bg-green-50 border-l-4 border-green-500' : ($overallStatus['color'] === 'yellow' ? 'bg-yellow-50 border-l-4 border-yellow-500' : 'bg-red-50 border-l-4 border-red-500') }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            @if($overallStatus['color'] === 'green')
                <svg class="h-12 w-12 text-green-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @elseif($overallStatus['color'] === 'yellow')
                <svg class="h-12 w-12 text-yellow-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            @else
                <svg class="h-12 w-12 text-red-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @endif
            <div>
                <h2 class="text-2xl font-bold {{ $overallStatus['color'] === 'green' ? 'text-green-900' : ($overallStatus['color'] === 'yellow' ? 'text-yellow-900' : 'text-red-900') }}">
                    {{ ucfirst($overallStatus['status']) }}
                </h2>
                <p class="text-sm {{ $overallStatus['color'] === 'green' ? 'text-green-700' : ($overallStatus['color'] === 'yellow' ? 'text-yellow-700' : 'text-red-700') }}">
                    {{ $overallStatus['message'] }}
                </p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500">Last checked: {{ now()->format('Y-m-d H:i:s') }}</p>
            <form action="{{ route('admin.health-check.diagnostic') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                    Run Full Diagnostic
                </button>
            </form>
        </div>
    </div>
</div>

<!-- System Checks Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">

    <!-- Database Health -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Database</h3>
            @if($health['database']['status'] === 'healthy')
                <span class="flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
            @elseif($health['database']['status'] === 'warning')
                <span class="inline-flex h-3 w-3 rounded-full bg-yellow-500"></span>
            @else
                <span class="inline-flex h-3 w-3 rounded-full bg-red-500"></span>
            @endif
        </div>
        <p class="text-sm text-gray-600 mb-2">{{ $health['database']['message'] }}</p>
        <p class="text-xs text-gray-500">{{ $health['database']['details'] }}</p>
    </div>

    <!-- Storage Health -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Storage</h3>
            @if($health['storage']['status'] === 'healthy')
                <span class="inline-flex h-3 w-3 rounded-full bg-green-500"></span>
            @elseif($health['storage']['status'] === 'warning')
                <span class="inline-flex h-3 w-3 rounded-full bg-yellow-500"></span>
            @else
                <span class="inline-flex h-3 w-3 rounded-full bg-red-500"></span>
            @endif
        </div>
        <p class="text-sm text-gray-600 mb-2">{{ $health['storage']['message'] }}</p>
        <p class="text-xs text-gray-500">{{ $health['storage']['details'] }}</p>
    </div>

    <!-- Cache Health -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Cache</h3>
            @if($health['cache']['status'] === 'healthy')
                <span class="inline-flex h-3 w-3 rounded-full bg-green-500"></span>
            @elseif($health['cache']['status'] === 'warning')
                <span class="inline-flex h-3 w-3 rounded-full bg-yellow-500"></span>
            @else
                <span class="inline-flex h-3 w-3 rounded-full bg-red-500"></span>
            @endif
        </div>
        <p class="text-sm text-gray-600 mb-2">{{ $health['cache']['message'] }}</p>
        <p class="text-xs text-gray-500">{{ $health['cache']['details'] }}</p>
    </div>

    <!-- Email Health -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Email Service</h3>
            @if($health['email']['status'] === 'healthy')
                <span class="inline-flex h-3 w-3 rounded-full bg-green-500"></span>
            @elseif($health['email']['status'] === 'warning')
                <span class="inline-flex h-3 w-3 rounded-full bg-yellow-500"></span>
            @else
                <span class="inline-flex h-3 w-3 rounded-full bg-red-500"></span>
            @endif
        </div>
        <p class="text-sm text-gray-600 mb-2">{{ $health['email']['message'] }}</p>
        <p class="text-xs text-gray-500">{{ $health['email']['details'] }}</p>
    </div>

    <!-- SMS Health -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">SMS Service</h3>
            @if($health['sms']['status'] === 'healthy')
                <span class="inline-flex h-3 w-3 rounded-full bg-green-500"></span>
            @elseif($health['sms']['status'] === 'warning')
                <span class="inline-flex h-3 w-3 rounded-full bg-yellow-500"></span>
            @else
                <span class="inline-flex h-3 w-3 rounded-full bg-red-500"></span>
            @endif
        </div>
        <p class="text-sm text-gray-600 mb-2">{{ $health['sms']['message'] }}</p>
        <p class="text-xs text-gray-500">{{ $health['sms']['details'] }}</p>
    </div>

    <!-- Permissions Health -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900">Permissions</h3>
            @if($health['permissions']['status'] === 'healthy')
                <span class="inline-flex h-3 w-3 rounded-full bg-green-500"></span>
            @elseif($health['permissions']['status'] === 'warning')
                <span class="inline-flex h-3 w-3 rounded-full bg-yellow-500"></span>
            @else
                <span class="inline-flex h-3 w-3 rounded-full bg-red-500"></span>
            @endif
        </div>
        <p class="text-sm text-gray-600 mb-2">{{ $health['permissions']['message'] }}</p>
        <p class="text-xs text-gray-500">{{ $health['permissions']['details'] }}</p>
    </div>

</div>

<!-- Broken Features Alert -->
@if(count($health['brokenFeatures']) > 0)
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex items-center mb-4">
        <svg class="h-6 w-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <h3 class="text-lg font-bold text-gray-900">Broken Features Detected ({{ count($health['brokenFeatures']) }})</h3>
    </div>

    <div class="space-y-3">
        @foreach($health['brokenFeatures'] as $broken)
            <div class="p-4 border-l-4 {{ $broken['severity'] === 'critical' ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50' }} rounded">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-sm {{ $broken['severity'] === 'critical' ? 'text-red-900' : 'text-yellow-900' }}">
                            {{ $broken['feature'] }}
                        </p>
                        <p class="text-sm {{ $broken['severity'] === 'critical' ? 'text-red-700' : 'text-yellow-700' }} mt-1">
                            {{ $broken['issue'] }}
                        </p>
                        <p class="text-xs {{ $broken['severity'] === 'critical' ? 'text-red-600' : 'text-yellow-600' }} mt-2">
                            <strong>Fix:</strong> {{ $broken['fix'] }}
                        </p>
                    </div>
                    <span class="ml-4 px-2 py-1 text-xs font-semibold rounded {{ $broken['severity'] === 'critical' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800' }}">
                        {{ ucfirst($broken['severity']) }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Recent Errors -->
@if($health['recentErrors']['count'] > 0)
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Recent Errors ({{ $health['recentErrors']['count'] }} in last 100 log lines)</h3>

    @if(count($health['recentErrors']['errors']) > 0)
        <div class="space-y-2">
            @foreach($health['recentErrors']['errors'] as $error)
                <div class="p-3 bg-red-50 border-l-4 border-red-400 rounded text-xs font-mono text-red-900">
                    {{ $error }}
                </div>
            @endforeach
        </div>
        @if($health['recentErrors']['count'] > 5)
            <p class="text-sm text-gray-500 mt-3">+ {{ $health['recentErrors']['count'] - 5 }} more errors in logs</p>
        @endif
    @endif
</div>
@endif

<!-- System Statistics -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">System Statistics</h3>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <p class="text-3xl font-bold text-blue-600">{{ number_format($health['systemStats']['users_total']) }}</p>
            <p class="text-sm text-gray-600">Total Users</p>
        </div>

        <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-3xl font-bold text-green-600">{{ number_format($health['systemStats']['farmers']) }}</p>
            <p class="text-sm text-gray-600">Farmers</p>
        </div>

        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <p class="text-3xl font-bold text-purple-600">{{ number_format($health['systemStats']['professionals']) }}</p>
            <p class="text-sm text-gray-600">Professionals</p>
        </div>

        <div class="text-center p-4 bg-yellow-50 rounded-lg">
            <p class="text-3xl font-bold text-yellow-600">{{ number_format($health['systemStats']['volunteers']) }}</p>
            <p class="text-sm text-gray-600">Volunteers</p>
        </div>

        <div class="text-center p-4 bg-indigo-50 rounded-lg">
            <p class="text-3xl font-bold text-indigo-600">{{ number_format($health['systemStats']['livestock_total']) }}</p>
            <p class="text-sm text-gray-600">Total Livestock</p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-2xl font-bold text-gray-600">{{ number_format($health['systemStats']['users_today']) }}</p>
            <p class="text-xs text-gray-600">New Users Today</p>
        </div>

        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-2xl font-bold text-gray-600">{{ number_format($health['systemStats']['livestock_today']) }}</p>
            <p class="text-xs text-gray-600">Livestock Added Today</p>
        </div>

        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-2xl font-bold text-gray-600">{{ number_format($health['systemStats']['messages_sent']) }}</p>
            <p class="text-xs text-gray-600">Messages Sent</p>
        </div>

        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-2xl font-bold text-gray-600">{{ number_format($health['systemStats']['messages_pending']) }}</p>
            <p class="text-xs text-gray-600">Pending Messages</p>
        </div>
    </div>
</div>

@endsection
