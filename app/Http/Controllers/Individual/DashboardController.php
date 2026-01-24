<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmRecord;
use App\Models\Livestock;
use App\Models\VaccinationHistory;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Total number of steps in the livestock registration form
     */
    const TOTAL_LIVESTOCK_STEPS = 6;

    /**
     * Total number of steps in the farm record form
     */
    const TOTAL_FARM_RECORD_STEPS = 3;

    /**
     * Show individual dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Get user's farm records
        $farmRecords = FarmRecord::where('user_id', $user->id)
            ->orWhere('farmer_id', $user->id)
            ->get();

        // Get statistics
        $stats = [
            'total_farm_records' => $farmRecords->count(),
            'total_livestock' => Livestock::where('owner_id', $user->id)->count(),
            'healthy_livestock' => Livestock::where('owner_id', $user->id)->where('health_status', 'healthy')->count(),
            'sick_livestock' => Livestock::where('owner_id', $user->id)->whereIn('health_status', ['sick', 'recovering'])->count(),
            'vaccinated_livestock' => Livestock::where('owner_id', $user->id)->where('is_vaccinated', true)->count(),
            'due_for_vaccination' => Livestock::where('owner_id', $user->id)->where('is_vaccinated', false)->count(),
            'total_vaccinations' => VaccinationHistory::where('user_id', $user->id)->count(),
            'recent_vaccinations' => VaccinationHistory::where('user_id', $user->id)
                ->where('vaccination_date', '>=', now()->subMonth())
                ->count(),
            'total_service_requests' => ServiceRequest::where('user_id', $user->id)->count(),
            'pending_service_requests' => ServiceRequest::where('user_id', $user->id)->where('status', 'pending')->count(),
            'completed_service_requests' => ServiceRequest::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        // Get recent activities
        $recent_livestock = Livestock::where('owner_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        $recent_vaccinations = VaccinationHistory::where('user_id', $user->id)
            ->with('livestock')
            ->latest('vaccination_date')
            ->take(5)
            ->get();

        $recent_service_requests = ServiceRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $upcoming_vaccinations = Livestock::where('owner_id', $user->id)
            ->where('is_vaccinated', false)
            ->take(5)
            ->get();

        return view('individual.dashboard', compact(
            'stats',
            'farmRecords',
            'recent_livestock',
            'recent_vaccinations',
            'recent_service_requests',
            'upcoming_vaccinations'
        ));
    }

    // ========================================
    // LIVESTOCK MANAGEMENT
    // ========================================

    /**
     * Start new livestock registration (Step 1)
     */
    public function createLivestock()
    {
        Session::forget('livestock_data');
        Session::forget('livestock_id');
        
        return redirect()->route('individual.livestock.step', ['step' => 1]);
    }

    /**
     * Show specific step of livestock registration
     */
    public function showStep($step)
    {
        if ($step < 1 || $step > self::TOTAL_LIVESTOCK_STEPS) {
            return redirect()->route('individual.livestock.step', ['step' => 1]);
        }

        $livestockData = Session::get('livestock_data', []);
        $livestockId = Session::get('livestock_id');

        $livestock = null;
        if ($livestockId) {
            $livestock = Livestock::where('owner_id', auth()->id())->find($livestockId);
        }

        return view('individual.livestock.step' . $step, compact('step', 'livestockData', 'livestock'));
    }

    /**
     * Save livestock step data
     */
    public function saveStep(Request $request, $step)
    {
        if ($step < 1 || $step > self::TOTAL_LIVESTOCK_STEPS) {
            return redirect()->route('individual.livestock.step', ['step' => 1]);
        }

        $validated = $this->validateLivestockStep($request, $step);

        $livestockData = Session::get('livestock_data', []);
        $livestockData["step{$step}"] = $validated;
        Session::put('livestock_data', $livestockData);

        $livestockId = Session::get('livestock_id');
        
        if ($livestockId) {
            $livestock = Livestock::where('owner_id', auth()->id())->find($livestockId);
            if ($livestock) {
                $allData = $this->mergeLivestockStepData($livestockData);
                $livestock->update($allData);
            }
        } else {
            $allData = $this->mergeLivestockStepData($livestockData);
            $allData['owner_id'] = auth()->id();
            $allData['user_id'] = auth()->id();
            $allData['status'] = 'active';
            
            if (!empty($allData['tag_number'])) {
                $existing = Livestock::where('owner_id', auth()->id())
                    ->where('tag_number', $allData['tag_number'])
                    ->first();
                if ($existing) {
                    $livestock = $existing;
                    $livestock->update($allData);
                } else {
                    $livestock = Livestock::create($allData);
                }
            } else {
                $livestock = Livestock::create($allData);
            }
            
            Session::put('livestock_id', $livestock->id);
        }

        if ($step == self::TOTAL_LIVESTOCK_STEPS) {
            Session::forget('livestock_data');
            Session::forget('livestock_id');
            return redirect()->route('individual.livestock.index')
                ->with('success', 'Livestock registered successfully!');
        }

        return redirect()->route('individual.livestock.step', ['step' => $step + 1])
            ->with('success', 'Step ' . $step . ' saved!');
    }

    /**
     * Save livestock as draft
     */
    public function saveDraft(Request $request)
    {
        $livestockData = Session::get('livestock_data', []);
        
        if (empty($livestockData)) {
            return redirect()->route('individual.dashboard')
                ->with('error', 'No data to save as draft.');
        }

        $this->saveDraftLivestock($livestockData);

        return redirect()->route('individual.dashboard')
            ->with('success', 'Livestock registration saved as draft!');
    }

    /**
     * Submit final livestock
     */
    public function submitLivestock(Request $request)
    {
        $livestockData = Session::get('livestock_data', []);
        
        if (empty($livestockData)) {
            return redirect()->route('individual.dashboard')
                ->with('error', 'No data to submit.');
        }

        $allData = $this->mergeLivestockStepData($livestockData);
        $allData['owner_id'] = auth()->id();
        $allData['user_id'] = auth()->id();
        $allData['status'] = 'active';

        $livestockId = Session::get('livestock_id');
        
        if ($livestockId) {
            $livestock = Livestock::where('owner_id', auth()->id())->find($livestockId);
            if ($livestock) {
                $livestock->update($allData);
            }
        } else {
            $livestock = Livestock::create($allData);
        }

        Session::forget('livestock_data');
        Session::forget('livestock_id');

        return redirect()->route('individual.livestock.index')
            ->with('success', 'Livestock registered successfully!');
    }

    /**
     * Show livestock list
     */
    public function livestock()
    {
        $livestock = Livestock::where('owner_id', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->paginate(20);

        return view('individual.livestock.index', compact('livestock'));
    }

    /**
     * Show livestock details
     */
    public function showLivestock($id)
    {
        $livestock = Livestock::where('owner_id', auth()->id())
            ->with(['vaccinationHistory', 'serviceRequests'])
            ->findOrFail($id);

        return view('individual.livestock.show', compact('livestock'));
    }

    /**
     * Edit livestock
     */
    public function editLivestock($id)
    {
        $livestock = Livestock::where('owner_id', auth()->id())
            ->findOrFail($id);

        Session::put('livestock_id', $livestock->id);
        Session::put('livestock_data', $this->loadLivestockIntoSteps($livestock));

        return redirect()->route('individual.livestock.step', ['step' => 1]);
    }

    /**
     * Delete livestock
     */
    public function deleteLivestock($id)
    {
        $livestock = Livestock::where('owner_id', auth()->id())
            ->findOrFail($id);

        $livestock->delete();

        return redirect()->route('individual.livestock.index')
            ->with('success', 'Livestock deleted successfully!');
    }

    // ========================================
    // SERVICE REQUESTS CRUD
    // ========================================

    /**
     * Show service requests list
     */
    public function serviceRequests()
    {
        $serviceRequests = ServiceRequest::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('individual.service-requests.index', compact('serviceRequests'));
    }

    /**
     * Show create service request form
     */
    public function createServiceRequest()
    {
        $livestock = Livestock::where('owner_id', auth()->id())
            ->where('status', 'active')
            ->get();

        return view('individual.service-requests.create', compact('livestock'));
    }

    /**
     * Store new service request
     */
    public function storeServiceRequest(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|string',
            'livestock_id' => 'nullable|exists:livestock,id',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,emergency',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'contact_phone' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        ServiceRequest::create($validated);

        return redirect()->route('individual.service-requests.index')
            ->with('success', 'Service request submitted successfully!');
    }

    /**
     * Show service request details
     */
    public function showServiceRequest($id)
    {
        $serviceRequest = ServiceRequest::where('user_id', auth()->id())
            ->with('livestock')
            ->findOrFail($id);

        return view('individual.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Show edit service request form
     */
    public function editServiceRequest($id)
    {
        $serviceRequest = ServiceRequest::where('user_id', auth()->id())
            ->findOrFail($id);

        // Only allow editing if pending
        if ($serviceRequest->status !== 'pending') {
            return redirect()->route('individual.service-requests.show', $id)
                ->with('error', 'You can only edit pending requests.');
        }

        $livestock = Livestock::where('owner_id', auth()->id())
            ->where('status', 'active')
            ->get();

        return view('individual.service-requests.edit', compact('serviceRequest', 'livestock'));
    }

    /**
     * Update service request
     */
    public function updateServiceRequest(Request $request, $id)
    {
        $serviceRequest = ServiceRequest::where('user_id', auth()->id())
            ->findOrFail($id);

        // Only allow updating if pending
        if ($serviceRequest->status !== 'pending') {
            return redirect()->route('individual.service-requests.show', $id)
                ->with('error', 'You can only update pending requests.');
        }

        $validated = $request->validate([
            'service_type' => 'required|string',
            'livestock_id' => 'nullable|exists:livestock,id',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,emergency',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'contact_phone' => 'required|string',
            'location' => 'nullable|string',
        ]);

        $serviceRequest->update($validated);

        return redirect()->route('individual.service-requests.show', $id)
            ->with('success', 'Service request updated successfully!');
    }

    /**
     * Cancel service request
     */
    public function cancelServiceRequest($id)
    {
        $serviceRequest = ServiceRequest::where('user_id', auth()->id())
            ->findOrFail($id);

        // Only allow canceling if pending
        if ($serviceRequest->status !== 'pending') {
            return redirect()->route('individual.service-requests.show', $id)
                ->with('error', 'You can only cancel pending requests.');
        }

        $serviceRequest->update(['status' => 'cancelled']);

        return redirect()->route('individual.service-requests.index')
            ->with('success', 'Service request cancelled successfully!');
    }

    // ========================================
    // FARM RECORDS CRUD
    // ========================================

    /**
     * Start new farm record submission (Step 1)
     */
    public function createFarmRecord()
    {
        Session::forget('farm_record_data');
        Session::forget('farm_record_id');
        
        return redirect()->route('individual.farm-records.step', ['step' => 1]);
    }

    /**
     * Show specific step of farm record form
     */
    public function showFarmRecordStep($step)
    {
        if ($step < 1 || $step > self::TOTAL_FARM_RECORD_STEPS) {
            return redirect()->route('individual.farm-records.step', ['step' => 1]);
        }

        $formData = Session::get('farm_record_data', []);

        return view('individual.farm-records.step' . $step, compact('step', 'formData'));
    }

    /**
     * Save farm record step data
     */
    public function saveFarmRecordStep(Request $request, $step)
    {
        if ($step < 1 || $step > self::TOTAL_FARM_RECORD_STEPS) {
            return redirect()->route('individual.farm-records.step', ['step' => 1]);
        }

        $validated = $this->validateFarmRecordStep($request, $step);

        $formData = Session::get('farm_record_data', []);
        $formData["step{$step}"] = $validated;
        Session::put('farm_record_data', $formData);

        if ($step == self::TOTAL_FARM_RECORD_STEPS) {
            // Don't submit yet, just go to final step
            return redirect()->route('individual.farm-records.step', ['step' => $step]);
        }

        return redirect()->route('individual.farm-records.step', ['step' => $step + 1])
            ->with('success', 'Step ' . $step . ' saved!');
    }

    /**
     * Submit final farm record
     */
    public function submitFarmRecord(Request $request)
    {
        $formData = Session::get('farm_record_data', []);
        
        if (empty($formData)) {
            return redirect()->route('individual.dashboard')
                ->with('error', 'No data to submit.');
        }

        $allData = $this->mergeFarmRecordStepData($formData);
        $allData['user_id'] = auth()->id();
        $allData['farmer_id'] = auth()->id();
        $allData['status'] = 'submitted';
        $allData['submitted_at'] = now();
        $allData['created_by_role'] = 'individual';

        // Convert arrays to JSON
        if (isset($allData['livestock_types'])) {
            $allData['livestock_types'] = json_encode($allData['livestock_types']);
        }
        if (isset($allData['common_diseases'])) {
            $allData['common_diseases'] = json_encode($allData['common_diseases']);
        }
        if (isset($allData['preferred_services'])) {
            $allData['preferred_services'] = json_encode($allData['preferred_services']);
        }
        if (isset($allData['training_needs'])) {
            $allData['training_needs'] = json_encode($allData['training_needs']);
        }

        FarmRecord::create($allData);

        Session::forget('farm_record_data');
        Session::forget('farm_record_id');

        return redirect()->route('individual.dashboard')
            ->with('success', 'Farm record submitted successfully! It will be reviewed by an administrator.');
    }

    /**
     * Show farm record details
     */
    public function showFarmRecord($id)
    {
        $record = FarmRecord::where('user_id', auth()->id())
            ->orWhere('farmer_id', auth()->id())
            ->findOrFail($id);

        return view('individual.farm-records.show', compact('record'));
    }

    /**
     * Edit farm record (redirect to step 1)
     */
    public function editFarmRecord($id)
    {
        $record = FarmRecord::where('user_id', auth()->id())
            ->orWhere('farmer_id', auth()->id())
            ->findOrFail($id);

        // Only allow editing if not approved
        if ($record->status === 'approved') {
            return redirect()->route('individual.farm-records.show', $id)
                ->with('error', 'You cannot edit approved farm records.');
        }

        Session::put('farm_record_id', $record->id);
        Session::put('farm_record_data', $this->loadFarmRecordIntoSteps($record));

        return redirect()->route('individual.farm-records.step', ['step' => 1]);
    }

    // ========================================
    // VACCINATIONS
    // ========================================

    /**
     * Show vaccination history
     */
    public function vaccinations()
    {
        $vaccinations = VaccinationHistory::where('user_id', auth()->id())
            ->with('livestock')
            ->latest('vaccination_date')
            ->paginate(20);

        return view('individual.vaccinations.index', compact('vaccinations'));
    }

    // ========================================
    // PROFILE
    // ========================================

    /**
     * Show profile
     */
    public function profile()
    {
        $user = auth()->user();
        return view('individual.profile', compact('user'));
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    private function validateLivestockStep(Request $request, $step)
    {
        switch ($step) {
            case 1:
                return $request->validate([
                    'livestock_type' => 'required|string',
                    'other_type' => 'nullable|string|max:255',
                    'tag_number' => 'nullable|string|max:255',
                    'name' => 'nullable|string|max:255',
                    'breed' => 'nullable|string|max:255',
                    'gender' => 'required|in:male,female,unknown',
                    'date_of_birth' => 'nullable|date',
                    'age_years' => 'nullable|integer|min:0',
                    'age_months' => 'nullable|integer|min:0|max:11',
                ]);
            case 2:
                return $request->validate([
                    'weight' => 'nullable|numeric|min:0',
                    'weight_unit' => 'nullable|string|in:kg,lbs',
                    'height' => 'nullable|numeric|min:0',
                    'color' => 'nullable|string|max:255',
                    'markings' => 'nullable|string',
                ]);
            case 3:
                return $request->validate([
                    'health_status' => 'required|in:healthy,sick,recovering,deceased',
                    'current_conditions' => 'nullable|string',
                    'last_health_check' => 'nullable|date',
                    'veterinarian_name' => 'nullable|string|max:255',
                    'veterinarian_phone' => 'nullable|string|max:20',
                    'quarantine_status' => 'nullable|boolean',
                ]);
            case 4:
                return $request->validate([
                    'is_vaccinated' => 'nullable|boolean',
                    'last_vaccination_date' => 'nullable|date',
                    'total_vaccinations' => 'nullable|integer|min:0',
                    'has_due_vaccinations' => 'nullable|boolean',
                    'next_vaccination_date' => 'nullable|date',
                    'vaccination_notes' => 'nullable|string',
                ]);
            case 5:
                return $request->validate([
                    'production_purpose' => 'nullable|in:meat,dairy,eggs,breeding,work,mixed,other',
                    'daily_milk_production' => 'nullable|numeric|min:0',
                    'monthly_egg_production' => 'nullable|integer|min:0',
                    'daily_feed_amount' => 'nullable|numeric|min:0',
                    'feeding_schedule' => 'nullable|string',
                    'dietary_notes' => 'nullable|string',
                ]);
            case 6:
                return $request->validate([
                    'acquisition_method' => 'nullable|in:birth,purchase,gift,inheritance,other',
                    'acquisition_date' => 'nullable|date',
                    'previous_owner' => 'nullable|string|max:255',
                    'purchase_price' => 'nullable|numeric|min:0',
                    'notes' => 'nullable|string',
                ]);
            default:
                return [];
        }
    }

    private function validateFarmRecordStep(Request $request, $step)
    {
        switch ($step) {
            case 1:
                return $request->validate([
                    'farmer_name' => 'required|string|max:255',
                    'farmer_phone' => 'required|string|max:20',
                    'farmer_email' => 'required|email|max:255',
                    'farmer_village' => 'required|string|max:255',
                    'farmer_state' => 'required|string|max:255',
                    'farmer_country' => 'required|string|max:255',
                    'household_size' => 'required|integer|min:1',
                    'role_in_sector' => 'required|string',
                ]);
            case 2:
                return $request->validate([
                    'livestock_types' => 'required|array',
                    'total_livestock_count' => 'required|integer|min:1',
                    'vaccination_status' => 'required|string',
                    'common_diseases' => 'nullable|array',
                    'other_diseases_text' => 'nullable|string',
                ]);
            case 3:
                return $request->validate([
                    'preferred_services' => 'required|array',
                    'has_cold_chain' => 'required|boolean',
                    'training_needs' => 'nullable|array',
                    'alerts_outbreak' => 'nullable|boolean',
                    'alerts_vaccine' => 'nullable|boolean',
                    'alerts_awareness' => 'nullable|boolean',
                    'alerts_public' => 'nullable|boolean',
                    'preferred_language' => 'required|string',
                    'feedback' => 'nullable|string',
                    'consent_data_use' => 'required|boolean',
                ]);
            default:
                return [];
        }
    }

    private function mergeLivestockStepData($livestockData)
    {
        $merged = [];
        foreach ($livestockData as $stepKey => $stepData) {
            $merged = array_merge($merged, $stepData);
        }
        return $merged;
    }

    private function mergeFarmRecordStepData($formData)
    {
        $merged = [];
        foreach ($formData as $stepKey => $stepData) {
            $merged = array_merge($merged, $stepData);
        }
        return $merged;
    }

    private function saveDraftLivestock($livestockData)
    {
        $allData = $this->mergeLivestockStepData($livestockData);
        $allData['owner_id'] = auth()->id();
        $allData['user_id'] = auth()->id();
        $allData['status'] = 'active';

        $livestockId = Session::get('livestock_id');
        
        if ($livestockId) {
            $livestock = Livestock::where('owner_id', auth()->id())->find($livestockId);
            if ($livestock) {
                $livestock->update($allData);
            } else {
                $livestock = Livestock::create($allData);
                Session::put('livestock_id', $livestock->id);
            }
        } else {
            $livestock = Livestock::create($allData);
            Session::put('livestock_id', $livestock->id);
        }

        return $livestock;
    }

    private function loadLivestockIntoSteps(Livestock $livestock)
    {
        return [
            'step1' => [
                'livestock_type' => $livestock->livestock_type,
                'other_type' => $livestock->other_type,
                'tag_number' => $livestock->tag_number,
                'name' => $livestock->name,
                'breed' => $livestock->breed,
                'gender' => $livestock->gender,
                'date_of_birth' => $livestock->date_of_birth,
                'age_years' => $livestock->age_years,
                'age_months' => $livestock->age_months,
            ],
            'step2' => [
                'weight' => $livestock->weight,
                'weight_unit' => $livestock->weight_unit,
                'height' => $livestock->height,
                'color' => $livestock->color,
                'markings' => $livestock->markings,
            ],
            'step3' => [
                'health_status' => $livestock->health_status,
                'current_conditions' => $livestock->current_conditions,
                'last_health_check' => $livestock->last_health_check,
                'veterinarian_name' => $livestock->veterinarian_name,
                'veterinarian_phone' => $livestock->veterinarian_phone,
                'quarantine_status' => $livestock->quarantine_status,
            ],
            'step4' => [
                'is_vaccinated' => $livestock->is_vaccinated,
                'last_vaccination_date' => $livestock->last_vaccination_date,
                'total_vaccinations' => $livestock->total_vaccinations,
                'has_due_vaccinations' => $livestock->has_due_vaccinations,
                'next_vaccination_date' => $livestock->next_vaccination_date,
                'vaccination_notes' => $livestock->vaccination_notes,
            ],
            'step5' => [
                'production_purpose' => $livestock->production_purpose,
                'daily_milk_production' => $livestock->daily_milk_production,
                'monthly_egg_production' => $livestock->monthly_egg_production,
                'daily_feed_amount' => $livestock->daily_feed_amount,
                'feeding_schedule' => $livestock->feeding_schedule,
                'dietary_notes' => $livestock->dietary_notes,
            ],
            'step6' => [
                'acquisition_method' => $livestock->acquisition_method,
                'acquisition_date' => $livestock->acquisition_date,
                'previous_owner' => $livestock->previous_owner,
                'purchase_price' => $livestock->purchase_price,
                'notes' => $livestock->notes,
            ],
        ];
    }

    private function loadFarmRecordIntoSteps(FarmRecord $record)
    {
        return [
            'step1' => [
                'farmer_name' => $record->farmer_name,
                'farmer_phone' => $record->farmer_phone,
                'farmer_email' => $record->farmer_email,
                'farmer_village' => $record->farmer_village,
                'farmer_state' => $record->farmer_state,
                'farmer_country' => $record->farmer_country,
                'household_size' => $record->household_size,
                'role_in_sector' => $record->role_in_sector,
            ],
            'step2' => [
                'livestock_types' => json_decode($record->livestock_types, true) ?? [],
                'total_livestock_count' => $record->total_livestock_count,
                'vaccination_status' => $record->vaccination_status,
                'common_diseases' => json_decode($record->common_diseases, true) ?? [],
                'other_diseases_text' => $record->other_diseases_text,
            ],
            'step3' => [
                'preferred_services' => json_decode($record->preferred_services, true) ?? [],
                'has_cold_chain' => $record->has_cold_chain,
                'training_needs' => json_decode($record->training_needs, true) ?? [],
                'alerts_outbreak' => $record->alerts_outbreak,
                'alerts_vaccine' => $record->alerts_vaccine,
                'alerts_awareness' => $record->alerts_awareness,
                'alerts_public' => $record->alerts_public,
                'preferred_language' => $record->preferred_language,
                'feedback' => $record->feedback,
                'consent_data_use' => $record->consent_data_use,
            ],
        ];
    }
    public function farmRecordStep1()
{
    return view('farmer.farm-records.step1');
}

public function storeFarmRecordStep1(Request $request)
{
    $validated = $request->validate([
        'farmer_name' => 'required|string|max:255',
        'farmer_email' => 'nullable|email|max:255',
        'farmer_phone' => 'required|string|max:20',
        'farmer_address' => 'required|string',
        'farmer_city' => 'required|string|max:255',
        'farmer_state' => 'required|string|max:255',
        'farmer_lga' => 'nullable|string|max:255',
        'farm_name' => 'nullable|string|max:255',
        'farm_size' => 'nullable|numeric|min:0',
        'farm_size_unit' => 'nullable|in:hectares,acres',
        'farm_type' => 'required|in:subsistence,commercial,mixed',
        'average_household_size' => 'nullable|integer|min:1',
    ]);

    // Store in session
    session(['farm_record_step1' => $validated]);
    
    return redirect()->route('individual.farm-records.step2');
}
public function farmRecordStep2()
{
    // Redirect back if step 1 not completed
    if (!session('farm_record_step1')) {
        return redirect()->route('individual.farm-records.step1')
            ->with('error', 'Please complete Step 1 first.');
    }
    
    return view('farmer.farm-records.step2');
}

public function storeFarmRecordStep2(Request $request)
{
    $validated = $request->validate([
        'total_livestock_count' => 'required|integer|min:0',
        'livestock_types' => 'required|array|min:1',
        'livestock_types.*' => 'in:cattle,goats,sheep,poultry,pigs,other',
        'young_count' => 'nullable|integer|min:0',
        'adult_count' => 'nullable|integer|min:0',
        'old_count' => 'nullable|integer|min:0',
        'breed_information' => 'nullable|string',
        'vaccination_status' => 'required|in:up_to_date,partially_vaccinated,not_vaccinated',
    ]);

    // Convert arrays to JSON for storage
    $validated['livestock_types'] = json_encode($validated['livestock_types']);
    
    // Store in session
    session(['farm_record_step2' => $validated]);
    
    return redirect()->route('individual.farm-records.step3');
}
public function farmRecordStep3()
{
    if (!session('farm_record_step2')) {
        return redirect()->route('individual.farm-records.step2')
            ->with('error', 'Please complete Step 2 first.');
    }
    
    return view('farmer.farm-records.step3');
}

public function storeFarmRecordStep3(Request $request)
{
    $validated = $request->validate([
        'last_vaccination_date' => 'nullable|date',
        'past_diseases' => 'nullable|array',
        'past_diseases.*' => 'in:fmd,newcastle,anthrax,cbpp,lsd,ppr,fasciolosis,trypanosomosis,mastitis,brucellosis,other',
        'has_health_issues' => 'nullable|boolean',
        'health_notes' => 'nullable|string',
        'veterinarian_name' => 'nullable|string|max:255',
        'veterinarian_phone' => 'nullable|string|max:20',
        'last_vet_visit' => 'nullable|date',
        'disease_outbreak_history' => 'nullable|boolean',
        'disease_notes' => 'nullable|string',
    ]);

    // Convert arrays to JSON
    if (isset($validated['past_diseases'])) {
        $validated['past_diseases'] = json_encode($validated['past_diseases']);
    }
    
    session(['farm_record_step3' => $validated]);
    
    return redirect()->route('individual.farm-records.step4');
}
public function farmRecordStep4()
{
    if (!session('farm_record_step3')) {
        return redirect()->route('individual.farm-records.step3')
            ->with('error', 'Please complete Step 3 first.');
    }
    
    return view('farmer.farm-records.step4');
}

public function storeFarmRecordStep4(Request $request)
{
    $validated = $request->validate([
        'service_needs' => 'nullable|array',
        'service_needs.*' => 'in:vaccination,treatment,consultation,emergency,breeding,nutrition',
        'urgency_level' => 'required|in:low,medium,high,emergency',
        'service_description' => 'nullable|string',
        'preferred_service_date' => 'nullable|date',
        'preferred_vet_services' => 'nullable|array',
        'preferred_vet_services.*' => 'in:on_site,mobile_clinic,tele_vet',
        'cold_chain_access' => 'nullable|boolean',
        'training_needs' => 'nullable|array',
        'training_needs.*' => 'in:disease_prevention,animal_nutrition,biosecurity',
        'needs_immediate_attention' => 'nullable|boolean',
    ]);

    // Convert arrays to JSON
    foreach (['service_needs', 'preferred_vet_services', 'training_needs'] as $field) {
        if (isset($validated[$field])) {
            $validated[$field] = json_encode($validated[$field]);
        }
    }
    
    session(['farm_record_step4' => $validated]);
    
    return redirect()->route('individual.farm-records.step5');
}
public function farmRecordStep5()
{
    if (!session('farm_record_step4')) {
        return redirect()->route('individual.farm-records.step4')
            ->with('error', 'Please complete Step 4 first.');
    }
    
    return view('farmer.farm-records.step5');
}

public function storeFarmRecordStep5(Request $request)
{
    $validated = $request->validate([
        'sms_alerts' => 'nullable|boolean',
        'email_alerts' => 'nullable|boolean',
        'phone_alerts' => 'nullable|boolean',
        'alert_types' => 'nullable|array',
        'alert_types.*' => 'in:outbreak,vaccine_availability,awareness_campaigns,public_announcements,vaccination_reminders',
        'preferred_contact_method' => 'required|in:sms,email,phone,whatsapp',
        'alternative_phone' => 'nullable|string|max:20',
    ]);

    // Convert arrays to JSON
    if (isset($validated['alert_types'])) {
        $validated['alert_types'] = json_encode($validated['alert_types']);
    }
    
    session(['farm_record_step5' => $validated]);
    
    return redirect()->route('individual.farm-records.step6');
}
public function farmRecordStep6()
{
    if (!session('farm_record_step5')) {
        return redirect()->route('individual.farm-records.step5')
            ->with('error', 'Please complete Step 5 first.');
    }
    
    return view('farmer.farm-records.step6');
}

public function storeFarmRecordStep6(Request $request)
{
    $validated = $request->validate([
        'data_sharing_consent' => 'required|accepted',
        'research_participation_consent' => 'nullable|boolean',
        'marketing_consent' => 'nullable|boolean',
        'preferred_language' => 'required|in:english,hausa,yoruba,igbo,fulfulde,pidgin,french,swahili',
        'form_rating' => 'nullable|integer|min:1|max:5',
        'feedback' => 'nullable|string',
        'additional_comments' => 'nullable|string',
    ]);

    // Combine all session data
    $allData = array_merge(
        session('farm_record_step1', []),
        session('farm_record_step2', []),
        session('farm_record_step3', []),
        session('farm_record_step4', []),
        session('farm_record_step5', []),
        $validated
    );

    // Add system fields
    $allData['user_id'] = auth()->id();
    $allData['created_by_role'] = 'individual';
    $allData['status'] = 'submitted';
    $allData['submitted_at'] = now();

    // Create farm record
    $farmRecord = FarmRecord::create($allData);

    // Clear all session data
    session()->forget([
        'farm_record_step1',
        'farm_record_step2',
        'farm_record_step3',
        'farm_record_step4',
        'farm_record_step5',
    ]);

    // Redirect with success message
    return redirect()->route('individual.dashboard')
        ->with('success', 'Farm record submitted successfully! Our team will review it within 24 hours.');
}
}