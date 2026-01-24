<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        $user = auth()->user();

        // Check if user has the required role
        if ($user->role !== $role) {
            // Redirect based on user's actual role
            return $this->redirectToAppropriateRoute($user->role);
        }

        // Check if user account is active
        $accountStatus = $user->account_status ?? $user->status ?? 'inactive';
        if ($accountStatus !== 'active') {
            auth()->logout();
            
            $statusMessages = [
                'inactive' => 'Your account is inactive. Please contact support.',
                'suspended' => 'Your account has been suspended. Please contact support.',
                'banned' => 'Your account has been banned. Please contact support.',
            ];
            
            $message = $statusMessages[$accountStatus] ?? 'Your account is not active. Please contact support.';
            
            return redirect()->route('login')->with('error', $message);
        }

        // Allow the request to proceed
        return $next($request);
    }

    /**
     * Redirect user to their appropriate dashboard
     *
     * @param string $role
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectToAppropriateRoute(string $role)
    {
        $message = 'You do not have permission to access this page.';

        return match($role) {
            'admin' => redirect()->route('admin.dashboard')->with('error', $message),
            'data_collector' => redirect()->route('data-collector.dashboard')->with('error', $message),
            'individual' => redirect()->route('individual.dashboard')->with('error', $message),
            default => redirect()->route('login')->with('error', 'Invalid user role. Please contact support.'),
        };
    }
}