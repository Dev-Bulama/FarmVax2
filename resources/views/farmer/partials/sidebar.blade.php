<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-green-800 to-green-900 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" id="sidebar">
    <div class="flex flex-col h-full">
        
        <!-- Logo & Brand -->
        <div class="flex items-center justify-between h-16 px-6 bg-green-900 border-b border-green-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">FarmVax</h1>
                    <p class="text-green-300 text-xs">Farmer Portal</p>
                </div>
            </div>
            <button id="close-sidebar" class="lg:hidden text-white hover:text-green-300 p-1">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- User Info Card -->
        <div class="px-4 py-4 bg-green-900/50 border-b border-green-700">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center ring-2 ring-green-400">
                    <span class="text-white font-bold text-lg">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-green-300">{{ auth()->user()->email }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 bg-green-700 text-green-200 text-xs font-semibold rounded">Farmer</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            
            <!-- Dashboard -->
            <a href="{{ route('individual.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('individual.dashboard') || request()->routeIs('farmer.dashboard') ? 'bg-green-700 text-white shadow-lg' : 'text-green-100 hover:bg-green-700/50 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
                @if(isset($outbreakAlerts) && $outbreakAlerts->count() > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $outbreakAlerts->count() }}</span>
                @endif
            </a>

            <!-- Divider -->
            <div class="pt-2 pb-2">
                <div class="border-t border-green-700"></div>
                <p class="mt-2 px-3 text-xs font-semibold text-green-400 uppercase tracking-wider">Livestock Management</p>
            </div>

            <!-- My Livestock -->
            <a href="{{ route('individual.livestock.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('individual.livestock.*') ? 'bg-green-700 text-white shadow-lg' : 'text-green-100 hover:bg-green-700/50 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>My Livestock</span>
            </a>

            <!-- Vaccinations -->
            <a href="{{ route('individual.vaccinations.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('individual.vaccinations.*') ? 'bg-green-700 text-white shadow-lg' : 'text-green-100 hover:bg-green-700/50 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Vaccinations</span>
                @if(isset($upcomingVaccinations) && $upcomingVaccinations > 0)
                    <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $upcomingVaccinations }}</span>
                @endif
            </a>
<!-- Herd Groups -->
<a href="{{ route('farmer.herd-groups.index') }}" 
   class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition {{ request()->routeIs('farmer.herd-groups.*') ? 'bg-brand-green text-brand-dark shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
    </svg>
    <span class="font-semibold">Herd Groups</span>
</a>
            <!-- Divider -->
            <div class="pt-2 pb-2">
                <div class="border-t border-green-700"></div>
                <p class="mt-2 px-3 text-xs font-semibold text-green-400 uppercase tracking-wider">Services</p>
            </div>

            <!-- Service Requests -->
            <a href="{{ route('individual.service-requests.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('individual.service-requests.*') ? 'bg-green-700 text-white shadow-lg' : 'text-green-100 hover:bg-green-700/50 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Service Requests</span>
                @if(isset($activeServiceRequests) && $activeServiceRequests > 0)
                    <span class="ml-auto bg-purple-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $activeServiceRequests }}</span>
                @endif
            </a>

            <!-- Farm Records -->
            <a href="{{ route('individual.farm-records.step1') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('individual.farm-records.*') ? 'bg-green-700 text-white shadow-lg' : 'text-green-100 hover:bg-green-700/50 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Farm Records</span>
            </a>

            <!-- Divider -->
            <!-- <div class="pt-2 pb-2">
                <div class="border-t border-green-700"></div>
                <p class="mt-2 px-3 text-xs font-semibold text-green-400 uppercase tracking-wider">Communication</p>
            </div>

            <!-- Messages -->
            <!-- <a href="{{ url('/chat') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all {{ request()->is('chat*') ? 'bg-green-700 text-white shadow-lg' : 'text-green-100 hover:bg-green-700/50 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <span>Messages</span>
                @if(isset($recentMessages) && $recentMessages->count() > 0)
                    <span class="ml-auto bg-blue-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $recentMessages->count() }}</span>
                @endif
            </a> --> 

            <!-- Divider -->
            <div class="pt-2 pb-2">
                <div class="border-t border-green-700"></div>
                <p class="mt-2 px-3 text-xs font-semibold text-green-400 uppercase tracking-wider">Account</p>
            </div>

            <!-- Profile -->
            <a href="{{ route('farmer.profile') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('individual.profile') ? 'bg-green-700 text-white shadow-lg' : 'text-green-100 hover:bg-green-700/50 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>My Profile</span>
            </a>

            <!-- Help & Support -->
            <a href="{{ route('farmer.help') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('farmer.help') ? 'bg-green-700 text-white shadow-lg' : 'text-green-100 hover:bg-green-700/50 hover:text-white' }}">
                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Help & Support</span>
            </a>
        </nav>

        <!-- Logout Button -->
        <div class="px-3 py-4 border-t border-green-700 bg-green-900">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="group flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all text-green-100 hover:bg-red-600 hover:text-white">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Mobile Overlay -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

<!-- Mobile Menu Toggle (Show when sidebar is hidden) -->
<button id="mobile-menu-button" class="lg:hidden fixed top-4 left-4 z-30 p-2.5 rounded-lg bg-green-600 text-white hover:bg-green-700 shadow-lg">
    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

<script>
    // Sidebar toggle functionality
    const sidebar = document.getElementById('sidebar');
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileOverlay = document.getElementById('mobile-overlay');
    const closeSidebar = document.getElementById('close-sidebar');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        mobileOverlay.classList.toggle('hidden');
    }

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', toggleSidebar);
    }
    
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', toggleSidebar);
    }
    
    if (closeSidebar) {
        closeSidebar.addEventListener('click', toggleSidebar);
    }
</script>