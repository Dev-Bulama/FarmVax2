<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\FarmerEnrollment;
use App\Models\VolunteerStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    /**
     * Show volunteer dashboard.
     */
    public function index()
{
    $user = Auth::user();
    $volunteer = $user->volunteer;

    if (!$volunteer) {
        // Create volunteer record if it doesn't exist
        $volunteer = Volunteer::create([
            'user_id' => $user->id,
            'farmers_enrolled' => 0,
            'is_active' => true,
            'approval_status' => 'approved',
        ]);
    }

    // Get or create volunteer stats (use volunteer->id not user->id)
    $volunteerStat = VolunteerStat::firstOrCreate(
        ['volunteer_id' => $volunteer->id],
        [
            'total_enrollments' => 0,
            'active_farmers' => 0,
            'total_points' => 0,
            'current_badge' => 'bronze',
            'rank' => 0,
        ]
    );

    // Update enrollments count - use volunteer->id
    $enrollmentsCount = FarmerEnrollment::where('enrolled_by', $volunteer->id)->count();
    
    // Calculate points (10 points per enrollment)
    $totalPoints = $enrollmentsCount * 10;

    // Update stats
    $volunteerStat->update([
        'total_enrollments' => $enrollmentsCount,
        'total_points' => $totalPoints,
    ]);
    
    $volunteer->update(['farmers_enrolled' => $enrollmentsCount]);

    // Calculate rank
    $rank = VolunteerStat::where('total_points', '>', $totalPoints)->count() + 1;

    // Build stats array for dashboard
    $stats = [
        'farmers_enrolled' => $enrollmentsCount,
        'total_points' => $totalPoints,
        'badges' => $this->calculateBadges($enrollmentsCount),
        'rank' => $rank,
        'referral_code' => $volunteer->referral_code ?? 'FV-PENDING',
    ];

    // Get recent enrollments
    $recentEnrollments = FarmerEnrollment::where('enrolled_by', $volunteer->id)
        ->with('farmer')
        ->latest()
        ->take(5)
        ->get();

    // Get leaderboard (top 10 volunteers) - FIXED: Include user ID
    $leaderboard = DB::table('volunteer_stats')
        ->join('volunteers', 'volunteer_stats.volunteer_id', '=', 'volunteers.id')
        ->join('users', 'volunteers.user_id', '=', 'users.id')
        ->select(
            'users.id as user_id',
            'users.name', 
            'volunteer_stats.total_points', 
            'volunteer_stats.total_enrollments',
            'volunteers.referral_code'
        )
        ->orderBy('volunteer_stats.total_points', 'desc')
        ->orderBy('volunteer_stats.total_enrollments', 'desc')
        ->limit(10)
        ->get();

    return view('volunteer.dashboard', compact('user', 'volunteer', 'stats', 'recentEnrollments', 'leaderboard'));
}
    /**
     * Calculate badges earned based on enrollments
     */
    private function calculateBadges($enrollments)
    {
        $badges = 0;
        $milestones = [5, 10, 25, 50, 100];
        
        foreach ($milestones as $milestone) {
            if ($enrollments >= $milestone) {
                $badges++;
            }
        }
        
        return $badges;
    }

    /**
     * Show enroll farmer form.
     */
    public function showEnrollForm()
    {
        $user = Auth::user();
        $countries = \App\Models\Country::orderBy('name')->get();
        
        return view('volunteer.enroll-farmer', compact('user', 'countries'));
    }

    /**
     * Enroll a new farmer.
     */
    public function enrollFarmer(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'country_id' => ['required', 'exists:countries,id'],
            'state_id' => ['required', 'exists:states,id'],
            'lga_id' => ['required', 'exists:lgas,id'],
            'address' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $volunteer = Auth::user()->volunteer;

        // Create farmer user
        $farmer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'lga_id' => $request->lga_id,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'farmer',
            'referred_by' => $volunteer->id, // Link referral
        ]);

        // Create enrollment record
        FarmerEnrollment::create([
            'farmer_id' => $farmer->id,
            'enrolled_by' => $volunteer->id,
            'enrollment_method' => 'volunteer',
            'location' => $request->address,
            'notes' => 'Enrolled by volunteer: ' . Auth::user()->name,
        ]);

        // Increment volunteer's farmers enrolled count
        $volunteer->increment('farmers_enrolled');

        return redirect()->route('volunteer.dashboard')
            ->with('success', 'Farmer enrolled successfully! +10 points earned!');
    }

    /**
     * Show farmers enrolled by this volunteer.
     */
    public function myFarmers()
    {
        $user = Auth::user();
        $volunteer = $user->volunteer;
        
        // Get enrollment stats
        $totalFarmers = FarmerEnrollment::where('enrolled_by', $volunteer->id)->count();
        $thisMonth = FarmerEnrollment::where('enrolled_by', $volunteer->id)
            ->whereMonth('created_at', now()->month)
            ->count();
        $pointsEarned = $totalFarmers * 10;
        
        $farmers = FarmerEnrollment::where('enrolled_by', $volunteer->id)
            ->with(['farmer.country', 'farmer.state', 'farmer.lga'])
            ->latest()
            ->paginate(10);

        return view('volunteer.my-farmers', compact('user', 'farmers', 'totalFarmers', 'thisMonth', 'pointsEarned'));
    }

    /**
     * Show volunteer activity log.
     */
    public function activity()
    {
        $volunteer = Auth::user()->volunteer;
        
        $totalEnrolled = FarmerEnrollment::where('enrolled_by', $volunteer->id)->count();
        $thisMonth = FarmerEnrollment::where('enrolled_by', $volunteer->id)
            ->whereMonth('created_at', now()->month)
            ->count();
        $thisWeek = FarmerEnrollment::where('enrolled_by', $volunteer->id)
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->count();
        $today = FarmerEnrollment::where('enrolled_by', $volunteer->id)
            ->whereDate('created_at', now())
            ->count();
        $recentActivity = FarmerEnrollment::where('enrolled_by', $volunteer->id)
            ->with('farmer')
            ->latest()
            ->take(20)
            ->get();

        return view('volunteer.activity', compact(
            'totalEnrolled',
            'thisMonth',
            'thisWeek',
            'today',
            'recentActivity'
        ));
    }

    /**
     * Show volunteer profile
     */
    public function profile()
    {
        return view('volunteer.profile');
    }
}