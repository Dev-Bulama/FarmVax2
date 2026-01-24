<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use App\Models\VaccinationHistory;
use App\Models\ServiceRequest;
use App\Models\FarmRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display farmer dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get statistics
        $stats = [
            'total_livestock' => Livestock::where('owner_id', $user->id)->count(),
            'vaccinated' => VaccinationHistory::whereHas('livestock', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })->distinct('livestock_id')->count(),
            'pending_requests' => ServiceRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'farm_records' => FarmRecord::where('farmer_id', $user->id)->count(),
        ];

        // Recent vaccinations
        $recentVaccinations = VaccinationHistory::with('livestock')
            ->whereHas('livestock', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->latest()
            ->take(5)
            ->get();

        // Recent service requests
        $recentRequests = ServiceRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Upcoming vaccination reminders (vaccinations due in next 30 days)
        $upcomingVaccinations = VaccinationHistory::with('livestock')
            ->whereHas('livestock', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->where('next_vaccination_date', '<=', now()->addDays(30))
            ->where('next_vaccination_date', '>=', now())
            ->orderBy('next_vaccination_date')
            ->take(5)
            ->get();

        return view('farmer.dashboard', compact(
            'user',
            'stats',
            'recentVaccinations',
            'recentRequests',
            'upcomingVaccinations'
        ));
    }

    /**
     * Display farmer profile.
     */
    public function profile()
    {
        $user = Auth::user();
        $enrollmentInfo = $user->enrollmentRecord;

        return view('farmer.profile', compact('user', 'enrollmentInfo'));
    }

    /**
     * Update farmer profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:500'],
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('farmer.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Display livestock list.
     */
    public function livestock()
    {
        $user = Auth::user();
        
        $livestock = Livestock::where('owner_id', $user->id)
            ->with('vaccinationHistory')
            ->latest()
            ->paginate(10);

        return view('farmer.livestock.index', compact('livestock'));
    }

    /**
     * Show create livestock form.
     */
    public function createLivestock()
    {
        return view('farmer.livestock.create');
    }

    /**
     * Store new livestock.
     */
    public function storeLivestock(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'in:cattle,goat,sheep,poultry,pig,other'],
            'breed' => ['nullable', 'string', 'max:255'],
            'tag_number' => ['required', 'string', 'max:100', 'unique:livestock'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['required', 'in:male,female'],
            'health_status' => ['required', 'in:healthy,sick,recovering,quarantine'],
            'notes' => ['nullable', 'string'],
        ]);

        Livestock::create([
            'owner_id' => Auth::id(),
            'type' => $request->type,
            'breed' => $request->breed,
            'tag_number' => $request->tag_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'health_status' => $request->health_status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('farmer.livestock')
            ->with('success', 'Livestock added successfully!');
    }

    /**
     * Display service requests.
     */
    public function serviceRequests()
    {
        $user = Auth::user();
        
        $requests = ServiceRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('farmer.service-requests.index', compact('requests'));
    }

    /**
     * Show create service request form.
     */
    public function createServiceRequest()
    {
        return view('farmer.service-requests.create');
    }

    /**
     * Store new service request.
     */
    public function storeServiceRequest(Request $request)
    {
        $request->validate([
            'service_type' => ['required', 'string', 'in:vaccination,treatment,consultation,emergency'],
            'description' => ['required', 'string'],
            'preferred_date' => ['nullable', 'date', 'after:today'],
            'urgency' => ['required', 'in:low,medium,high,critical'],
        ]);

        ServiceRequest::create([
            'user_id' => Auth::id(),
            'service_type' => $request->service_type,
            'description' => $request->description,
            'preferred_date' => $request->preferred_date,
            'urgency' => $request->urgency,
            'status' => 'pending',
        ]);

        return redirect()->route('farmer.service-requests')
            ->with('success', 'Service request submitted successfully! We will contact you soon.');
    }

    /**
     * Display vaccination history.
     */
    public function vaccinations()
    {
        $user = Auth::user();
        
        $vaccinations = VaccinationHistory::with('livestock')
            ->whereHas('livestock', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('farmer.vaccinations.index', compact('vaccinations'));
    }
}