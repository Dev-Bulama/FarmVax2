<?php

namespace App\Http\Controllers\Individual;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LivestockController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $livestock = Livestock::where('user_id', $user->id)
            ->with(['herdGroup', 'vaccinationHistory'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = [
            'total'     => Livestock::where('user_id', $user->id)->sum('quantity'),
            'healthy'   => Livestock::where('user_id', $user->id)->where('health_status', 'healthy')->sum('quantity'),
            'sick'      => Livestock::where('user_id', $user->id)->whereIn('health_status', ['sick', 'under_treatment'])->sum('quantity'),
            'vaccinated'=> Livestock::where('user_id', $user->id)->where('is_vaccinated', true)->sum('quantity'),
        ];

        return view('farmer.livestock.index', compact('livestock', 'stats'));
    }

    public function create()
    {
        $herdGroups = \App\Models\HerdGroup::where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        return view('farmer.livestock.create', compact('herdGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'livestock_type' => 'required|in:cattle,goat,sheep,pig,chicken,duck,turkey,rabbit,horse,donkey,other',
            'breed'          => 'nullable|string|max:255',
            'tag_number'     => 'nullable|string|max:255',
            'name'           => 'nullable|string|max:255',
            'gender'         => 'required|in:male,female,unknown',
            'date_of_birth'  => 'nullable|date',
            'age_years'      => 'nullable|integer|min:0',
            'age_months'     => 'nullable|integer|min:0|max:11',
            'health_status'  => 'required|in:healthy,sick,recovering,deceased',
            'weight_kg'      => 'nullable|numeric|min:0',
            'color_markings' => 'nullable|string',
            'herd_group_id'  => 'nullable|exists:herd_groups,id',
            'is_vaccinated'  => 'nullable|boolean',
            'notes'          => 'nullable|string',
            'quantity'       => 'nullable|integer|min:1',
        ]);

        $data = [
            'user_id'        => Auth::id(),
            'owner_id'       => Auth::id(),
            'livestock_type' => $validated['livestock_type'],
            'type'           => $validated['livestock_type'],
            'breed'          => $validated['breed'] ?? null,
            'tag_number'     => $validated['tag_number'] ?? null,
            'name'           => $validated['name'] ?? null,
            'gender'         => $validated['gender'],
            'date_of_birth'  => $validated['date_of_birth'] ?? null,
            'age_years'      => $validated['age_years'] ?? null,
            'age_months'     => $validated['age_months'] ?? null,
            'health_status'  => $validated['health_status'],
            'weight'         => $validated['weight_kg'] ?? null,
            'weight_unit'    => 'kg',
            'color'          => $validated['color_markings'] ?? null,
            'herd_group_id'  => $validated['herd_group_id'] ?? null,
            'is_vaccinated'  => $validated['is_vaccinated'] ?? false,
            'notes'          => $validated['notes'] ?? null,
            'quantity'       => $validated['quantity'] ?? 1,
            'status'         => 'active',
        ];

        $livestock = Livestock::create($data);

        if ($livestock->herd_group_id) {
            $livestock->herdGroup->updateStatistics();
        }

        return redirect()->route('farmer.livestock.index')
            ->with('success', 'Livestock added successfully!');
    }

    public function show($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())
            ->with(['herdGroup', 'vaccinationHistory'])
            ->findOrFail($id);

        return view('farmer.livestock.show', compact('livestock'));
    }

    public function edit($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);

        $herdGroups = \App\Models\HerdGroup::where('user_id', Auth::id())
            ->where('is_active', true)
            ->get();

        return view('farmer.livestock.edit', compact('livestock', 'herdGroups'));
    }

    public function update(Request $request, $id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'livestock_type' => 'required|in:cattle,goat,sheep,pig,chicken,duck,turkey,rabbit,horse,donkey,other',
            'breed'          => 'nullable|string|max:255',
            'tag_number'     => 'nullable|string|max:255',
            'name'           => 'nullable|string|max:255',
            'gender'         => 'required|in:male,female,unknown',
            'date_of_birth'  => 'nullable|date',
            'age_years'      => 'nullable|integer|min:0',
            'age_months'     => 'nullable|integer|min:0|max:11',
            'health_status'  => 'required|in:healthy,sick,recovering,deceased',
            'weight_kg'      => 'nullable|numeric|min:0',
            'color_markings' => 'nullable|string',
            'herd_group_id'  => 'nullable|exists:herd_groups,id',
            'is_vaccinated'  => 'nullable|boolean',
            'notes'          => 'nullable|string',
            'quantity'       => 'nullable|integer|min:1',
        ]);

        $oldHerdGroupId = $livestock->herd_group_id;

        $livestock->update([
            'livestock_type' => $validated['livestock_type'],
            'type'           => $validated['livestock_type'],
            'breed'          => $validated['breed'] ?? null,
            'tag_number'     => $validated['tag_number'] ?? null,
            'name'           => $validated['name'] ?? null,
            'gender'         => $validated['gender'],
            'date_of_birth'  => $validated['date_of_birth'] ?? null,
            'age_years'      => $validated['age_years'] ?? null,
            'age_months'     => $validated['age_months'] ?? null,
            'health_status'  => $validated['health_status'],
            'weight'         => $validated['weight_kg'] ?? null,
            'color'          => $validated['color_markings'] ?? null,
            'herd_group_id'  => $validated['herd_group_id'] ?? null,
            'is_vaccinated'  => $validated['is_vaccinated'] ?? false,
            'notes'          => $validated['notes'] ?? null,
            'quantity'       => $validated['quantity'] ?? $livestock->quantity,
        ]);

        if ($oldHerdGroupId && $oldHerdGroupId != $livestock->herd_group_id) {
            $old = \App\Models\HerdGroup::find($oldHerdGroupId);
            if ($old) $old->updateStatistics();
        }
        if ($livestock->herd_group_id) {
            $livestock->herdGroup->updateStatistics();
        }

        return redirect()->route('farmer.livestock.index')
            ->with('success', 'Livestock updated successfully!');
    }

    public function destroy($id)
    {
        $livestock = Livestock::where('user_id', Auth::id())->findOrFail($id);

        $herdGroupId = $livestock->herd_group_id;
        $livestock->delete();

        if ($herdGroupId) {
            $herdGroup = \App\Models\HerdGroup::find($herdGroupId);
            if ($herdGroup) $herdGroup->updateStatistics();
        }

        return redirect()->route('farmer.livestock.index')
            ->with('success', 'Livestock deleted successfully!');
    }
}
