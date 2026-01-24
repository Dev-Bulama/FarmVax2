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
        // Store file
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $storedName = 'imports/' . time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
        $file->move(storage_path('app/public/' . dirname($storedName)), basename($storedName));

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
     * Process import with column mapping
     */
    public function process(Request $request, $importId)
    {
        $request->validate([
            'mapping' => 'required|array',
            'mapping.name' => 'required',
            'mapping.email' => 'required',
            'mapping.phone' => 'required',
        ]);

        $import = UserImport::findOrFail($importId);
        
        if ($import->status !== 'pending') {
            return back()->with('error', 'This import has already been processed.');
        }

        try {
            // Store column mapping
            $import->update([
                'column_mapping' => $request->mapping,
            ]);

            // Mark as started
            $import->markAsStarted();

            // Process the import
            $this->processImportFile($import, $request->mapping);

            // Mark as completed
            $import->markAsCompleted();

            return redirect()->route('admin.import.show', $import->id)
                ->with('success', "Import completed! {$import->successful_imports} users imported successfully.");

        } catch (\Exception $e) {
            $import->markAsFailed($e->getMessage());
            return redirect()->route('admin.import.show', $import->id)
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Process the actual import file
     */
    protected function processImportFile(UserImport $import, array $mapping)
    {
        $filePath = storage_path('app/public/' . $import->stored_filename);
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        
        $highestRow = $sheet->getHighestRow();
        
        // Get Nigeria as default country
        $defaultCountry = Country::where('code', 'NG')->first();

       for ($row = 2; $row <= $highestRow; $row++) {
    try {
        // Extract data based on mapping
        $data = [];
        
        // Get all cells in the row
        $cellIterator = $sheet->getRowIterator($row, $row)->current()->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        
        $rowCells = [];
        foreach ($cellIterator as $cell) {
            $rowCells[] = $cell->getValue();
        }
        
        // Map data based on column mapping
        foreach ($mapping as $field => $columnIndex) {
            if ($columnIndex !== null && $columnIndex !== '') {
                $data[$field] = $rowCells[(int)$columnIndex] ?? null;
            }
        }

                // Validate required fields
                if (empty($data['name']) || empty($data['email'])) {
                    $import->addError($row, 'required', 'Missing required fields (name or email)');
                    $import->incrementFailed();
                    continue;
                }

                // Check for duplicate email
                if (User::where('email', $data['email'])->exists()) {
                    $import->addError($row, 'email', 'Email already exists: ' . $data['email']);
                    $import->incrementDuplicates();
                    continue;
                }

                // Generate random password
                $password = $this->generatePassword();

                // Create user
                DB::beginTransaction();

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'password' => Hash::make($password),
                    'role' => $import->user_type,
                    'country_id' => $defaultCountry ? $defaultCountry->id : null,
                    'address' => $data['address'] ?? null,
                    'farm_name' => $data['farm_name'] ?? null,
                    'is_active' => true,
                    'status' => 'active',
                    'account_status' => 'active',
                ]);

                // Create role-specific profile
                if ($import->user_type === 'volunteer') {
                    Volunteer::create([
                        'user_id' => $user->id,
                        'approval_status' => 'approved',
                        'is_active' => true,
                        'submitted_at' => now(),
                    ]);
                } elseif ($import->user_type === 'animal_health_professional') {
                    AnimalHealthProfessional::create([
                        'user_id' => $user->id,
                        'approval_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'submitted_at' => now(),
                    ]);
                }

                // Track imported user
                $importedUser = ImportedUser::create([
                    'import_id' => $import->id,
                    'user_id' => $user->id,
                    'generated_password' => $password,
                    'welcome_email_sent' => false,
                ]);

                DB::commit();

                // Send welcome email
                $this->sendWelcomeEmail($importedUser);

                $import->incrementSuccess($user->id);

            } catch (\Exception $e) {
                DB::rollBack();
                $import->addError($row, 'exception', $e->getMessage());
                $import->incrementFailed();
            }
        }
    }

    /**
     * Generate random password
     */
    protected function generatePassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 0, $length);
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