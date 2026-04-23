<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\AdView;
use App\Models\User;
use Carbon\Carbon;

class AdService
{
    /**
     * Get ads targeted to a specific user.
     * ads table columns: is_active (bool), target_type (string), target_roles (JSON array),
     * target_locations (JSON), country_id, state_id, lga_id, views, clicks
     */
    public function getAdsForUser(User $user, $type = null, $limit = 3)
    {
        $query = Ad::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', Carbon::now());
            });

        if ($type) {
            $query->where('type', $type);
        }

        // target_type: 'all' | 'role' | 'location'
        $query->where(function ($q) use ($user) {
            $q->where('target_type', 'all')
              ->orWhere(function ($sq) use ($user) {
                  $sq->where('target_type', 'role')
                     ->whereJsonContains('target_roles', $user->role);
              })
              ->orWhere(function ($sq) use ($user) {
                  $sq->where('target_type', 'location')
                     ->where(function ($lq) use ($user) {
                         $lq->where('country_id', $user->country_id)
                            ->orWhere('state_id', $user->state_id)
                            ->orWhere('lga_id', $user->lga_id);
                     });
              });
        });

        $ads = $query->inRandomOrder()->limit($limit)->get();

        foreach ($ads as $ad) {
            $this->trackAdView($ad, $user);
        }

        return $ads;
    }

    /**
     * Track a single ad view (once per user per day).
     */
    public function trackAdView(Ad $ad, User $user)
    {
        $exists = AdView::where('ad_id', $ad->id)
            ->where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if (!$exists) {
            AdView::create([
                'ad_id'      => $ad->id,
                'user_id'    => $user->id,
                'ip_address' => request()->ip(),
                'viewed_at'  => now(),
            ]);

            // ads table uses 'views', not 'views_count'
            $ad->increment('views');
        }
    }

    /**
     * Track an ad click.
     */
    public function trackAdClick($adId, User $user)
    {
        $ad = Ad::find($adId);
        if (!$ad) {
            return false;
        }

        // ads table uses 'clicks', not 'clicks_count'
        $ad->increment('clicks');
        return true;
    }

    /**
     * Get banner ads (type = banner).
     */
    public function getBannerAds($user = null)
    {
        return Ad::where('is_active', true)
            ->where('type', 'banner')
            ->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->limit(1)
            ->get();
    }

    /**
     * Get sidebar ads (type = sidebar).
     */
    public function getSidebarAds($user = null)
    {
        return Ad::where('is_active', true)
            ->where('type', 'sidebar')
            ->where('start_date', '<=', now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->limit(2)
            ->get();
    }

    /**
     * Get inline ads for a specific user.
     */
    public function getInlineAds(User $user)
    {
        return $this->getAdsForUser($user, 'inline', 3);
    }
}
