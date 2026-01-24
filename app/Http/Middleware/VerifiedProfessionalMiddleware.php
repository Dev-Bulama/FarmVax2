<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AnimalHealthProfessional;

class VerifiedProfessionalMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Check if user is a professional
        if ($user->role !== 'animal_health_professional') {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        
        // Get professional profile
        $profile = AnimalHealthProfessional::where('user_id', $user->id)->first();
        
        // Check if approved
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved by admin before accessing this feature.');
        }
        
        return $next($request);
    }
}