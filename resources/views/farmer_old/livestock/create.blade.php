<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Livestock - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/farmvax-brand.css') }}">
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-brand-green">
                
                <!-- Logo -->
                <div class="flex items-center h-16 flex-shrink-0 px-4 bg-brand-green">
                    <span class="text-xl font-bold text-white">FarmVax</span>
                </div>

                <!-- Navigation -->
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

                <!-- Logout -->
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
                            <h1 class="text-2xl font-bold brand-teal">Add New Livestock</h1>
                            <p class="text-sm text-gray-600">Register a new animal to your farm</p>
                        </div>
                        <a href="{{ route('farmer.livestock') }}" class="text-brand-green hover:underline text-sm font-medium">
                            ← Back to Livestock
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="px-4 sm:px-6 lg:px-8 py-8">

                <!-- Form Card -->
                <div class="max-w-3xl mx-auto bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold brand-teal">Livestock Information</h2>
                    </div>

                    <form method="POST" action="{{ route('farmer.livestock.store') }}" class="p-6">
                        @csrf

                        @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</p>
                                    <ul class="list-disc list-inside text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Type of Livestock -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Type of Livestock <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="type" 
                                    id="type" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-brand-green"
                                >
                                    <option value="">Select type...</option>
                                    <option value="cattle" {{ old('type') == 'cattle' ? 'selected' : '' }}>Cattle</option>
                                    <option value="goat" {{ old('type') == 'goat' ? 'selected' : '' }}>Goat</option>
                                    <option value="sheep" {{ old('type') == 'sheep' ? 'selected' : '' }}>Sheep</option>
                                    <option value="poultry" {{ old('type') == 'poultry' ? 'selected' : '' }}>Poultry</option>
                                    <option value="pig" {{ old('type') == 'pig' ? 'selected' : '' }}>Pig</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Breed -->
                            <div>
                                <label for="breed" class="block text-sm font-medium text-gray-700 mb-2">
                                    Breed
                                </label>
                                <input 
                                    type="text" 
                                    name="breed" 
                                    id="breed"
                                    value="{{ old('breed') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-brand-green"
                                    placeholder="e.g., Holstein, Boer, Merino"
                                >
                            </div>

                            <!-- Tag Number -->
                            <div>
                                <label for="tag_number" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tag/ID Number <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="tag_number" 
                                    id="tag_number" 
                                    required
                                    value="{{ old('tag_number') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-brand-green"
                                    placeholder="e.g., COW-001, G-2023-05"
                                >
                                <p class="mt-1 text-xs text-gray-500">Unique identifier for this animal</p>
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="gender" 
                                    id="gender" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-brand-green"
                                >
                                    <option value="">Select gender...</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                    Date of Birth
                                </label>
                                <input 
                                    type="date" 
                                    name="date_of_birth" 
                                    id="date_of_birth"
                                    value="{{ old('date_of_birth') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-brand-green"
                                >
                            </div>

                            <!-- Health Status -->
                            <div>
                                <label for="health_status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Health Status <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="health_status" 
                                    id="health_status" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-brand-green"
                                >
                                    <option value="">Select status...</option>
                                    <option value="healthy" {{ old('health_status') == 'healthy' ? 'selected' : '' }}>Healthy</option>
                                    <option value="sick" {{ old('health_status') == 'sick' ? 'selected' : '' }}>Sick</option>
                                    <option value="recovering" {{ old('health_status') == 'recovering' ? 'selected' : '' }}>Recovering</option>
                                    <option value="quarantine" {{ old('health_status') == 'quarantine' ? 'selected' : '' }}>Quarantine</option>
                                </select>
                            </div>

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Additional Notes
                                </label>
                                <textarea 
                                    name="notes" 
                                    id="notes" 
                                    rows="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-brand-green"
                                    placeholder="Any additional information about this animal (medical history, special care requirements, etc.)"
                                >{{ old('notes') }}</textarea>
                            </div>

                        </div>

                        <!-- Info Box -->
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Helpful Tips</p>
                                    <ul class="mt-1 text-sm text-blue-700 list-disc list-inside">
                                        <li>Use a unique tag number that you can easily identify</li>
                                        <li>Record accurate birth dates for better health tracking</li>
                                        <li>Update health status regularly to monitor your herd</li>
                                        <li>You can add vaccination records after registering the animal</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <button 
                                type="submit"
                                class="flex-1 bg-brand-green text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition flex items-center justify-center"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add Livestock
                            </button>

                            <a 
                                href="{{ route('farmer.livestock') }}" 
                                class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300 transition text-center"
                            >
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>

            </main>

        </div>

    </div>

    <script src="{{ asset('js/farmvax-mobile.js') }}"></script>
</body>
</html>