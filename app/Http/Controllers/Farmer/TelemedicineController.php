<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\TelemedicineRequest;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelemedicineController extends Controller
{
    public function index()
    {
        $requests = TelemedicineRequest::where('requester_id', Auth::id())
            ->with(['professional', 'livestock'])
            ->latest()
            ->paginate(10);

        return view('farmer.telemedicine.index', compact('requests'));
    }

    public function create()
    {
        $livestock = Livestock::where('user_id', Auth::id())
            ->whereNotIn('health_status', ['deceased'])
            ->orderBy('livestock_type')
            ->get();

        return view('farmer.telemedicine.create', compact('livestock'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason'      => 'required|string|max:1000',
            'notes'       => 'nullable|string|max:2000',
            'livestock_id'=> 'nullable|exists:livestock,id',
            'priority'    => 'required|in:normal,urgent',
            'scheduled_at'=> 'nullable|date|after:now',
        ]);

        $validated['requester_id'] = Auth::id();

        TelemedicineRequest::create($validated);

        return redirect()->route('farmer.telemedicine.index')
            ->with('success', 'Video consultation request submitted. A vet will be assigned shortly.');
    }

    public function show($id)
    {
        $req = TelemedicineRequest::where('requester_id', Auth::id())
            ->with(['professional', 'livestock'])
            ->findOrFail($id);

        return view('farmer.telemedicine.show', compact('req'));
    }

    public function cancel($id)
    {
        $req = TelemedicineRequest::where('requester_id', Auth::id())
            ->whereIn('status', ['pending', 'assigned'])
            ->findOrFail($id);

        $req->update(['status' => 'cancelled']);

        return redirect()->route('farmer.telemedicine.index')
            ->with('success', 'Consultation request cancelled.');
    }

    public function join($id)
    {
        $req = TelemedicineRequest::where('requester_id', Auth::id())
            ->whereIn('status', ['assigned', 'in_progress'])
            ->findOrFail($id);

        if ($req->status === 'assigned') {
            $req->update(['status' => 'in_progress', 'started_at' => now()]);
        }

        return view('farmer.telemedicine.room', compact('req'));
    }
}
