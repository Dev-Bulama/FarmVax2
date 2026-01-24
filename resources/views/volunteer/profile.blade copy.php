<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    @include('volunteer.partials.sidebar')

    <div class="flex-1 overflow-auto">
        <header class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-2xl font-bold text-indigo-600">My Profile</h1>
                <p class="text-sm text-gray-600">View and manage your volunteer information</p>
            </div>
            
            <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                <svg id="menu-open-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="menu-close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <main class="px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-md p-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="max-w-4xl mx-auto">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Personal Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <p class="text-base text-gray-900">{{ auth()->user()->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <p class="text-base text-gray-900">{{ auth()->user()->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <p class="text-base text-gray-900">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <p class="text-base text-gray-900">{{ auth()->user()->address ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->volunteer)
                <!-- Volunteer Information -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Volunteer Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Organization</label>
                                <p class="text-base text-gray-900">{{ auth()->user()->volunteer->organization ?? 'Independent' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                                <p class="text-base text-gray-900">{{ auth()->user()->volunteer->availability ?? 'Flexible' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Motivation</label>
                                <p class="text-base text-gray-900">{{ auth()->user()->volunteer->motivation ?? 'Helping the farming community' }}</p>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-6 p-4 rounded-md {{ auth()->user()->volunteer->status === 'active' ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200' }}">
                            <div class="flex items-center">
                                @if(auth()->user()->volunteer->status === 'active')
                                <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium text-green-800">Active Volunteer</span>
                                @else
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-800">Status: {{ ucfirst(auth()->user()->volunteer->status) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">My Impact</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-indigo-50 rounded-md">
                                <p class="text-2xl font-bold text-indigo-600">{{ auth()->user()->volunteer->farmers_enrolled ?? 0 }}</p>
                                <p class="text-sm text-gray-600">Farmers Enrolled</p>
                            </div>
                            <div class="p-4 bg-green-50 rounded-md">
                                <p class="text-2xl font-bold text-green-600">{{ auth()->user()->enrolledFarmers()->whereMonth('created_at', now()->month)->count() }}</p>
                                <p class="text-sm text-gray-600">This Month</p>
                            </div>
                            <div class="p-4 bg-blue-50 rounded-md">
                                <p class="text-2xl font-bold text-blue-600">{{ auth()->user()->enrolledFarmers()->whereDate('created_at', '>=', now()->subDays(7))->count() }}</p>
                                <p class="text-sm text-gray-600">This Week</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Account Settings -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Account Settings</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 border rounded-md">
                                <div>
                                    <p class="font-medium text-gray-900">Change Password</p>
                                    <p class="text-sm text-gray-500">Update your password</p>
                                </div>
                                <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Change
                                </button>
                            </div>
                            <div class="flex items-center justify-between p-4 border rounded-md">
                                <div>
                                    <p class="font-medium text-gray-900">Email Notifications</p>
                                    <p class="text-sm text-gray-500">Manage notification preferences</p>
                                </div>
                                <button class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                    Manage
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    const menuButton = document.getElementById('mobile-menu-button');
    const menuOpenIcon = document.getElementById('menu-open-icon');
    const menuCloseIcon = document.getElementById('menu-close-icon');

    if (menuButton) {
        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            menuOpenIcon.classList.toggle('hidden');
            menuCloseIcon.classList.toggle('hidden');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            menuOpenIcon.classList.remove('hidden');
            menuCloseIcon.classList.add('hidden');
        });
    }
});
</script>

</body>
</html>