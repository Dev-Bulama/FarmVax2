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
    public function profile()
    {
        $user = auth()->user();
        $professional = $user->animalHealthProfessional;

        return view('professional.profile', compact('user', 'professional'));
    }

    /**
     * Show farm records managed by this professional.
     */
    // public function farmRecords()
    // {
    //     $user = auth()->user();
    //     $professional = $user->animalHealthProfessional;

    //     // Check if approved
    //     if (!$professional || $professional->approval_status !== 'approved') {
    //         return redirect()->route('professional.pending-approval')
    //             ->with('error', 'Your account must be approved to access farm records.');
    //     }

    //     $farmRecords = $user->farmRecords()
    //         ->with('farmer')
    //         ->latest()
    //         ->paginate(10);

    //     return view('professional.farm-records', compact('user', 'farmRecords'));
    // }

    /**
     * Show service requests.
     */
    /**
 * Show service requests for professional
 */
public function serviceRequests()
{
    $serviceRequests = ServiceRequest::where('assigned_to', auth()->id())
        ->orWhere(function($query) {
            $query->whereNull('assigned_to')
                  ->where('status', 'pending');
        })
        ->with(['user', 'livestock'])
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    $stats = [
        'total' => ServiceRequest::where('assigned_to', auth()->id())->count(),
        'pending' => ServiceRequest::where('assigned_to', auth()->id())->where('status', 'pending')->count(),
        'in_progress' => ServiceRequest::where('assigned_to', auth()->id())->where('status', 'in_progress')->count(),
        'completed' => ServiceRequest::where('assigned_to', auth()->id())->where('status', 'completed')->count(),
    ];

    return view('professional.service-requests.index', compact('serviceRequests', 'stats'));
}
    // public function serviceRequests()
    // {
    //     $user = auth()->user();
    //     $professional = $user->animalHealthProfessional;

    //     // Check if approved
    //     if (!$professional || $professional->approval_status !== 'approved') {
    //         return redirect()->route('professional.pending-approval')
    //             ->with('error', 'Your account must be approved to access service requests.');
    //     }

    //     // Service requests will be implemented later
    //     $serviceRequests = collect();

    //     return view('professional.service-requests', compact('user', 'serviceRequests'));
    // }
    /**
 * Show farm records for professional
 */
public function farmRecords()
{
    $farmRecords = FarmRecord::where('status', 'approved')
        ->orWhere('user_id', auth()->id())
        ->latest()
        ->paginate(15);

    $stats = [
        'total' => FarmRecord::where('status', 'approved')->count(),
        'approved' => FarmRecord::where('status', 'approved')->count(),
        'pending' => FarmRecord::where('status', 'submitted')->count(),
        'my_records' => FarmRecord::where('user_id', auth()->id())->count(),
    ];

    return view('professional.farm-records.index', compact('farmRecords', 'stats'));
}
}