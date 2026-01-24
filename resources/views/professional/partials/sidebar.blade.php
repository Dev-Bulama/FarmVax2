<!-- Mobile Overlay -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64" style="background-color: #3B82F6;">
        
        <!-- Logo -->
        <div class="flex items-center h-16 flex-shrink-0 px-4">
            <span class="text-xl font-bold text-white">FarmVax Professional</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('professional.dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('professional.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('professional.farm-records.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('professional.farm-records.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Farm Records
            </a>

            <a href="{{ route('professional.service-requests.index') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('professional.service-requests.*') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Service Requests
            </a>

            <a href="{{ route('professional.profile') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md {{ request()->routeIs('professional.profile') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
        </nav>

        <!-- Logout -->
        <div class="flex-shrink-0 px-2 py-4 border-t border-blue-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-blue-700">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </div>
</aside>