<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\User;
use App\Models\FarmerEnrollment;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    /**
     * Display a listing of volunteers.
     */
   public function index()
{
    $volunteers = Volunteer::with(['user.country', 'user.state', 'user.lga'])
        ->latest()
        ->paginate(20);

    return view('admin.volunteers.index', compact('volunteers'));
}

    /**
     * Show the form for creating a new volunteer.
     */
    public function create()
    {
        return view('admin.volunteers.create');
    }

    /**
     * Store a newly created volunteer.
     */
    public function store(Request $request)
    {
        // Implementation here
    }

    /**
     * Display the specified volunteer.
     */
    public function show($id)
    {
        $volunteer = Volunteer::with('user')->findOrFail($id);
        
        return view('admin.volunteers.show', compact('volunteer'));
    }

    /**
     * Show the form for editing the specified volunteer.
     */
    public function edit($id)
    {
        $volunteer = Volunteer::with('user')->findOrFail($id);
        
        return view('admin.volunteers.edit', compact('volunteer'));
    }

    /**
     * Update the specified volunteer.
     */
    public function update(Request $request, $id)
    {
        // Implementation here
    }

    /**
     * Remove the specified volunteer.
     */
    public function destroy($id)
    {
        // Implementation here
    }

    /**
     * Show referrals made by a specific volunteer.
     */
    public function referrals($id)
    {
        $volunteer = Volunteer::with('user')->findOrFail($id);
        
        // Get all farmers referred by this volunteer (via referral code)
        $referrals = User::where('referred_by', $volunteer->id)
            ->where('role', 'farmer')
            ->with(['country', 'state', 'lga'])
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total_referrals' => User::where('referred_by', $volunteer->id)->count(),
            'total_points' => User::where('referred_by', $volunteer->id)->count() * 10,
            'this_month' => User::where('referred_by', $volunteer->id)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];
        
        return view('admin.volunteers.referrals', compact('volunteer', 'referrals', 'stats'));
    }

    /**
     * Approve a volunteer application.
     */
    public function approve($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->update([
            'approval_status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Volunteer approved successfully!');
    }

    /**
     * Reject a volunteer application.
     */
    public function reject($id)
    {
        $volunteer = Volunteer::findOrFail($id);
        $volunteer->update([
            'approval_status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Volunteer rejected.');
    }
}