<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifiedCollectorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * This middleware ensures that data collectors are approved by admin
     * before they can access the system.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $user = auth()->user();

        // Only apply to data collectors
        if ($user->role !== 'data_collector') {
            // Not a data collector, let them through
            return $next($request);
        }

        // Check if data collector is approved
        if (!$user->is_approved) {
            // Data collector is not yet approved
            return redirect()->route('pending-approval')
                ->with('warning', 'Your application is pending admin approval. You will be notified once approved.');
        }

        // Check if account is active
        if ($user->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        // Data collector is approved and active, allow access
        return $next($request);
    }
}