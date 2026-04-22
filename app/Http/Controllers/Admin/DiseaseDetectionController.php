<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiseaseDetection;
use Illuminate\Http\Request;

class DiseaseDetectionController extends Controller
{
    public function index(Request $request)
    {
        $query = DiseaseDetection::with(['user', 'livestock'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('urgency')) {
            $query->where('urgency_level', $request->urgency);
        }
        if ($request->filled('sick')) {
            $query->where('is_sick', $request->sick === '1');
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%"));
        }

        $scans = $query->paginate(25)->withQueryString();

        $stats = [
            'total'    => DiseaseDetection::count(),
            'sick'     => DiseaseDetection::where('is_sick', true)->count(),
            'healthy'  => DiseaseDetection::where('is_sick', false)->count(),
            'critical' => DiseaseDetection::where('urgency_level', 'critical')->count(),
            'pending'  => DiseaseDetection::where('status', 'processing')->count(),
        ];

        return view('admin.disease-detection.index', compact('scans', 'stats'));
    }

    public function show($id)
    {
        $scan = DiseaseDetection::with(['user', 'livestock'])->findOrFail($id);
        return view('admin.disease-detection.show', compact('scan'));
    }
}
