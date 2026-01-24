<!-- Data Collector Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:static md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-blue-800">
        
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 flex-shrink-0 px-4 bg-blue-900">
            <div class="flex items-center">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span class="ml-2 text-white text-xl font-bold">FarmVax</span>
            </div>
            <button onclick="toggleSidebar()" class="md:hidden text-white hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto">
            <div class="px-2 py-4 space-y-1">
                
                <!-- Dashboard -->
                <a href="{{ route('data-collector.dashboard') }}" class="{{ request()->routeIs('data-collector.dashboard') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <!-- New Farm Record -->
                <a href="{{ route('data-collector.farm-record.create') }}" class="{{ request()->routeIs('data-collector.farm-record.*') && !request()->routeIs('data-collector.farm-records.*') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Farm Record
                </a>

                <!-- My Submissions -->
                <a href="{{ route('data-collector.farm-records.index') }}" class="{{ request()->routeIs('data-collector.farm-records.index') || request()->routeIs('data-collector.farm-records.show') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    My Submissions
                </a>

                <!-- Drafts -->
                <a href="{{ route('data-collector.farm-records.drafts') }}" class="{{ request()->routeIs('data-collector.farm-records.drafts') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Drafts
                </a>

                <!-- Profile -->
                <a href="{{ route('data-collector.profile') }}" class="{{ request()->routeIs('data-collector.profile') ? 'bg-blue-900 text-white' : 'text-blue-100 hover:bg-blue-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profile
                </a>

            </div>
        </nav>

    </div>
</aside>

<!-- Overlay for mobile -->
<div id="overlay" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('overlay').classList.toggle('hidden');
    }
</script>