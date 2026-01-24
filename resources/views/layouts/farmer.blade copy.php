<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Farmer Dashboard') - {{ config('app.name', 'FarmVax') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: true }">
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-green-900 text-white transition-transform duration-300 ease-in-out lg:translate-x-0">
        <div class="flex items-center justify-between h-16 px-6 bg-green-800">
            <div class="flex items-center">
                <i class="fas fa-tractor text-green-400 text-2xl mr-2"></i>
                <span class="text-xl font-bold">FarmVax</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="mt-6 px-4">
            <a href="{{ route('individual.dashboard') }}" class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('individual.dashboard') ? 'bg-green-600' : 'hover:bg-green-800' }}">
                <i class="fas fa-home mr-3"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('individual.livestock.index') }}" class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('individual.livestock.*') ? 'bg-green-600' : 'hover:bg-green-800' }}">
                <i class="fas fa-paw mr-3"></i>
                <span>My Livestock</span>
            </a>

            <a href="{{ route('individual.service-requests.index') }}" class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('individual.service-requests.*') ? 'bg-green-600' : 'hover:bg-green-800' }}">
                <i class="fas fa-hands-helping mr-3"></i>
                <span>Service Requests</span>
            </a>

            <a href="{{ url('/chat') }}" class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->is('chat*') ? 'bg-green-600' : 'hover:bg-green-800' }}">
                <i class="fas fa-comments mr-3"></i>
                <span>Messages</span>
            </a>

            <a href="{{ route('individual.profile') }}" class="flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('individual.profile') ? 'bg-green-600' : 'hover:bg-green-800' }}">
                <i class="fas fa-user mr-3"></i>
                <span>My Profile</span>
            </a>
        </nav>

        <div class="absolute bottom-0 w-64 p-4 bg-green-800">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center w-full text-left">
                    <div class="w-10 h-10 rounded-full bg-green-600 flex items-center justify-center mr-3">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-300">Farmer</div>
                    </div>
                    <i class="fas fa-chevron-up"></i>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute bottom-full left-0 w-full mb-2 bg-green-700 rounded-lg shadow-lg">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-green-600 rounded-lg">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <div class="lg:pl-64">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
            <button @click="sidebarOpen = true" class="lg:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center space-x-4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </div>
        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')

</body>
</html>
