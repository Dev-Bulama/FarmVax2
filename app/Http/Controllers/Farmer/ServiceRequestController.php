<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of service requests
     */
    public function index(Request $request)
    {
        $query = ServiceRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('service_description', 'like', '%' . $request->search . '%')
                  ->orWhere('service_title', 'like', '%' . $request->search . '%')
                  ->orWhere('service_type', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by service type
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        $serviceRequests = $query->paginate(15);

        return view('individual.service-requests.index', compact('serviceRequests'));
    }

    /**
     * Show the form for creating a new service request
     */
    public function create()
    {
        return view('individual.service-requests.create');
    }

    /**
     * Store a newly created service request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:vaccination,treatment,consultation,emergency,routine_checkup,breeding,deworming,castration,pregnancy_check,artificial_insemination,nutritional_advice,nutrition_advice,disease_diagnosis,surgery,other',
            'service_title' => 'nullable|string|max:255',
            'service_description' => 'required|string|max:1000',
            'livestock_type' => 'nullable|string',
            'number_of_animals' => 'nullable|integer|min:1',
            'preferred_date' => 'nullable|date|after:today',
            'urgency_level' => 'required|in:low,medium,high,critical',
            'priority' => 'required|in:routine,important,critical,low,medium,high',
            'contact_phone' => 'nullable|string',
            'location' => 'nullable|string',
        ]);

        // Generate reference number
        $referenceNumber = 'SR-' . date('Ymd') . '-' . str_pad(ServiceRequest::count() + 1, 5, '0', STR_PAD_LEFT);

        // Auto-generate title if not provided
        $serviceTitle = $validated['service_title'] ?? ucfirst($validated['service_type']) . ' Request';

        ServiceRequest::create([
            'user_id' => Auth::id(),
            'service_type' => $validated['service_type'],
            'service_title' => $serviceTitle,
            'service_description' => $validated['service_description'],
            'livestock_type' => $validated['livestock_type'] ?? null,
            'number_of_animals' => $validated['number_of_animals'] ?? null,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'urgency_level' => $validated['urgency_level'],
            'priority' => $validated['priority'],
            'contact_phone' => $validated['contact_phone'] ?? Auth::user()->phone,
            'location' => $validated['location'] ?? Auth::user()->address,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'reference_number' => $referenceNumber,
            'requested_by_role' => 'individual',
        ]);

        return redirect()
            ->route('farmer.service-requests.index')
            ->with('success', 'Service request submitted successfully! Reference: ' . $referenceNumber);
    }

    /**
     * Display the specified service request
     */
    public function show($id)
    {
        $serviceRequest = ServiceRequest::where('user_id', Auth::id())
            ->findOrFail($id);

        return view('individual.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Cancel a service request
     */
    public function cancel($id)
    {
        $serviceRequest = ServiceRequest::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->findOrFail($id);

        $serviceRequest->update([
            'status' => 'cancelled',
            'cancellation_reason' => request('reason') ?? 'Cancelled by user',
        ]);

        return redirect()
            ->route('farmer.service-requests.index')
            ->with('success', 'Service request cancelled successfully.');
    }
}