<!-- Mobile Menu Button (Fixed Position) -->
<button id="mobileMenuBtn" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-green-600 text-white shadow-lg hover:bg-green-700 focus:outline-none">
    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
    </svg>
</button>

<!-- Mobile Sidebar Overlay -->
<div id="mobileSidebarOverlay" class="hidden md:hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>

<!-- Sidebar with Mobile Support -->
<aside id="mobileSidebar" class="fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40 md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-green-800 h-full">
        
        <!-- Logo with Close Button (Mobile) -->
        <div class="flex items-center justify-between h-16 flex-shrink-0 px-4 bg-green-900">
            <div class="flex items-center">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="ml-2 text-white text-xl font-bold">FarmVax</span>
            </div>
            <!-- Close Button (Mobile Only) -->
            <button id="closeSidebarBtn" class="md:hidden text-white hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto">
            <div class="px-2 py-4 space-y-1">
                
                <!-- Dashboard -->
                <a href="{{ route('individual.dashboard') }}" class="{{ request()->routeIs('individual.dashboard') ? 'bg-green-900' : '' }} text-green-100 hover:bg-green-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <!-- My Livestock -->
                <a href="{{ route('individual.livestock.index') }}" class="{{ request()->routeIs('individual.livestock.*') ? 'bg-green-900' : '' }} text-green-100 hover:bg-green-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    My Livestock
                </a>

                <!-- Vaccinations -->
                <a href="{{ route('individual.vaccinations.index') }}" class="{{ request()->routeIs('individual.vaccinations.*') ? 'bg-green-900' : '' }} text-green-100 hover:bg-green-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    Vaccinations
                </a>

                <!-- Service Requests -->
                <a href="{{ route('individual.service-requests.index') }}" class="{{ request()->routeIs('individual.service-requests.*') ? 'bg-green-900' : '' }} text-green-100 hover:bg-green-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Service Requests
                </a>

                <!-- Farm Records -->
                <a href="{{ route('individual.farm-records.create') }}" class="{{ request()->routeIs('individual.farm-records.*') ? 'bg-green-900' : '' }} text-green-100 hover:bg-green-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Farm Records
                </a>

                <!-- Profile -->
                <a href="{{ route('individual.profile') }}" class="{{ request()->routeIs('individual.profile') ? 'bg-green-900' : '' }} text-green-100 hover:bg-green-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    My Profile
                </a>

            </div>
        </nav>

    </div>
</aside>

<!-- JavaScript for Mobile Menu -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileSidebarOverlay = document.getElementById('mobileSidebarOverlay');
    const closeSidebarBtn = document.getElementById('closeSidebarBtn');

    // Open sidebar
    mobileMenuBtn.addEventListener('click', function() {
        mobileSidebar.classList.remove('-translate-x-full');
        mobileSidebarOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
    });

    // Close sidebar
    function closeSidebar() {
        mobileSidebar.classList.add('-translate-x-full');
        mobileSidebarOverlay.classList.add('hidden');
        document.body.style.overflow = ''; // Restore scrolling
    }

    closeSidebarBtn.addEventListener('click', closeSidebar);
    mobileSidebarOverlay.addEventListener('click', closeSidebar);

    // Close sidebar when clicking a link (mobile only)
    if (window.innerWidth < 768) {
        const sidebarLinks = mobileSidebar.querySelectorAll('a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', closeSidebar);
        });
    }
});
</script>