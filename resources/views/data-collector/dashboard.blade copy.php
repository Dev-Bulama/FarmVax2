<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Collector Dashboard - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-blue-800">
                
                <!-- Logo -->
                <div class="flex items-center h-16 flex-shrink-0 px-4 bg-blue-900">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="ml-2 text-white text-xl font-bold">FarmVax</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto">
                    <div class="px-2 py-4 space-y-1">
                        
                        <!-- Dashboard -->
                        <a href="{{ route('data-collector.dashboard') }}" class="bg-blue-900 text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>

                        <!-- New Farm Record -->
                        <a href="{{ route('data-collector.farm-records.create') }}" class="text-blue-100 hover:bg-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            New Farm Record
                        </a>

                        <!-- My Submissions -->
                        <a href="{{ route('data-collector.farm-records.index') }}" class="text-blue-100 hover:bg-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            My Submissions
                        </a>

                        <!-- Draft Records -->
                        <a href="{{ route('data-collector.farm-records.drafts') }}" class="text-blue-100 hover:bg-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Draft Records
                            @if($stats['draft_records'] > 0)
                            <span class="ml-auto bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $stats['draft_records'] }}</span>
                            @endif
                        </a>

                        <!-- Profile -->
                        <a href="{{ route('data-collector.profile') }}" class="text-blue-100 hover:bg-blue-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            My Profile
                        </a>

                    </div>
                </nav>

                <!-- Performance Summary in Sidebar -->
                @if($profile)
                <div class="px-4 py-4 bg-blue-900">
                    <div class="text-white">
                        <p class="text-xs font-medium text-blue-300 mb-2">Performance</p>
                        <div class="space-y-2">
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span>Accuracy Rate</span>
                                    <span class="font-semibold">{{ number_format($stats['accuracy_rate'], 1) }}%</span>
                                </div>
                                <div class="w-full bg-blue-700 rounded-full h-2">
                                    <div class="bg-green-400 h-2 rounded-full" style="width: {{ min($stats['accuracy_rate'], 100) }}%"></div>
                                </div>
                            </div>
                            <div class="text-xs text-blue-300">
                                {{ $stats['approved_records'] }}/{{ $stats['total_submissions'] }} approved
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">Data Collector Dashboard</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- User Info -->
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Data Collector</p>
                        </div>
                        
                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6">
                
                <!-- Success Message -->
                @if(session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Welcome Message -->
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                    <p class="mt-1 text-sm text-gray-600">Track your farm data collection progress and submissions.</p>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    
                    <!-- Total Submissions -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-blue-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Submissions</dt>
                                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['total_submissions'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Records -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-green-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Approved</dt>
                                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['approved_records'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Review -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-yellow-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Review</dt>
                                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['pending_review'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Draft Records -->
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-gray-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Drafts</dt>
                                        <dd class="text-3xl font-semibold text-gray-900">{{ $stats['draft_records'] }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Quick Actions -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        
                        <!-- New Farm Record -->
                        <a href="{{ route('data-collector.farm-records.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-lg shadow-md hover:shadow-lg transition text-white">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-semibold">New Farm Record</p>
                                    <p class="text-sm text-blue-100">Start collecting data</p>
                                </div>
                            </div>
                        </a>

                        <!-- Continue Draft -->
                        <a href="{{ route('data-collector.farm-records.drafts') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-2 border-transparent hover:border-blue-500">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-semibold text-gray-900">Continue Draft</p>
                                    <p class="text-sm text-gray-500">{{ $stats['draft_records'] }} drafts saved</p>
                                </div>
                            </div>
                        </a>

                        <!-- View Submissions -->
                        <a href="{{ route('data-collector.farm-records.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition border-2 border-transparent hover:border-blue-500">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-semibold text-gray-900">View Submissions</p>
                                    <p class="text-sm text-gray-500">All submitted records</p>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Draft Records -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Draft Records</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            @if($draft_records->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($draft_records as $record)
                                <li class="py-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $record->farm_name ?: 'Untitled Farm' }}</p>
                                            <p class="text-xs text-gray-500">{{ $record->farmer_name ?: 'No farmer name' }} • Updated {{ $record->updated_at->diffForHumans() }}</p>
                                        </div>
                                        <a href="{{ route('data-collector.farm-records.edit', $record->id) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                            Continue →
                                        </a>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <div class="mt-4">
                                <a href="{{ route('data-collector.farm-records.drafts') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                    View all drafts →
                                </a>
                            </div>
                            @else
                            <p class="text-sm text-gray-500">No draft records. Start a new farm record to begin.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Submissions -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Submissions</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            @if($recent_submissions->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($recent_submissions as $record)
                                <li class="py-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $record->farm_name ?: 'Farm Record' }}</p>
                                            <p class="text-xs text-gray-500">{{ $record->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $record->status === 'approved' ? 'bg-green-100 text-green-800' : ($record->status === 'rejected' ? 'bg-red-100 text-red-800' : ($record->status === 'under_review' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                        </span>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <div class="mt-4">
                                <a href="{{ route('data-collector.farm-records.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                    View all submissions →
                                </a>
                            </div>
                            @else
                            <p class="text-sm text-gray-500">No submissions yet.</p>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Performance Summary -->
                @if($profile)
                <div class="mt-6 bg-white shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Performance Summary</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Accuracy Rate</dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($stats['accuracy_rate'], 1) }}%</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Submissions</dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $profile->total_submissions }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Approved</dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $profile->approved_submissions }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Territory</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $profile->assigned_territory ?: 'Not assigned' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </main>

        </div>

    </div>

</body>
</html>