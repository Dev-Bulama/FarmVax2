<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserImport;
use App\Models\ImportedUser;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\AnimalHealthProfessional;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Imports\ChunkReadFilter;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class UserImportController extends Controller
{
    /**
     * Show import page with history
     */
    public function index()
    {
        $imports = UserImport::with(['importedBy'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_imports' => UserImport::count(),
            'total_users_imported' => UserImport::sum('successful_imports'),
            'pending_emails' => ImportedUser::pendingEmail()->count(),
            'completed_imports' => UserImport::completed()->count(),
        ];

        return view('admin.import.index', compact('imports', 'stats'));
    }

    /**
     * Show upload form
     */
    public function create()
    {
        return view('admin.import.create');
    }

    /**
     * Handle file upload and show column mapping
     */
   public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
        'user_type' => 'required|in:farmer,volunteer,animal_health_professional',
    ]);

    try {
        // Store file — ensure destination directory exists
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName = 'imports/' . time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $destDir = storage_path('app/public/imports');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        $file->move($destDir, basename($storedName));

        // Read file headers and sample data
        $filePath = storage_path('app/public/' . $storedName);
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        // Get headers (first row) - UPDATED METHOD
        $headers = [];
        $highestColumn = $sheet->getHighestColumn();
        
        // Get column letter range
        $columnIterator = $sheet->getRowIterator(1, 1)->current()->getCellIterator();
        $columnIterator->setIterateOnlyExistingCells(false);
        
        foreach ($columnIterator as $cell) {
            $headers[] = $cell->getValue();
        }
        
        // Remove empty trailing headers
        $headers = array_filter($headers, function($value) {
            return $value !== null && $value !== '';
        });

        // Get sample data (first 3 rows) - UPDATED METHOD
        $sampleData = [];
        $maxRow = min(4, $sheet->getHighestRow());
        
        for ($row = 2; $row <= $maxRow; $row++) {
            $rowData = [];
            $cellIterator = $sheet->getRowIterator($row, $row)->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            
            $colIndex = 0;
            foreach ($cellIterator as $cell) {
                if ($colIndex >= count($headers)) break;
                $rowData[] = $cell->getValue();
                $colIndex++;
            }
            
            if (!empty(array_filter($rowData))) {
                $sampleData[] = $rowData;
            }
        }

        // Count total records
        $totalRecords = $sheet->getHighestRow() - 1; // Minus header row

        // Create import record
        $import = UserImport::create([
            'imported_by' => auth()->id(),
            'original_filename' => $originalName,
            'stored_filename' => $storedName,
            'user_type' => $request->user_type,
            'total_records' => $totalRecords,
            'status' => 'pending',
        ]);

        return view('admin.import.mapping', compact('import', 'headers', 'sampleData'));

    } catch (\Exception $e) {
        return back()->with('error', 'Error reading file: ' . $e->getMessage());
    }
}
    /**
     * Save column mapping, mark import as started, return JSON for AJAX chunk loop.
     */
    public function process(Request $request, $importId)
    {
        $request->validate([
            'mapping'       => 'required|array',
            'mapping.name'  => 'required',
            'mapping.email' => 'required',
            'mapping.phone' => 'required',
        ]);

        $import = UserImport::findOrFail($importId);

        if ($import->status !== 'pending') {
            return response()->json(['error' => 'This import has already been processed.'], 422);
        }

        $import->update(['column_mapping' => $request->mapping]);
        $import->markAsStarted();

        return response()->json([
            'success'        => true,
            'import_id'      => $import->id,
            'total_records'  => $import->total_records,
        ]);
    }

    /**
     * Process a chunk of rows (called repeatedly by the browser until done).
     */
    public function processChunk(Request $request, $importId)
    {
        try {
            $import = UserImport::findOrFail($importId);

            if ($import->status !== 'processing') {
                return response()->json(['error' => 'Import is not in processing state.'], 422);
            }

            $startRow    = max(2, (int) $request->input('start_row', 2));
            $chunkSize   = min(max(1, (int) $request->input('chunk_size', 100)), 200);
            $lastDataRow = $import->total_records + 1; // row 1 = header
            $endRow      = min($startRow + $chunkSize - 1, $lastDataRow);

            @set_time_limit(120);
            @ini_set('memory_limit', '256M');
            ignore_user_abort(true);

            $mapping = $import->column_mapping;

            // Detect optional columns once per chunk using raw DB to avoid Doctrine cache issues
            $userColumns      = DB::select("SHOW COLUMNS FROM `users`");
            $columnNames      = array_column($userColumns, 'Field');
            $hasFarmName      = in_array('farm_name', $columnNames);
            $hasIsActive      = in_array('is_active', $columnNames);
            $hasAccountStatus = in_array('account_status', $columnNames);

            $filePath = storage_path('app/public/' . $import->stored_filename);

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'Import file not found on server. Please re-upload and try again.'], 422);
            }

            $defaultCountry = Country::findByCode('NG');

            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            $reader->setReadFilter(new ChunkReadFilter($startRow, $endRow));
            $spreadsheet = $reader->load($filePath);
            $sheet       = $spreadsheet->getActiveSheet();

            for ($row = $startRow; $row <= $endRow; $row++) {
                try {
                    $rowIterator = $sheet->getRowIterator($row, $row)->current();
                    if (!$rowIterator) {
                        continue;
                    }
                    $cellIterator = $rowIterator->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);

                    $rowCells = [];
                    foreach ($cellIterator as $cell) {
                        $rowCells[] = $cell->getValue();
                    }

                    $data = [];
                    foreach ($mapping as $field => $columnIndex) {
                        if ($columnIndex !== null && $columnIndex !== '') {
                            $data[$field] = $rowCells[(int) $columnIndex] ?? null;
                        }
                    }

                    if (empty($data['name']) || empty($data['email'])) {
                        $import->addError($row, 'required', 'Missing required fields (name or email)');
                        $import->incrementFailed();
                        continue;
                    }

                    $existingUser = User::where('email', $data['email'])->first();

                    if ($existingUser) {
                        // For existing farmers, add livestock to their existing records instead of skipping
                        if ($import->user_type === 'farmer' && !empty($data['livestock_type'])) {
                            $lsType = strtolower($data['livestock_type']);
                            $qty    = $this->parseQuantity($data['livestock_count'] ?? null);
                            try {
                                $existing = \App\Models\Livestock::where('user_id', $existingUser->id)
                                    ->where('livestock_type', $lsType)
                                    ->first();
                                if ($existing) {
                                    $existing->increment('quantity', $qty);
                                } else {
                                    \App\Models\Livestock::create([
                                        'user_id'        => $existingUser->id,
                                        'owner_id'       => $existingUser->id,
                                        'livestock_type' => $lsType,
                                        'type'           => $lsType,
                                        'quantity'       => $qty,
                                        'health_status'  => 'healthy',
                                    ]);
                                }
                            } catch (\Exception $e) {
                                \Log::warning("Livestock update skipped for existing user {$existingUser->id}: " . $e->getMessage());
                            }
                        }
                        $import->incrementDuplicates();
                        continue;
                    }

                    $password = $this->generatePassword();

                    DB::beginTransaction();

                    $userPayload = [
                        'name'       => $data['name'],
                        'email'      => $data['email'],
                        'phone'      => $data['phone'] ?? null,
                        'password'   => Hash::make($password),
                        'role'       => $import->user_type,
                        'country_id' => $defaultCountry ? $defaultCountry->id : null,
                        'address'    => $data['address'] ?? null,
                        'status'     => 'active',
                    ];

                    // Only include optional columns that exist in the table
                    if ($hasFarmName)      { $userPayload['farm_name']      = $data['farm_name'] ?? null; }
                    if ($hasIsActive)      { $userPayload['is_active']      = true; }
                    if ($hasAccountStatus) { $userPayload['account_status'] = 'active'; }

                    $user = User::create($userPayload);

                    if ($import->user_type === 'volunteer') {
                        $volColumns = array_column(DB::select("SHOW COLUMNS FROM `volunteers`"), 'Field');
                        $volPayload = [
                            'user_id'         => $user->id,
                            'approval_status' => 'approved',
                            'submitted_at'    => now(),
                        ];
                        if (in_array('is_active', $volColumns)) {
                            $volPayload['is_active'] = true;
                        }
                        Volunteer::create($volPayload);
                    } elseif ($import->user_type === 'animal_health_professional') {
                        AnimalHealthProfessional::create([
                            'user_id'         => $user->id,
                            'approval_status' => 'approved',
                            'approved_by'     => auth()->id(),
                            'approved_at'     => now(),
                            'submitted_at'    => now(),
                        ]);
                    } elseif ($import->user_type === 'farmer' && !empty($data['livestock_type'])) {
                        $lsType = strtolower($data['livestock_type']);
                        $qty    = $this->parseQuantity($data['livestock_count'] ?? null);
                        try {
                            $existing = \App\Models\Livestock::where('user_id', $user->id)
                                ->where('livestock_type', $lsType)
                                ->first();
                            if ($existing) {
                                $existing->increment('quantity', $qty);
                            } else {
                                \App\Models\Livestock::create([
                                    'user_id'        => $user->id,
                                    'owner_id'       => $user->id,
                                    'livestock_type' => $lsType,
                                    'type'           => $lsType,
                                    'quantity'       => $qty,
                                    'health_status'  => 'healthy',
                                ]);
                            }
                        } catch (\Exception $e) {
                            \Log::warning("Livestock creation skipped for user {$user->id}: " . $e->getMessage());
                        }
                    }

                    ImportedUser::create([
                        'import_id'          => $import->id,
                        'user_id'            => $user->id,
                        'generated_password' => $password,
                        'welcome_email_sent' => false,
                    ]);

                    DB::commit();
                    $import->incrementSuccess($user->id);

                } catch (\Exception $e) {
                    DB::rollBack();
                    $import->addError($row, 'exception', $e->getMessage());
                    $import->incrementFailed();
                }
            }

            $done = ($endRow >= $lastDataRow);

            if ($done) {
                $import->markAsCompleted();
            }

            $import->refresh();

            return response()->json([
                'done'             => $done,
                'next_row'         => $endRow + 1,
                'successful'       => $import->successful_imports,
                'failed'           => $import->failed_imports,
                'duplicates'       => $import->duplicate_emails,
                'total'            => $import->total_records,
                'progress_percent' => $done ? 100 : (int) min(99, ($endRow - 1) / max(1, $import->total_records) * 100),
                'show_url'         => route('admin.import.show', $import->id),
            ]);

        } catch (\Throwable $e) {
            \Log::error('processChunk fatal error: ' . $e->getMessage(), [
                'import_id' => $importId,
                'start_row' => $request->input('start_row'),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ]);
            return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Return current import progress as JSON (for polling).
     */
    public function status($importId)
    {
        $import = UserImport::findOrFail($importId);

        return response()->json([
            'status'           => $import->status,
            'successful'       => $import->successful_imports,
            'failed'           => $import->failed_imports,
            'duplicates'       => $import->duplicate_emails,
            'total'            => $import->total_records,
            'progress_percent' => $import->success_rate,
        ]);
    }

    /**
     * Parse a livestock quantity value that may be a single number or a
     * comma/slash/space-separated list (e.g. "20,9,3" → 32).
     */
    protected function parseQuantity($value): int
    {
        if ($value === null || $value === '') {
            return 1;
        }

        $str = (string) $value;

        // Split on commas, semicolons, slashes, pipes, or whitespace
        $parts = preg_split('/[\s,;\/|]+/', $str);

        $total = 0;
        foreach ($parts as $part) {
            $num = trim($part);
            if (is_numeric($num)) {
                $total += (int) $num;
            }
        }

        return max(1, $total);
    }

    /**
     * Generate a cryptographically random password.
     */
    protected function generatePassword($length = 12): string
    {
        $chars    = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$';
        $charLen  = strlen($chars) - 1;
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charLen)];
        }
        return $password;
    }

    /**
     * Send welcome email to imported user
     */
    protected function sendWelcomeEmail(ImportedUser $importedUser)
    {
        try {
            $user = $importedUser->user;
            $password = $importedUser->decrypted_password;

            Mail::send('emails.welcome-imported-user', [
                'user' => $user,
                'password' => $password,
                'loginUrl' => route('login'),
            ], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                    ->subject('Welcome to FarmVax - Your Account Details');
            });

            $importedUser->markEmailAsSent();

        } catch (\Exception $e) {
            // Log error but don't fail the import
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }

    /**
     * Show import details
     */
    public function show($id)
    {
        $import = UserImport::with(['importedBy', 'importedUsers.user'])->findOrFail($id);
        
        return view('admin.import.show', compact('import'));
    }

    /**
     * Resend welcome email
     */
    public function resendEmail($importedUserId)
    {
        $importedUser = ImportedUser::with('user')->findOrFail($importedUserId);

        if (!$importedUser->canResendEmail()) {
            return back()->with('error', 'Cannot resend email. Maximum resend limit reached or too soon since last send.');
        }

        $this->sendWelcomeEmail($importedUser);

        return back()->with('success', 'Welcome email resent successfully to ' . $importedUser->user->name);
    }

    /**
     * Resend all pending emails for an import batch
     */
    public function resendBatchEmails($importId)
    {
        $import = UserImport::findOrFail($importId);
        $pendingUsers = $import->importedUsers()->pendingEmail()->get();

        $sent = 0;
        foreach ($pendingUsers as $importedUser) {
            if ($importedUser->canResendEmail()) {
                $this->sendWelcomeEmail($importedUser);
                $sent++;
            }
        }

        return back()->with('success', "Resent welcome emails to {$sent} users.");
    }

    /**
     * Delete import record
     */
    public function destroy($id)
    {
        $import = UserImport::findOrFail($id);
        
        // Delete stored file
        if (Storage::disk('public')->exists($import->stored_filename)) {
            Storage::disk('public')->delete($import->stored_filename);
        }

        $import->delete();

        return redirect()->route('admin.import.index')
            ->with('success', 'Import record deleted successfully.');
    }

    /**
     * Download import template
     */
    public function downloadTemplate($type = 'farmer')
    {
        $filename = 'FarmVax_' . ucfirst($type) . '_Import_Template.csv';
        
        $headers = $this->getTemplateHeaders($type);
        
        $callback = function() use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get template headers based on user type
     */
    protected function getTemplateHeaders($type)
    {
        $common = ['Full Name', 'Email Address', 'Phone Number', 'Address'];

        return match($type) {
            'farmer' => array_merge($common, ['Farm Name', 'Farm Address', 'Farm Size']),
            'volunteer' => array_merge($common, ['Organization', 'Motivation']),
            'animal_health_professional' => array_merge($common, ['Organization', 'Specialization', 'License Number']),
            default => $common,
        };
    }
}