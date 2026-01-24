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
        * { font-family: 'Inter', sans-serif; }
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
        
        @include('admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            
            <!-- Top Bar -->
            <header class="bg-white shadow-sm sticky top-0 z-20">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
                            <p class="text-sm text-gray-600 mt-1">Welcome back, {{ auth()->user()->name }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">{{ now()->format('l, F j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="p-6">
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    
                    <!-- Total Users -->
                    <div class="bg-gradient-to-br from-[#11455B] to-[#0d3345] rounded-2xl shadow-lg p-6 text-white card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-200 text-sm font-medium">Total Users</p>
                                <p class="text-3xl font-bold mt-2">{{ isset($stats['total_users']) ? number_format($stats['total_users']) : '0' }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Farmers -->
                    <div class="bg-gradient-to-br from-[#2FCB6E] to-[#25a356] rounded-2xl shadow-lg p-6 text-white card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-100 text-sm font-medium">Farmers</p>
                                <p class="text-3xl font-bold mt-2">{{ isset($stats['farmers']) ? number_format($stats['farmers']) : '0' }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Professionals -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Professionals</p>
                                <p class="text-3xl font-bold mt-2">{{ isset($stats['professionals']) ? number_format($stats['professionals']) : '0' }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Volunteers -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white card-hover">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium">Volunteers</p>
                                <p class="text-3xl font-bold mt-2">{{ isset($stats['volunteers']) ? number_format($stats['volunteers']) : '0' }}</p>
                            </div>
                            <div class="bg-white/20 p-3 rounded-xl">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Pending Alert -->
                @if(isset($pendingProfessionals) && $pendingProfessionals->count() > 0)
                <div class="mb-8">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
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
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full {{ $user->role === 'farmer' ? 'bg-secondary/20 text-secondary' : 'bg-primary/20 text-primary' }} flex items-center justify-center font-black text-lg">
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
                                        <p class="text-xs text-gray-600">{{ $professional->professional_type_text ?? 'Professional' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $professional->created_at->diffForHumans() }}</p>
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

</body>
</html>