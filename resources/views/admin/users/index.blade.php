@extends('layouts.admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
    <p class="text-gray-600 mt-1">Manage all system users</p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-600">
        <p class="text-sm text-gray-600">Total Users</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-600">
        <p class="text-sm text-gray-600">Farmers</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['farmers'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-600">
        <p class="text-sm text-gray-600">Professionals</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['professionals'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-600">
        <p class="text-sm text-gray-600">Volunteers</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['volunteers'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-600">
        <p class="text-sm text-gray-600">Active</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, phone..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Banned</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                <option value="">All Roles</option>
                <option value="farmer" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                <option value="animal_health_professional" {{ request('role') == 'animal_health_professional' ? 'selected' : '' }}>Professional</option>
                <option value="volunteer" {{ request('role') == 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-[#11455B] text-white rounded-lg hover:bg-[#0d3345] transition">
                Filter
            </button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div class="bg-white rounded-lg shadow p-4 mb-6" x-data="{ selectedUsers: [], showBulkActions: false }">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-700">
                <span x-text="selectedUsers.length"></span> user(s) selected
            </span>
            <button @click="showBulkActions = !showBulkActions"
                    x-show="selectedUsers.length > 0"
                    class="px-4 py-2 bg-[#11455B] text-white rounded-lg hover:bg-[#0d3345] transition text-sm">
                Bulk Actions ▾
            </button>
        </div>
    </div>

    <!-- Bulk Actions Dropdown -->
    <div x-show="showBulkActions && selectedUsers.length > 0"
         class="mt-4 p-4 border-t border-gray-200"
         style="display: none;">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">Bulk Convert Selected Users To:</h4>
        <div class="flex flex-wrap gap-2">
            <form action="{{ route('admin.users.bulk-convert-role') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="new_role" value="farmer">
                <template x-for="userId in selectedUsers" :key="userId">
                    <input type="hidden" name="user_ids[]" :value="userId">
                </template>
                <button type="submit"
                        onclick="return confirm('Convert selected users to Farmer? They will be logged out immediately.')"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                    → Farmer
                </button>
            </form>

            <form action="{{ route('admin.users.bulk-convert-role') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="new_role" value="animal_health_professional">
                <template x-for="userId in selectedUsers" :key="userId">
                    <input type="hidden" name="user_ids[]" :value="userId">
                </template>
                <button type="submit"
                        onclick="return confirm('Convert selected users to Professional? They will be logged out immediately.')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    → Professional
                </button>
            </form>

            <form action="{{ route('admin.users.bulk-convert-role') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="new_role" value="volunteer">
                <template x-for="userId in selectedUsers" :key="userId">
                    <input type="hidden" name="user_ids[]" :value="userId">
                </template>
                <button type="submit"
                        onclick="return confirm('Convert selected users to Volunteer? They will be logged out immediately.')"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm">
                    → Volunteer
                </button>
            </form>
        </div>

        <div class="mt-3 text-xs text-gray-500">
            <strong>Note:</strong> Admin users and users already having the target role will be skipped automatically.
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-lg shadow overflow-hidden" x-data="bulkUserSelection()">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox"
                               @change="toggleAll($event)"
                               class="rounded border-gray-300 text-[#2FCB6E] focus:ring-[#2FCB6E]">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role !== 'admin')
                                <input type="checkbox"
                                       :value="{{ $user->id }}"
                                       @change="toggleUser({{ $user->id }}, $event)"
                                       class="user-checkbox rounded border-gray-300 text-[#2FCB6E] focus:ring-[#2FCB6E]">
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#11455B] to-[#2FCB6E] rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($user->role == 'admin') bg-red-100 text-red-800
                                @elseif($user->role == 'farmer') bg-green-100 text-green-800
                                @elseif($user->role == 'animal_health_professional') bg-blue-100 text-blue-800
                                @elseif($user->role == 'volunteer') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->phone ?? 'N/A' }}
                        </td>
<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
    @if($user->country && $user->state && $user->lga)
        {{ $user->lga->name }}, {{ $user->state->name }}, {{ $user->country->name }}
    @else
        <span class="text-gray-400 italic">Not set</span>
    @endif
</td>
                      
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->account_status == 'active')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                            @elseif($user->account_status == 'suspended')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Suspended</span>
                            @elseif($user->account_status == 'banned')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Banned</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Edit -->
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="text-blue-600 hover:text-blue-900 transition" title="Edit">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>

                                @if($user->role !== 'admin')
                                    <!-- Convert Role -->
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open" type="button"
                                                class="text-indigo-600 hover:text-indigo-900 transition" title="Convert Role">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                        </button>

                                        <div x-show="open" @click.away="open = false"
                                             class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                             style="display: none;">
                                            <div class="py-1" role="menu">
                                                <div class="px-4 py-2 text-xs text-gray-500 border-b">Convert to:</div>

                                                @if($user->role !== 'farmer')
                                                <form action="{{ route('admin.users.convert-role', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="new_role" value="farmer">
                                                    <button type="submit" onclick="return confirm('Convert this user to Farmer? They will be logged out immediately.')"
                                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Farmer
                                                    </button>
                                                </form>
                                                @endif

                                                @if($user->role !== 'animal_health_professional')
                                                <form action="{{ route('admin.users.convert-role', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="new_role" value="animal_health_professional">
                                                    <button type="submit" onclick="return confirm('Convert this user to Professional? They will be logged out immediately.')"
                                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Professional
                                                    </button>
                                                </form>
                                                @endif

                                                @if($user->role !== 'volunteer')
                                                <form action="{{ route('admin.users.convert-role', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="new_role" value="volunteer">
                                                    <button type="submit" onclick="return confirm('Convert this user to Volunteer? They will be logged out immediately.')"
                                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Volunteer
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Activate/Suspend -->
                                    @if($user->account_status == 'active')
                                        <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Suspend this user?')"
                                                    class="text-yellow-600 hover:text-yellow-900 transition" title="Suspend">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" onclick="return confirm('Activate this user?')"
                                                    class="text-green-600 hover:text-green-900 transition" title="Activate">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Delete -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                                class="text-red-600 hover:text-red-900 transition" title="Delete">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="mt-4 text-gray-500">No users found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
@endif

<script>
function bulkUserSelection() {
    return {
        init() {
            // Watch for changes in selectedUsers and update parent component
            this.$watch('selectedUsers', (value) => {
                // Sync with parent bulk actions component
                const bulkActionsDiv = document.querySelector('[x-data*="selectedUsers"]');
                if (bulkActionsDiv && bulkActionsDiv.__x) {
                    bulkActionsDiv.__x.$data.selectedUsers = value;
                }
            });
        },
        selectedUsers: [],
        toggleAll(event) {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            this.selectedUsers = [];

            if (event.target.checked) {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = true;
                    this.selectedUsers.push(parseInt(checkbox.value));
                });
            } else {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }

            // Update parent component
            this.updateParent();
        },
        toggleUser(userId, event) {
            if (event.target.checked) {
                if (!this.selectedUsers.includes(userId)) {
                    this.selectedUsers.push(userId);
                }
            } else {
                const index = this.selectedUsers.indexOf(userId);
                if (index > -1) {
                    this.selectedUsers.splice(index, 1);
                }
            }

            // Update parent component
            this.updateParent();
        },
        updateParent() {
            // Sync selected users with parent bulk actions component
            const bulkActionsDiv = document.querySelector('[x-data*="selectedUsers"]');
            if (bulkActionsDiv && bulkActionsDiv.__x) {
                bulkActionsDiv.__x.$data.selectedUsers = this.selectedUsers;
            }
        }
    };
}
</script>

@endsection