<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Record Details - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Component -->
        @include('components.data-collector-sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center">
                        <button onclick="toggleSidebar()" class="mr-4 text-gray-500 hover:text-gray-700 md:hidden">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Farm Record Details</h1>
                            <p class="text-sm text-gray-600">Record #{{ $record->id }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $record->status === 'approved' ? 'bg-green-100 text-green-800' : ($record->status === 'rejected' ? 'bg-red-100 text-red-800' : ($record->status === 'under_review' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                            {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6">
                
                <div class="max-w-5xl mx-auto">
                    
                    <!-- Farmer Information -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Farmer Information</h2>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_phone }}</dd>
                            </div>
                            @if($record->farmer_email)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_email }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Location</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $record->farmer_city }}, {{ $record->farmer_state }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Livestock Information -->
                    <div class="bg-white shadow rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Livestock Profile</h2>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Livestock</dt>
                                <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $record->total_livestock_count }}</dd>
                            </div>
                            @if($record->young_count || $record->adult_count || $record->old_count)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Age Distribution</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    Young: {{ $record->young_count ?? 0 }}, 
                                    Adult: {{ $record->adult_count ?? 0 }}, 
                                    Old: {{ $record->old_count ?? 0 }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Back Button -->
                    <div class="flex justify-between">
                        <a href="{{ route('data-collector.farm-records.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            ← Back to Submissions
                        </a>
                        @if($record->status === 'draft')
                        <a href="{{ route('data-collector.farm-records.edit', $record->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Continue Editing
                        </a>
                        @endif
                    </div>

                </div>

            </main>
        </div>
    </div>

</body>
</html>