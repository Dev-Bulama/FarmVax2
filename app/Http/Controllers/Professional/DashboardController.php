<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\AnimalHealthProfessional;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get professional profile
        $profile = AnimalHealthProfessional::where('user_id', $user->id)->first();
        
        // If no profile exists, create one (shouldn't happen but safety check)
        if (!$profile) {
            $profile = AnimalHealthProfessional::create([
                'user_id' => $user->id,
                'professional_type' => 'veterinarian',
                'approval_status' => 'pending',
                'submitted_at' => now(),
            ]);
        }
        
        // Check approval status
        $isPending = $profile->approval_status === 'pending';
        $isRejected = $profile->approval_status === 'rejected';
        $isApproved = $profile->approval_status === 'approved';
        
        // Statistics - only show for approved professionals
        if ($isApproved) {
            $stats = [
                // Pending requests that are not assigned yet (available to claim)
                'pending_requests' => ServiceRequest::where('status', 'pending')
                    ->whereNull('assigned_to')
                    ->count(),
                
                // Requests assigned to this professional that are in progress
                'active_requests' => ServiceRequest::where('assigned_to', $user->id)
                    ->where('status', 'in_progress')
                    ->count(),
                
                // Completed requests by this professional
                'completed_requests' => ServiceRequest::where('assigned_to', $user->id)
                    ->where('status', 'completed')
                    ->count(),
                
                // Total requests ever assigned to this professional
                'total_requests' => ServiceRequest::where('assigned_to', $user->id)
                    ->count(),
            ];
            
            // Recent service requests - show both assigned to them and pending ones
            $recentRequests = ServiceRequest::where(function($query) use ($user) {
                    // Either assigned to this professional
                    $query->where('assigned_to', $user->id)
                          // Or pending and not assigned yet (available to claim)
                          ->orWhere(function($q) {
                              $q->where('status', 'pending')
                                ->whereNull('assigned_to');
                          });
                })
                ->with(['user:id,name,email,phone']) // Eager load the farmer who made the request
                ->orderByRaw("CASE 
                    WHEN status = 'pending' AND assigned_to IS NULL THEN 1
                    WHEN assigned_to = {$user->id} AND status = 'in_progress' THEN 2
                    WHEN assigned_to = {$user->id} AND status = 'completed' THEN 3
                    ELSE 4
                END")
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            // Pending or rejected - show empty stats
            $stats = [
                'pending_requests' => 0,
                'active_requests' => 0,
                'completed_requests' => 0,
                'total_requests' => 0,
            ];
            
            $recentRequests = collect(); // Empty collection
        }
        
        return view('professional.dashboard', compact(
            'user',
            'profile',
            'stats',
            'recentRequests',
            'isPending',
            'isRejected',
            'isApproved'
        ));
    }
}