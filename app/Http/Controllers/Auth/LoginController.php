<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Rate limiting
        $this->checkTooManyFailedAttempts($request);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

      if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if account is active
            $accountStatus = $user->account_status ?? $user->status ?? 'inactive';
            if ($accountStatus !== 'active') {
                Auth::logout();
                
                $statusMessages = [
                    'inactive' => 'Your account is inactive. Please contact support.',
                    'suspended' => 'Your account has been suspended. Please contact support.',
                    'banned' => 'Your account has been banned. Please contact support.',
                ];
                
                $message = $statusMessages[$accountStatus] ?? 'Your account is not active. Please contact support.';
                
                throw ValidationException::withMessages([
                    'email' => [$message],
                ]);
            }

            $request->session()->regenerate();

            // Clear rate limiter on successful login
            RateLimiter::clear($this->throttleKey($request));

            // Redirect based on user role
            return $this->redirectBasedOnRole();
        }

        // Increment rate limiter
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => ['These credentials do not match our records.'],
        ]);
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectBasedOnRole()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'farmer':
    return redirect()->route('individual.dashboard');  // âœ… CORRECT!
            case 'animal_health_professional':
                // Check if approved
                if ($user->hasApprovedProfessionalProfile()) {
                    return redirect()->route('professional.dashboard');
                } else {
                    return redirect()->route('professional.pending-approval');
                }
            case 'volunteer':
                return redirect()->route('volunteer.dashboard');
            default:
                return redirect('/');
        }
    }

    /**
     * Check for too many failed login attempts.
     */
    protected function checkTooManyFailedAttempts(Request $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            
            throw ValidationException::withMessages([
                'email' => ["Too many login attempts. Please try again in {$seconds} seconds."],
            ]);
        }
    }

    /**
     * Get the rate limiting throttle key.
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }
}