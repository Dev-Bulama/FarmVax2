<?php

namespace App\Http\Controllers\DataCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FarmRecord;
use App\Models\DataCollectorProfile;

class DashboardController extends Controller
{
    /**
     * Show data collector dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        $profile = $user->dataCollectorProfile;

        // Get statistics
        $stats = [
            'total_submissions' => FarmRecord::where('user_id', $user->id)->count(),
            'draft_records' => FarmRecord::where('user_id', $user->id)->draft()->count(),
            'submitted_records' => FarmRecord::where('user_id', $user->id)->submitted()->count(),
            'approved_records' => FarmRecord::where('user_id', $user->id)->approved()->count(),
            'rejected_records' => FarmRecord::where('user_id', $user->id)->rejected()->count(),
            'pending_review' => FarmRecord::where('user_id', $user->id)->underReview()->count(),
            'accuracy_rate' => $profile ? $profile->accuracy_rate : 0,
            'performance_percentage' => $profile ? $profile->performance_percentage : 0,
        ];

        // Get recent activities
        $draft_records = FarmRecord::where('user_id', $user->id)
            ->draft()
            ->latest()
            ->take(5)
            ->get();

        $recent_submissions = FarmRecord::where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'under_review', 'approved', 'rejected'])
            ->latest()
            ->take(5)
            ->get();

        return view('data-collector.dashboard', compact(
            'stats',
            'profile',
            'draft_records',
            'recent_submissions'
        ));
    }

    /**
     * Show farm records list (placeholder)
     */
    public function farmRecords()
    {
        $farmRecords = FarmRecord::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('data-collector.farm-records.index', compact('farmRecords'));
    }

    /**
     * Show farm record details (placeholder)
     */
    public function showFarmRecord($id)
    {
        $record = FarmRecord::where('user_id', auth()->id())
            ->findOrFail($id);

        return view('data-collector.farm-records.show', compact('record'));
    }

    /**
     * Show profile
     */
    public function profile()
    {
        $user = auth()->user();
        $profile = $user->dataCollectorProfile;
        
        return view('data-collector.profile', compact('user', 'profile'));
    }
}