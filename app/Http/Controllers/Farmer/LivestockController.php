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
        $user = Auth::user();

        $query = Livestock::where('user_id', $user->id)
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

        $stats = [
            'total' => Livestock::where('user_id', $user->id)->count(),
            'healthy' => Livestock::where('user_id', $user->id)->where('health_status', 'healthy')->count(),
            'sick' => Livestock::where('user_id', $user->id)->whereIn('health_status', ['sick'])->count(),
            'vaccinated' => Livestock::where('user_id', $user->id)->where('is_vaccinated', true)->count(),
        ];

        return view('individual.livestock.index', compact('livestock', 'stats'));
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
            'livestock_type' => 'required|in:cattle,goat,sheep,pig,chicken,duck,turkey,rabbit,horse,donkey,other',
            'tag_number' => 'nullable|string|max:100|unique:livestock',
            'breed' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,unknown',
            'health_status' => 'required|in:healthy,sick,recovering,deceased',
            'acquisition_date' => 'nullable|date',
            'acquisition_method' => 'nullable|in:birth,purchase,gift,inheritance,other',
            'notes' => 'nullable|string|max:1000',
            'name' => 'nullable|string|max:255',
            'age_years' => 'nullable|integer|min:0',
            'age_months' => 'nullable|integer|min:0|max:11',
            'weight_kg' => 'nullable|numeric|min:0',
            'color_markings' => 'nullable|string|max:255',
            'is_vaccinated' => 'nullable|boolean',
            'herd_group_id' => 'nullable|exists:herd_groups,id',
        ]);

        Livestock::create([
            'user_id' => Auth::id(),
            'owner_id' => Auth::id(),  // Also set owner_id for compatibility
            'livestock_type' => $validated['livestock_type'],
            'type' => $validated['livestock_type'],  // production DB requires this column
            'tag_number' => $validated['tag_number'],
            'name' => $validated['name'] ?? null,
            'breed' => $validated['breed'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'],
            'health_status' => $validated['health_status'],
            'acquisition_date' => $validated['acquisition_date'] ?? null,
            'acquisition_method' => $validated['acquisition_method'] ?? null,
            'age_years' => $validated['age_years'] ?? null,
            'age_months' => $validated['age_months'] ?? null,
            'weight' => $validated['weight_kg'] ?? null,  // Map weight_kg to weight
            'weight_unit' => 'kg',  // Set unit
            'color' => $validated['color_markings'] ?? null,  // Map color_markings to color
            'is_vaccinated' => $validated['is_vaccinated'] ?? false,
            'herd_group_id' => $validated['herd_group_id'] ?? null,
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
            'livestock_type' => 'required|in:cattle,goat,sheep,pig,chicken,duck,turkey,rabbit,horse,donkey,other',
            'tag_number' => 'nullable|string|max:100|unique:livestock,tag_number,' . $id,
            'breed' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,unknown',
            'health_status' => 'required|in:healthy,sick,recovering,deceased',
            'notes' => 'nullable|string|max:1000',
            'name' => 'nullable|string|max:255',
            'age_years' => 'nullable|integer|min:0',
            'age_months' => 'nullable|integer|min:0|max:11',
            'weight_kg' => 'nullable|numeric|min:0',
            'color_markings' => 'nullable|string|max:255',
            'is_vaccinated' => 'nullable|boolean',
            'herd_group_id' => 'nullable|exists:herd_groups,id',
        ]);

        // Map form fields to database fields
        $updateData = $validated;
        if (isset($validated['weight_kg'])) {
            $updateData['weight'] = $validated['weight_kg'];
            $updateData['weight_unit'] = 'kg';
            unset($updateData['weight_kg']);
        }
        if (isset($validated['color_markings'])) {
            $updateData['color'] = $validated['color_markings'];
            unset($updateData['color_markings']);
        }

        // Set type column (production DB requires this)
        $updateData['type'] = $updateData['livestock_type'];

        $animal->update($updateData);

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