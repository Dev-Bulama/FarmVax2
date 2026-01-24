<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Livestock;
use App\Models\VaccinationHistory;
use App\Models\ServiceRequest;
use App\Models\FarmRecord;
use App\Models\OutbreakAlert;
use App\Models\Ad;
use App\Models\BulkMessage;
use App\Models\AdView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display farmer dashboard with ALL features
     */
    public function index()
    {
        $user = Auth::user();

        // ========== LIVESTOCK STATISTICS ==========
        $totalLivestock = Livestock::where('user_id', $user->id)
            ->where('status', '!=', 'deceased')
            ->count();
            
        $healthyLivestock = Livestock::where('user_id', $user->id)
            ->where('health_status', 'healthy')
            ->count();
            
        $sickLivestock = Livestock::where('user_id', $user->id)
            ->whereIn('health_status', ['sick', 'under_treatment'])
            ->count();
            
        $quarantinedLivestock = Livestock::where('user_id', $user->id)
            ->where('quarantine_status', true)
            ->count();
        
        // Calculate health score
        $healthScore = $totalLivestock > 0 
            ? round(($healthyLivestock / $totalLivestock) * 100) 
            : 0;

        // ========== VACCINATION STATISTICS ==========
        $totalVaccinations = VaccinationHistory::whereHas('livestock', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();
        
     $upcomingVaccinations = VaccinationHistory::whereHas('livestock', function($query) use ($user) {
    $query->where('user_id', $user->id);
})
->where(function($query) {
    $query->where('next_booster_due_date', '>=', Carbon::now())
          ->where('next_booster_due_date', '<=', Carbon::now()->addDays(30))
          ->orWhere(function($q) {
              $q->where('next_dose_due_date', '>=', Carbon::now())
                ->where('next_dose_due_date', '<=', Carbon::now()->addDays(30));
          });
})
->count();

$overdueVaccinations = VaccinationHistory::whereHas('livestock', function($query) use ($user) {
    $query->where('user_id', $user->id);
})
->where(function($query) {
    $query->where('next_booster_due_date', '<', Carbon::now())
          ->orWhere('next_dose_due_date', '<', Carbon::now());
})
->count();

        // ========== SERVICE REQUESTS ==========
        $activeServiceRequests = ServiceRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
            
        $completedServiceRequests = ServiceRequest::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // ========== RECENT DATA ==========
        $recentLivestock = Livestock::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $upcomingVaccinationsList = VaccinationHistory::with('livestock')
            ->whereHas('livestock', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where(function($query) {
            $query->where('next_booster_due_date', '>=', Carbon::now())
                  ->orWhere('next_dose_due_date', '>=', Carbon::now());
        })
            ->where(function($query) {
            $query->where('next_booster_due_date', '<=', Carbon::now()->addDays(30))
                  ->orWhere('next_dose_due_date', '<=', Carbon::now()->addDays(30));
        })
            ->orderBy('next_booster_due_date', 'asc')
            ->limit(5)
            ->get();
        
        $recentServiceRequests = ServiceRequest::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        // ========== OUTBREAK ALERTS (LOCATION-BASED) ==========
      // Simplified Outbreak Alerts Query
        $outbreakAlerts = \App\Models\OutbreakAlert::where('is_active', 1)
            ->orderBy('severity', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ========== BULK MESSAGES (TARGETED) ==========
        $recentMessages = BulkMessage::where('status', 'sent')
            ->where(function($query) use ($user) {
                // All users
                $query->where('target_audience', 'all')
                    // Role-based (farmers)
                    ->orWhere(function($q) {
                        $q->where('target_audience', 'role')
                          ->where(function($sq) {
                              $sq->whereJsonContains('recipient_data->target_roles', 'farmer')
                                 ->orWhereJsonContains('target_roles->target_roles', 'farmer');
                          });
                    })
                    // Location-based
                    ->orWhere(function($q) use ($user) {
                        $q->where('target_audience', 'location')
                          ->where(function($sq) use ($user) {
                              $sq->where('country_id', $user->country_id)
                                 ->orWhere('state_id', $user->state_id)
                                 ->orWhere('lga_id', $user->lga_id);
                          });
                    });
            })
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // ========== ACTIVE ADS (TARGETED) ==========
        $activeAds = Ad::where('is_active', 1)
            ->where('start_date', '<=', Carbon::now())
            ->where(function($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where(function($query) use ($user) {
                // All users
                $query->where('target_audience', 'all')
                    // Role-based (farmers)
                    ->orWhere(function($q) {
                        $q->where('target_audience', 'role')
                          ->where(function($sq) {
                              $sq->whereJsonContains('target_roles->target_roles', 'farmer')
                                 ->orWhereJsonContains('target_roles->target_roles', 'individual');
                          });
                    })
                    // Location-based
                    ->orWhere(function($q) use ($user) {
                        $q->where('target_audience', 'location')
                          ->where(function($sq) use ($user) {
                              $sq->where('country_id', $user->country_id)
                                 ->orWhere('state_id', $user->state_id)
                                 ->orWhere('lga_id', $user->lga_id);
                          });
                    });
            })
            ->orderBy('priority', 'desc')
            ->limit(3)
            ->get();

        // Track ad views
        foreach ($activeAds as $ad) {
            AdView::firstOrCreate([
                'ad_id' => $ad->id,
                'user_id' => $user->id,
            ]);
        }

        // ========== FARM RECORD STATUS ==========
        $farmRecord = FarmRecord::where('user_id', $user->id)
            ->orWhere('farmer_id', $user->id)
            ->first();

        return view('farmer.dashboard', compact(
            // Livestock
            'totalLivestock',
            'healthyLivestock',
            'sickLivestock',
            'quarantinedLivestock',
            'healthScore',
            'recentLivestock',
            // Vaccinations
            'totalVaccinations',
            'upcomingVaccinations',
            'overdueVaccinations',
            'upcomingVaccinationsList',
            // Service Requests
            'activeServiceRequests',
            'completedServiceRequests',
            'recentServiceRequests',
            // NEW FEATURES
            'outbreakAlerts',
            'recentMessages',
            'activeAds',
            'farmRecord'
        ));
    }

    /**
     * Track ad click
     */
    public function trackAdClick($adId)
    {
        $user = Auth::user();
        
        $adView = AdView::where('ad_id', $adId)
            ->where('user_id', $user->id)
            ->first();
            
        if ($adView && !$adView->clicked) {
            $adView->update([
                'clicked' => true,
                'clicked_at' => Carbon::now(),
            ]);
        }
        
        $ad = Ad::find($adId);
        if ($ad && $ad->link_url) {
            return redirect($ad->link_url);
        }
        
        return back();
    }
}