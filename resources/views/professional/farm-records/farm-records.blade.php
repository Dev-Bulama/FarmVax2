<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Records - FarmVax Professional</title>
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
    </style>
</head>
<body class="bg-gray-50">

<div class="flex h-screen overflow-hidden">
    
    <div id="mobile-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"></div>

    <!-- Sidebar with DARK BLUE primary color -->
    <aside id="sidebar" class="fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50 md:z-auto w-64 bg-primary flex flex-col">
        
        <div class="flex items-center justify-between h-16 px-4 bg-primary">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-br from-secondary/80 to-secondary rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <span class="text-white text-lg font-bold">FarmVax</span>
            </div>
            <button id="close-sidebar" class="md:hidden text-white hover:text-secondary">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="px-4 py-3 bg-primary border-b border-white/10">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-secondary rounded-full flex items-center justify-center">
                    <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name ?? 'P', 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Professional' }}</p>
                    <p class="text-xs text-gray-300">Professional</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('professional.dashboard') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            <a href="{{ route('professional.farm-records') }}" class="flex items-center px-3 py-3 text-sm font-semibold text-white bg-secondary/20 rounded-lg border-l-4 border-secondary">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Farm Records
            </a>
            <a href="{{ route('professional.service-requests') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Service Requests
            </a>
            <a href="{{ route('professional.profile') }}" class="flex items-center px-3 py-3 text-sm font-medium text-gray-200 hover:bg-white/10 rounded-lg transition">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                My Profile
            </a>
        </nav>

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

    <div class="flex-1 flex flex-col overflow-hidden">
        
        <header class="bg-white shadow-sm z-10">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <button id="mobile-menu-button" class="md:hidden p-2 rounded-lg text-primary hover:bg-gray-100">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-black text-primary">Farm Records</h1>
                            <p class="text-sm text-gray-600 hidden sm:block">Manage and submit farm data records</p>
                        </div>
                    </div>
                    <a href="{{ route('professional.farm-records.create') }}" class="hidden sm:flex items-center px-4 py-2 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition shadow-lg shadow-primary/20">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Submit Record
                    </a>
                </div>
            </div>
        </header>

        @if(session('success'))
        <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
            <div class="bg-secondary/10 border border-secondary/20 text-secondary rounded-xl p-4">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        </div>
        @endif

        <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-8 bg-gray-50">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-600">Total Records</p>
                        <div class="p-2 bg-primary/10 rounded-lg">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-primary">{{ $farmRecords->total() ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-600">Submitted</p>
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-blue-600">{{ $farmRecords->where('status', 'submitted')->count() }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-600">Under Review</p>
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-yellow-600">{{ $farmRecords->where('status', 'under_review')->count() }}</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-semibold text-gray-600">Approved</p>
                        <div class="p-2 bg-secondary/10 rounded-lg">
                            <svg class="h-5 w-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-secondary">{{ $farmRecords->where('status', 'approved')->count() }}</p>
                </div>
            </div>

            <!-- Mobile Add Button -->
            <div class="sm:hidden mb-6">
                <a href="{{ route('professional.farm-records.create') }}" class="flex items-center justify-center w-full px-4 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition shadow-lg shadow-primary/20">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Submit New Record
                </a>
            </div>

            <!-- Farm Records List -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                @if($farmRecords->count() > 0)
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Farmer Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Farm Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Livestock</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($farmRecords as $record)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 bg-primary/10 rounded-full flex items-center justify-center">
                                                <span class="text-primary font-black text-sm">{{ strtoupper(substr($record->farmer_name ?? 'F', 0, 2)) }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-bold text-gray-900">{{ $record->farmer_name ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">{{ $record->farmer_phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $record->farm_name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $record->farmer_city ?? 'N/A' }}, {{ $record->farmer_state ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ $record->total_livestock_count ?? 0 }} animals
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = $record->status ?? 'draft';
                                            $colors = [
                                                'draft' => 'bg-gray-100 text-gray-800',
                                                'submitted' => 'bg-blue-100 text-blue-800',
                                                'under_review' => 'bg-yellow-100 text-yellow-800',
                                                'approved' => 'bg-secondary/10 text-secondary font-black',
                                                'rejected' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $record->created_at ? $record->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('professional.farm-records.show', $record->id) }}" class="text-primary font-bold hover:text-secondary transition">View →</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards -->
                    <div class="md:hidden p-4 space-y-4">
                        @foreach($farmRecords as $record)
                        <div class="bg-gray-50 rounded-xl p-4 border-l-4 border-primary">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center flex-1">
                                    <div class="h-12 w-12 bg-primary/10 rounded-full flex items-center justify-center">
                                        <span class="text-primary font-black text-sm">{{ strtoupper(substr($record->farmer_name ?? 'F', 0, 2)) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-bold text-gray-900">{{ $record->farmer_name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $record->farmer_city ?? 'N/A' }}, {{ $record->farmer_state ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                @php
                                    $status = $record->status ?? 'draft';
                                    $colors = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'submitted' => 'bg-blue-100 text-blue-800',
                                        'under_review' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-secondary/10 text-secondary font-black',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $colors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </div>
                            <div class="space-y-2 text-xs mb-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Livestock:</span>
                                    <span class="font-semibold text-gray-900">{{ $record->total_livestock_count ?? 0 }} animals</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Date:</span>
                                    <span class="font-semibold text-gray-900">{{ $record->created_at ? $record->created_at->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('professional.farm-records.show', $record->id) }}" class="block w-full text-center py-2 bg-primary text-white font-bold rounded-lg hover:bg-primary/90 transition shadow-lg shadow-primary/20">
                                View Details
                            </a>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($farmRecords->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $farmRecords->links() }}
                    </div>
                    @endif

                @else
                    <div class="text-center py-16">
                        <div class="mx-auto h-16 w-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-4">
                            <svg class="h-10 w-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-primary">No farm records submitted</h3>
                        <p class="mt-1 text-sm text-gray-500 mb-6">Get started by submitting your first farm data record.</p>
                        <a href="{{ route('professional.farm-records.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition shadow-lg shadow-primary/20">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Submit Your First Record
                        </a>
                    </div>
                @endif
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

        menuButton.addEventListener('click', function() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        });

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        closeButton.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        if (window.innerWidth < 768) {
            const navLinks = sidebar.querySelectorAll('a');
            navLinks.forEach(link => link.addEventListener('click', closeSidebar));
        }
    });
</script>

</body>
</html>