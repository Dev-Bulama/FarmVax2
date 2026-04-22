<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\TelemedicineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelemedicineController extends Controller
{
    public function index()
    {
        $pending   = TelemedicineRequest::where('status', 'pending')->with('requester', 'livestock')->latest()->get();
        $myActive  = TelemedicineRequest::where('professional_id', Auth::id())
                        ->whereIn('status', ['assigned', 'in_progress'])
                        ->with('requester', 'livestock')->latest()->get();
        $myHistory = TelemedicineRequest::where('professional_id', Auth::id())
                        ->whereIn('status', ['completed', 'cancelled'])
                        ->with('requester', 'livestock')->latest()->paginate(10);

        return view('professional.telemedicine.index', compact('pending', 'myActive', 'myHistory'));
    }

    public function accept($id)
    {
        $req = TelemedicineRequest::where('status', 'pending')->findOrFail($id);
        $req->update([
            'professional_id' => Auth::id(),
            'status'          => 'assigned',
        ]);

        return redirect()->route('professional.telemedicine.index')
            ->with('success', 'You have accepted the consultation request.');
    }

    public function join($id)
    {
        $req = TelemedicineRequest::where('professional_id', Auth::id())
            ->whereIn('status', ['assigned', 'in_progress'])
            ->with('requester', 'livestock')
            ->findOrFail($id);

        if ($req->status === 'assigned') {
            $req->update(['status' => 'in_progress', 'started_at' => now()]);
        }

        return view('professional.telemedicine.room', compact('req'));
    }

    public function complete(Request $request, $id)
    {
        $req = TelemedicineRequest::where('professional_id', Auth::id())
            ->where('status', 'in_progress')
            ->findOrFail($id);

        $validated = $request->validate([
            'professional_notes' => 'nullable|string|max:5000',
        ]);

        $duration = $req->started_at
            ? (int) $req->started_at->diffInMinutes(now())
            : null;

        $req->update([
            'status'              => 'completed',
            'ended_at'            => now(),
            'duration_minutes'    => $duration,
            'professional_notes'  => $validated['professional_notes'] ?? null,
        ]);

        return redirect()->route('professional.telemedicine.index')
            ->with('success', 'Consultation marked as completed.');
    }
}
