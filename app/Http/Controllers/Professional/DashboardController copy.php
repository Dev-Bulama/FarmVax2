<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show professional dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $professional = $user->animalHealthProfessional;

        // Check if approved
        if (!$professional || $professional->approval_status !== 'approved') {
            return redirect()->route('professional.pending-approval');
        }

        // Get stats
        $farmRecordsCount = $user->farmRecords()->count();
        $serviceRequestsCount = 0; // Will be implemented later

        return view('professional.dashboard', compact('user', 'professional', 'farmRecordsCount', 'serviceRequestsCount'));
    }

    /**
     * Show professional profile.
     */
    // public function profile()
    // {
    //     $user = auth()->user();
    //     $professional = $user->animalHealthProfessional;

    //     return view('professional.profile', compact('user', 'professional'));
    // }

    /**
     * Show farm records managed by this professional.
     */
    public function farmRecords()
    {
        $user = auth()->user();
        $professional = $user->animalHealthProfessional;

        // Check if approved
        if (!$professional || $professional->approval_status !== 'approved') {
            return redirect()->route('professional.pending-approval')
                ->with('error', 'Your account must be approved to access farm records.');
        }

        $farmRecords = $user->farmRecords()
            ->with('farmer')
            ->latest()
            ->paginate(10);

$stats = [
    'total' => \App\Models\FarmRecord::where('status', 'approved')->count(),
    'approved' => \App\Models\FarmRecord::where('status', 'approved')->count(),
    'pending' => \App\Models\FarmRecord::where('status', 'submitted')->count(),
    'my_records' => $user->farmRecords()->count(),
];

return view('professional.farm-records.index', compact('user', 'farmRecords', 'stats'));    
    
    }

    /**
     * Show service requests.
     */
    public function serviceRequests()
    {
        $user = auth()->user();
        $professional = $user->animalHealthProfessional;

        // Check if approved
        if (!$professional || $professional->approval_status !== 'approved') {
            return redirect()->route('professional.pending-approval')
                ->with('error', 'Your account must be approved to access service requests.');
        }

        // Service requests will be implemented later
        $serviceRequests = collect();

        return view('professional.service-requests', compact('user', 'serviceRequests'));
    }
    public function profile()
    {
        return view('professional.profile');
    }

    public function pendingApproval()
    {
        return view('professional.pending-approval');
    }

    // public function profile()
    // {
    //     return view('professional.profile');
    // }

    /**
     * Show pending approval page
     */
    // public function pendingApproval()
    // {
    //     return view('professional.pending-approval');
    // }

    /**
     * Show farm records list
     */
    // public function farmRecords()
    // {
    //     $farmRecords = \App\Models\FarmRecord::where('user_id', auth()->id())
    //         ->orWhere('created_by_role', 'data_collector')
    //         ->latest()
    //         ->get();
            
    //     $totalRecords = $farmRecords->count();
    //     $approvedRecords = $farmRecords->where('status', 'approved')->count();
    //     $pendingRecords = $farmRecords->where('status', 'pending')->count();

    //     return view('professional.farm-records', compact(
    //         'farmRecords',
    //         'totalRecords',
    //         'approvedRecords',
    //         'pendingRecords'
    //     ));
    // }

    /**
     * Show service requests list
     */
    // public function serviceRequests()
    // {
    //     $serviceRequests = \App\Models\ServiceRequest::latest()->get();
        
    //     $totalRequests = $serviceRequests->count();
    //     $pendingRequests = $serviceRequests->where('status', 'pending')->count();
    //     $completedRequests = $serviceRequests->where('status', 'completed')->count();
    //     $urgentRequests = $serviceRequests->whereIn('urgency_level', ['high', 'emergency'])->count();

    //     return view('professional.service-requests', compact(
    //         'serviceRequests',
    //         'totalRequests',
    //         'pendingRequests',
    //         'completedRequests',
    //         'urgentRequests'
    //     ));
    // }

}