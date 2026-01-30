<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AnimalHealthProfessional;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with(['country', 'state', 'lga']);

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        // Calculate statistics
        $stats = [
            'total' => User::count(),
            'farmers' => User::where('role', 'farmer')->count(),
            'professionals' => User::where('role', 'animal_health_professional')->count(),
            'volunteers' => User::where('role', 'volunteer')->count(),
            'active' => User::where('account_status', 'active')->count(),
            'suspended' => User::where('account_status', 'suspended')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,farmer,animal_health_professional,volunteer',
            'address' => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'account_status' => 'nullable|in:active,inactive,suspended,banned',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['account_status'] = $validated['account_status'] ?? 'active';
        $validated['status'] = 'active';

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create($validated);

            // Create related records based on role
            if ($user->role === 'volunteer') {
                Volunteer::create([
                    'user_id' => $user->id,
                    'status' => 'active',
                    'approval_status' => 'approved',
                    'is_active' => true,
                    'points' => 0,
                    'joined_at' => now(),
                ]);
            }

            if ($user->role === 'animal_health_professional') {
                AnimalHealthProfessional::create([
                    'user_id' => $user->id,
                    'approval_status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'submitted_at' => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a user
     */
    public function edit($id)
    {
        $user = User::with(['country', 'state', 'lga'])->findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:admin,farmer,animal_health_professional,volunteer',
            'account_status' => 'required|in:active,inactive,suspended,banned',
            'country_id' => 'nullable|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
            'address' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Activate a user
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['account_status' => 'active', 'status' => 'active']);
        return back()->with('success', 'User activated successfully!');
    }

    /**
     * Suspend a user
     */
    public function suspend($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot suspend admin users!');
        }

        $user->update(['account_status' => 'suspended', 'status' => 'suspended']);
        return back()->with('success', 'User suspended successfully!');
    }

    /**
     * Deactivate a user
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot deactivate admin users!');
        }

        $user->update(['account_status' => 'inactive', 'status' => 'inactive']);
        return back()->with('success', 'User deactivated successfully!');
    }

    /**
     * Ban a user
     */
    public function ban($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot ban admin users!');
        }

        $user->update(['account_status' => 'banned', 'status' => 'banned']);
        return back()->with('success', 'User banned successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin users!');
        }

        DB::beginTransaction();
        try {
            // Delete related records
            if ($user->volunteer) {
                $user->volunteer->delete();
            }
            if ($user->animalHealthProfessional) {
                $user->animalHealthProfessional->delete();
            }

            $user->delete();
            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions on users
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,suspend,deactivate,ban,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $validated['user_ids'])->get();

        foreach ($users as $user) {
            if ($user->role === 'admin') {
                continue;
            }

            switch ($validated['action']) {
                case 'activate':
                    $user->update(['account_status' => 'active', 'status' => 'active']);
                    break;
                case 'suspend':
                    $user->update(['account_status' => 'suspended', 'status' => 'suspended']);
                    break;
                case 'deactivate':
                    $user->update(['account_status' => 'inactive', 'status' => 'inactive']);
                    break;
                case 'ban':
                    $user->update(['account_status' => 'banned', 'status' => 'banned']);
                    break;
                case 'delete':
                    $user->delete();
                    break;
            }
        }

        return back()->with('success', 'Bulk action completed successfully');
    }

    /**
     * Convert user role dynamically
     */
    public function convertRole(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent converting admin users
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot convert admin users to other roles!');
        }

        $validated = $request->validate([
            'new_role' => 'required|in:farmer,animal_health_professional,volunteer',
        ]);

        $newRole = $validated['new_role'];
        $oldRole = $user->role;

        // If same role, no conversion needed
        if ($oldRole === $newRole) {
            return back()->with('info', 'User already has this role');
        }

        DB::beginTransaction();
        try {
            // Step 1: Handle OLD role cleanup
            $this->cleanupOldRoleData($user, $oldRole);

            // Step 2: Update user role
            $user->update(['role' => $newRole]);

            // Step 3: Create NEW role data
            $this->createNewRoleData($user, $newRole);

            // Step 4: Log the conversion
            $this->logRoleConversion($user, $oldRole, $newRole);

            // Step 5: Clear user sessions (force re-login to new dashboard)
            $this->invalidateUserSessions($user);

            DB::commit();

            return back()->with('success',
                "User role converted from " . ucfirst(str_replace('_', ' ', $oldRole)) .
                " to " . ucfirst(str_replace('_', ' ', $newRole)) . " successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Role conversion error: ' . $e->getMessage());
            return back()->with('error', 'Error converting user role: ' . $e->getMessage());
        }
    }

    /**
     * Cleanup old role-specific data
     */
    protected function cleanupOldRoleData($user, $oldRole)
    {
        switch ($oldRole) {
            case 'volunteer':
                // Deactivate volunteer profile instead of deleting (preserve history)
                if ($user->volunteer) {
                    $user->volunteer->update([
                        'is_active' => false,
                        'status' => 'inactive',
                    ]);
                }
                break;

            case 'animal_health_professional':
                // Deactivate professional profile (preserve history)
                if ($user->animalHealthProfessional) {
                    $user->animalHealthProfessional->update([
                        'approval_status' => 'inactive',
                    ]);
                }
                break;

            case 'farmer':
                // Farmers don't have separate profile table
                // Keep all farm data intact for potential future conversion back
                break;
        }
    }

    /**
     * Create new role-specific data
     */
    protected function createNewRoleData($user, $newRole)
    {
        switch ($newRole) {
            case 'volunteer':
                // Check if volunteer profile exists (from previous conversion)
                if ($user->volunteer) {
                    // Reactivate existing profile
                    $user->volunteer->update([
                        'is_active' => true,
                        'status' => 'active',
                        'approval_status' => 'approved',
                    ]);
                } else {
                    // Create new volunteer profile
                    Volunteer::create([
                        'user_id' => $user->id,
                        'status' => 'active',
                        'approval_status' => 'approved',
                        'is_active' => true,
                        'points' => 0,
                        'joined_at' => now(),
                    ]);
                }
                break;

            case 'animal_health_professional':
                // Check if professional profile exists
                if ($user->animalHealthProfessional) {
                    // Reactivate existing profile
                    $user->animalHealthProfessional->update([
                        'approval_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                    ]);
                } else {
                    // Create new professional profile
                    AnimalHealthProfessional::create([
                        'user_id' => $user->id,
                        'approval_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'submitted_at' => now(),
                    ]);
                }
                break;

            case 'farmer':
                // Farmers don't have separate profile table
                // All user data remains intact
                break;
        }
    }

    /**
     * Log role conversion for audit trail
     */
    protected function logRoleConversion($user, $oldRole, $newRole)
    {
        try {
            // Only log if table exists
            if (Schema::hasTable('role_conversion_logs')) {
                DB::table('role_conversion_logs')->insert([
                    'user_id' => $user->id,
                    'old_role' => $oldRole,
                    'new_role' => $newRole,
                    'converted_by' => auth()->id(),
                    'converted_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Silent fail - don't break conversion if logging fails
            Log::warning('Failed to log role conversion: ' . $e->getMessage());
        }
    }

    /**
     * Invalidate user sessions to force re-login
     */
    protected function invalidateUserSessions($user)
    {
        // Update remember_token to invalidate sessions
        $user->update([
            'remember_token' => Str::random(60),
        ]);
    }
}