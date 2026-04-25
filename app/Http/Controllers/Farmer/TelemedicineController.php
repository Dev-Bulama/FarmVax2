<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\TelemedicineRequest;
use App\Models\AnimalHealthProfessional;
use App\Models\Livestock;
use App\Models\User;
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
            'reason'       => 'required|string|max:1000',
            'notes'        => 'nullable|string|max:2000',
            'livestock_id' => 'nullable|exists:livestock,id',
            'priority'     => 'required|in:normal,urgent',
        ]);

        $validated['requester_id'] = Auth::id();

        // Find an available approved vet — prefer vets with no active calls
        $approvedVetIds = AnimalHealthProfessional::where('approval_status', 'approved')
            ->pluck('user_id');

        $vetId = null;
        if ($approvedVetIds->isNotEmpty()) {
            $busyIds = TelemedicineRequest::whereIn('status', ['assigned', 'in_progress'])
                ->whereNotNull('professional_id')
                ->pluck('professional_id');

            $freeIds = $approvedVetIds->diff($busyIds);
            $vetId   = $freeIds->isNotEmpty()
                ? $freeIds->random()
                : $approvedVetIds->random();
        }

        if ($vetId) {
            $validated['professional_id'] = $vetId;
            $validated['status']          = 'assigned';
            $msg = 'A vet is available — tap "Join Call" to start your session now.';
        } else {
            $validated['status'] = 'pending';
            $msg = 'No vet is available right now. Your request has been queued.';
        }

        $req = TelemedicineRequest::create($validated);

        return redirect()->route('farmer.telemedicine.show', $req->id)
            ->with('success', $msg);
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
            // Clear any stale signals from a previous session for this room
            \App\Models\TelemedicineSignal::purgeRoom($req->room_code);
        }

        $joinedAt = now()->timestamp;
        return view('farmer.telemedicine.room', compact('req', 'joinedAt'));
    }
}
