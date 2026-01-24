<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Professional Dashboard') - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-dark': '#11455b',
                        'brand-green': '#2fcb6e',
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">
    
    <!-- Top Navigation Bar -->
    <nav class="bg-brand-dark shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left: Logo & Hamburger -->
                <div class="flex items-center">
                    <!-- Mobile Hamburger Button -->
                    <button id="mobile-menu-button" class="lg:hidden text-white hover:text-brand-green mr-4 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    
                    <!-- Logo -->
                    <h1 class="text-lg sm:text-xl font-bold text-white flex items-center">
                        <span class="text-brand-green">Farm</span>Vax
                        <span class="hidden sm:inline ml-2 text-xs bg-brand-green text-brand-dark px-2 py-1 rounded">Professional</span>
                    </h1>
                </div>
                
                <!-- Right: User Info & Logout -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <span class="text-white text-sm hidden sm:inline">{{ auth()->user()->name }}</span>
                    <span class="text-brand-green text-xs sm:text-sm font-semibold hidden md:inline">●</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-white hover:text-brand-green text-sm font-semibold transition flex items-center">
                            <svg class="h-5 w-5 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="flex pt-16">
        
        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden" onclick="toggleMobileMenu()"></div>
        
        <!-- Sidebar with Brand Colors -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 w-64 bg-brand-dark shadow-lg min-h-screen sidebar-transition z-40 mt-16 lg:mt-0">
            <nav class="mt-5 px-3">
                
                <!-- Dashboard -->
                <a href="{{ route('professional.dashboard') }}" 
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition {{ request()->routeIs('professional.dashboard') ? 'bg-brand-green text-brand-dark shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-semibold">Dashboard</span>
                </a>

                <!-- Service Requests -->
                <a href="{{ route('professional.service-requests.index') }}" 
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition {{ request()->routeIs('professional.service-requests.*') ? 'bg-brand-green text-brand-dark shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="font-semibold">Service Requests</span>
                </a>

                <!-- Farm Records -->
                <a href="{{ route('professional.farm-records.index') }}" 
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition {{ request()->routeIs('professional.farm-records.*') ? 'bg-brand-green text-brand-dark shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-semibold">Farm Records</span>
                </a>

                <!-- My Profile -->
                <a href="{{ route('professional.profile') }}" 
                   class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition {{ request()->routeIs('professional.profile') ? 'bg-brand-green text-brand-dark shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="font-semibold">My Profile</span>
                </a>

                <!-- Divider -->
                <div class="my-4 border-t border-brand-green border-opacity-30"></div>

                <!-- Help & Support -->
                <div class="px-3 py-2">
                    <p class="text-xs font-semibold text-brand-green uppercase tracking-wider mb-3">Support</p>
                    <a href="mailto:support@farmvax.com" class="flex items-center text-sm text-gray-300 hover:text-brand-green transition">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Help Center</span>
                    </a>
                </div>

                <!-- Footer Info -->
                <div class="absolute bottom-0 left-0 right-0 px-3 py-4 border-t border-brand-green border-opacity-30">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-green flex items-center justify-center text-brand-dark font-bold text-lg">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">Professional</p>
                        </div>
                    </div>
                </div>

            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden">
            @yield('content')
        </main>
    </div>

    <!-- JavaScript for Mobile Menu -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');

        function toggleMobileMenu() {
            sidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
            hamburgerIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }

        mobileMenuButton.addEventListener('click', toggleMobileMenu);

        // Close menu when clicking on a link (mobile only)
        if (window.innerWidth < 1024) {
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        toggleMobileMenu();
                    }
                });
            });
        }

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('-translate-x-full');
                mobileOverlay.classList.add('hidden');
                hamburgerIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            }
        });
    </script>

</body>
</html>