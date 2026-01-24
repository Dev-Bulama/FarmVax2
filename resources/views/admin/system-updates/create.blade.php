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

<!-- Warning Box -->
<div class="mt-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
    <div class="flex">
        <svg class="h-5 w-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <div>
            <h4 class="text-red-800 font-semibold">Warning:</h4>
            <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                <li>Ensure the ZIP file contains only application files (app/, resources/, database/, etc.)</li>
                <li>DO NOT include .env file, storage/app, or storage/framework directories</li>
                <li>Test the update package in a development/staging environment first</li>
                <li>Always backup database and files before applying updates to production</li>
                <li>Update will NOT affect user accounts, credentials, roles, or existing data</li>
            </ul>
        </div>
    </div>
</div>

@endsection
