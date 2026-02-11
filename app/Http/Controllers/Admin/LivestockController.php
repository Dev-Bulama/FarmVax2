<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LivestockController extends Controller
{
    /**
     * Display all livestock with owner details
     */
    public function index(Request $request)
    {
        $query = Livestock::with(['owner', 'farmRecord'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tag_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('breed', 'like', "%{$search}%")
                    ->orWhereHas('owner', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('livestock_type', $request->type);
        }

        // Filter by health status
        if ($request->filled('health_status')) {
            $query->where('health_status', $request->health_status);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $livestock = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => Livestock::sum('quantity'),
            'cattle'    => Livestock::where('livestock_type', 'cattle')->sum('quantity'),
            'goat'      => Livestock::where('livestock_type', 'goat')->sum('quantity'),
            'sheep'     => Livestock::where('livestock_type', 'sheep')->sum('quantity'),
            'chicken'   => Livestock::where('livestock_type', 'chicken')->sum('quantity'),
            'pig'       => Livestock::where('livestock_type', 'pig')->sum('quantity'),
            'other'     => Livestock::whereIn('livestock_type', ['duck', 'turkey', 'rabbit', 'horse', 'donkey', 'other'])->sum('quantity'),
            'healthy'   => Livestock::where('health_status', 'healthy')->sum('quantity'),
            'sick'      => Livestock::where('health_status', 'sick')->sum('quantity'),
            'vaccinated'=> Livestock::where('is_vaccinated', true)->sum('quantity'),
        ];

        return view('admin.livestock.index', compact('livestock', 'stats'));
    }

    /**
     * Show single livestock details
     */
    public function show($id)
    {
        $animal = Livestock::with(['owner', 'farmRecord', 'vaccinationHistory', 'herdGroup'])
            ->findOrFail($id);

        return view('admin.livestock.show', compact('animal'));
    }

    /**
     * Import livestock from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->with('error', 'Could not open the uploaded file.');
        }

        $header = fgetcsv($handle); // Skip header row
        $imported = 0;
        $totalAnimals = 0;
        $skipped = 0;
        $errors = [];

        $validTypes = ['cattle', 'goat', 'sheep', 'pig', 'chicken', 'duck', 'turkey', 'rabbit', 'horse', 'donkey', 'other'];
        $validHealth = ['healthy', 'sick', 'recovering', 'deceased'];
        $validGenders = ['male', 'female', 'unknown'];

        while (($row = fgetcsv($handle)) !== false) {
            try {
                // CSV columns: owner_email, livestock_type, breed, tag_number, name, gender, health_status, age_years, notes, quantity
                if (count($row) < 3) {
                    $skipped++;
                    continue;
                }

                $ownerEmail    = trim($row[0] ?? '');
                $livestockType = strtolower(trim($row[1] ?? ''));
                $breed         = trim($row[2] ?? '');
                $tagNumber     = trim($row[3] ?? '') ?: null;
                $name          = trim($row[4] ?? '') ?: null;
                $gender        = strtolower(trim($row[5] ?? 'unknown'));
                $healthStatus  = strtolower(trim($row[6] ?? 'healthy'));
                $ageYears      = intval($row[7] ?? 0);
                $notes         = trim($row[8] ?? '') ?: null;
                $quantity      = $this->parseQuantity($row[9] ?? '1');

                // Validate type
                if (!in_array($livestockType, $validTypes)) {
                    $errors[] = "Row " . ($imported + $skipped + 2) . ": Invalid livestock type '{$livestockType}'";
                    $skipped++;
                    continue;
                }

                // Validate health status
                if (!in_array($healthStatus, $validHealth)) {
                    $healthStatus = 'healthy';
                }

                // Validate gender
                if (!in_array($gender, $validGenders)) {
                    $gender = 'unknown';
                }

                // Find owner
                $owner = null;
                if ($ownerEmail) {
                    $owner = User::where('email', $ownerEmail)->first();
                }

                // Check for duplicate tag number (skip check for batch/flock entries with quantity > 1)
                if ($tagNumber && $quantity === 1 && Livestock::where('tag_number', $tagNumber)->exists()) {
                    $errors[] = "Row " . ($imported + $skipped + 2) . ": Tag number '{$tagNumber}' already exists, skipped";
                    $skipped++;
                    continue;
                }

                // For batch entries (quantity > 1), don't store a tag number to avoid collisions
                $storedTag = ($quantity > 1) ? null : $tagNumber;

                Livestock::create([
                    'user_id'        => $owner?->id,
                    'livestock_type' => $livestockType,
                    'type'           => $livestockType,
                    'quantity'       => $quantity,
                    'breed'          => $breed ?: null,
                    'tag_number'     => $storedTag,
                    'name'           => $name,
                    'gender'         => $gender,
                    'health_status'  => $healthStatus,
                    'age_years'      => $ageYears > 0 ? $ageYears : null,
                    'notes'          => $notes,
                    'status'         => 'active',
                ]);

                $imported++;
                $totalAnimals += $quantity;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($imported + $skipped + 2) . ": " . $e->getMessage();
                $skipped++;
            }
        }

        fclose($handle);

        $animalLabel = $totalAnimals !== $imported
            ? number_format($totalAnimals) . ' animals across ' . $imported . ' records'
            : $imported . ' records';

        $message = "Import complete: {$animalLabel} added, {$skipped} skipped.";
        if (!empty($errors)) {
            $message .= ' Errors: ' . implode('; ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= ' ... and ' . (count($errors) - 5) . ' more.';
            }
        }

        return back()->with('success', $message);
    }

    /**
     * Parse a quantity value from CSV.
     * Handles formats: "20000", "20,000", "20,000 birds", "1500 heads", "", null
     * Returns integer >= 1.
     */
    protected function parseQuantity($raw): int
    {
        $raw = trim((string) $raw);

        if ($raw === '' || $raw === null) {
            return 1;
        }

        // Remove thousands separators (commas) and extract the first number
        $raw = str_replace(',', '', $raw);
        preg_match('/(\d+)/', $raw, $matches);

        if (empty($matches[1])) {
            return 1;
        }

        $qty = (int) $matches[1];

        // Cap at 10 million — values above this are almost certainly phone numbers
        // or other numeric data that got mapped to the wrong column.
        if ($qty > 10_000_000) {
            return 1;
        }

        return $qty >= 1 ? $qty : 1;
    }

    /**
     * Download CSV template for import
     */
    public function importTemplate()
    {
        $filename = 'livestock_import_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Column 10: quantity — number of animals this row represents (e.g. 20000 for a flock)
            fputcsv($handle, ['owner_email', 'livestock_type', 'breed', 'tag_number', 'name', 'gender', 'health_status', 'age_years', 'notes', 'quantity']);
            fputcsv($handle, ['farmer@example.com', 'cattle', 'Holstein', 'CTL-001', 'Bessie', 'female', 'healthy', '3', 'Good milk producer', '1']);
            fputcsv($handle, ['farmer@example.com', 'goat', 'Boer', 'GT-002', '', 'male', 'healthy', '2', '', '1']);
            fputcsv($handle, ['farmer@example.com', 'chicken', 'Broiler', '', '', 'unknown', 'healthy', '0', 'Batch flock', '20000']);
            fputcsv($handle, ['farmer@example.com', 'chicken', 'Noiler', '', '', 'unknown', 'healthy', '0', 'Second flock', '5000']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
