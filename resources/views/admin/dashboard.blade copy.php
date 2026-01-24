<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#11455B',
                        secondary: '#2FCB6E',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(17, 69, 91, 0.15);
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Mobile Overlay -->
        <div id="mobile-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50 md:z-auto w-64 bg-primary flex flex-col">
            
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 bg-primary/90">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-secondary to-primary rounded-lg flex items-center justify-center">
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
            <div class="px-4 py-3 bg-primary/80">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
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
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-3 text-sm font-semibold text-white bg-secondary/20 rounded-lg border-l-4 border-secondary">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.professionals.pending') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pending Approvals
                    @if(isset($stats['pending_professionals']) && $stats['pending_professionals'] > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $stats['pending_professionals'] }}
                    </span>
                    @endif
                </a>

                <a href="{{ route('admin.farmers') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Farmers
                </a>

                <a href="{{ route('admin.professionals.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Professionals
                </a>

                <a href="{{ route('admin.volunteers.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Volunteers
                </a>

                <a href="{{ route('admin.service-requests.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Service Requests
                </a>

                <a href="{{ route('admin.farm-records.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Farm Records
                </a>

                <a href="{{ route('admin.statistics') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Statistics
                </a>

                <div class="pt-4 border-t border-white/10">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Features</p>

                    <a href="{{ route('admin.ads.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        Advertisements
                    </a>

                    <a href="{{ route('admin.outbreak-alerts.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Outbreak Alerts
                    </a>

                    <a href="{{ route('admin.bulk-messages.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Bulk Messaging
                    </a>

                    <a href="{{ url('/chat/conversations') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Live Chat
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Settings
                    </a>
                </div>
            </nav>

            <!-- Logout -->
            <div class="px-3 py-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <!-- Mobile Menu Button -->
                            <button id="mobile-menu-button" class="md:hidden p-2 rounded-lg text-primary hover:bg-gray-100">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <div>
                                <h1 class="text-2xl font-black text-primary">Admin Dashboard</h1>
                                <p class="text-sm text-gray-600 hidden sm:block">System Overview & Management</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs text-gray-500">{{ now()->format('l, F j, Y') }}</p>
                                <p class="text-xs text-gray-400">{{ now()->format('g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Success Message -->
            @if(session('success'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8 bg-gray-50">

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    
                    <!-- Total Users -->
                    <div class="card-hover bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-xl bg-primary p-4">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-semibold text-gray-600">Total Users</p>
                                <p class="text-3xl font-black text-primary">{{ $stats['total_users'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Farmers -->
                    <div class="card-hover bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-xl bg-secondary p-4">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-semibold text-gray-600">Farmers</p>
                                <p class="text-3xl font-black text-secondary">{{ $stats['total_farmers'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Professionals -->
                    <div class="card-hover bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-xl bg-primary p-4">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-semibold text-gray-600">Professionals</p>
                                <p class="text-3xl font-black text-primary">{{ $stats['total_professionals'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Approvals -->
                    <div class="card-hover bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 rounded-xl bg-yellow-500 p-4">
                                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5">
                                <p class="text-sm font-semibold text-gray-600">Pending</p>
                                <p class="text-3xl font-black text-yellow-600">{{ $stats['pending_professionals'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Secondary Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Total Livestock</p>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['total_livestock'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Farm Records</p>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['total_farm_records'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Volunteers</p>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['total_volunteers'] ?? 0 }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 mb-1">Pending Requests</p>
                        <p class="text-2xl font-black text-gray-900">{{ $stats['pending_service_requests'] ?? 0 }}</p>
                    </div>
                </div>

                <!-- Pending Approvals Alert -->
                @if(isset($pendingProfessionals) && $pendingProfessionals->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 rounded-r-xl">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-bold text-yellow-800">
                                {{ $pendingProfessionals->count() }} Professional Application(s) Awaiting Review
                            </h3>
                            <div class="mt-2">
                                <a href="{{ route('admin.professionals.pending') }}" class="text-sm font-semibold text-yellow-800 hover:text-yellow-900 underline">
                                    Review Applications →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Two Column Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Recent Users -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-bold text-primary">Recent Registrations</h2>
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold text-secondary hover:text-secondary/80">View All →</a>
                        </div>
                        <div class="p-6">
                            @if(isset($recentUsers) && $recentUsers->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentUsers as $user)
                                <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full {{ $user->role === 'farmer' ? 'bg-secondary/20' : 'bg-primary/20' }} flex items-center justify-center font-black {{ $user->role === 'farmer' ? 'text-secondary' : 'text-primary' }} text-lg">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4 flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-600 truncate">{{ $user->email }}</p>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <span class="text-xs px-3 py-1 rounded-full font-semibold {{ $user->role === 'farmer' ? 'bg-secondary/20 text-secondary' : 'bg-primary/20 text-primary' }}">
                                            {{ $user->role_display_name }}
                                        </span>
                                        <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-center text-gray-500 py-8">No recent registrations</p>
                            @endif
                        </div>
                    </div>

                    <!-- Pending Professionals -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-lg font-bold text-primary">Pending Applications</h2>
                            <a href="{{ route('admin.professionals.pending') }}" class="text-sm font-semibold text-secondary hover:text-secondary/80">View All →</a>
                        </div>
                        <div class="p-6">
                            @if(isset($pendingProfessionals) && $pendingProfessionals->count() > 0)
                            <div class="space-y-4">
                                @foreach($pendingProfessionals as $professional)
                                <div class="flex items-start p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-yellow-100 flex items-center justify-center font-black text-yellow-600 text-lg">
                                        {{ substr($professional->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4 flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $professional->user->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $professional->professional_type_text }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $professional->submitted_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('admin.professionals.review', $professional->id) }}" class="text-xs bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 font-semibold">
                                            Review
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No pending applications</p>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>

            </main>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            const menuButton = document.getElementById('mobile-menu-button');
            const closeButton = document.getElementById('close-sidebar');

            // Open sidebar
            menuButton.addEventListener('click', function() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            });

            // Close sidebar
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            closeButton.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);

            // Close on nav link click (mobile)
            if (window.innerWidth < 768) {
                const navLinks = sidebar.querySelectorAll('a');
                navLinks.forEach(link => {
                    link.addEventListener('click', closeSidebar);
                });
            }
        });
    </script>

</body>
</html>