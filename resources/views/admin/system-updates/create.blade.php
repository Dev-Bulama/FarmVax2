@extends('layouts.admin')

@section('title', 'Upload System Update')
@section('page-title', 'Upload System Update')

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

<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.system-updates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            <!-- Version Number -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Version Number <span class="text-red-500">*</span>
                </label>
                <input type="text" name="version" value="{{ old('version') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('version') border-red-500 @enderror"
                       placeholder="e.g., 1.1.0">
                <p class="text-xs text-gray-500 mt-1">Use semantic versioning (MAJOR.MINOR.PATCH)</p>
                @error('version')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Release Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Release Name
                </label>
                <input type="text" name="release_name" value="{{ old('release_name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="e.g., FarmVax Production Update Q1 2026">
                <p class="text-xs text-gray-500 mt-1">Optional: Provide a friendly name for this release</p>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Brief description of what this update includes...">{{ old('description') }}</textarea>
            </div>

            <!-- Changelog -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Changelog
                </label>
                <textarea name="changelog" rows="6"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                          placeholder="Detailed list of changes:&#10;- Fixed bulk SMS functionality&#10;- Added system update feature&#10;- Improved professional document display&#10;...">{{ old('changelog') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Detailed list of changes, bug fixes, and new features</p>
            </div>

            <!-- Update File -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Update File (ZIP) <span class="text-red-500">*</span>
                </label>
                <input type="file" name="update_file" accept=".zip" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('update_file') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Maximum file size: 500MB. Must be a ZIP file containing application files.</p>
                @error('update_file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Update Options -->
            <div class="border-t pt-6">
                <h4 class="text-sm font-medium text-gray-700 mb-4">Update Options</h4>

                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="requires_migration" value="1" {{ old('requires_migration') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">
                            Requires database migration
                            <span class="text-xs text-gray-500">(runs php artisan migrate)</span>
                        </span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="requires_cache_clear" value="1" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">
                            Clear application cache
                            <span class="text-xs text-gray-500">(recommended)</span>
                        </span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="requires_restart" value="1" {{ old('requires_restart') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">
                            Requires system restart
                            <span class="text-xs text-gray-500">(for major updates)</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.system-updates.index') }}"
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                Upload Update
            </button>
        </div>
    </form>
</div>

<!-- Instructions Box -->
<div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
    <div class="flex">
        <svg class="h-5 w-5 text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <div class="flex-1">
            <h4 class="text-blue-800 font-semibold mb-2">📋 How to Prepare and Apply System Updates</h4>
            <div class="text-sm text-blue-900 space-y-3">
                <div>
                    <p class="font-semibold mb-1">Step 1: Prepare the Update ZIP File</p>
                    <ul class="list-disc list-inside space-y-1 ml-2 text-blue-800">
                        <li>Create a ZIP file containing ONLY the application files (app/, config/, database/, public/, resources/, routes/)</li>
                        <li><strong>DO NOT include:</strong> .env file, storage/ directory, vendor/ directory, node_modules/, or .git/</li>
                        <li>Maintain the exact directory structure (files should be in root of ZIP, not nested in a folder)</li>
                        <li>Example correct structure: update.zip → app/, config/, public/, resources/, etc.</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold mb-1">Step 2: Upload the Update (This Page)</p>
                    <ul class="list-disc list-inside space-y-1 ml-2 text-blue-800">
                        <li>Fill in the version number using semantic versioning (e.g., 1.2.0)</li>
                        <li>Add a clear description and changelog</li>
                        <li>Upload your prepared ZIP file (max 500MB)</li>
                        <li>Check appropriate options (migration, cache clear, restart)</li>
                        <li>Click "Upload Update" - this only UPLOADS, does not apply yet</li>
                    </ul>
                </div>

                <div>
                    <p class="font-semibold mb-1">Step 3: Apply the Update</p>
                    <ul class="list-disc list-inside space-y-1 ml-2 text-blue-800">
                        <li>Go to System Updates list and find your uploaded update</li>
                        <li>Review the details carefully</li>
                        <li>Click "Apply Update" button to install</li>
                        <li>The system will extract files, run migrations (if needed), and clear cache</li>
                        <li>Wait for success confirmation before using the system</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Warning Box -->
<div class="mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
    <div class="flex">
        <svg class="h-5 w-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <div class="flex-1">
            <h4 class="text-red-800 font-semibold mb-2">⚠️ Important Warnings:</h4>
            <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                <li><strong>ALWAYS backup database and files before applying updates to production</strong></li>
                <li>Test the update package in a development/staging environment first</li>
                <li>Ensure server has write permissions for: app/, config/, public/, resources/, routes/, database/</li>
                <li>Schedule updates during low-traffic periods to minimize user impact</li>
                <li>Update will NOT affect user data, but may modify database schema if migrations are included</li>
                <li>If update fails, the system will rollback changes - check error logs for details</li>
            </ul>
        </div>
    </div>
</div>

<!-- Troubleshooting Box -->
<div class="mt-4 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
    <div class="flex">
        <svg class="h-5 w-5 text-yellow-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <div class="flex-1">
            <h4 class="text-yellow-800 font-semibold mb-2">🔧 Common Issues & Solutions:</h4>
            <div class="text-sm text-yellow-900 space-y-2">
                <div>
                    <p class="font-semibold">Issue: "Update file not found" error when applying</p>
                    <p class="ml-2">→ Solution: Ensure storage/app/private/system-updates/ directory exists with write permissions (755)</p>
                </div>
                <div>
                    <p class="font-semibold">Issue: Upload fails or times out</p>
                    <p class="ml-2">→ Solution: Check PHP upload_max_filesize and post_max_size settings (should be at least 512M)</p>
                </div>
                <div>
                    <p class="font-semibold">Issue: Update applies but site shows errors</p>
                    <p class="ml-2">→ Solution: Run manually: php artisan cache:clear && php artisan config:clear && php artisan view:clear</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
