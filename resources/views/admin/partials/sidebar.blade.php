<!-- Mobile Menu Button -->
<button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-[#11455B] text-white hover:bg-[#0d3345] focus:outline-none">
    <svg id="menu-open-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
    <svg id="menu-close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
</button>

<!-- Mobile Overlay -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 h-full bg-[#11455B]">
        
        <!-- Logo -->
        <div class="flex items-center justify-between h-16 px-4 bg-[#0d3345]">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-br from-[#2FCB6E] to-[#11455B] rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <span class="text-white text-lg font-bold">FarmVax</span>
            </div>
            <!-- Close button for mobile -->
            <button id="close-sidebar" class="md:hidden text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Admin Badge -->
        <div class="px-4 py-3 bg-[#0d3345]/80">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-[#2FCB6E] rounded-full flex items-center justify-center">
                    <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-300">Administrator</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
                class="flex items-center px-3 py-3 text-sm font-semibold text-white rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#2FCB6E]/20 border-l-4 border-[#2FCB6E]' : 'hover:bg-white/10' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            <!-- User Management Dropdown -->
            <div class="dropdown-section">
                <button onclick="toggleDropdown('userManagement')" 
                    class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-white rounded-md hover:bg-white/10 transition">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>User Management</span>
                    </div>
                    <svg class="h-4 w-4 transform transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="userManagement" class="dropdown-content hidden mt-1 ml-8 space-y-1">
                    <a href="{{ route('admin.professionals.pending') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.professionals.pending') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pending Approvals
                        @if(isset($stats['pending_professionals']) && $stats['pending_professionals'] > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $stats['pending_professionals'] }}
                        </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.farmers') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.farmers') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Farmers
                    </a>
                    <a href="{{ route('admin.professionals.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.professionals.index') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Professionals
                    </a>
                    <a href="{{ route('admin.volunteers.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.volunteers.index') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Volunteers
                    </a>
                    <a href="{{ route('admin.users.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.users.index') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        All Users
                    </a>
                    <a href="{{ route('admin.import.index') }}" 
       class="flex items-center px-4 py-3 text-white-700 hover:bg-[#2FCB6E] hover:text-white transition rounded-lg {{ request()->routeIs('admin.import.*') ? 'bg-[#2FCB6E] text-white' : '' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <span>Bulk Import</span>
    </a>
                </div>
            </div>
<!-- Bulk Import -->
<!--<li>-->
    
<!--</li>-->
            <!-- Records Dropdown -->
            <div class="dropdown-section">
                <button onclick="toggleDropdown('records')" 
                    class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-white rounded-md hover:bg-white/10 transition">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Records</span>
                    </div>
                    <svg class="h-4 w-4 transform transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="records" class="dropdown-content hidden mt-1 ml-8 space-y-1">
                    <a href="{{ route('admin.farm-records.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.farm-records.index') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Farm Records
                    </a>
                    <a href="{{ route('admin.service-requests.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.service-requests.index') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Service Requests
                    </a>
                </div>
            </div>

            <!-- Communication Dropdown -->
            <div class="dropdown-section">
                <button onclick="toggleDropdown('communication')" 
                    class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-white rounded-md hover:bg-white/10 transition">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        <span>Communication</span>
                    </div>
                    <svg class="h-4 w-4 transform transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="communication" class="dropdown-content hidden mt-1 ml-8 space-y-1">
                    <a href="{{ route('admin.outbreak-alerts.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.outbreak-alerts.*') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Outbreak Alerts
                    </a>
                    <a href="{{ route('admin.bulk-messages.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.bulk-messages.*') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Bulk Messages
                    </a>
                    <a href="{{ route('admin.ads.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.ads.*') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Advertisements
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <!-- <div class="dropdown-section">
                <button onclick="toggleDropdown('settings')" 
                    class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-white rounded-md hover:bg-white/10 transition">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Settings</span>
                    </div>
                    <svg class="h-4 w-4 transform transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="settings" class="dropdown-content hidden mt-1 ml-8 space-y-1">
                    <a href="{{ route('admin.settings.index') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.settings.index') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        General Settings
                    </a>
                    <a href="{{ route('admin.settings.email') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.settings.email') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Email Settings
                    </a>
                    <a href="{{ route('admin.settings.sms') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.settings.sms') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        SMS Settings
                    </a>
                    <a href="{{ route('admin.settings.ai') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.settings.ai') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        AI Settings
                    </a>
                    <a href="{{ route('admin.settings.professional-types') }}" 
                        class="flex items-center px-3 py-2 text-sm text-white rounded-md hover:bg-white/10 transition {{ request()->routeIs('admin.settings.professional-types') ? 'bg-white/10' : '' }}">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Professional Types
                    </a>
                </div>
            </div> -->
            <!-- Settings -->
<div x-data="{ open: false }">
    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-white hover:bg-white/10 transition rounded-lg mb-1">
        <span class="flex items-center">
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </span>
        <svg class="h-4 w-4 transform transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>
    <div x-show="open" x-collapse class="ml-4 space-y-1">
        <a href="{{ route('admin.settings.general') }}" class="flex items-center px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 transition rounded-lg">
            General
        </a>
        <a href="{{ route('admin.settings.email') }}" class="flex items-center px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 transition rounded-lg">
            Email
        </a>
        <a href="{{ route('admin.settings.sms') }}" class="flex items-center px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 transition rounded-lg">
            SMS
        </a>
        <a href="{{ route('admin.settings.ai') }}" class="flex items-center px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 transition rounded-lg">
            AI Chatbot
        </a>
        <a href="{{ route('admin.settings.ai-training') }}" class="flex items-center px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 transition rounded-lg">
    AI Training Data
</a>
        <a href="{{ route('admin.settings.professional-types') }}" class="flex items-center px-4 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 transition rounded-lg">
            Professional Types
        </a>
    </div>
</div>

            <!-- Statistics -->
            <a href="{{ route('admin.statistics') }}" 
                class="flex items-center px-3 py-2 text-sm font-medium text-white rounded-md transition {{ request()->routeIs('admin.statistics') ? 'bg-[#2FCB6E]/20 border-l-4 border-[#2FCB6E]' : 'hover:bg-white/10' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Statistics
            </a>
            <!-- Import/Export -->
<a href="{{ route('admin.import-export.index') }}" 
   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('admin.import-export.*') ? 'bg-gray-100 border-r-4 border-blue-600' : '' }}">
    <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
    </svg>
    <span class="font-medium">Import/Export</span>
