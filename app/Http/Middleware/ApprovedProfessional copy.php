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
        $user = auth()->user();

        // Check if user is an animal health professional
        if (!$user || !$user->isAnimalHealthProfessional()) {
            return redirect()->route('login');
        }

        // Check if professional profile exists and is approved
        $professional = $user->animalHealthProfessional;

        if (!$professional) {
            return redirect()->route('professional.pending-approval')
                ->with('error', 'Professional profile not found.');
        }

        if ($professional->approval_status !== 'approved') {
            return redirect()->route('professional.pending-approval')
                ->with('info', 'Your application is pending approval. You will receive an email once approved.');
        }

        return $next($request);
    }
}