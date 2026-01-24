<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\FarmRecord;
use App\Models\Livestock;
use App\Models\ServiceRequest;

class ImportExportController extends Controller
{
    /**
     * Show import/export dashboard
     */
    public function index()
    {
        // Get backup files list
        $backups = [];
        if (Storage::disk('local')->exists('backups')) {
            $files = Storage::disk('local')->files('backups');
            foreach ($files as $file) {
                $backups[] = [
                    'name' => basename($file),
                    'size' => $this->formatBytes(Storage::disk('local')->size($file)),
                    'date' => date('Y-m-d H:i:s', Storage::disk('local')->lastModified($file)),
                    'path' => $file,
                ];
            }
            // Sort by date descending
            usort($backups, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }

        $stats = [
            'total_users' => User::count(),
            'total_farm_records' => FarmRecord::count(),
            'total_livestock' => Livestock::count(),
            'total_service_requests' => ServiceRequest::count(),
        ];

        return view('admin.import-export.index', compact('backups', 'stats'));
    }

    /**
     * ========================================
     * EXPORT FUNCTIONALITY
     * ========================================
     */

    /**
     * Export Users to CSV
     */
    public function exportUsers()
    {
        $users = User::with(['country', 'state', 'lga'])->get();

        $filename = 'users_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Role', 'Account Status', 
                'Country', 'State', 'LGA', 'Address', 'Created At'
            ]);

            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->role,
                    $user->account_status ?? 'active',
                    $user->country->name ?? '',
                    $user->state->name ?? '',
                    $user->lga->name ?? '',
                    $user->address,
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Farm Records to CSV
     */
    public function exportFarmRecords()
    {
        $farmRecords = FarmRecord::with('user')->get();

        $filename = 'farm_records_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($farmRecords) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Farm Name', 'Farmer Name', 'Farmer Email', 'Farmer Phone',
                'Farm Size', 'Farm Type', 'Total Livestock', 'Status', 'State', 'LGA', 'Submitted At'
            ]);

            // Data
            foreach ($farmRecords as $record) {
                fputcsv($file, [
                    $record->id,
                    $record->farm_name,
                    $record->farmer_name ?? $record->user->name ?? '',
                    $record->farmer_email ?? $record->user->email ?? '',
                    $record->farmer_phone ?? $record->user->phone ?? '',
                    $record->farm_size . ' ' . ($record->farm_size_unit ?? ''),
                    $record->farm_type,
                    $record->total_livestock_count,
                    $record->status,
                    $record->farmer_state,
                    $record->farmer_lga,
                    $record->submitted_at ? $record->submitted_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Livestock to CSV
     */
    public function exportLivestock()
    {
        $livestock = Livestock::with('owner')->get();

        $filename = 'livestock_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($livestock) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Tag Number', 'Type', 'Breed', 'Gender', 'Age', 
                'Health Status', 'Owner Name', 'Owner Email', 'Created At'
            ]);

            // Data
            foreach ($livestock as $animal) {
                fputcsv($file, [
                    $animal->id,
                    $animal->tag_number,
                    $animal->livestock_type,
                    $animal->breed,
                    $animal->gender,
                    $animal->age,
                    $animal->health_status,
                    $animal->owner->name ?? '',
                    $animal->owner->email ?? '',
                    $animal->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Service Requests to CSV
     */
    public function exportServiceRequests()
    {
        $serviceRequests = ServiceRequest::with('user')->get();

        $filename = 'service_requests_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($serviceRequests) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID', 'Reference', 'Farmer Name', 'Service Type', 'Priority', 
                'Status', 'Description', 'Created At'
            ]);

            // Data
            foreach ($serviceRequests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->reference_number ?? 'SR-' . $request->id,
                    $request->user->name ?? '',
                    $request->service_type,
                    $request->priority ?? $request->urgency_level,
                    $request->status,
                    $request->service_description ?? $request->description,
                    $request->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * ========================================
     * COMPLETE DATABASE BACKUP
     * ========================================
     */

    /**
     * Create COMPLETE database backup (includes passwords & all data)
     */
    public function createBackup()
    {
        try {
            $filename = 'farmvax_backup_' . date('Y-m-d_His') . '.sql';
            $backupPath = storage_path('app/backups');

            // Create backups directory if it doesn't exist
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $fullPath = $backupPath . '/' . $filename;

            // Use PHP-based backup to ensure it works on shared hosting
            $this->completeBackup($fullPath);

            return redirect()->route('admin.import-export.index')
                ->with('success', 'Complete database backup created successfully: ' . $filename);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Complete PHP-based backup with ALL data including passwords
     */
    private function completeBackup($filepath)
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE');
        
        $content = "-- FarmVax Complete Database Backup\n";
        $content .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $content .= "-- Database: {$dbName}\n";
        $content .= "-- INCLUDES: All tables, user passwords, sessions, and relationships\n\n";
        $content .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $content .= "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';\n\n";

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            
            // Get table structure
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $content .= "\n\n-- ========================================\n";
            $content .= "-- Table: {$tableName}\n";
            $content .= "-- ========================================\n";
            $content .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $content .= $createTable[0]->{'Create Table'} . ";\n\n";

            // Get table data
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $content .= "-- Data for table: {$tableName}\n";
                $content .= "LOCK TABLES `{$tableName}` WRITE;\n";
                
                // Split into chunks to avoid memory issues
                $chunks = $rows->chunk(100);
                foreach ($chunks as $chunk) {
                    $values = [];
                    foreach ($chunk as $row) {
                        $rowData = array_map(function($value) {
                            if (is_null($value)) {
                                return 'NULL';
                            }
                            return "'" . addslashes($value) . "'";
                        }, (array)$row);
                        $values[] = '(' . implode(', ', $rowData) . ')';
                    }
                    $content .= "INSERT INTO `{$tableName}` VALUES " . implode(",\n", $values) . ";\n";
                }
                
                $content .= "UNLOCK TABLES;\n\n";
            }
        }

        $content .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $content .= "\n-- Backup completed successfully\n";
        
        file_put_contents($filepath, $content);
    }

    /**
     * ========================================
     * IMPORT/RESTORE FUNCTIONALITY
     * ========================================
     */

    /**
     * Import/Restore database from backup file
     */
    public function importBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:102400', // Max 100MB
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('backup_file');
            $sqlContent = file_get_contents($file->getRealPath());

            // Split SQL into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $sqlContent)),
                function($stmt) {
                    return !empty($stmt) && 
                           !str_starts_with($stmt, '--') && 
                           !str_starts_with($stmt, '/*');
                }
            );

            // Execute each statement
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    DB::unprepared($statement);
                }
            }

            DB::commit();

            // Clear all caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->route('admin.import-export.index')
                ->with('success', 'Database restored successfully! All user data, passwords, and activities have been imported.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Restore from existing backup file
     */
    public function restoreBackup($filename)
    {
        try {
            $path = storage_path('app/backups/' . $filename);
            
            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'Backup file not found.');
            }

            DB::beginTransaction();

            $sqlContent = file_get_contents($path);

            // Split SQL into individual statements
            $statements = array_filter(
                array_map('trim', explode(';', $sqlContent)),
                function($stmt) {
                    return !empty($stmt) && 
                           !str_starts_with($stmt, '--') && 
                           !str_starts_with($stmt, '/*');
                }
            );

            // Execute each statement
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    DB::unprepared($statement);
                }
            }

            DB::commit();

            // Clear all caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->route('admin.import-export.index')
                ->with('success', 'Database restored successfully from: ' . $filename);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Download backup file
     */
    public function downloadBackup($filename)
    {
        $path = 'backups/' . $filename;
        
        if (!Storage::disk('local')->exists($path)) {
            return redirect()->back()->with('error', 'Backup file not found.');
        }

        return Storage::disk('local')->download($path);
    }

    /**
     * Delete backup file
     */
    public function deleteBackup($filename)
    {
        $path = 'backups/' . $filename;
        
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
            return redirect()->back()->with('success', 'Backup deleted successfully.');
        }

        return redirect()->back()->with('error', 'Backup file not found.');
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}