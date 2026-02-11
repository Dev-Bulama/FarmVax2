<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SystemUpdateController extends Controller
{
    /**
     * Display system versions and update interface
     */
    public function index()
    {
        $currentVersion = SystemVersion::getCurrentVersion();
        $versions = SystemVersion::latest()->paginate(20);

        $stats = [
            'current_version' => $currentVersion?->version ?? 'Unknown',
            'total_updates' => SystemVersion::count(),
            'successful' => SystemVersion::where('status', 'applied')->count(),
            'failed' => SystemVersion::where('status', 'failed')->count(),
        ];

        return view('admin.system-updates.index', compact('currentVersion', 'versions', 'stats'));
    }

    /**
     * Show upload form for new update
     */
    public function create()
    {
        return view('admin.system-updates.create');
    }

    /**
     * Store a new system update
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'version' => 'required|string|max:20|unique:system_versions,version',
            'release_name' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'changelog' => 'nullable|string',
            'update_file' => 'required|file|mimes:zip|max:512000', // Max 500MB
            'requires_migration' => 'nullable|boolean',
            'requires_cache_clear' => 'nullable|boolean',
            'requires_restart' => 'nullable|boolean',
        ]);

        try {
            // Ensure the system-updates directory exists
            $updateDir = storage_path('app/private/system-updates');
            if (!File::exists($updateDir)) {
                File::makeDirectory($updateDir, 0755, true);
            }

            $file = $request->file('update_file');
            $fileName = 'update_' . $validated['version'] . '_' . time() . '.zip';
            $filePath = $file->storeAs('system-updates', $fileName, 'local');

            // Verify file was stored successfully
            if (!$filePath || !Storage::disk('local')->exists($filePath)) {
                throw new \Exception('Failed to store update file. Please check storage permissions for: ' . $updateDir);
            }

            $version = SystemVersion::create([
                'version' => $validated['version'],
                'release_name' => $validated['release_name'],
                'description' => $validated['description'],
                'changelog' => $validated['changelog'],
                'update_file_path' => $filePath,
                'update_file_size' => $file->getSize(),
                'status' => 'pending',
                'requires_migration' => $request->has('requires_migration'),
                'requires_cache_clear' => $request->has('requires_cache_clear') ? true : false,
                'requires_restart' => $request->has('requires_restart'),
            ]);

            return redirect()->route('admin.system-updates.index')
                ->with('success', 'System update uploaded successfully! Version: ' . $validated['version']);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Failed to upload update: ' . $e->getMessage());
        }
    }

    /**
     * Show details of a specific version
     */
    public function show($id)
    {
        $version = SystemVersion::with('appliedBy')->findOrFail($id);
        return view('admin.system-updates.show', compact('version'));
    }

    /**
     * Apply a system update
     */
    public function apply(Request $request, $id)
    {
        $version = SystemVersion::findOrFail($id);

        if ($version->status === 'applied') {
            return back()->with('error', 'This update has already been applied.');
        }

        DB::beginTransaction();
        try {
            // Create backup info
            $backupInfo = [
                'backup_time' => now()->toIso8601String(),
                'backup_note' => 'Automatic backup before update to version ' . $version->version,
            ];

            // Get the update file using Storage facade (disk-agnostic approach)
            $updateFilePath = Storage::disk('local')->path($version->update_file_path);

            if (!file_exists($updateFilePath)) {
                throw new \Exception('Update file not found: ' . $updateFilePath . ' (stored as: ' . $version->update_file_path . ')');
            }

            // Extract update to temporary directory
            $tempDir = storage_path('app/temp-update-' . time());
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            $zip = new ZipArchive;
            if ($zip->open($updateFilePath) === TRUE) {
                $zip->extractTo($tempDir);
                $zip->close();
            } else {
                throw new \Exception('Failed to extract update ZIP file');
            }

            // Apply update: Copy files from temp directory to application root
            $filesCopied = $this->copyUpdateFiles($tempDir, base_path());

            // Run migrations if required
            if ($version->requires_migration) {
                Artisan::call('migrate', ['--force' => true]);
                $backupInfo['migration_output'] = Artisan::output();
            }

            // Clear cache if required
            if ($version->requires_cache_clear) {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
            }

            // Clean up temp directory
            File::deleteDirectory($tempDir);

            // Mark this version as current and applied
            $version->update([
                'status' => 'applied',
                'applied_at' => now(),
                'applied_by' => auth()->id(),
                'backup_info' => $backupInfo,
            ]);

            $version->markAsCurrent();

            DB::commit();

            \Log::info('System updated to version ' . $version->version, [
                'applied_by' => auth()->user()->email,
                'timestamp' => now()
            ]);

            return redirect()->route('admin.system-updates.index')
                ->with('success', 'System successfully updated to version ' . $version->version . '! (' . $filesCopied . ' files updated)');

        } catch (\Exception $e) {
            DB::rollBack();

            // Log error
            $version->update([
                'status' => 'failed',
                'error_log' => $e->getMessage() . "\n" . $e->getTraceAsString(),
            ]);

            \Log::error('System update failed', [
                'version' => $version->version,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a pending update
     */
    public function destroy($id)
    {
        $version = SystemVersion::findOrFail($id);

        if ($version->status === 'applied' && $version->is_current) {
            return back()->with('error', 'Cannot delete the current active version.');
        }

        // Delete update file
        if ($version->update_file_path && Storage::disk('local')->exists($version->update_file_path)) {
            Storage::disk('local')->delete($version->update_file_path);
        }

        $version->delete();

        return redirect()->route('admin.system-updates.index')
            ->with('success', 'Update deleted successfully.');
    }

    /**
     * Copy update files from temp directory to application
     */
    protected function copyUpdateFiles($source, $destination)
    {
        if (!File::exists($source)) {
            throw new \Exception('Source directory does not exist: ' . $source);
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $copied = 0;
        $skippedFiles = [];
        $failedFiles = [];

        // Sensitive files/dirs to never overwrite
        $skipPatterns = ['.env', '.git', 'storage/app', 'storage/framework', 'storage/logs'];

        foreach ($iterator as $item) {
            $subPath = $iterator->getSubPathName();
            $targetPath = $destination . DIRECTORY_SEPARATOR . $subPath;

            if ($item->isDir()) {
                if (!File::exists($targetPath)) {
                    File::makeDirectory($targetPath, 0755, true);
                }
                continue;
            }

            // Skip sensitive files
            $shouldSkip = false;
            foreach ($skipPatterns as $pattern) {
                if (strpos($subPath, $pattern) !== false) {
                    $shouldSkip = true;
                    $skippedFiles[] = $subPath;
                    break;
                }
            }
            if ($shouldSkip) continue;

            // Ensure target directory exists
            $targetDir = dirname($targetPath);
            if (!File::exists($targetDir)) {
                if (!File::makeDirectory($targetDir, 0755, true)) {
                    $failedFiles[] = $subPath . ' (could not create directory)';
                    continue;
                }
            }

            // Use getRealPath() to ensure absolute string path
            $sourcePath = $item->getRealPath();

            // Check source is readable
            if (!is_readable($sourcePath)) {
                $failedFiles[] = $subPath . ' (source not readable)';
                continue;
            }

            // Check target is writable (if file exists, check it's writable; if not, check dir is writable)
            if (file_exists($targetPath) && !is_writable($targetPath)) {
                // Try to make writable
                @chmod($targetPath, 0644);
            }

            if (!is_writable($targetDir)) {
                $failedFiles[] = $subPath . ' (target directory not writable: ' . $targetDir . ')';
                continue;
            }

            // Copy the file
            if (copy($sourcePath, $targetPath)) {
                @chmod($targetPath, 0644);
                $copied++;
            } else {
                $failedFiles[] = $subPath . ' (copy failed)';
            }
        }

        \Log::info('System update file copy complete', [
            'copied' => $copied,
            'skipped' => count($skippedFiles),
            'failed' => count($failedFiles),
            'failed_files' => array_slice($failedFiles, 0, 20),
        ]);

        if (!empty($failedFiles)) {
            $failCount = count($failedFiles);
            $preview = implode(', ', array_slice($failedFiles, 0, 5));
            $more = $failCount > 5 ? " and " . ($failCount - 5) . " more" : '';
            throw new \Exception("Failed to copy {$failCount} file(s): {$preview}{$more}. Check file permissions on your hosting panel.");
        }

        if ($copied === 0 && empty($failedFiles)) {
            throw new \Exception('No files were copied. The ZIP may be empty or contain only skipped files.');
        }

        return $copied;
    }
}
