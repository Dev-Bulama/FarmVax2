@extends('layouts.admin')

@section('title', 'System Updates & Version Management')
@section('page-title', 'System Updates & Version Management')

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <p class="text-green-700">✓ {{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <p class="text-red-700">✗ {{ session('error') }}</p>
    </div>
@endif

<!-- Current Version & Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Current Version</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['current_version'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Updates</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_updates'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Successful</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['successful'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Failed</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['failed'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Upload New Update Button -->
<div class="mb-6">
    <a href="{{ route('admin.system-updates.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        Upload New Update
    </a>
</div>

<!-- Version History Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900">Version History</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Release Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Size</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($versions as $version)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-900">v{{ $version->version }}</span>
                                @if($version->is_current)
                                    <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Current
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $version->release_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $version->status_badge_color }}">
                                {{ ucfirst($version->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $version->applied_at ? $version->applied_at->format('M d, Y h:i A') : 'Not applied' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $version->update_file_size ? number_format($version->update_file_size / 1048576, 2) . ' MB' : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.system-updates.show', $version->id) }}" class="text-blue-600 hover:text-blue-900">View</a>

                            @if($version->status === 'pending')
                                <form action="{{ route('admin.system-updates.apply', $version->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to apply this update? This action cannot be undone.');">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Apply</button>
                                </form>
                            @endif

                            @if(!$version->is_current && $version->status !== 'applied')
                                <form action="{{ route('admin.system-updates.destroy', $version->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this update?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No version history available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($versions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $versions->links() }}
        </div>
    @endif
</div>

<!-- Important Notes -->
<div class="mt-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
    <div class="flex">
        <svg class="h-5 w-5 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div>
            <h4 class="text-yellow-800 font-semibold">Important Notes:</h4>
            <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside space-y-1">
                <li>Always backup your database and files before applying updates</li>
                <li>Updates should be tested in a staging environment first</li>
                <li>Applying updates may temporarily interrupt service</li>
                <li>Update files must be in ZIP format containing application files</li>
                <li>User accounts, credentials, and data are preserved during updates</li>
            </ul>
        </div>
    </div>
</div>

@endsection
