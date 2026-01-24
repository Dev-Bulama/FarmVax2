<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 1: Stakeholder Information - FarmVax</title>
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
                    <span class="text-sm font-medium text-blue-600">Step 1 of 6</span>
                    <span class="text-sm font-medium text-gray-500">17% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: 17%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="font-medium text-blue-600">Stakeholder Info</span>
                    <span>Livestock Profile</span>
                    <span>Health & Vaccination</span>
                    <span>Service Needs</span>
                    <span>Alert Preferences</span>
                    <span>Consent</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 1: Stakeholder Information</h2>
                    <p class="mt-1 text-sm text-gray-600">Farmer's personal information and farm details</p>
                </div>

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

                <form method="POST" action="{{ route('data-collector.farm-record.save-step', ['step' => 1]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Farmer Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Farmer Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Farmer Name -->
                            <div class="md:col-span-2">
                                <label for="farmer_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="farmer_name" 
                                    id="farmer_name" 
                                    value="{{ old('farmer_name', $farmRecordData['step1']['farmer_name'] ?? '') }}"
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('farmer_name') border-red-300 @enderror"
                                    placeholder="Enter farmer's full name"
                                >
                                @error('farmer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="farmer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    name="farmer_phone" 
                                    id="farmer_phone" 
                                    value="{{ old('farmer_phone', $farmRecordData['step1']['farmer_phone'] ?? '') }}"
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="+234 123 456 7890"
                                >
                                @error('farmer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="farmer_email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address
                                </label>
                                <input 
                                    type="email" 
                                    name="farmer_email" 
                                    id="farmer_email" 
                                    value="{{ old('farmer_email', $farmRecordData['step1']['farmer_email'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="farmer@example.com"
                                >
                                @error('farmer_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Average Household Size -->
                            <div>
                                <label for="average_household_size" class="block text-sm font-medium text-gray-700 mb-1">
                                    Average Household Size
                                </label>
                                <input 
                                    type="number" 
                                    name="average_household_size" 
                                    id="average_household_size" 
                                    value="{{ old('average_household_size', $farmRecordData['step1']['average_household_size'] ?? '') }}"
                                    min="1"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 5"
                                >
                                <p class="mt-1 text-xs text-gray-500">Number of people in the household</p>
                                @error('average_household_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Location Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Location Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="farmer_address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Address
                                </label>
                                <textarea 
                                    name="farmer_address" 
                                    id="farmer_address" 
                                    rows="2"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter full address"
                                >{{ old('farmer_address', $farmRecordData['step1']['farmer_address'] ?? '') }}</textarea>
                                @error('farmer_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                                    City/Town
                                </label>
                                <input 
                                    type="text" 
                                    name="city" 
                                    id="city" 
                                    value="{{ old('city', $farmRecordData['step1']['city'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter city"
                                >
                                @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- State -->
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                                    State
                                </label>
                                <input 
                                    type="text" 
                                    name="state" 
                                    id="state" 
                                    value="{{ old('state', $farmRecordData['step1']['state'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter state"
                                >
                                @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- LGA -->
                            <div>
                                <label for="lga" class="block text-sm font-medium text-gray-700 mb-1">
                                    LGA (Local Government Area)
                                </label>
                                <input 
                                    type="text" 
                                    name="lga" 
                                    id="lga" 
                                    value="{{ old('lga', $farmRecordData['step1']['lga'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter LGA"
                                >
                                @error('lga')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- GPS Coordinates -->
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">
                                    Latitude (GPS)
                                </label>
                                <input 
                                    type="number" 
                                    name="latitude" 
                                    id="latitude" 
                                    step="any"
                                    value="{{ old('latitude', $farmRecordData['step1']['latitude'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 6.5244"
                                >
                                @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">
                                    Longitude (GPS)
                                </label>
                                <input 
                                    type="number" 
                                    name="longitude" 
                                    id="longitude" 
                                    step="any"
                                    value="{{ old('longitude', $farmRecordData['step1']['longitude'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 3.3792"
                                >
                                @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Farm Details Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Farm Details
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Farm Name -->
                            <div>
                                <label for="farm_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Farm Name
                                </label>
                                <input 
                                    type="text" 
                                    name="farm_name" 
                                    id="farm_name" 
                                    value="{{ old('farm_name', $farmRecordData['step1']['farm_name'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter farm name"
                                >
                                @error('farm_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Farm Type -->
                            <div>
                                <label for="farm_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Farm Type
                                </label>
                                <select 
                                    name="farm_type" 
                                    id="farm_type" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Select type</option>
                                    <option value="commercial" {{ old('farm_type', $farmRecordData['step1']['farm_type'] ?? '') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="subsistence" {{ old('farm_type', $farmRecordData['step1']['farm_type'] ?? '') == 'subsistence' ? 'selected' : '' }}>Subsistence</option>
                                    <option value="mixed" {{ old('farm_type', $farmRecordData['step1']['farm_type'] ?? '') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                                @error('farm_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Farm Size -->
                            <div>
                                <label for="farm_size" class="block text-sm font-medium text-gray-700 mb-1">
                                    Farm Size
                                </label>
                                <input 
                                    type="number" 
                                    name="farm_size" 
                                    id="farm_size" 
                                    step="0.01"
                                    value="{{ old('farm_size', $farmRecordData['step1']['farm_size'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter size"
                                >
                                @error('farm_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Farm Size Unit -->
                            <div>
                                <label for="farm_size_unit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Unit
                                </label>
                                <select 
                                    name="farm_size_unit" 
                                    id="farm_size_unit" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                                    <option value="">Select unit</option>
                                    <option value="acres" {{ old('farm_size_unit', $farmRecordData['step1']['farm_size_unit'] ?? '') == 'acres' ? 'selected' : '' }}>Acres</option>
                                    <option value="hectares" {{ old('farm_size_unit', $farmRecordData['step1']['farm_size_unit'] ?? '') == 'hectares' ? 'selected' : '' }}>Hectares</option>
                                    <option value="square_meters" {{ old('farm_size_unit', $farmRecordData['step1']['farm_size_unit'] ?? '') == 'square_meters' ? 'selected' : '' }}>Square Meters</option>
                                </select>
                                @error('farm_size_unit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
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

                        <button 
                            type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Next: Livestock Profile
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
                // Create a form and submit to draft endpoint
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("data-collector.farm-record.draft") }}';
                
                // Add CSRF token
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