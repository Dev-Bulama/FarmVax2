<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Volunteer Dashboard') - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .brand-dark { color: #11455b; }
        .bg-brand-dark { background-color: #11455b; }
        .brand-green { color: #2fcb6e; }
        .bg-brand-green { background-color: #2fcb6e; }
        
        /* Mobile sidebar animation */
        .sidebar-mobile {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar-mobile.active {
            transform: translateX(0);
        }
        
        /* Overlay */
        .sidebar-overlay {
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-50">
    
    {{-- Mobile Header --}}
    <div class="lg:hidden bg-brand-dark text-white px-4 py-3 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center">
            <button id="mobile-menu-button" class="mr-3 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h2 class="text-xl font-bold">FarmVax</h2>
        </div>
        <div class="w-8 h-8 rounded-full flex items-center justify-center bg-brand-green">
            <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
        </div>
    </div>

    {{-- Overlay for mobile --}}
    <div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 bg-black bg-opacity-50 z-40"></div>

    <div class="flex h-screen overflow-hidden">
        
        {{-- Sidebar (Desktop & Mobile) --}}
        <aside id="sidebar" class="sidebar-mobile lg:transform-none fixed lg:static inset-y-0 left-0 z-50 w-64 bg-brand-dark flex flex-col lg:flex">
            
            {{-- Close button (mobile only) --}}
            <div class="lg:hidden p-4 flex items-center justify-between border-b border-green-600">
                <h2 class="text-xl font-bold text-white">Menu</h2>
                <button id="close-sidebar" class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Logo (desktop only) --}}
            <div class="hidden lg:block p-6 border-b border-green-600">
                <h2 class="text-2xl font-bold text-white">FarmVax</h2>
                <p class="text-sm text-brand-green">Volunteer Portal</p>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                
                <a href="{{ route('volunteer.dashboard') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('volunteer.dashboard') ? 'bg-brand-green text-white shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('volunteer.enroll.farmer') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('volunteer.enroll.farmer') ? 'bg-brand-green text-white shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Enroll Farmer
                </a>

                <a href="{{ route('volunteer.my-farmers') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('volunteer.my-farmers') ? 'bg-brand-green text-white shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    My Farmers
                </a>

                <a href="{{ route('volunteer.activity') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('volunteer.activity') ? 'bg-brand-green text-white shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Activity
                </a>

                <a href="{{ route('volunteer.profile') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-lg transition {{ request()->routeIs('volunteer.profile') ? 'bg-brand-green text-white shadow-lg' : 'text-gray-200 hover:bg-brand-green hover:bg-opacity-20 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    My Profile
                </a>

            </nav>

            {{-- User Profile --}}
            <div class="p-4 border-t border-green-600">
                <div class="flex items-center text-white p-2 mb-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-brand-green">
                        <span class="text-white font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400">Volunteer</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium text-white rounded-lg hover:bg-red-600 transition">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            <div class="lg:hidden h-14"></div> {{-- Spacer for mobile header --}}
            @yield('content')
        </main>

    </div>

    {{-- JavaScript for mobile menu --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeSidebar = document.getElementById('close-sidebar');

            // Open sidebar
            mobileMenuButton.addEventListener('click', function() {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            // Close sidebar
            function closeSidebarMenu() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            closeSidebar.addEventListener('click', closeSidebarMenu);
            overlay.addEventListener('click', closeSidebarMenu);

            // Close sidebar when clicking a link (mobile only)
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeSidebarMenu();
                    }
                });
            });
        });
    </script>

</body>
</html>