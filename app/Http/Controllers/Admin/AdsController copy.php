<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    public function index()
    {
        $ads = Ad::with('creator')->latest()->paginate(20);

        $stats = [
            'total' => Ad::count(),
            'active' => Ad::where('status', 'active')->count(),
            'total_views' => Ad::sum('views'),
            'total_clicks' => Ad::sum('clicks'),
        ];

        return view('admin.ads.index', compact('ads', 'stats'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'required|string',
            'target_roles' => 'nullable|array',
            'target_locations' => 'nullable|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public/ads');
            $validated['image'] = Storage::url($validated['image']);
        }

        $validated['created_by'] = auth()->id();
        $validated['views'] = 0;
        $validated['clicks'] = 0;

        Ad::create($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement created successfully');
    }

    public function edit($id)
    {
        $ad = Ad::findOrFail($id);

        return view('admin.ads.edit', compact('ad'));
    }

    public function update(Request $request, $id)
    {
        $ad = Ad::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'category' => 'required|string',
            'target_roles' => 'nullable|array',
            'target_locations' => 'nullable|array',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive,expired',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($ad->image) {
                Storage::delete(str_replace('/storage/', 'public/', $ad->image));
            }

            $validated['image'] = $request->file('image')->store('public/ads');
            $validated['image'] = Storage::url($validated['image']);
        }

        $ad->update($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement updated successfully');
    }

    public function destroy($id)
    {
        $ad = Ad::findOrFail($id);

        // Delete image
        if ($ad->image) {
            Storage::delete(str_replace('/storage/', 'public/', $ad->image));
        }

        $ad->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Advertisement deleted successfully');
    }

    public function analytics($id)
    {
        $ad = Ad::with(['adViews.user'])->findOrFail($id);

        $viewsByDate = AdView::where('ad_id', $id)
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.ads.analytics', compact('ad', 'viewsByDate'));
    }
}
