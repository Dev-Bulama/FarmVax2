<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    
    <div class="flex h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 flex-shrink-0" style="background-color: #11455b;">
            <div class="h-full flex flex-col">
                
                {{-- Logo --}}
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-white">FarmVax</h2>
                    <p class="text-sm" style="color: #2fcb6e;">Individual Dashboard</p>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                    
                    <a href="{{ route('individual.dashboard') }}" 
                       class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition {{ request()->routeIs('individual.dashboard') ? 'text-white shadow-lg' : 'text-gray-200 hover:bg-opacity-20 hover:text-white' }}"
                       style="{{ request()->routeIs('individual.dashboard') ? 'background-color: #2fcb6e;' : '' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('individual.livestock.index') }}" 
                       class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition {{ request()->routeIs('individual.livestock.*') ? 'text-white shadow-lg' : 'text-gray-200 hover:bg-opacity-20 hover:text-white' }}"
                       style="{{ request()->routeIs('individual.livestock.*') ? 'background-color: #2fcb6e;' : '' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Livestock
                    </a>

                    <a href="{{ route('individual.farm-records.index') }}" 
                       class="group flex items-center px-3 py-3 text-sm font-medium rounded-lg mb-1 transition {{ request()->routeIs('individual.farm-records.*') ? 'text-white shadow-lg' : 'text-gray-200 hover:bg-opacity-20 hover:text-white' }}"
                       style="{{ request()->routeIs('individual.farm-records.*') ? 'background-color: #2fcb6e;' : '' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Farm Records
                    </a>

                </nav>

                {{-- User Profile --}}
                <div class="p-4 border-t" style="border-color: rgba(255,255,255,0.1);">
                    <a href="{{ route('individual.profile') }}" class="flex items-center text-white hover:bg-opacity-20 hover:bg-white p-2 rounded transition">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3" style="background-color: #2fcb6e;">
                            <span class="text-white font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ ucfirst(Auth::user()->role) }}</p>
                        </div>
                    </a>
                </div>

            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</body>
</html>