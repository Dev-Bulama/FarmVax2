<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovedProfessional
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user is an animal health professional
        if (!auth()->user()->isAnimalHealthProfessional()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if professional profile exists and is approved
        $professional = auth()->user()->animalHealthProfessional;
        
        if (!$professional) {
            return redirect()->route('professional.pending-approval')
                ->with('error', 'Please complete your professional profile.');
        }

        if ($professional->approval_status !== 'approved') {
            return redirect()->route('professional.pending-approval')
                ->with('info', 'Your application is pending approval.');
        }

        return $next($request);
    }
}