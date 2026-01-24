<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AnimalHealthProfessional;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
}