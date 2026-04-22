<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelemedicineRequest;
use App\Models\User;
use Illuminate\Http\Request;

class TelemedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = TelemedicineRequest::with(['requester', 'professional', 'livestock'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('requester', fn($q) => $q->where('name', 'like', "%$s%"));
        }

        $requests = $query->paginate(20)->withQueryString();

        $stats = [
            'total'       => TelemedicineRequest::count(),
            'pending'     => TelemedicineRequest::where('status', 'pending')->count(),
            'in_progress' => TelemedicineRequest::where('status', 'in_progress')->count(),
            'completed'   => TelemedicineRequest::where('status', 'completed')->count(),
            'cancelled'   => TelemedicineRequest::where('status', 'cancelled')->count(),
        ];

        return view('admin.telemedicine.index', compact('requests', 'stats'));
    }

    public function show($id)
    {
        $req = TelemedicineRequest::with(['requester', 'professional', 'livestock'])
            ->findOrFail($id);

        $vets = User::where('role', 'animal_health_professional')
            ->whereHas('animalHealthProfessional', fn($q) => $q->where('approval_status', 'approved'))
            ->orderBy('name')
            ->get();

        return view('admin.telemedicine.show', compact('req', 'vets'));
    }

    public function assign(Request $request, $id)
    {
        $req = TelemedicineRequest::findOrFail($id);
        $request->validate(['professional_id' => 'required|exists:users,id']);

        $req->update([
            'professional_id' => $request->professional_id,
            'status'          => 'assigned',
        ]);

        return redirect()->route('admin.telemedicine.show', $id)
            ->with('success', 'Vet assigned successfully.');
    }

    public function cancel($id)
    {
        $req = TelemedicineRequest::findOrFail($id);
        $req->update(['status' => 'cancelled', 'admin_notes' => 'Cancelled by admin.']);

        return redirect()->route('admin.telemedicine.index')
            ->with('success', 'Consultation request cancelled.');
    }

    public function updateNotes(Request $request, $id)
    {
        $req = TelemedicineRequest::findOrFail($id);
        $req->update(['admin_notes' => $request->admin_notes]);

        return back()->with('success', 'Notes saved.');
    }
}
