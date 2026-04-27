<?php

namespace App\Http\Controllers;

use App\Models\DiseaseDetection;
use App\Models\Livestock;
use App\Services\DiseaseDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DiseaseDetectionController extends Controller
{
    public function __construct(protected DiseaseDetectionService $service) {}

    public function index()
    {
        $scans = DiseaseDetection::where('user_id', Auth::id())
            ->with('livestock')
            ->latest()
            ->paginate(12);

        return view('disease-detection.index', compact('scans'));
    }

    public function create()
    {
        $user     = Auth::user();
        $livestock = Livestock::where('user_id', $user->id)
            ->whereNotIn('health_status', ['deceased'])
            ->orderBy('livestock_type')
            ->get();

        return view('disease-detection.create', compact('livestock'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'             => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
            'animal_type'       => 'required|string|max:50',
            'livestock_id'      => 'nullable|exists:livestock,id',
            'symptoms_reported' => 'nullable|string|max:1000',
        ]);

        $imagePath = $request->file('image')->store('disease-scans', 'public');

        $detection = DiseaseDetection::create([
            'user_id'           => Auth::id(),
            'livestock_id'      => $request->livestock_id,
            'image_path'        => $imagePath,
            'animal_type'       => $request->animal_type,
            'symptoms_reported' => $request->symptoms_reported,
            'status'            => 'processing',
        ]);

        // Run AI analysis synchronously (for shared hosting without queue)
        $this->service->analyse($detection);

        // Redirect to public results page (Phase 6/7)
        return redirect()->route('disease-detection.public', $detection->id)
            ->with('success', 'Analysis complete!');
    }

    /**
     * Public scan endpoint — no authentication required.
     * Called via fetch() from the floating scanner bubble on the landing page.
     * Returns JSON { success, redirect } so the browser can navigate to results.
     */
    public function publicStore(Request $request)
    {
        $request->validate([
            'image'             => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
            'animal_type'       => 'nullable|string|max:50',
            'symptoms_reported' => 'nullable|string|max:1000',
        ]);

        $imagePath = $request->file('image')->store('disease-scans', 'public');

        $detection = DiseaseDetection::create([
            'user_id'           => Auth::id(),   // null for guests, user ID if logged in
            'image_path'        => $imagePath,
            'animal_type'       => $request->input('animal_type', 'livestock'),
            'symptoms_reported' => $request->input('symptoms_reported'),
            'status'            => 'processing',
        ]);

        $this->service->analyse($detection);

        return response()->json([
            'success'  => true,
            'redirect' => route('disease-detection.public', $detection->id),
        ]);
    }

    public function publicShow($id)
    {
        $scan = DiseaseDetection::with('livestock')->findOrFail($id);

        return view('disease-detection.public-show', compact('scan'));
    }

    public function show($id)
    {
        $scan = DiseaseDetection::where('user_id', Auth::id())
            ->with('livestock')
            ->findOrFail($id);

        return view('disease-detection.show', compact('scan'));
    }

    public function destroy($id)
    {
        $scan = DiseaseDetection::where('user_id', Auth::id())->findOrFail($id);
        Storage::disk('public')->delete($scan->image_path);
        $scan->delete();

        return redirect()->route('disease-detection.index')
            ->with('success', 'Scan record deleted.');
    }
}
