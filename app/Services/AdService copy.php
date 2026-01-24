<?php

namespace App\Services;

use App\Models\Ad;
use App\Models\AdView;
use App\Models\User;
use Carbon\Carbon;

class AdService
{
    /**
     * Get ads targeted to a specific user
     */
    public function getAdsForUser(User $user, $type = null, $limit = 3)
    {
        $query = Ad::where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());

        // Filter by ad type if specified
        if ($type) {
            $query->where('type', $type);
        }

        // Filter by target audience (role-based targeting)
        $query->where(function($q) use ($user) {
            $q->whereJsonContains('target_audience', 'all')
              ->orWhereJsonContains('target_audience', $user->role);
        });

        // Filter by location if ad has location targeting
        $query->where(function($q) use ($user) {
            $q->whereNull('target_location')
              ->orWhereJsonContains('target_location', ['country_id' => $user->country_id])
              ->orWhereJsonContains('target_location', ['state_id' => $user->state_id])
              ->orWhereJsonContains('target_location', ['lga_id' => $user->lga_id]);
        });

        // Get random ads to show variety
        $ads = $query->inRandomOrder()
            ->limit($limit)
            ->get();

        // Track views for these ads
        foreach ($ads as $ad) {
            $this->trackAdView($ad, $user);
        }

        return $ads;
    }

    /**
     * Track ad view
     */
    public function trackAdView(Ad $ad, User $user)
    {
        // Check if user already viewed this ad today
        $existingView = AdView::where('ad_id', $ad->id)
            ->where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if (!$existingView) {
            // Create new view record
            AdView::create([
                'ad_id' => $ad->id,
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'viewed_at' => now(),
                'clicked' => false,
            ]);

            // Increment ad views count
            $ad->increment('views_count');
        }
    }

    /**
     * Track ad click
     */
    public function trackAdClick($adId, User $user)
    {
        $ad = Ad::find($adId);

        if (!$ad) {
            return false;
        }

        // Find the most recent view by this user
        $adView = AdView::where('ad_id', $adId)
            ->where('user_id', $user->id)
            ->whereNull('clicked_at')
            ->latest()
            ->first();

        if ($adView) {
            $adView->update([
                'clicked' => true,
                'clicked_at' => now(),
            ]);

            // Increment ad clicks count
            $ad->increment('clicks_count');

            return true;
        }

        return false;
    }

    /**
     * Get banner ads
     */
    public function getBannerAds(User $user)
    {
        return $this->getAdsForUser($user, 'banner', 1);
    }

    /**
     * Get sidebar ads
     */
    public function getSidebarAds(User $user)
    {
        return $this->getAdsForUser($user, 'sidebar', 2);
    }

    /**
     * Get inline ads
     */
    public function getInlineAds(User $user)
    {
        return $this->getAdsForUser($user, 'inline', 3);
    }
}