</a>

        </nav>

        <!-- Logout -->
        <div class="flex-shrink-0 px-2 py-4 border-t border-[#0d3345]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-white rounded-md hover:bg-white/10 transition">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>

    </div>
</aside>

<!-- JavaScript for Dropdown & Mobile Menu -->
<script>
    // Toggle Dropdown Function
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const arrow = dropdown.previousElementSibling.querySelector('.dropdown-arrow');
        
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
            localStorage.setItem('dropdown_' + id, 'open');
        } else {
            dropdown.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
            localStorage.setItem('dropdown_' + id, 'closed');
        }
    }

    // Restore dropdown states on page load
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = ['userManagement', 'records', 'communication', 'settings'];
        
        dropdowns.forEach(id => {
            const state = localStorage.getItem('dropdown_' + id);
            if (state === 'open') {
                const dropdown = document.getElementById(id);
                const arrow = dropdown.previousElementSibling.querySelector('.dropdown-arrow');
                dropdown.classList.remove('hidden');
                arrow.style.transform = 'rotate(180deg)';
            }
        });

        // Auto-open dropdown if current page is inside it
        const currentUrl = window.location.href;
        
        if (currentUrl.includes('/professionals') || currentUrl.includes('/farmers') || 
            currentUrl.includes('/volunteers') || currentUrl.includes('/users')) {
            const dropdown = document.getElementById('userManagement');
            if (dropdown && dropdown.classList.contains('hidden')) {
                toggleDropdown('userManagement');
            }
        } else if (currentUrl.includes('/farm-records') || currentUrl.includes('/service-requests')) {
            const dropdown = document.getElementById('records');
            if (dropdown && dropdown.classList.contains('hidden')) {
                toggleDropdown('records');
            }
        } else if (currentUrl.includes('/outbreak-alerts') || currentUrl.includes('/bulk-messages') || 
                   currentUrl.includes('/ads')) {
            const dropdown = document.getElementById('communication');
            if (dropdown && dropdown.classList.contains('hidden')) {
                toggleDropdown('communication');
            }
        } else if (currentUrl.includes('/settings')) {
            const dropdown = document.getElementById('settings');
            if (dropdown && dropdown.classList.contains('hidden')) {
                toggleDropdown('settings');
            }
        }
    });

    // Mobile Menu Toggle
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    const menuButton = document.getElementById('mobile-menu-button');
    const closeButton = document.getElementById('close-sidebar');
    const menuOpenIcon = document.getElementById('menu-open-icon');
    const menuCloseIcon = document.getElementById('menu-close-icon');

    if (menuButton && sidebar && overlay) {
        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            if (menuOpenIcon && menuCloseIcon) {
                menuOpenIcon.classList.toggle('hidden');
                menuCloseIcon.classList.toggle('hidden');
            }
        });

        if (closeButton) {
            closeButton.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                if (menuOpenIcon && menuCloseIcon) {
                    menuOpenIcon.classList.remove('hidden');
                    menuCloseIcon.classList.add('hidden');
                }
            });
        }

        overlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            if (menuOpenIcon && menuCloseIcon) {
                menuOpenIcon.classList.remove('hidden');
                menuCloseIcon.classList.add('hidden');
            }
        });

        // Close menu on link click (mobile)
        if (window.innerWidth < 768) {
            const navLinks = sidebar.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    if (menuOpenIcon && menuCloseIcon) {
                        menuOpenIcon.classList.remove('hidden');
                        menuCloseIcon.classList.add('hidden');
                    }
                });
            });
        }
    }
</script>