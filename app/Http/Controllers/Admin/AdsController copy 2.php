<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\AdView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    /**
     * Display a listing of ads
     */
    public function index()
    {
        $ads = Ad::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => Ad::count(),
            'active' => Ad::where('status', 'active')->count(),
            'inactive' => Ad::where('status', 'inactive')->count(),
            'expired' => Ad::where('status', 'active')->where('end_date', '<', now())->count(),
            'total_views' => AdView::count(),
            'total_clicks' => AdView::where('clicked', true)->count(),
        ];

        return view('admin.ads.index', compact('ads', 'stats'));
    }

    /**
     * Show the form for creating a new ad
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created ad
     */
   /**
 * Store a newly created ad
 */
/**
 * Store a newly created ad
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:banner,popup,sidebar,inline',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'link_url' => 'nullable|url',
        'target_type' => 'required|in:all,role,location',
        'target_roles' => 'nullable|array',
        'target_roles.*' => 'in:farmer,animal_health_professional,volunteer',
        'country_id' => 'nullable|exists:countries,id',
        'state_id' => 'nullable|exists:states,id',
        'lga_id' => 'nullable|exists:lgas,id',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'status' => 'required|in:active,inactive',
        'priority' => 'nullable|integer|min:0|max:100',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = $image->storeAs('ads', $imageName, 'public');
        $validated['image_path'] = $imagePath;
    }

    // Store targeting data as JSON
    $validated['targeting_data'] = json_encode([
        'target_type' => $validated['target_type'],
        'target_roles' => $validated['target_roles'] ?? [],
        'country_id' => $validated['country_id'] ?? null,
        'state_id' => $validated['state_id'] ?? null,
        'lga_id' => $validated['lga_id'] ?? null,
    ]);

    // Set required fields
    $validated['created_by'] = auth()->id();
    $validated['content'] = $validated['description'] ?? '';
    $validated['category'] = 'general'; // Default category

    // Remove fields that aren't in the database
    unset($validated['image']);
    unset($validated['target_roles']);
    unset($validated['description']);

    Ad::create($validated);

    return redirect()->route('admin.ads.index')
        ->with('success', 'Advertisement created successfully!');
}    /**
     * Display the specified ad
     */
    public function show($id)
    {
        $ad = Ad::with(['creator', 'country', 'state', 'lga'])->findOrFail($id);
        
        $stats = [
            'total_views' => AdView::where('ad_id', $id)->count(),
            'total_clicks' => AdView::where('ad_id', $id)->where('clicked', true)->count(),
            'click_rate' => 0,
            'unique_users' => AdView::where('ad_id', $id)->distinct('user_id')->count('user_id'),
        ];

        if ($stats['total_views'] > 0) {
            $stats['click_rate'] = round(($stats['total_clicks'] / $stats['total_views']) * 100, 2);
        }

        return view('admin.ads.show', compact('ad', 'stats'));
    }

    /**
     * Show the form for editing the specified ad
     */
    public function edit($id)
    {
        $ad = Ad::with(['country', 'state', 'lga'])->findOrFail($id);
        return view('admin.ads.edit', compact('ad'));
    }

   /**
 * Update the specified ad
 */
/**
 * Update the specified ad
 */
public function update(Request $request, $id)
{
    $ad = Ad::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:banner,popup,sidebar,inline',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'link_url' => 'nullable|url',
        'target_type' => 'required|in:all,role,location',
        'target_roles' => 'nullable|array',
        'target_roles.*' => 'in:farmer,animal_health_professional,volunteer',
        'country_id' => 'nullable|exists:countries,id',
        'state_id' => 'nullable|exists:states,id',
        'lga_id' => 'nullable|exists:lgas,id',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'status' => 'required|in:active,inactive',
        'priority' => 'nullable|integer|min:0|max:100',
    ]);

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image
        if ($ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = $image->storeAs('ads', $imageName, 'public');
        $validated['image_path'] = $imagePath;
    }

    // Store targeting data as JSON
    $validated['targeting_data'] = json_encode([
        'target_type' => $validated['target_type'],
        'target_roles' => $validated['target_roles'] ?? [],
        'country_id' => $validated['country_id'] ?? null,
        'state_id' => $validated['state_id'] ?? null,
        'lga_id' => $validated['lga_id'] ?? null,
    ]);

    // Set content and category
    $validated['content'] = $validated['description'] ?? '';
    $validated['category'] = $ad->category ?? 'general';

    // Remove fields that aren't in the database
    unset($validated['image']);
    unset($validated['target_roles']);
    unset($validated['description']);

    $ad->update($validated);

    return redirect()->route('admin.ads.index')
        ->with('success', 'Advertisement updated successfully!');
}
    /**
     * Toggle ad status
     */
    public function toggleStatus($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->status = $ad->status == 'active' ? 'inactive' : 'active';
        $ad->save();

        return back()->with('success', 'Ad status updated successfully!');
    }

    /**
     * Remove the specified ad
     */
    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);

        // Delete image
        if ($ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement deleted successfully!');
    }
}