<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivestockController extends Controller
{
    /**
     * Display a listing of livestock
     */
    public function index(Request $request)
    {
        $query = Livestock::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('tag_number', 'like', '%' . $request->search . '%')
                  ->orWhere('livestock_type', 'like', '%' . $request->search . '%')
                  ->orWhere('breed', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('livestock_type', $request->type);
        }

        // Filter by health status
        if ($request->filled('health_status')) {
            $query->where('health_status', $request->health_status);
        }

        $livestock = $query->paginate(15);

        return view('individual.livestock.index', compact('livestock'));
    }

    /**
     * Show the form for creating new livestock
     */
    // public function create()
    // {
    //     return view('individual.livestock.create');
    // }
    public function create()
{
    $herdGroups = \App\Models\HerdGroup::where('user_id', Auth::id())
        ->where('is_active', true)
        ->get();
    
    return view('farmer.livestock.create', compact('herdGroups'));
}

    /**
     * Store a newly created livestock
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'livestock_type' => 'required|in:cattle,goat,sheep,poultry,pig,other',
            'tag_number' => 'required|string|max:100|unique:livestock',
            'breed' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female',
            'health_status' => 'required|in:healthy,sick,under_treatment,recovering',
            'acquisition_date' => 'nullable|date',
            'acquisition_method' => 'nullable|in:birth,purchase,gift,other',
            'notes' => 'nullable|string|max:1000',
        ]);

        Livestock::create([
            'user_id' => Auth::id(),
            'livestock_type' => $validated['livestock_type'],
            'tag_number' => $validated['tag_number'],
            'breed' => $validated['breed'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'],
            'health_status' => $validated['health_status'],
            'acquisition_date' => $validated['acquisition_date'] ?? null,
            'acquisition_method' => $validated['acquisition_method'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('individual.livestock.index')
            ->with('success', 'Livestock added successfully!');
    }

    /**
     * Display the specified livestock
     */
    public function show($id)
    {
        $animal = Livestock::where('user_id', Auth::id())
            ->with('vaccinationHistory')
            ->findOrFail($id);

        return view('individual.livestock.show', compact('animal'));
    }

    /**
     * Show the form for editing livestock
     */
    public function edit($id)
    {
        $animal = Livestock::where('user_id', Auth::id())->findOrFail($id);

        return view('individual.livestock.edit', compact('animal'));
    }

    /**
     * Update the specified livestock
     */
    public function update(Request $request, $id)
    {
        $animal = Livestock::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'livestock_type' => 'required|in:cattle,goat,sheep,poultry,pig,other',
            'tag_number' => 'required|string|max:100|unique:livestock,tag_number,' . $id,
            'breed' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female',
            'health_status' => 'required|in:healthy,sick,under_treatment,recovering',
            'notes' => 'nullable|string|max:1000',
        ]);

        $animal->update($validated);

        return redirect()
            ->route('individual.livestock.index')
            ->with('success', 'Livestock updated successfully!');
    }

    /**
     * Remove the specified livestock
     */
    public function destroy($id)
    {
        $animal = Livestock::where('user_id', Auth::id())->findOrFail($id);
        $animal->delete();

        return redirect()
            ->route('individual.livestock.index')
            ->with('success', 'Livestock deleted successfully!');
    }
}