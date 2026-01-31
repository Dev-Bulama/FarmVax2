@extends('layouts.admin')

@section('title', 'Update Details - v' . $version->version)
@section('page-title', 'Update Details - v' . $version->version)

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.system-updates.index') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to System Updates
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left Column - Version Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full mx-auto flex items-center justify-center mb-4">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Version {{ $version->version }}</h3>

                <!-- Status Badge -->
                <div class="mt-3">
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $version->status_badge_color }}">
                        {{ ucfirst($version->status) }}
                    </span>
                    @if($version->is_current)
                        <span class="ml-1 px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            Current
                        </span>
                    @endif
                </div>
            </div>

            <hr class="my-6">

            <!-- Version Details -->
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Release Name</p>
                    <p class="text-sm text-gray-900">{{ $version->release_name ?? 'N/A' }}</p>
                </div>

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Upload Date</p>
                    <p class="text-sm text-gray-900">{{ $version->created_at->format('M d, Y h:i A') }}</p>
                </div>

                @if($version->applied_at)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Applied Date</p>
                        <p class="text-sm text-gray-900">{{ $version->applied_at->format('M d, Y h:i A') }}</p>
                    </div>
                @endif

                @if($version->appliedBy)
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-semibold">Applied By</p>
                        <p class="text-sm text-gray-900">{{ $version->appliedBy->name }}</p>
                    </div>
                @endif

                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">File Size</p>
                    <p class="text-sm text-gray-900">{{ $version->update_file_size ? number_format($version->update_file_size / 1048576, 2) . ' MB' : 'N/A' }}</p>
                </div>
            </div>

            <hr class="my-6">

            <!-- Pre-Apply Checklist -->
            @if($version->status === 'pending')
                <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-xs font-semibold text-yellow-800 mb-2">✓ Pre-Apply Checklist:</p>
                    <ul class="text-xs text-yellow-700 space-y-1">
                        <li>✓ Database backup created</li>
                        <li>✓ Files backup created</li>
                        <li>✓ Storage permissions verified</li>
                        <li>✓ Low-traffic period scheduled</li>
                    </ul>
                </div>
            @endif

            <!-- Actions -->
            @if($version->status === 'pending')
                <form action="{{ route('admin.system-updates.apply', $version->id) }}" method="POST" onsubmit="return confirm('⚠️ FINAL CONFIRMATION\n\nHave you:\n✓ Created a database backup?\n✓ Created a files backup?\n✓ Verified storage permissions?\n✓ Informed users about maintenance?\n\nClick OK to proceed with update.');">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        ✓ Apply This Update
                    </button>
                </form>
            @endif

            @if(!$version->is_current && $version->status !== 'applied')
                <form action="{{ route('admin.system-updates.destroy', $version->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Delete this update?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                        ✗ Delete Update
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Right Column - Details -->
    <div class="lg:col-span-2 space-y-6">

        <!-- Instructions (for pending updates) -->
        @if($version->status === 'pending')
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-lg p-6">
                <div class="flex items-start">
                    <svg class="h-6 w-6 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-bold mb-2">📋 Before Applying This Update</h3>
                        <ol class="list-decimal list-inside space-y-2 text-sm">
                            <li><strong>Create Database Backup:</strong> Export full database using phpMyAdmin or command: <code class="bg-blue-700 px-2 py-0.5 rounded">mysqldump -u user -p database > backup.sql</code></li>
                            <li><strong>Create Files Backup:</strong> Download or copy entire application directory</li>
                            <li><strong>Verify Storage Permissions:</strong> Ensure these directories are writable (755):
                                <ul class="list-disc list-inside ml-4 mt-1">
                                    <li><code class="bg-blue-700 px-2 py-0.5 rounded">storage/app/private/system-updates/</code></li>
                                    <li><code class="bg-blue-700 px-2 py-0.5 rounded">app/, config/, public/, resources/, routes/</code></li>
                                </ul>
                            </li>
                            <li><strong>Review Changelog:</strong> Read the changes below to understand what will be updated</li>
                            <li><strong>Schedule Maintenance Window:</strong> Apply during low-traffic periods</li>
                            <li><strong>Inform Users:</strong> Notify users about scheduled maintenance if applicable</li>
                        </ol>
                        <div class="mt-4 p-3 bg-blue-700 rounded">
                            <p class="text-xs"><strong>💡 Tip:</strong> Once you click "Apply This Update," the system will automatically extract files, run migrations (if needed), and clear caches. The process typically takes 1-5 minutes depending on update size.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Description -->
        @if($version->description)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Description</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $version->description }}</p>
                </div>
            </div>
        @endif

        <!-- Changelog -->
        @if($version->changelog)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Changelog</h3>
                </div>
                <div class="p-6">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap font-mono bg-gray-50 p-4 rounded">{{ $version->changelog }}</pre>
                </div>
            </div>
        @endif

        <!-- Update Options -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Update Configuration</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 {{ $version->requires_migration ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Database Migration</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 {{ $version->requires_cache_clear ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">Cache Clear</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 {{ $version->requires_restart ? 'text-green-500' : 'text-gray-300' }} mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm text-gray-700">System Restart</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Log (if failed) -->
        @if($version->status === 'failed' && $version->error_log)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg shadow">
                <div class="px-6 py-4 border-b border-red-200">
                    <h3 class="text-lg font-bold text-red-900">Error Log</h3>
                </div>
                <div class="p-6">
                    <pre class="text-sm text-red-700 whitespace-pre-wrap font-mono bg-red-100 p-4 rounded overflow-x-auto">{{ $version->error_log }}</pre>
                </div>
            </div>
        @endif

        <!-- Backup Info (if applied) -->
        @if($version->status === 'applied' && $version->backup_info)
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Backup Information</h3>
                </div>
                <div class="p-6">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap font-mono bg-gray-50 p-4 rounded">{{ json_encode($version->backup_info, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
        @endif

    </div>

</div>

@endsection
