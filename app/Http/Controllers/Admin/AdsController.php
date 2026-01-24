<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    /**
     * Display a listing of ads
     */
    public function index()
    {
        $ads = Ad::with('creator')->latest()->paginate(20);

        $stats = [
            'total' => Ad::count(),
            'active' => Ad::where('is_active', true)->count(),
            'total_views' => Ad::sum('views_count'),
            'total_clicks' => Ad::sum('clicks_count'),
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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url',
            'type' => 'required|in:banner,sidebar,popup,inline',
            'target_audience' => 'nullable|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'cost_per_click' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $ad = Ad::create($validated);

    // Send email notifications if requested
    if ($request->has('send_notification')) {
        dispatch(new \App\Jobs\AdNotificationJob($ad));
    }

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement created successfully!');
    }

    /**
     * Display the specified ad
     */
    public function show($id)
    {
        $ad = Ad::with('creator')->findOrFail($id);
        
        $recentViews = AdView::where('ad_id', $id)
            ->with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('admin.ads.show', compact('ad', 'recentViews'));
    }

    /**
     * Show the form for editing the specified ad
     */
    public function edit($id)
    {
        $ad = Ad::findOrFail($id);
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update the specified ad
     */
    public function update(Request $request, $id)
    {
        $ad = Ad::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link_url' => 'nullable|url',
            'type' => 'required|in:banner,sidebar,popup,inline',
            'target_audience' => 'nullable|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'cost_per_click' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($ad->image_url) {
                $oldPath = str_replace('/storage/', '', $ad->image_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('ads', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $ad->update($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement updated successfully!');
    }

    /**
     * Remove the specified ad
     */
    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);
        
        // Delete image
        if ($ad->image_url) {
            $path = str_replace('/storage/', '', $ad->image_url);
            Storage::disk('public')->delete($path);
        }
        
        $ad->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement deleted successfully!');
    }

    /**
     * Toggle ad active status
     */
    public function toggleStatus($id)
    {
        $ad = Ad::findOrFail($id);
        $ad->is_active = !$ad->is_active;
        $ad->save();

        $status = $ad->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Advertisement {$status} successfully!");
    }
}