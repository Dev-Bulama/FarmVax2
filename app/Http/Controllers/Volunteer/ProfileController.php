<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'organization' => 'nullable|string|max:255',
            'assigned_area' => 'nullable|string|max:255',
            'motivation' => 'nullable|string',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);

        // Update user info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->address = $validated['address'] ?? $user->address;
        
        // Update location - FIXED
        $user->country_id = $validated['country_id'] ?? $user->country_id;
        $user->state_id = $validated['state_id'] ?? $user->state_id;
        $user->lga_id = $validated['lga_id'] ?? $user->lga_id;

        // Change password if provided
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update volunteer info
        if ($user->volunteer) {
            $user->volunteer->update([
                'organization' => $validated['organization'] ?? $user->volunteer->organization,
                'assigned_area' => $validated['assigned_area'] ?? $user->volunteer->assigned_area,
                'motivation' => $validated['motivation'] ?? $user->volunteer->motivation,
            ]);
        }
        
        return redirect()->route('volunteer.profile')->with('success', 'Profile updated successfully!');
    }
}