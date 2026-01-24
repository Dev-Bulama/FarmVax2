<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step X: [Title] - FarmVax</title>
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
                            <h1 class="text-2xl font-bold text-gray-900">New Farm Record</h1>
                            <p class="text-sm text-gray-600">Complete all 6 steps to submit</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Data Collector</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6">
                <div class="max-w-4xl mx-auto">

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-600">Step 2 of 6</span>
                    <span class="text-sm font-medium text-gray-500">33% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 33%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Stakeholder Info</span>
                    <span class="font-medium text-blue-600">Livestock Profile</span>
                    <span>Health & Vaccination</span>
                    <span>Service Needs</span>
                    <span>Alert Preferences</span>
                    <span>Consent</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 2: Livestock Profile</h2>
                    <p class="mt-1 text-sm text-gray-600">Information about the livestock on the farm</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="px-6 py-4 bg-green-50 border-b border-green-200">
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

                <!-- Error Summary -->
                @if($errors->any())
                <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('data-collector.farm-record.save-step', ['step' => 2]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Total Livestock Count -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Total Livestock Count
                        </h3>

                        <div class="max-w-md">
                            <label for="total_livestock_count" class="block text-sm font-medium text-gray-700 mb-1">
                                Total Number of Animals <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="total_livestock_count" 
                                id="total_livestock_count" 
                                value="{{ old('total_livestock_count', $farmRecordData['step2']['total_livestock_count'] ?? '') }}"
                                required
                                min="0"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-lg @error('total_livestock_count') border-red-300 @enderror"
                                placeholder="0"
                            >
                            <p class="mt-1 text-sm text-gray-500">Total count of all livestock on the farm</p>
                            @error('total_livestock_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Types of Livestock -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Types of Livestock
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">Select all types of livestock on this farm:</p>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @php
                                $livestockTypes = ['cattle', 'goats', 'sheep', 'poultry', 'pigs', 'horses', 'donkeys', 'rabbits', 'ducks', 'turkeys', 'guinea_fowl', 'other'];
                                $selectedTypes = old('livestock_types', $farmRecordData['step2']['livestock_types'] ?? []);
                            @endphp

                            @foreach($livestockTypes as $type)
                            <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array($type, $selectedTypes) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="livestock_types[]" 
                                    value="{{ $type }}"
                                    {{ in_array($type, $selectedTypes) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1"
                                >
                                <div class="ml-3">
                                    <span class="block text-sm font-medium text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                    </span>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        @error('livestock_types')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Age Distribution -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Age Distribution
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">Approximate count of animals by age category:</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Young Animals -->
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <label for="young_count" class="block text-sm font-medium text-gray-900 mb-2">
                                    <svg class="inline h-5 w-5 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                    </svg>
                                    Young Animals
                                </label>
                                <input 
                                    type="number" 
                                    name="young_count" 
                                    id="young_count" 
                                    value="{{ old('young_count', $farmRecordData['step2']['young_count'] ?? '') }}"
                                    min="0"
                                    class="block w-full px-3 py-2 border border-green-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="0"
                                >
                                <p class="mt-1 text-xs text-gray-600">0-1 years old</p>
                                @error('young_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Adult Animals -->
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <label for="adult_count" class="block text-sm font-medium text-gray-900 mb-2">
                                    <svg class="inline h-5 w-5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Adult Animals
                                </label>
                                <input 
                                    type="number" 
                                    name="adult_count" 
                                    id="adult_count" 
                                    value="{{ old('adult_count', $farmRecordData['step2']['adult_count'] ?? '') }}"
                                    min="0"
                                    class="block w-full px-3 py-2 border border-blue-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="0"
                                >
                                <p class="mt-1 text-xs text-gray-600">1-7 years old</p>
                                @error('adult_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Old Animals -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-300">
                                <label for="old_count" class="block text-sm font-medium text-gray-900 mb-2">
                                    <svg class="inline h-5 w-5 text-gray-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Old/Senior Animals
                                </label>
                                <input 
                                    type="number" 
                                    name="old_count" 
                                    id="old_count" 
                                    value="{{ old('old_count', $farmRecordData['step2']['old_count'] ?? '') }}"
                                    min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500"
                                    placeholder="0"
                                >
                                <p class="mt-1 text-xs text-gray-600">7+ years old</p>
                                @error('old_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800">
                                <strong>Note:</strong> Age categories are approximate. Total of young + adult + old should ideally match the total livestock count above.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a 
                                href="{{ route('data-collector.farm-record.step', ['step' => 1]) }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </a>

                            <button 
                                type="button" 
                                onclick="saveDraft()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Save as Draft
                            </button>
                        </div>

                        <button 
                            type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Next: Health & Vaccination
                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <!-- Save Draft Script -->
    <script>
        function saveDraft() {
            if (confirm('Save your progress as a draft? You can continue later.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("data-collector.farm-record.draft") }}';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

</body>
</html>