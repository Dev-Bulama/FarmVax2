<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\TelemedicineRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    /**
     * Poll for newly assigned calls (used by front-end every 5 s).
     */
    public function poll(Request $request)
    {
        $after = (int) $request->input('after', 0);

        // Catch both 'assigned' (waiting) and 'in_progress' (farmer already joined)
        $calls = TelemedicineRequest::where('professional_id', Auth::id())
            ->whereIn('status', ['assigned', 'in_progress'])
            ->where('id', '>', $after)
            ->with('requester')
            ->get();

        return response()->json([
            'calls' => $calls->map(fn ($r) => [
                'id'       => $r->id,
                'farmer'   => $r->requester->name,
                'reason'   => Str::limit($r->reason, 120),
                'priority' => $r->priority,
                'join_url' => route('professional.telemedicine.join', $r->id),
            ]),
        ]);
    }

    /**
     * Decline a call — releases it back to pending so another vet can pick it up.
     */
    public function decline($id)
    {
        $req = TelemedicineRequest::where('professional_id', Auth::id())
            ->where('status', 'assigned')
            ->findOrFail($id);

        $req->update(['professional_id' => null, 'status' => 'pending']);

        return response()->json(['ok' => true]);
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

        $joinedAt = now()->timestamp;
        return view('professional.telemedicine.room', compact('req', 'joinedAt'));
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
