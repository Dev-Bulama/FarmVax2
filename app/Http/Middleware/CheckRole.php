<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Handle legacy role names
        $role = $this->normalizeLegacyRole($role);

        // Check if user has the required role
        if ($user->role !== $role) {
            // Redirect to appropriate dashboard based on user's actual role
            return redirect()->route($this->getDashboardRoute($user->role));
        }

        return $next($request);
    }

    /**
     * Normalize legacy role names to new role names.
     */
    private function normalizeLegacyRole(string $role): string
    {
        return match($role) {
            'individual' => 'farmer',
            'data_collector' => 'animal_health_professional',
            default => $role,
        };
    }

    /**
     * Get dashboard route based on user role.
     */
    private function getDashboardRoute(string $role): string
    {
        return match($role) {
            'admin' => 'admin.dashboard',
            'farmer' => 'individual.dashboard',
            'animal_health_professional' => 'professional.dashboard',
            'volunteer' => 'volunteer.dashboard',
            default => 'login',
        };
    }
}