<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\FarmerEnrollment;
use App\Models\VolunteerStat;
use Illuminate\Http\Request;
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
        $user = auth()->user();
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

        // Get or create volunteer stats
        $volunteerStat = VolunteerStat::firstOrCreate(
            ['volunteer_id' => $user->id],
            [
                'total_enrollments' => 0,
                'active_farmers' => 0,
                'total_points' => 0,
                'current_badge' => 'bronze',
                'rank' => 0,
            ]
        );

        // Update enrollments count
        $enrollmentsCount = FarmerEnrollment::where('enrolled_by', $user->id)->count();
        $volunteer->update(['farmers_enrolled' => $enrollmentsCount]);
        $volunteerStat->update(['total_enrollments' => $enrollmentsCount]);

        // Calculate points (10 points per enrollment)
        $totalPoints = $enrollmentsCount * 10;
        $volunteerStat->update(['total_points' => $totalPoints]);

        // Calculate rank
        $rank = VolunteerStat::where('total_points', '>', $totalPoints)->count() + 1;
        $volunteerStat->update(['rank' => $rank]);

        // Build stats array for dashboard
        $stats = [
            'farmers_enrolled' => $enrollmentsCount,
            'total_points' => $totalPoints,
            'badges' => $this->calculateBadges($enrollmentsCount),
            'rank' => $rank,
        ];

        // Get recent enrollments
        $recentEnrollments = FarmerEnrollment::where('enrolled_by', $user->id)
            ->with('farmer')
            ->latest()
            ->take(5)
            ->get();

        // Get leaderboard (top 10 volunteers)
        $leaderboard = DB::table('volunteer_stats')
            ->join('users', 'volunteer_stats.volunteer_id', '=', 'users.id')
            ->select('users.id', 'users.name', 'volunteer_stats.total_points')
            ->orderBy('volunteer_stats.total_points', 'desc')
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
        $user = auth()->user();
        
        return view('volunteer.enroll-farmer', compact('user'));
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
            'address' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        // Create farmer user
        $farmer = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'farmer',
        ]);

        // Create enrollment record
        FarmerEnrollment::create([
            'farmer_id' => $farmer->id,
            'enrolled_by' => auth()->id(),
            'enrollment_method' => 'volunteer',
            'location' => $request->address,
            'notes' => 'Enrolled by volunteer: ' . auth()->user()->name,
        ]);

        // Increment volunteer's farmers enrolled count
        $volunteer = auth()->user()->volunteer;
        $volunteer->increment('farmers_enrolled');

        return redirect()->route('volunteer.dashboard')
            ->with('success', 'Farmer enrolled successfully! +10 points earned!');
    }

    /**
     * Show farmers enrolled by this volunteer.
     */
    public function myFarmers()
    {
        $user = auth()->user();
        
        $farmers = FarmerEnrollment::where('enrolled_by', $user->id)
            ->with('farmer')
            ->latest()
            ->paginate(10);

        return view('volunteer.my-farmers', compact('user', 'farmers'));
    }

    /**
     * Show volunteer activity log.
     */
    public function activity()
    {
        $totalEnrolled = auth()->user()->enrolledFarmers()->count();
        $thisMonth = auth()->user()->enrolledFarmers()
            ->whereMonth('created_at', now()->month)
            ->count();
        $thisWeek = auth()->user()->enrolledFarmers()
            ->whereDate('created_at', '>=', now()->subDays(7))
            ->count();
        $today = auth()->user()->enrolledFarmers()
            ->whereDate('created_at', now())
            ->count();
        $recentActivity = auth()->user()->enrolledFarmers()
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