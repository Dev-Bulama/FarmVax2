<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AnimalHealthProfessional;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show professional profile
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get professional profile
        $profile = AnimalHealthProfessional::where('user_id', $user->id)->first();
        
        // If no profile exists, create one
        if (!$profile) {
            $profile = AnimalHealthProfessional::create([
                'user_id' => $user->id,
                'professional_type' => 'veterinarian',
                'approval_status' => 'pending',
                'submitted_at' => now(),
            ]);
        }
        
        return view('professional.profile.index', compact('user', 'profile'));
    }
    
    /**
     * Update professional profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            
            // Professional info
            'professional_type' => 'required|string',
            'license_number' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'organization' => 'nullable|string|max:255',
            'specialization' => 'nullable|string',
            'bio' => 'nullable|string|max:1000',
            
            // Password (optional)
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            
            // Profile picture
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        // Update user info
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
        ];
        
        // Handle password change
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $userData['password'] = Hash::make($request->new_password);
        }
        
        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $userData['profile_picture'] = $path;
        }
        
        $user->update($userData);
        
        // Update professional profile
        $profile = AnimalHealthProfessional::where('user_id', $user->id)->first();
        
        $profileData = [
            'professional_type' => $validated['professional_type'],
            'license_number' => $validated['license_number'] ?? null,
            'experience_years' => $validated['experience_years'] ?? null,
            'organization' => $validated['organization'] ?? null,
            'specialization' => $validated['specialization'] ?? null,
            'bio' => $validated['bio'] ?? null,
        ];
        
        if ($profile) {
            $profile->update($profileData);
        } else {
            $profileData['user_id'] = $user->id;
            $profileData['approval_status'] = 'pending';
            $profileData['submitted_at'] = now();
            AnimalHealthProfessional::create($profileData);
        }
        
        return redirect()->route('professional.profile')
            ->with('success', 'Profile updated successfully!');
    }
}