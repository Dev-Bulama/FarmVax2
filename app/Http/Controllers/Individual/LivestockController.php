<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivestockController extends Controller
{
    /**
     * Display a listing of livestock
     */
    public function index()
    {
        $user = Auth::user();
        
        $livestock = Livestock::where('user_id', $user->id)
            ->with(['herdGroup', 'vaccinationHistory'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $stats = [
            'total' => Livestock::where('user_id', $user->id)->count(),
            'healthy' => Livestock::where('user_id', $user->id)->where('health_status', 'healthy')->count(),
            'sick' => Livestock::where('user_id', $user->id)->whereIn('health_status', ['sick', 'under_treatment'])->count(),
            'vaccinated' => Livestock::where('user_id', $user->id)->where('is_vaccinated', true)->count(),
        ];
        
        return view('individual.livestock.index', compact('livestock', 'stats'));
    }

    /**
     * Show the form for creating new livestock
     */
    public function create()
    {
        $herdGroups = \App\Models\HerdGroup::where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();
        
        return view('individual.livestock.create', compact('herdGroups'));
    }

    /**
     * Store a newly created livestock
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'livestock_type' => 'required|string',
            'breed' => 'nullable|string|max:255',
            'tag_number' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'age_years' => 'nullable|integer|min:0',
            'age_months' => 'nullable|integer|min:0|max:11',
            'health_status' => 'required|in:healthy,sick,under_treatment,deceased',
            'weight_kg' => 'nullable|numeric|min:0',
            'color_markings' => 'nullable|string',
            'herd_group_id' => 'nullable|exists:herd_groups,id',
            'is_vaccinated' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        $livestock = Livestock::create($validated);

        // Update herd group statistics if assigned
        if ($livestock->herd_group_id) {
            $livestock->herdGroup->updateStatistics();
        }

        return redirect()->route('individual.livestock.show', $livestock->id)
            ->with('success', 'Livestock added successfully!');
    }

    /**
     * Display the specified livestock
     */
    public function show($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())
            ->with(['herdGroup', 'vaccinationHistory'])
            ->findOrFail($id);
        
        return view('individual.livestock.show', compact('livestock'));
    }

    /**
     * Show the form for editing livestock
     */
    public function edit($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);
        
        $herdGroups = \App\Models\HerdGroup::where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();
        
        return view('individual.livestock.edit', compact('livestock', 'herdGroups'));
    }

    /**
     * Update the specified livestock
     */
    public function update(Request $request, $id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'livestock_type' => 'required|string',
            'breed' => 'nullable|string|max:255',
            'tag_number' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'age_years' => 'nullable|integer|min:0',
            'age_months' => 'nullable|integer|min:0|max:11',
            'health_status' => 'required|in:healthy,sick,under_treatment,deceased',
            'weight_kg' => 'nullable|numeric|min:0',
            'color_markings' => 'nullable|string',
            'herd_group_id' => 'nullable|exists:herd_groups,id',
            'is_vaccinated' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $oldHerdGroupId = $livestock->herd_group_id;
        $livestock->update($validated);

        // Update old herd statistics
        if ($oldHerdGroupId && $oldHerdGroupId != $livestock->herd_group_id) {
            $oldHerdGroup = \App\Models\HerdGroup::find($oldHerdGroupId);
            if ($oldHerdGroup) {
                $oldHerdGroup->updateStatistics();
            }
        }

        // Update new herd statistics
        if ($livestock->herd_group_id) {
            $livestock->herdGroup->updateStatistics();
        }

        return redirect()->route('individual.livestock.show', $livestock->id)
            ->with('success', 'Livestock updated successfully!');
    }

    /**
     * Remove the specified livestock
     */
    public function destroy($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);
        
        $herdGroupId = $livestock->herd_group_id;
        
        $livestock->delete();

        // Update herd statistics if was in a herd
        if ($herdGroupId) {
            $herdGroup = \App\Models\HerdGroup::find($herdGroupId);
            if ($herdGroup) {
                $herdGroup->updateStatistics();
            }
        }

        return redirect()->route('individual.livestock.index')
            ->with('success', 'Livestock deleted successfully!');
    }
}