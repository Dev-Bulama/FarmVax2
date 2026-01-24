<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AnimalHealthProfessional;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with(['state', 'lga']);

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status (account_status for new field, status for legacy)
        if ($request->filled('status')) {
            $query->where(function($q) use ($request) {
                $q->where('status', $request->status)
                  ->orWhere('account_status', $request->status);
            });
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

        $users = $query->latest()->paginate(20);

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
     * Show the form for editing a user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
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
            'status' => 'required|in:active,suspended,pending',
            'role' => 'required|in:admin,data_collector,individual',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
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

        $user->update(['status' => 'suspended']);

        return back()->with('success', 'User suspended successfully!');
    }

    /**
     * Activate a user
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return back()->with('success', 'User activated successfully!');
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

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
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

        $user->update(['account_status' => 'deactivated']);

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

        $user->update(['account_status' => 'banned']);

        return back()->with('success', 'User banned successfully!');
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
            // Skip admins for safety
            if ($user->role === 'admin') {
                continue;
            }

            switch ($validated['action']) {
                case 'activate':
                    $user->update(['account_status' => 'active']);
                    break;
                case 'suspend':
                    $user->update(['account_status' => 'suspended']);
                    break;
                case 'deactivate':
                    $user->update(['account_status' => 'deactivated']);
                    break;
                case 'ban':
                    $user->update(['account_status' => 'banned']);
                    break;
                case 'delete':
                    $user->delete();
                    break;
            }
        }

        return back()->with('success', 'Bulk action completed successfully');
    }

    /**
     * Create new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user
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
            'state_id' => 'nullable|exists:states,id',
            'lga_id' => 'nullable|exists:lgas,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['account_status'] = 'active';
        $validated['status'] = 'active';

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }
}
