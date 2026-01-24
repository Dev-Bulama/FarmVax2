<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Livestock - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/farmvax-brand.css') }}">
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (same as other pages) -->
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-brand-green">
                
                <div class="flex items-center h-16 flex-shrink-0 px-4 bg-brand-green">
                    <span class="text-xl font-bold text-white">FarmVax</span>
                </div>

                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    <a href="{{ route('farmer.dashboard') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-green-600">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('farmer.livestock') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white bg-green-600 rounded-md">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        My Livestock
                    </a>

                    <a href="{{ route('farmer.vaccinations') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-green-600">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Vaccinations
                    </a>

                    <a href="{{ route('farmer.service-requests') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-green-600">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Service Requests
                    </a>

                    <a href="{{ route('farmer.farm-records.step1') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-green-600">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Farm Records
                    </a>

                    <a href="{{ route('farmer.profile') }}" class="flex items-center px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-green-600">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>
                </nav>

                <div class="flex-shrink-0 px-2 py-4 border-t border-green-600">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-2 py-2 text-sm font-medium text-white rounded-md hover:bg-green-600">
                            <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            
            <!-- Header -->
            <header class="bg-white shadow">
                <div class="px-4 sm:px-6 lg:px-8 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold brand-teal">My Livestock</h1>
                            <p class="text-sm text-gray-600">Manage all your animals</p>
                        </div>
                        <a href="{{ route('farmer.livestock.create') }}" class="bg-brand-green text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                            + Add Livestock
                        </a>
                    </div>
                </div>
            </header>

            <!-- Success Message -->
            @if(session('success'))
            <div class="mx-4 sm:mx-6 lg:mx-8 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4">
                    {{ session('success') }}
                </div>
            </div>
            @endif

            <!-- Main Content Area -->
            <main class="px-4 sm:px-6 lg:px-8 py-8">

                <!-- Stats Card -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 rounded-md bg-brand-green p-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Livestock</p>
                            <p class="text-3xl font-bold brand-teal">{{ $livestock->total() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Livestock Grid/List -->
                @if($livestock->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    @foreach($livestock as $animal)
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                        <!-- Animal Header -->
                        <div class="p-4 border-b border-gray-200 {{ $animal->type === 'cattle' ? 'bg-blue-50' : ($animal->type === 'goat' ? 'bg-green-50' : ($animal->type === 'sheep' ? 'bg-purple-50' : ($animal->type === 'poultry' ? 'bg-yellow-50' : 'bg-gray-50'))) }}">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-lg font-bold text-gray-900">{{ $animal->tag_number }}</p>
                                    <p class="text-sm text-gray-600">{{ ucfirst($animal->type) }} {{ $animal->breed ? '- ' . $animal->breed : '' }}</p>
                                </div>
                                <div class="h-12 w-12 rounded-full {{ $animal->health_status === 'healthy' ? 'bg-green-100' : ($animal->health_status === 'sick' ? 'bg-red-100' : ($animal->health_status === 'recovering' ? 'bg-yellow-100' : 'bg-orange-100')) }} flex items-center justify-center">
                                    <svg class="h-6 w-6 {{ $animal->health_status === 'healthy' ? 'text-green-600' : ($animal->health_status === 'sick' ? 'text-red-600' : ($animal->health_status === 'recovering' ? 'text-yellow-600' : 'text-orange-600')) }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($animal->health_status === 'healthy')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        @endif
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Animal Details -->
                        <div class="p-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Gender:</span>
                                <span class="font-medium text-gray-900">{{ ucfirst($animal->gender) }}</span>
                            </div>
                            @if($animal->date_of_birth)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Age:</span>
                                <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($animal->date_of_birth)->diffForHumans(null, true) }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Health Status:</span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $animal->health_status === 'healthy' ? 'bg-green-100 text-green-800' : ($animal->health_status === 'sick' ? 'bg-red-100 text-red-800' : ($animal->health_status === 'recovering' ? 'bg-yellow-100 text-yellow-800' : 'bg-orange-100 text-orange-800')) }}">
                                    {{ ucfirst($animal->health_status) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Vaccinations:</span>
                                <span class="font-medium text-gray-900">{{ $animal->vaccinationHistory->count() }}</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="p-4 border-t border-gray-200 bg-gray-50">
                            <a href="#" class="block w-full text-center bg-brand-green text-white px-4 py-2 rounded-lg text-sm font-medium hover:opacity-90 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-lg shadow p-4">
                    {{ $livestock->links() }}
                </div>

                @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No livestock registered yet</h3>
                    <p class="mt-2 text-sm text-gray-500">Get started by adding your first animal to the system.</p>
                    <div class="mt-6">
                        <a href="{{ route('farmer.livestock.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-brand-green hover:opacity-90">
                            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Your First Livestock
                        </a>
                    </div>
                </div>
                @endif

            </main>

        </div>

    </div>

    <script src="{{ asset('js/farmvax-mobile.js') }}"></script>
</body>
</html>