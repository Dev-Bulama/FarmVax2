<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AnimalHealthProfessional;
use App\Models\Volunteer;
use App\Models\Livestock;
use App\Models\FarmRecord;
use App\Models\ServiceRequest;
use App\Models\VaccinationHistory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Calculate all statistics
        $stats = [
            'total_users' => User::count(),
            'farmers' => User::where('role', 'farmer')->count(),
            'professionals' => AnimalHealthProfessional::where('approval_status', 'approved')->count(),
            'pending_professionals' => AnimalHealthProfessional::where('approval_status', 'pending')->count(),
            'volunteers' => Volunteer::count(),
            'total_livestock' => DB::table('livestock')->count(),
            'total_farm_records' => DB::table('farm_records')->count(),
            'pending_service_requests' => DB::table('service_requests')->where('status', 'pending')->count(),
        ];

        // Get pending professionals with user relationship
        $pendingProfessionals = AnimalHealthProfessional::with('user')
            ->where('approval_status', 'pending')
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get();

        // Get recent users
        $recentUsers = User::whereIn('role', ['farmer', 'animal_health_professional', 'volunteer'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingProfessionals', 'recentUsers'));
    }

   /**
 * Show all farmers
 */
public function farmers()
{
    $farmers = User::with(['country', 'state', 'lga'])
        ->where('role', 'farmer')
        ->withCount('livestock')
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    $stats = [
        'total' => User::where('role', 'farmer')->count(),
        'active' => User::where('role', 'farmer')->where('account_status', 'active')->count(),
        'total_livestock' => DB::table('livestock')->count(),
        'total_farm_records' => DB::table('farm_records')->count(),
    ];

    return view('admin.farmers.index', compact('farmers', 'stats'));
}

    /**
     * Show all professionals
     */
   /**
 * Show all professionals
 */
public function professionals()
{
    $professionals = AnimalHealthProfessional::with(['user.country', 'user.state', 'user.lga'])
        ->where('approval_status', 'approved')
        ->orderBy('approved_at', 'desc')
        ->paginate(20);

    $stats = [
        'total' => AnimalHealthProfessional::count(),
        'approved' => AnimalHealthProfessional::where('approval_status', 'approved')->count(),
        'pending' => AnimalHealthProfessional::where('approval_status', 'pending')->count(),
        'rejected' => AnimalHealthProfessional::where('approval_status', 'rejected')->count(),
    ];

    return view('admin.professionals.index', compact('professionals', 'stats'));
}

    /**
     * Show pending professionals
     */
   /**
 * Show pending professionals
 */
public function pendingProfessionals()
{
    $pendingProfessionals = AnimalHealthProfessional::with(['user.country', 'user.state', 'user.lga'])
        ->where('approval_status', 'pending')
        ->orderBy('submitted_at', 'desc')
        ->paginate(20);

    $stats = [
        'pending' => AnimalHealthProfessional::where('approval_status', 'pending')->count(),
        'approved_today' => AnimalHealthProfessional::where('approval_status', 'approved')
            ->whereDate('approved_at', today())
            ->count(),
        'rejected_today' => AnimalHealthProfessional::where('approval_status', 'rejected')
            ->whereDate('approved_at', today())
            ->count(),
    ];

    return view('admin.professionals.pending', compact('pendingProfessionals', 'stats'));
}

   /**
     * Review professional application
     */
    public function reviewProfessional($id)
    {
        $professional = AnimalHealthProfessional::with([
            'user.country',
            'user.state', 
            'user.lga',
            'verificationDocuments'
        ])->findOrFail($id);

        return view('admin.professionals.review', compact('professional'));
    }

    /**
     * Approve professional
     */
    public function approveProfessional($id)
    {
        $professional = AnimalHealthProfessional::findOrFail($id);
        
        $professional->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('admin.professionals.pending')
            ->with('success', 'Professional application approved successfully!');
    }

    /**
     * Reject professional
     */
    public function rejectProfessional(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $professional = AnimalHealthProfessional::findOrFail($id);
        
        $professional->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()
            ->route('admin.professionals.pending')
            ->with('success', 'Professional application rejected.');
    }

    /**
     * Show all volunteers
     */
   /**
 * Show all volunteers
 */
public function volunteers()
{
    $volunteers = Volunteer::with(['user.country', 'user.state', 'user.lga'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    $stats = [
        'total' => Volunteer::count(),
        'active' => Volunteer::where('is_active', true)->count(),
        'inactive' => Volunteer::where('is_active', false)->count(),
        'total_enrollments' => DB::table('farmer_enrollments')->count(),
    ];

    return view('admin.volunteers.index', compact('volunteers', 'stats'));
}

    /**
     * Show volunteer details
     */
   public function showVolunteer($id)
{
    $volunteer = Volunteer::with(['user.country', 'user.state', 'user.lga', 'enrolledFarmers.farmer'])
        ->findOrFail($id);

    return view('admin.volunteers.show', compact('volunteer'));
}

    /**
     * Deactivate volunteer
     */
    public function deactivateVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        
        $volunteer->update([
            'status' => 'inactive',
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.volunteers.index')
            ->with('success', 'Volunteer deactivated successfully.');
    }

    /**
     * Activate volunteer
     */
    public function activateVolunteer($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        
        $volunteer->update([
            'status' => 'active',
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.volunteers.index')
            ->with('success', 'Volunteer activated successfully.');
    }
 
  /**
 * Show all service requests
 */
public function serviceRequests()
{
    $serviceRequests = DB::table('service_requests')
        ->join('users', 'service_requests.user_id', '=', 'users.id')
        ->select('service_requests.*', 'users.name as farmer_name', 'users.email as farmer_email', 'users.phone as farmer_phone')
        ->orderBy('service_requests.created_at', 'desc')
        ->paginate(20);

    $stats = [
        'total' => DB::table('service_requests')->count(),
        'pending' => DB::table('service_requests')->where('status', 'pending')->count(),
        'in_progress' => DB::table('service_requests')->where('status', 'in_progress')->count(),
        'completed' => DB::table('service_requests')->where('status', 'completed')->count(),
        'cancelled' => DB::table('service_requests')->where('status', 'cancelled')->count(),
    ];

    return view('admin.service-requests.index', compact('serviceRequests', 'stats'));
}

  /**
     * Show individual service request
     */
    public function showServiceRequest($id)
    {
        $serviceRequest = ServiceRequest::with(['user', 'livestock'])
    ->findOrFail($id);
    // $serviceRequest = ServiceRequest::with(['user', 'livestock', 'assignedProfessional'])
    // ->findOrFail($id);

        return view('admin.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Assign service request to professional
     */
    public function assignServiceRequest(Request $request, $id)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $serviceRequest = ServiceRequest::findOrFail($id);
        $serviceRequest->update([
            'assigned_to' => $validated['assigned_to'],
            'assigned_by' => auth()->id(),
            'status' => 'assigned',
        ]);

        return back()->with('success', 'Service request assigned successfully!');
    }

    /**
     * Update service request status
     */
    public function updateServiceRequestStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed,cancelled,rejected',
            'notes' => 'nullable|string',
        ]);

        $serviceRequest = ServiceRequest::findOrFail($id);
        
        $updateData = [
            'status' => $validated['status'],
        ];

        if ($validated['status'] === 'completed') {
            $updateData['completion_date'] = now();
        }

        if ($validated['status'] === 'rejected') {
            $updateData['rejection_reason'] = $validated['notes'] ?? 'Rejected by admin';
            $updateData['reviewed_by'] = auth()->id();
            $updateData['reviewed_at'] = now();
        }

        if (isset($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $serviceRequest->update($updateData);

        return back()->with('success', 'Service request status updated successfully!');
    }
   /**
 * Show all farm records
 */
public function farmRecords()
{
    $farmRecords = DB::table('farm_records')
        ->join('users', 'farm_records.user_id', '=', 'users.id')
        ->select('farm_records.*', 'users.name as creator_name', 'users.email as creator_email')
        ->orderBy('farm_records.created_at', 'desc')
        ->paginate(20);

    $stats = [
        'total' => DB::table('farm_records')->count(),
        'pending' => DB::table('farm_records')->where('status', 'submitted')->count(),
        'approved' => DB::table('farm_records')->where('status', 'approved')->count(),
        'rejected' => DB::table('farm_records')->where('status', 'rejected')->count(),
    ];

    return view('admin.farm-records.index', compact('farmRecords', 'stats'));
}

    /**
     * Show pending farm records
     */
    public function pendingFarmRecords()
    {
        $farmRecords = DB::table('farm_records')
            ->join('users', 'farm_records.user_id', '=', 'users.id')
            ->select('farm_records.*', 'users.name as creator_name')
            ->where('farm_records.status', 'submitted')
            ->orderBy('farm_records.created_at', 'desc')
            ->paginate(20);

        return view('admin.farm-records.pending', compact('farmRecords'));
    }

    /**
     * Show farm record details
     */
    public function showFarmRecord($id)
    {
        $record = DB::table('farm_records')
            ->join('users', 'farm_records.user_id', '=', 'users.id')
            ->select('farm_records.*', 'users.name as creator_name', 'users.email as creator_email')
            ->where('farm_records.id', $id)
            ->first();

        if (!$record) {
            abort(404);
        }

        return view('admin.farm-records.show', compact('record'));
    }

    /**
     * Approve farm record
     */
    public function approveFarmRecord($id)
    {
        DB::table('farm_records')
            ->where('id', $id)
            ->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.farm-records.pending')
            ->with('success', 'Farm record approved successfully!');
    }

    /**
     * Reject farm record
     */
    public function rejectFarmRecord(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::table('farm_records')
            ->where('id', $id)
            ->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_notes' => $request->rejection_reason,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.farm-records.pending')
            ->with('success', 'Farm record rejected.');
    }

    /**
     * Show all users
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show statistics page
     */
    public function statistics()
    {
        // User statistics
        $userStats = [
            'total_users' => User::count(),
            'total_farmers' => User::where('role', 'farmer')->count(),
            'total_professionals' => AnimalHealthProfessional::where('approval_status', 'approved')->count(),
            'total_volunteers' => Volunteer::count(),
        ];

        // Livestock statistics
        $livestockStats = [
            'total_livestock' => DB::table('livestock')->count(),
            'total_cattle' => DB::table('livestock')->where('type', 'cattle')->count(),
            'total_goats' => DB::table('livestock')->where('type', 'goat')->count(),
            'total_sheep' => DB::table('livestock')->where('type', 'sheep')->count(),
            'total_poultry' => DB::table('livestock')->where('type', 'poultry')->count(),
        ];

        // Service statistics
        $serviceStats = [
            'total_vaccinations' => DB::table('vaccination_history')->count(),
            'pending_requests' => DB::table('service_requests')->where('status', 'pending')->count(),
            'completed_requests' => DB::table('service_requests')->where('status', 'completed')->count(),
        ];

        // Monthly growth (last 6 months)
        $monthlyGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyGrowth[] = [
                'month' => $date->format('M Y'),
                'users' => User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        }

        return view('admin.statistics', compact(
            'userStats',
            'livestockStats',
            'serviceStats',
            'monthlyGrowth'
        ));
    }

    /**
     * Show analytics page
     */
    public function analytics()
    {
        return $this->statistics();
    }
}