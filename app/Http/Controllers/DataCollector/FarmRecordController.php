<?php

namespace App\Http\Controllers\DataCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmRecord;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class FarmRecordController extends Controller
{
    const TOTAL_STEPS = 6;

    public function create()
    {
        Session::forget('farm_record_data');
        Session::forget('farm_record_id');
        return redirect()->route('data-collector.farm-record.step', ['step' => 1]);
    }

    public function showStep($step)
    {
        if ($step < 1 || $step > self::TOTAL_STEPS) {
            return redirect()->route('data-collector.farm-record.step', ['step' => 1]);
        }

        $farmRecordData = Session::get('farm_record_data', []);
        $farmRecordId = Session::get('farm_record_id');

        $farmRecord = null;
        if ($farmRecordId) {
            $farmRecord = FarmRecord::where('user_id', auth()->id())->find($farmRecordId);
        }

        return view('data-collector.farm-record.step' . $step, compact('step', 'farmRecordData', 'farmRecord'));
    }

    public function saveStep(Request $request, $step)
    {
        if ($step < 1 || $step > self::TOTAL_STEPS) {
            return redirect()->route('data-collector.farm-record.step', ['step' => 1]);
        }

        $validated = $this->validateStep($request, $step);
        $farmRecordData = Session::get('farm_record_data', []);
        $farmRecordData["step{$step}"] = $validated;
        Session::put('farm_record_data', $farmRecordData);

        $this->saveDraftRecord($farmRecordData);

        if ($step == self::TOTAL_STEPS) {
            return redirect()->route('data-collector.farm-record.review');
        }

        return redirect()->route('data-collector.farm-record.step', ['step' => $step + 1])
            ->with('success', 'Step ' . $step . ' saved successfully!');
    }

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
     * Submit - ULTRA DEFENSIVE VERSION
     */
    public function submit(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $farmRecordData = Session::get('farm_record_data', []);
            
            if (empty($farmRecordData)) {
                throw new \Exception('No data to submit');
            }

            // Merge and clean data
            $allData = $this->mergeStepData($farmRecordData);
            $allData = $this->cleanAndMapFields($allData);
            
            // Required fields
            $allData['user_id'] = auth()->id();
            $allData['created_by_role'] = 'data_collector';
            $allData['status'] = 'submitted';
            $allData['submitted_at'] = now();

            $farmRecordId = Session::get('farm_record_id');
            
            if ($farmRecordId) {
                // Update existing - use DB query builder to be safe
                DB::table('farm_records')
                    ->where('id', $farmRecordId)
                    ->where('user_id', auth()->id())
                    ->update($allData);
                    
                Log::info('Farm record updated', ['id' => $farmRecordId]);
            } else {
                // Insert new - use DB query builder
                $allData['created_at'] = now();
                $allData['updated_at'] = now();
                $farmRecordId = DB::table('farm_records')->insertGetId($allData);
                
                Log::info('Farm record created', ['id' => $farmRecordId]);
            }

            // Update stats - simple increment
            DB::table('data_collector_profiles')
                ->where('user_id', auth()->id())
                ->increment('total_submissions');

            DB::commit();

            // Clear session
            Session::forget('farm_record_data');
            Session::forget('farm_record_id');

            return view('data-collector.farm-record.success');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Farm record submission failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return redirect()->route('data-collector.dashboard')
                ->with('error', 'Submission failed: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $record = FarmRecord::where('user_id', auth()->id())->findOrFail($id);
        return view('data-collector.farm-records.show', compact('record'));
    }

    public function edit($id)
    {
        $farmRecord = FarmRecord::where('user_id', auth()->id())->findOrFail($id);
        Session::put('farm_record_id', $farmRecord->id);
        Session::put('farm_record_data', $this->loadRecordIntoSteps($farmRecord));
        return redirect()->route('data-collector.farm-record.step', ['step' => 1]);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('data-collector.farm-records.show', $id);
    }

    private function validateStep(Request $request, $step)
    {
        $rules = match($step) {
            1 => [
                'farmer_name' => 'required|string|max:255',
                'farmer_email' => 'nullable|email|max:255',
                'farmer_phone' => 'required|string|max:20',
                'farmer_address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string|max:255',
                'lga' => 'nullable|string|max:255',
                'farm_name' => 'nullable|string|max:255',
                'farm_size' => 'nullable|numeric',
                'farm_size_unit' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'farm_type' => 'nullable|string',
                'average_household_size' => 'nullable|integer|min:1',
            ],
            2 => [
                'total_livestock_count' => 'required|integer|min:0',
                'livestock_types' => 'nullable|array',
                'young_count' => 'nullable|integer|min:0',
                'adult_count' => 'nullable|integer|min:0',
                'old_count' => 'nullable|integer|min:0',
            ],
            3 => [
                'last_vaccination_date' => 'nullable|date',
                'has_health_issues' => 'nullable|boolean',
                'current_health_issues' => 'nullable|array',
                'health_notes' => 'nullable|string',
                'veterinarian_name' => 'nullable|string|max:255',
                'veterinarian_phone' => 'nullable|string|max:20',
                'last_vet_visit' => 'nullable|date',
                'past_diseases' => 'nullable|array',
            ],
            4 => [
                'service_needs' => 'nullable|array',
                'urgency_level' => 'nullable|string',
                'service_description' => 'nullable|string',
                'preferred_service_date' => 'nullable|date',
                'needs_immediate_attention' => 'nullable|boolean',
            ],
            5 => [
                'sms_alerts' => 'nullable|boolean',
                'email_alerts' => 'nullable|boolean',
                'phone_alerts' => 'nullable|boolean',
                'alert_types' => 'nullable|array',
                'preferred_contact_method' => 'nullable|string',
                'alternative_phone' => 'nullable|string|max:20',
            ],
            6 => [
                'data_sharing_consent' => 'nullable|boolean',
                'research_participation_consent' => 'nullable|boolean',
                'marketing_consent' => 'nullable|boolean',
                'additional_comments' => 'nullable|string',
                'feedback' => 'nullable|string',
            ],
            default => []
        };

        return $request->validate($rules);
    }

    /**
     * CRITICAL: Clean and map field names to match database columns
     */
    private function cleanAndMapFields($data)
    {
        // Map form fields to database columns
        $mapping = [
            'city' => 'farmer_city',
            'state' => 'farmer_state',
            'lga' => 'farmer_lga',  // ← CRITICAL mapping
        ];

        foreach ($mapping as $formField => $dbField) {
            if (isset($data[$formField])) {
                $data[$dbField] = $data[$formField];
                unset($data[$formField]);  // Remove old key
            }
        }

        // Convert boolean fields
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
            if (isset($data[$field])) {
                $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        // Convert arrays to JSON
        $arrayFields = [
            'livestock_types',
            'current_health_issues',
            'past_diseases',
            'service_needs',
            'alert_types'
        ];
        
        foreach ($arrayFields as $field) {
            if (isset($data[$field])) {
                if (is_array($data[$field])) {
                    $data[$field] = json_encode($data[$field]);
                } elseif (is_string($data[$field]) && !$this->isJson($data[$field])) {
                    $data[$field] = json_encode([$data[$field]]);
                }
            }
        }

        // Remove any keys that don't exist in database
        $validColumns = Schema::getColumnListing('farm_records');
        $cleaned = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $validColumns)) {
                $cleaned[$key] = $value;
            } else {
                Log::warning("Removing invalid column: {$key}");
            }
        }

