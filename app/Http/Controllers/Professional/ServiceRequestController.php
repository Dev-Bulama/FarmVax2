<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\AnimalHealthProfessional;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ServiceRequestController extends Controller
{
    /**
     * Display all service requests
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get professional profile
        $profile = AnimalHealthProfessional::where('user_id', $user->id)->first();
        
        // Check if approved
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved before accessing service requests.');
        }
        
        // Get service requests
        $pendingRequests = ServiceRequest::where('status', 'pending')
            ->whereNull('assigned_to')
            ->with(['user:id,name,email,phone,country_id,state_id,lga_id'])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $myRequests = ServiceRequest::where('assigned_to', $user->id)
            ->with(['user:id,name,email,phone,country_id,state_id,lga_id'])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistics
        $stats = [
            'available' => $pendingRequests->count(),
            'in_progress' => $myRequests->where('status', 'in_progress')->count(),
            'completed' => $myRequests->where('status', 'completed')->count(),
            'total' => $myRequests->count(),
        ];
        
        return view('professional.service-requests.index', compact(
            'pendingRequests',
            'myRequests',
            'stats',
            'profile'
        ));
    }
    
    /**
     * Show a specific service request
     */
    public function show($id)
    {
        $user = Auth::user();
        
        // Get professional profile
        $profile = AnimalHealthProfessional::where('user_id', $user->id)->first();
        
        // Check if approved
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved before accessing service requests.');
        }
        
        // Get the service request
        $request = ServiceRequest::with(['user:id,name,email,phone,country_id,state_id,lga_id'])
            ->findOrFail($id);
        
        // Check if this professional can view this request
        $canView = $request->status === 'pending' && !$request->assigned_to 
                   || $request->assigned_to == $user->id;
        
        if (!$canView) {
            return redirect()->route('professional.service-requests.index')
                ->with('error', 'You do not have access to this service request.');
        }
        
        return view('professional.service-requests.show', compact(
            'request',
            'profile'
        ));
    }
    
    /**
     * Accept a service request
     */
    public function accept($id)
    {
        $user = Auth::user();
        
        // Get professional profile
        $profile = AnimalHealthProfessional::where('user_id', $user->id)->first();
        
        // Check if approved
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved before accepting requests.');
        }
        
        // Get the service request
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        // Check if request is still pending and unassigned
        if ($serviceRequest->status !== 'pending' || $serviceRequest->assigned_to) {
            return redirect()->route('professional.service-requests.index')
                ->with('error', 'This request is no longer available.');
        }
        
        // Generate reference number if not exists
        if (!$serviceRequest->reference_number) {
            $serviceRequest->reference_number = 'SR-' . strtoupper(Str::random(8));
        }
        
        // Assign to professional
        $serviceRequest->update([
            'assigned_to' => $user->id,
            'assigned_at' => now(),
            'status' => 'in_progress',
            'started_at' => now(),
            'assigned_veterinarian_name' => $user->name,
            'assigned_veterinarian_phone' => $user->phone,
        ]);
        
        // TODO: Send notification to farmer
        
        return redirect()->route('professional.service-requests.show', $id)
            ->with('success', 'Service request accepted successfully! You can now proceed with the service.');
    }
    
    /**
     * Complete a service request
     */
    public function complete(Request $request, $id)
    {
        $user = Auth::user();
        
        // Get professional profile
        $profile = AnimalHealthProfessional::where('user_id', $user->id)->first();
        
        // Check if approved
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved before completing requests.');
        }
        
        // Get the service request
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        // Check if this professional owns this request
        if ($serviceRequest->assigned_to != $user->id) {
            return redirect()->route('professional.service-requests.index')
                ->with('error', 'You do not have access to this service request.');
        }
        
        // Validate the completion data
        $validated = $request->validate([
            'service_notes' => 'required|string|min:10',
            'diagnosis' => 'nullable|string',
            'treatment_provided' => 'nullable|string',
            'medications_prescribed' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'actual_service_date' => 'required|date',
            'actual_cost' => 'nullable|numeric|min:0',
            'requires_followup' => 'boolean',
            'followup_date' => 'nullable|date|after:today',
            'followup_instructions' => 'nullable|string',
        ]);
        
        // Update service request
        $serviceRequest->update([
            'status' => 'completed',
            'completed_at' => now(),
            'service_notes' => $validated['service_notes'],
            'diagnosis' => $validated['diagnosis'] ?? null,
            'treatment_provided' => $validated['treatment_provided'] ?? null,
            'medications_prescribed' => $validated['medications_prescribed'] ?? null,
            'recommendations' => $validated['recommendations'] ?? null,
            'actual_service_date' => $validated['actual_service_date'],
            'actual_cost' => $validated['actual_cost'] ?? null,
            'requires_followup' => $request->has('requires_followup'),
            'followup_date' => $validated['followup_date'] ?? null,
            'followup_instructions' => $validated['followup_instructions'] ?? null,
            'outcome' => 'successful',
        ]);
        
        // TODO: Send notification to farmer
        
        return redirect()->route('professional.service-requests.index')
            ->with('success', 'Service request marked as completed successfully!');
    }
    
    /**
     * Cancel/reject a service request
     */
    public function cancel(Request $request, $id)
    {
        $user = Auth::user();
        
        // Get the service request
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        // Check if this professional owns this request
        if ($serviceRequest->assigned_to != $user->id) {
            return redirect()->route('professional.service-requests.index')
                ->with('error', 'You do not have access to this service request.');
        }
        
        // Validate
        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:10',
        ]);
        
        // Update service request
        $serviceRequest->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $validated['cancellation_reason'],
            'assigned_to' => null, // Release back to pool
            'assigned_at' => null,
        ]);
        
        // TODO: Send notification to farmer
        
        return redirect()->route('professional.service-requests.index')
            ->with('success', 'Service request cancelled. It has been released back to the pool.');
    }
}