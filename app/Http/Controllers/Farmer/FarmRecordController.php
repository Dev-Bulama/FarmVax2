<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FarmRecordController extends Controller
{
    /**
     * Show all farm records
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get farm records for this user
        $farmRecords = FarmRecord::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Statistics
        $stats = [
            'total' => FarmRecord::where('user_id', $user->id)->count(),
            'submitted' => FarmRecord::where('user_id', $user->id)->where('status', 'submitted')->count(),
            'approved' => FarmRecord::where('user_id', $user->id)->where('status', 'approved')->count(),
            'draft' => FarmRecord::where('user_id', $user->id)->where('status', 'draft')->count(),
        ];
        
        return view('farmer.farm-records.index', compact('farmRecords', 'stats'));
    }
    
    /**
     * Show individual farm record
     */
    public function show($id)
    {
        $user = Auth::user();
        $farmRecord = FarmRecord::where('user_id', $user->id)->findOrFail($id);
        
        return view('farmer.farm-records.show', compact('farmRecord'));
    }
    
    /**
     * Step 1: Basic Farm Information
     */
    public function step1()
    {
        // Clear any existing session data
        Session::forget('farm_record_data');
        
        return view('farmer.farm-records.step1');
    }
    
    /**
     * Process Step 1 and go to Step 2
     */
    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'farmer_name' => 'required|string|max:255',
            'farmer_phone' => 'required|string|max:20',
            'farmer_email' => 'nullable|email|max:255',
            'farmer_address' => 'nullable|string',
            'farmer_city' => 'required|string|max:255',
            'farmer_state' => 'required|string|max:255',
            'farmer_lga' => 'nullable|string|max:255',
            'farm_name' => 'nullable|string|max:255',
            'farm_size' => 'nullable|numeric|min:0',
            'farm_size_unit' => 'required|string',
            'farm_type' => 'required|in:commercial,subsistence,mixed',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        
        // Store in session
        Session::put('farm_record_data.step1', $validated);
        
        return redirect()->route('farmer.farm-records.step2');
    }
    
    /**
     * Step 2: Livestock Information
     */
    public function step2()
    {
        // Check if step 1 is completed
        if (!Session::has('farm_record_data.step1')) {
            return redirect()->route('farmer.farm-records.step1')
                ->with('error', 'Please complete Step 1 first.');
        }
        
        return view('farmer.farm-records.step2');
    }
    
    /**
     * Process Step 2 and go to Step 3
     */
    public function postStep2(Request $request)
    {
        $validated = $request->validate([
            'livestock_types' => 'required|string',
            'total_livestock_count' => 'required|integer|min:0',
            'young_count' => 'nullable|integer|min:0',
            'adult_count' => 'nullable|integer|min:0',
            'old_count' => 'nullable|integer|min:0',
            'breed_information' => 'nullable|string',
            'livestock_details' => 'nullable|string',
        ]);
        
        // Store in session
        Session::put('farm_record_data.step2', $validated);
        
        return redirect()->route('farmer.farm-records.step3');
    }
    
    /**
     * Step 3: Health & Vaccination
     */
    public function step3()
    {
        // Check if steps 1 and 2 are completed
        if (!Session::has('farm_record_data.step1') || !Session::has('farm_record_data.step2')) {
            return redirect()->route('farmer.farm-records.step1')
                ->with('error', 'Please complete all previous steps.');
        }
        
        return view('farmer.farm-records.step3');
    }
    
    /**
     * Process Step 3 and save farm record
     */
    public function postStep3(Request $request)
    {
        $validated = $request->validate([
            'last_vaccination_date' => 'nullable|date',
            'vaccination_history' => 'nullable|string',
            'has_health_issues' => 'boolean',
            'current_health_issues' => 'nullable|string',
            'health_notes' => 'nullable|string',
            'veterinarian_name' => 'nullable|string|max:255',
            'veterinarian_phone' => 'nullable|string|max:20',
            'service_needs' => 'nullable|string',
            'urgency_level' => 'required|in:low,medium,high,emergency',
            'needs_immediate_attention' => 'boolean',
            'sms_alerts' => 'boolean',
            'email_alerts' => 'boolean',
        ]);
        
        // Get all session data
        $step1Data = Session::get('farm_record_data.step1', []);
        $step2Data = Session::get('farm_record_data.step2', []);
        $step3Data = $validated;
        
        // Merge all data
        $allData = array_merge($step1Data, $step2Data, $step3Data);
        
        // Add user and status info
        $allData['user_id'] = Auth::id();
        $allData['created_by_role'] = Auth::user()->role === 'farmer' ? 'individual' : Auth::user()->role;
        $allData['status'] = 'submitted';
        $allData['submitted_at'] = now();
        
        // Create farm record
        $farmRecord = FarmRecord::create($allData);
        
        // Clear session data
        Session::forget('farm_record_data');
        
        return redirect()->route('farmer.farm-records.show', $farmRecord->id)
            ->with('success', 'Farm record submitted successfully!');
    }
    
    /**
     * Go back to previous step
     */
    public function previousStep($step)
    {
        if ($step == 2) {
            return redirect()->route('farmer.farm-records.step1');
        } elseif ($step == 3) {
            return redirect()->route('farmer.farm-records.step2');
        }
        
        return redirect()->route('farmer.farm-records.step1');
    }
}