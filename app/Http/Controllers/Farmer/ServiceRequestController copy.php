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
                $q->where('description', 'like', '%' . $request->search . '%')
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

        $requests = $query->paginate(15);

        return view('individual.service-requests.index', compact('requests'));
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
            'service_type' => 'required|in:vaccination,treatment,consultation,emergency',
            'description' => 'required|string|max:1000',
            'preferred_date' => 'nullable|date|after:today',
            'urgency' => 'required|in:low,medium,high,critical',
        ]);

        ServiceRequest::create([
            'user_id' => Auth::id(),
            'service_type' => $validated['service_type'],
            'description' => $validated['description'],
            'preferred_date' => $validated['preferred_date'] ?? null,
            'urgency' => $validated['urgency'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('individual.service-requests.index')
            ->with('success', 'Service request submitted successfully! We will contact you soon.');
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
}