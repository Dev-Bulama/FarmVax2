<?php

namespace App\Http\Controllers\DataCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmRecord;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FarmRecordController extends Controller
{
    /**
     * Total number of steps in the form
     */
    const TOTAL_STEPS = 6;

    /**
     * Start new farm record (Step 1)
     */
    public function create()
    {
        // Clear any existing session data
        Session::forget('farm_record_data');
        Session::forget('farm_record_id');
        
        return redirect()->route('data-collector.farm-record.step', ['step' => 1]);
    }

    /**
     * Show specific step
     */
    public function showStep($step)
    {
        // Validate step number
        if ($step < 1 || $step > self::TOTAL_STEPS) {
            return redirect()->route('data-collector.farm-record.step', ['step' => 1]);
        }

        // Get existing data from session
        $farmRecordData = Session::get('farm_record_data', []);
        $farmRecordId = Session::get('farm_record_id');

        // Load existing farm record if editing
        $farmRecord = null;
        if ($farmRecordId) {
            $farmRecord = FarmRecord::where('user_id', auth()->id())
                ->find($farmRecordId);
        }

        return view('data-collector.farm-record.step' . $step, compact('step', 'farmRecordData', 'farmRecord'));
    }

    /**
     * Save step data
     */
    public function saveStep(Request $request, $step)
    {
        // Validate step number
        if ($step < 1 || $step > self::TOTAL_STEPS) {
            return redirect()->route('data-collector.farm-record.step', ['step' => 1]);
        }

        // Validate the step data
        $validated = $this->validateStep($request, $step);

        // Get existing data from session
        $farmRecordData = Session::get('farm_record_data', []);

        // Merge new data with existing data
        $farmRecordData["step{$step}"] = $validated;
        Session::put('farm_record_data', $farmRecordData);

        // Save as draft
        $this->saveDraftRecord($farmRecordData);

        // If last step, redirect to review
        if ($step == self::TOTAL_STEPS) {
            return redirect()->route('data-collector.farm-record.review');
        }

        // Go to next step
        return redirect()->route('data-collector.farm-record.step', ['step' => $step + 1])
            ->with('success', 'Step ' . $step . ' saved successfully!');
    }

    /**
     * Save as draft
     */
    public function saveDraft(Request $request)
    {
        $farmRecordData = Session::get('farm_record_data', []);
        
        if (empty($farmRecordData)) {
            return redirect()->route('data-collector.dashboard')
                ->with('error', 'No data to save as draft.');
        }

        $this->saveDraftRecord($farmRecordData);

        return redirect()->route('data-collector.dashboard')
            ->with('success', 'Farm record saved as draft!');
    }

