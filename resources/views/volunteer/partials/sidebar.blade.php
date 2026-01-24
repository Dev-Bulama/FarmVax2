<!-- Mobile Overlay -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64" style="background-color: #6366F1;">
        
        <!-- Logo -->
        <div class="flex items-center h-16 flex-shrink-0 px-4">
            <span class="text-xl font-bold text-white">FarmVax Volunteer</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('volunteer.dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('volunteer.dashboard') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('volunteer.enroll.farmer') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('volunteer.enroll.farmer') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Enroll Farmer
            </a>

            <a href="{{ route('volunteer.my-farmers') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('volunteer.my-farmers*') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                My Farmers
            </a>

            <a href="{{ route('volunteer.activity') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('volunteer.activity') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Activity
            </a>

            <a href="{{ route('volunteer.profile') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('volunteer.profile') ? 'bg-indigo-700' : 'hover:bg-indigo-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
        </nav>

        <!-- Logout -->
        <div class="flex-shrink-0 px-2 py-4 border-t border-indigo-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-indigo-700">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </div>
</aside>