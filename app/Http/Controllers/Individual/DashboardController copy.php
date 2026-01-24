<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmRecord;
use App\Models\Livestock;
use App\Models\VaccinationHistory;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Total number of steps in the livestock registration form
     */
    const TOTAL_STEPS = 6;

    /**
     * Show individual dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Get statistics
        $stats = [
            'total_livestock' => Livestock::where('owner_id', $user->id)->count(),
            'healthy_livestock' => Livestock::where('owner_id', $user->id)
                ->where('health_status', 'healthy')->count(),
            'sick_livestock' => Livestock::where('owner_id', $user->id)
                ->whereIn('health_status', ['sick', 'recovering'])->count(),
            'due_for_vaccination' => Livestock::where('owner_id', $user->id)
                ->where('is_vaccinated', false)->count(),
            'pending_service_requests' => ServiceRequest::where('user_id', $user->id)
                ->where('status', 'pending')->count(),
        ];

        // Get recent activities
        $upcoming_vaccinations = Livestock::where('owner_id', $user->id)
            ->where('is_vaccinated', false)
            ->latest()
            ->take(5)
            ->get();

        $recent_service_requests = ServiceRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('individual.dashboard', compact(
            'stats',
            'upcoming_vaccinations',
            'recent_service_requests'
        ));
    }

    /**
     * ========================================
     * LIVESTOCK REGISTRATION - MULTI-STEP FORM
     * ========================================
     */

    /**
     * Start new livestock registration (Step 1)
     */
    public function createLivestock()
    {
        // Clear any existing session data
        Session::forget('livestock_data');
        Session::forget('livestock_id');
        
        return redirect()->route('individual.livestock.step', ['step' => 1]);
    }

    /**
     * Show specific step of livestock registration
     */
    public function showStep($step)
    {
        // Validate step number
        if ($step < 1 || $step > self::TOTAL_STEPS) {
            return redirect()->route('individual.livestock.step', ['step' => 1]);
        }

        // Get existing data from session
        $livestockData = Session::get('livestock_data', []);
        $livestockId = Session::get('livestock_id');

        // Load existing livestock if editing
        $livestock = null;
        if ($livestockId) {
            $livestock = Livestock::where('owner_id', auth()->id())
                ->find($livestockId);
        }

        return view('individual.livestock.step' . $step, compact('step', 'livestockData', 'livestock'));
    }

    /**
     * Save step data
     */
    public function saveStep(Request $request, $step)
    {
        // Validate step number
        if ($step < 1 || $step > self::TOTAL_STEPS) {
            return redirect()->route('individual.livestock.step', ['step' => 1]);
        }

        // Validate the step data
        $validated = $this->validateStep($request, $step);

        // Get existing data from session
        $livestockData = Session::get('livestock_data', []);

        // Merge new data with existing data
        $livestockData["step{$step}"] = $validated;
        Session::put('livestock_data', $livestockData);

        // Get or create livestock record
        $livestockId = Session::get('livestock_id');
        
        if ($livestockId) {
            // Update existing record
            $livestock = Livestock::where('owner_id', auth()->id())->find($livestockId);
            if ($livestock) {
                $allData = $this->mergeStepData($livestockData);
                $livestock->update($allData);
            }
        } else {
            // Create new record ONLY on first access
            $allData = $this->mergeStepData($livestockData);
            $allData['owner_id'] = auth()->id();
            $allData['user_id'] = auth()->id();
            $allData['farm_record_id'] = null;
            $allData['status'] = 'draft';
            
            // Check for existing tag_number to avoid duplicates
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

        // If last step, finalize and redirect
        if ($step == self::TOTAL_STEPS) {
            // Mark as active (not draft)
            if ($livestock) {
                $livestock->update(['status' => 'active']);
            }
            
            Session::forget('livestock_data');
            Session::forget('livestock_id');
            
            return redirect()->route('individual.livestock.index')
                ->with('success', 'Livestock registered successfully!');
        }

        // Go to next step
        return redirect()->route('individual.livestock.step', ['step' => $step + 1])
            ->with('success', 'Step ' . $step . ' saved!');
    }

    /**
     * Save as draft
     */
    public function saveDraft(Request $request)
    {
        $livestockData = Session::get('livestock_data', []);
        
        if (empty($livestockData)) {
            return redirect()->route('individual.dashboard')
                ->with('error', 'No data to save as draft.');
        }

        $allData = $this->mergeStepData($livestockData);
        $allData['owner_id'] = auth()->id();
        $allData['user_id'] = auth()->id();
        $allData['farm_record_id'] = null;
        $allData['status'] = 'draft';

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

        // Merge all step data
        $allData = $this->mergeStepData($livestockData);
        $allData['owner_id'] = auth()->id();
        $allData['user_id'] = auth()->id();
        $allData['farm_record_id'] = null;
        $allData['status'] = 'active'; // Mark as active

        // Create or update livestock
        $livestockId = Session::get('livestock_id');
        
        if ($livestockId) {
            $livestock = Livestock::where('owner_id', auth()->id())->find($livestockId);
            if ($livestock) {
                $livestock->update($allData);
            }
        } else {
            $livestock = Livestock::create($allData);
        }

        // Clear session
        Session::forget('livestock_data');
        Session::forget('livestock_id');

        return redirect()->route('individual.livestock.index')
            ->with('success', 'Livestock registered successfully!');
    }

    /**
     * Validate step data
     */
    private function validateStep(Request $request, $step)
    {
        switch ($step) {
            case 1: // Basic Information
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

            case 2: // Physical Characteristics
                return $request->validate([
                    'weight' => 'nullable|numeric|min:0',
                    'weight_unit' => 'nullable|string|in:kg,lbs',
                    'height' => 'nullable|numeric|min:0',
                    'color' => 'nullable|string|max:255',
                    'markings' => 'nullable|string',
                ]);

            case 3: // Health Status
                return $request->validate([
                    'health_status' => 'required|in:healthy,sick,recovering,deceased',
                    'current_conditions' => 'nullable|string',
                    'last_health_check' => 'nullable|date',
                    'veterinarian_name' => 'nullable|string|max:255',
                    'veterinarian_phone' => 'nullable|string|max:20',
                    'quarantine_status' => 'nullable|boolean',
                ]);

            case 4: // Vaccination History
                return $request->validate([
                    'is_vaccinated' => 'nullable|boolean',
                    'last_vaccination_date' => 'nullable|date',
                    'total_vaccinations' => 'nullable|integer|min:0',
                    'has_due_vaccinations' => 'nullable|boolean',
                    'next_vaccination_date' => 'nullable|date',
                    'vaccination_notes' => 'nullable|string',
                ]);

            case 5: // Production & Purpose
                return $request->validate([
                    'production_purpose' => 'nullable|in:meat,dairy,eggs,breeding,work,mixed,other',
                    'daily_milk_production' => 'nullable|numeric|min:0',
                    'monthly_egg_production' => 'nullable|integer|min:0',
                    'daily_feed_amount' => 'nullable|numeric|min:0',
                    'feeding_schedule' => 'nullable|string',
                    'dietary_notes' => 'nullable|string',
                ]);

            case 6: // Origin & Documents
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

    /**
     * Merge step data into single array
     */
    private function mergeStepData($livestockData)
    {
        $merged = [];
        
        foreach ($livestockData as $stepKey => $stepData) {
            $merged = array_merge($merged, $stepData);
        }

        return $merged;
    }

    /**
     * ========================================
     * LIVESTOCK MANAGEMENT
     * ========================================
     */

    /**
     * Show livestock list
     */
    public function livestock()
    {
        $livestock = Livestock::where('owner_id', auth()->id())
            ->where('status', 'active') // Only show active livestock
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

        // Load livestock data into session
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

    /**
     * Load livestock into step format
     */
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

    /**
     * ========================================
     * VACCINATION MANAGEMENT
     * ========================================
     */

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

    /**
     * ========================================
     * SERVICE REQUESTS
     * ========================================
     */

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
     * Store service request
     */
    public function storeServiceRequest(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|string',
            'livestock_id' => 'nullable|exists:livestock,id',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,emergency',
            'preferred_date' => 'nullable|date',
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
     * Cancel service request
     */
    public function cancelServiceRequest($id)
    {
        $serviceRequest = ServiceRequest::where('user_id', auth()->id())
            ->findOrFail($id);

        if ($serviceRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending requests can be cancelled.');
        }

        $serviceRequest->update(['status' => 'cancelled']);

        return redirect()->route('individual.service-requests.index')
            ->with('success', 'Service request cancelled successfully!');
    }

    /**
     * Show service request details
     */
    // public function showServiceRequest($id)
    // {
    //     $serviceRequest = ServiceRequest::where('user_id', auth()->id())
    //         ->with('livestock')
    //         ->findOrFail($id);
        
    //     return view('individual.service-requests.show', compact('serviceRequest'));
    // }

    /**
     * ========================================
     * PROFILE
     * ========================================
     */

    /**
     * Show profile
     */
    public function profile()
    {
        $user = auth()->user();
        return view('individual.profile', compact('user'));
    }
}