    /**
     * Submit final farm record - OPTIMIZED VERSION
     */
    public function submit(Request $request)
    {
        try {
            // Start transaction
            DB::beginTransaction();
            
            $farmRecordData = Session::get('farm_record_data', []);
            
            if (empty($farmRecordData)) {
                return redirect()->route('data-collector.dashboard')
                    ->with('error', 'No data to submit.');
            }

            // Merge all step data
            $allData = $this->mergeStepData($farmRecordData);
            
            // Set required fields
            $allData['user_id'] = auth()->id();
            $allData['created_by_role'] = 'data_collector';
            $allData['status'] = 'submitted';
            $allData['submitted_at'] = now();

            // Get existing record ID from session
            $farmRecordId = Session::get('farm_record_id');
            
            if ($farmRecordId) {
                // Update existing draft
                FarmRecord::where('id', $farmRecordId)
                    ->where('user_id', auth()->id())
                    ->update($allData);
                    
                Log::info('Farm record updated', ['id' => $farmRecordId]);
            } else {
                // Create new record
                $farmRecord = FarmRecord::create($allData);
                $farmRecordId = $farmRecord->id;
                
                Log::info('Farm record created', ['id' => $farmRecordId]);
            }

            // Update data collector stats (simplified - no complex logic)
            if (auth()->user()->dataCollectorProfile) {
                DB::table('data_collector_profiles')
                    ->where('user_id', auth()->id())
                    ->increment('total_submissions');
            }

            // Commit transaction
            DB::commit();

            // Clear session
            Session::forget('farm_record_data');
            Session::forget('farm_record_id');

            // Show success page
            return view('data-collector.farm-record.success');
            
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            
            Log::error('Farm record submission failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('data-collector.dashboard')
                ->with('error', 'Submission failed: ' . $e->getMessage());
        }
    }

    /**
     * Show farm record details
     */
    public function show($id)
    {
        $record = FarmRecord::where('user_id', auth()->id())
            ->findOrFail($id);

        return view('data-collector.farm-records.show', compact('record'));
    }

    /**
     * Edit existing farm record
     */
    public function edit($id)
    {
        $farmRecord = FarmRecord::where('user_id', auth()->id())
            ->findOrFail($id);

        // Load record data into session
        Session::put('farm_record_id', $farmRecord->id);
        Session::put('farm_record_data', $this->loadRecordIntoSteps($farmRecord));

        return redirect()->route('data-collector.farm-record.step', ['step' => 1]);
    }

    /**
     * Update farm record
     */
    public function update(Request $request, $id)
    {
        // This will be handled by the saveStep and submit methods
        return redirect()->route('data-collector.farm-records.show', $id);
    }

    /**
     * Validate step data
     */
    private function validateStep(Request $request, $step)
    {
        switch ($step) {
            case 1: // Stakeholder Information
                return $request->validate([
                    'farmer_name' => 'required|string|max:255',
                    'farmer_email' => 'nullable|email|max:255',
                    'farmer_phone' => 'required|string|max:20',
                    'farmer_address' => 'nullable|string',
                    'city' => 'nullable|string|max:255',
                    'state' => 'nullable|string|max:255',
                    'lga' => 'nullable|string|max:255',
                    'farm_name' => 'nullable|string|max:255',
                    'farm_size' => 'nullable|numeric',
                    'farm_size_unit' => 'nullable|string|in:acres,hectares,square_meters',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'farm_type' => 'nullable|string|in:commercial,subsistence,mixed',
                    'average_household_size' => 'nullable|integer|min:1',
                ]);

            case 2: // Livestock Profile
                return $request->validate([
                    'total_livestock_count' => 'required|integer|min:0',
                    'livestock_types' => 'nullable|array',
                    'young_count' => 'nullable|integer|min:0',
                    'adult_count' => 'nullable|integer|min:0',
                    'old_count' => 'nullable|integer|min:0',
                ]);

            case 3: // Health & Vaccination
                return $request->validate([
                    'last_vaccination_date' => 'nullable|date',
                    'has_health_issues' => 'nullable|boolean',
                    'current_health_issues' => 'nullable|array',
                    'health_notes' => 'nullable|string',
                    'veterinarian_name' => 'nullable|string|max:255',
                    'veterinarian_phone' => 'nullable|string|max:20',
                    'last_vet_visit' => 'nullable|date',
                    'past_diseases' => 'nullable|array',
                ]);

            case 4: // Service Needs
                return $request->validate([
                    'service_needs' => 'nullable|array',
                    'urgency_level' => 'nullable|string|in:low,medium,high,emergency',
                    'service_description' => 'nullable|string',
                    'preferred_service_date' => 'nullable|date',
                    'needs_immediate_attention' => 'nullable|boolean',
                ]);

            case 5: // Alert Preferences
                return $request->validate([
                    'sms_alerts' => 'nullable|boolean',
                    'email_alerts' => 'nullable|boolean',
                    'phone_alerts' => 'nullable|boolean',
                    'alert_types' => 'nullable|array',
                    'preferred_contact_method' => 'nullable|string|in:sms,email,phone,whatsapp',
                    'alternative_phone' => 'nullable|string|max:20',
                ]);

            case 6: // Feedback & Consent
                return $request->validate([
                    'data_sharing_consent' => 'nullable|boolean',
                    'research_participation_consent' => 'nullable|boolean',
                    'marketing_consent' => 'nullable|boolean',
                    'additional_comments' => 'nullable|string',
                    'feedback' => 'nullable|string',
                ]);

            default:
                return [];
        }
    }

    /**
     * Map form field names to database column names
     */
    private function mapFieldNames($data)
    {
        $mapping = [
            'city' => 'farmer_city',
            'state' => 'farmer_state',
            'lga' => 'farmer_lga',
        ];

        foreach ($mapping as $formField => $dbField) {
            if (isset($data[$formField])) {
                $data[$dbField] = $data[$formField];
                unset($data[$formField]);
            }
        }

        return $data;
    }

    /**
     * Save draft record - OPTIMIZED
     */
    private function saveDraftRecord($farmRecordData)
    {
        $allData = $this->mergeStepData($farmRecordData);
        $allData['status'] = 'draft';
        $allData['user_id'] = auth()->id();
        $allData['created_by_role'] = 'data_collector';

        $farmRecordId = Session::get('farm_record_id');
        
        if ($farmRecordId) {
            // Update existing
            FarmRecord::where('id', $farmRecordId)
                ->where('user_id', auth()->id())
                ->update($allData);
                
            $farmRecord = FarmRecord::find($farmRecordId);
        } else {
            // Create new
            $farmRecord = FarmRecord::create($allData);
            Session::put('farm_record_id', $farmRecord->id);
        }

        return $farmRecord;
    }

    /**
     * Merge step data into single array - OPTIMIZED
     */
    private function mergeStepData($farmRecordData)
    {
        $merged = [];
        
        foreach ($farmRecordData as $stepKey => $stepData) {
            if (is_array($stepData)) {
                $merged = array_merge($merged, $stepData);
            }
        }

        // Map form field names to database column names
        $merged = $this->mapFieldNames($merged);

        // Convert boolean checkboxes to proper format
        $booleanFields = [
            'has_health_issues', 
            'needs_immediate_attention',
            'sms_alerts', 
            'email_alerts', 
            'phone_alerts',
            'data_sharing_consent',
            'research_participation_consent',
            'marketing_consent'
        ];
        
        foreach ($booleanFields as $field) {
            if (isset($merged[$field])) {
                $merged[$field] = (bool) $merged[$field];
            }
        }

        // Convert arrays to JSON for storage
        $arrayFields = [
            'livestock_types',
            'current_health_issues',
            'past_diseases',
            'service_needs',
            'alert_types'
        ];
        
        foreach ($arrayFields as $field) {
            if (isset($merged[$field]) && is_array($merged[$field])) {
                $merged[$field] = json_encode($merged[$field]);
            }
        }

        return $merged;
    }

    /**
     * Load farm record into step format
     */
    private function loadRecordIntoSteps(FarmRecord $record)
    {
        // Decode JSON fields
        $livestock_types = is_string($record->livestock_types) ? json_decode($record->livestock_types, true) : $record->livestock_types;
        $current_health_issues = is_string($record->current_health_issues) ? json_decode($record->current_health_issues, true) : $record->current_health_issues;
        $past_diseases = is_string($record->past_diseases) ? json_decode($record->past_diseases, true) : $record->past_diseases;
        $service_needs = is_string($record->service_needs) ? json_decode($record->service_needs, true) : $record->service_needs;
        $alert_types = is_string($record->alert_types) ? json_decode($record->alert_types, true) : $record->alert_types;

        return [
            'step1' => [
                'farmer_name' => $record->farmer_name,
                'farmer_email' => $record->farmer_email,
                'farmer_phone' => $record->farmer_phone,
                'farmer_address' => $record->farmer_address,
                'city' => $record->farmer_city,
                'state' => $record->farmer_state,
                'lga' => $record->farmer_lga,
                'farm_name' => $record->farm_name,
                'farm_size' => $record->farm_size,
                'farm_size_unit' => $record->farm_size_unit,
                'latitude' => $record->latitude,
                'longitude' => $record->longitude,
                'farm_type' => $record->farm_type,
                'average_household_size' => $record->average_household_size,
            ],
            'step2' => [
                'total_livestock_count' => $record->total_livestock_count,
                'livestock_types' => $livestock_types ?? [],
                'young_count' => $record->young_count,
                'adult_count' => $record->adult_count,
                'old_count' => $record->old_count,
            ],
            'step3' => [
                'last_vaccination_date' => $record->last_vaccination_date,
                'has_health_issues' => $record->has_health_issues,
                'current_health_issues' => $current_health_issues ?? [],
                'health_notes' => $record->health_notes,
                'veterinarian_name' => $record->veterinarian_name,
                'veterinarian_phone' => $record->veterinarian_phone,
                'last_vet_visit' => $record->last_vet_visit,
                'past_diseases' => $past_diseases ?? [],
            ],
            'step4' => [
                'service_needs' => $service_needs ?? [],
                'urgency_level' => $record->urgency_level,
                'service_description' => $record->service_description,
                'preferred_service_date' => $record->preferred_service_date,
                'needs_immediate_attention' => $record->needs_immediate_attention,
            ],
            'step5' => [
                'sms_alerts' => $record->sms_alerts,
                'email_alerts' => $record->email_alerts,
                'phone_alerts' => $record->phone_alerts,
                'alert_types' => $alert_types ?? [],
                'preferred_contact_method' => $record->preferred_contact_method,
                'alternative_phone' => $record->alternative_phone,
            ],
            'step6' => [
                'data_sharing_consent' => $record->data_sharing_consent,
                'research_participation_consent' => $record->research_participation_consent,
                'marketing_consent' => $record->marketing_consent,
                'additional_comments' => $record->additional_comments,
                'feedback' => $record->feedback,
            ],
        ];
    }

    /**
     * Display submitted farm records
     */
    public function index()
    {
        $records = FarmRecord::where('user_id', auth()->id())
            ->whereIn('status', ['submitted', 'approved', 'under_review', 'rejected'])
            ->latest()
            ->paginate(20);

        return view('data-collector.farm-records.index', compact('records'));
    }

    /**
     * Display draft farm records
     */
    public function drafts()
    {
        $drafts = FarmRecord::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->latest()
            ->get();

        return view('data-collector.farm-records.drafts', compact('drafts'));
    }
}