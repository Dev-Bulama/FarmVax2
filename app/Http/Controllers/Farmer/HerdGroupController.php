<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\HerdGroup;
use App\Models\Livestock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HerdGroupController extends Controller
{
    /**
     * Display all herd groups
     */
    public function index()
    {
        $user = Auth::user();
        
        $herdGroups = HerdGroup::where('user_id', $user->id)
            ->withCount('livestock')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        // Statistics
        $stats = [
            'total_herds' => HerdGroup::where('user_id', $user->id)->count(),
            'active_herds' => HerdGroup::where('user_id', $user->id)->where('is_active', true)->count(),
            'total_animals' => HerdGroup::where('user_id', $user->id)->sum('total_count'),
            'needs_attention' => HerdGroup::where('user_id', $user->id)
                ->where('sick_count', '>', 0)
                ->count(),
        ];
        
        return view('farmer.herd-groups.index', compact('herdGroups', 'stats'));
    }
    
    /**
     * Show create form
     */
    public function create()
    {
        // Get unassigned livestock
        $unassignedLivestock = Livestock::where('user_id', Auth::id())
            ->whereNull('herd_group_id')
            ->get();
        
        return view('farmer.herd-groups.create', compact('unassignedLivestock'));
    }
    
    /**
     * Store new herd group
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'purpose' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'livestock_ids' => 'nullable|array',
            'livestock_ids.*' => 'exists:livestock,id',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['color_code'] = $validated['color_code'] ?? '#2fcb6e';
        
        $herdGroup = HerdGroup::create($validated);
        
        // Assign livestock to herd
        if (isset($validated['livestock_ids']) && !empty($validated['livestock_ids'])) {
            Livestock::whereIn('id', $validated['livestock_ids'])
                ->where('user_id', Auth::id())
                ->update(['herd_group_id' => $herdGroup->id]);
        }
        
        // Update statistics
        $herdGroup->updateStatistics();
        
        return redirect()->route('farmer.herd-groups.show', $herdGroup->id)
            ->with('success', 'Herd group created successfully!');
    }
    
    /**
     * Display herd group details
     */
    public function show($id)
    {
        $herdGroup = HerdGroup::where('user_id', Auth::id())
            ->with(['livestock', 'livestock.vaccinationHistory'])
            ->findOrFail($id);
        
        // Get statistics
        $stats = $herdGroup->getStatistics();
        
        // Get livestock by health status
        $healthyLivestock = $herdGroup->healthyLivestock()->get();
        $sickLivestock = $herdGroup->sickLivestock()->get();
        
        // Get recent vaccinations
        $recentVaccinations = $herdGroup->recentVaccinations(30);
        
        // Get unassigned livestock (for adding more)
        $unassignedLivestock = Livestock::where('user_id', Auth::id())
            ->whereNull('herd_group_id')
            ->get();
        
        return view('farmer.herd-groups.show', compact(
            'herdGroup',
            'stats',
            'healthyLivestock',
            'sickLivestock',
            'recentVaccinations',
            'unassignedLivestock'
        ));
    }
    
    /**
     * Show edit form
     */
    public function edit($id)
    {
        $herdGroup = HerdGroup::where('user_id', Auth::id())->findOrFail($id);
        
        // Get unassigned livestock
        $unassignedLivestock = Livestock::where('user_id', Auth::id())
            ->whereNull('herd_group_id')
            ->get();
        
        return view('farmer.herd-groups.edit', compact('herdGroup', 'unassignedLivestock'));
    }
    
    /**
     * Update herd group
     */
    public function update(Request $request, $id)
    {
        $herdGroup = HerdGroup::where('user_id', Auth::id())->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'purpose' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'is_active' => 'nullable|boolean',
        ]);
        
        $herdGroup->update($validated);
        
        return redirect()->route('farmer.herd-groups.show', $herdGroup->id)
            ->with('success', 'Herd group updated successfully!');
    }
    
    /**
     * Delete herd group
     */
    public function destroy($id)
    {
        $herdGroup = HerdGroup::where('user_id', Auth::id())->findOrFail($id);
        
        // Unassign all livestock from this herd
        Livestock::where('herd_group_id', $herdGroup->id)
            ->update(['herd_group_id' => null]);
        
        $herdGroup->delete();
        
        return redirect()->route('farmer.herd-groups.index')
            ->with('success', 'Herd group deleted successfully!');
    }
    
    /**
     * Add livestock to herd
     */
    public function addLivestock(Request $request, $id)
    {
        $herdGroup = HerdGroup::where('user_id', Auth::id())->findOrFail($id);
        
        $validated = $request->validate([
            'livestock_ids' => 'required|array',
            'livestock_ids.*' => 'exists:livestock,id',
        ]);
        
        // Assign livestock to herd
        Livestock::whereIn('id', $validated['livestock_ids'])
            ->where('user_id', Auth::id())
            ->update(['herd_group_id' => $herdGroup->id]);
        
        // Update statistics
        $herdGroup->updateStatistics();
        
        return redirect()->route('farmer.herd-groups.show', $herdGroup->id)
            ->with('success', 'Livestock added to herd successfully!');
    }
    
    /**
     * Remove livestock from herd
     */
    public function removeLivestock(Request $request, $id)
    {
        $herdGroup = HerdGroup::where('user_id', Auth::id())->findOrFail($id);
        
        $validated = $request->validate([
            'livestock_id' => 'required|exists:livestock,id',
        ]);
        
        // Remove livestock from herd
        Livestock::where('id', $validated['livestock_id'])
            ->where('user_id', Auth::id())
            ->update(['herd_group_id' => null]);
        
        // Update statistics
        $herdGroup->updateStatistics();
        
        return redirect()->route('farmer.herd-groups.show', $herdGroup->id)
            ->with('success', 'Livestock removed from herd!');
    }
    
    /**
     * Toggle herd active status
     */
    public function toggleStatus($id)
    {
        $herdGroup = HerdGroup::where('user_id', Auth::id())->findOrFail($id);
        
        $herdGroup->is_active = !$herdGroup->is_active;
        $herdGroup->save();
        
        $status = $herdGroup->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('farmer.herd-groups.show', $herdGroup->id)
            ->with('success', "Herd group {$status} successfully!");
    }
    
    /**
     * Get herd statistics API
     */
    public function statistics($id)
    {
        $herdGroup = HerdGroup::where('user_id', Auth::id())->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'statistics' => $herdGroup->getStatistics(),
        ]);
    }
}