        return $cleaned;
    }

    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function saveDraftRecord($farmRecordData)
    {
        $allData = $this->mergeStepData($farmRecordData);
        $allData = $this->cleanAndMapFields($allData);
        $allData['status'] = 'draft';
        $allData['user_id'] = auth()->id();
        $allData['created_by_role'] = 'data_collector';

        $farmRecordId = Session::get('farm_record_id');
        
        if ($farmRecordId) {
            DB::table('farm_records')
                ->where('id', $farmRecordId)
                ->where('user_id', auth()->id())
                ->update(array_merge($allData, ['updated_at' => now()]));
                
            $farmRecord = FarmRecord::find($farmRecordId);
        } else {
            $allData['created_at'] = now();
            $allData['updated_at'] = now();
            $id = DB::table('farm_records')->insertGetId($allData);
            $farmRecord = FarmRecord::find($id);
            Session::put('farm_record_id', $id);
        }

        return $farmRecord;
    }

    private function mergeStepData($farmRecordData)
    {
        $merged = [];
        
        foreach ($farmRecordData as $stepKey => $stepData) {
            if (is_array($stepData)) {
                $merged = array_merge($merged, $stepData);
            }
        }

        return $merged;
    }

    private function loadRecordIntoSteps(FarmRecord $record)
    {
        // Decode JSON fields safely
        $decode = function($field) use ($record) {
            $value = $record->$field;
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                return $decoded ?? [];
            }
            return is_array($value) ? $value : [];
        };

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
                'livestock_types' => $decode('livestock_types'),
                'young_count' => $record->young_count,
                'adult_count' => $record->adult_count,
                'old_count' => $record->old_count,
            ],
            'step3' => [
                'last_vaccination_date' => $record->last_vaccination_date,
                'has_health_issues' => $record->has_health_issues,
                'current_health_issues' => $decode('current_health_issues'),
                'health_notes' => $record->health_notes,
                'veterinarian_name' => $record->veterinarian_name,
                'veterinarian_phone' => $record->veterinarian_phone,
                'last_vet_visit' => $record->last_vet_visit,
                'past_diseases' => $decode('past_diseases'),
            ],
            'step4' => [
                'service_needs' => $decode('service_needs'),
                'urgency_level' => $record->urgency_level,
                'service_description' => $record->service_description,
                'preferred_service_date' => $record->preferred_service_date,
                'needs_immediate_attention' => $record->needs_immediate_attention,
            ],
            'step5' => [
                'sms_alerts' => $record->sms_alerts,
                'email_alerts' => $record->email_alerts,
                'phone_alerts' => $record->phone_alerts,
                'alert_types' => $decode('alert_types'),
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

    public function index()
    {
        $records = FarmRecord::where('user_id', auth()->id())
            ->whereIn('status', ['submitted', 'approved', 'under_review', 'rejected'])
            ->latest()
            ->paginate(20);

        return view('data-collector.farm-records.index', compact('records'));
    }

    public function drafts()
    {
        $drafts = FarmRecord::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->latest()
            ->get();

        return view('data-collector.farm-records.drafts', compact('drafts'));
    }
}