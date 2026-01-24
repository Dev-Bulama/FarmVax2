<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Farm Data - Step 1 - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('individual.dashboard') }}" class="text-green-600 hover:text-green-700 text-sm font-medium mb-4 inline-block">
                    ← Back to Dashboard
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Submit Farm Data</h1>
                <p class="mt-2 text-sm text-gray-600">Share your farming information to help improve agricultural services</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-green-600">Step 1 of 3</span>
                    <span class="text-sm font-medium text-gray-500">33% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 33%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="font-medium text-green-600">Personal Info</span>
                    <span>Livestock Data</span>
                    <span>Services</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 1: Personal Information</h2>
                    <p class="mt-1 text-sm text-gray-600">Your basic contact and location details</p>
                </div>

                @if($errors->any())
                <div class="px-6 py-4 bg-red-50 border-b border-red-200">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
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

                <form method="POST" action="{{ route('individual.farm-records.save-step', ['step' => 1]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Full Name -->
                    <div class="mb-6">
                        <label for="farmer_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="farmer_name" id="farmer_name" required
                            value="{{ old('farmer_name', $formData['step1']['farmer_name'] ?? auth()->user()->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="Enter your full name">
                        @error('farmer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="mb-6">
                        <label for="farmer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="farmer_phone" id="farmer_phone" required
                            value="{{ old('farmer_phone', $formData['step1']['farmer_phone'] ?? auth()->user()->phone) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="+234 123 456 7890">
                        <p class="mt-1 text-xs text-gray-500">For SMS alerts about outbreaks and announcements</p>
                        @error('farmer_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="mb-6">
                        <label for="farmer_email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="farmer_email" id="farmer_email" required
                            value="{{ old('farmer_email', $formData['step1']['farmer_email'] ?? auth()->user()->email) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="your@email.com">
                        <p class="mt-1 text-xs text-gray-500">For detailed updates and newsletters</p>
                        @error('farmer_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location Section -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Location Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Village/Town -->
                            <div>
                                <label for="farmer_village" class="block text-sm font-medium text-gray-700 mb-1">
                                    Village/Town <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="farmer_village" id="farmer_village" required
                                    value="{{ old('farmer_village', $formData['step1']['farmer_village'] ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                    placeholder="Enter village or town">
                                @error('farmer_village')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- State -->
                            <div>
                                <label for="farmer_state" class="block text-sm font-medium text-gray-700 mb-1">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <select name="farmer_state" id="farmer_state" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <option value="">Select State</option>
                                    <option value="Abia" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Abia' ? 'selected' : '' }}>Abia</option>
                                    <option value="Adamawa" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Adamawa' ? 'selected' : '' }}>Adamawa</option>
                                    <option value="Akwa Ibom" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Akwa Ibom' ? 'selected' : '' }}>Akwa Ibom</option>
                                    <option value="Anambra" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Anambra' ? 'selected' : '' }}>Anambra</option>
                                    <option value="Bauchi" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Bauchi' ? 'selected' : '' }}>Bauchi</option>
                                    <option value="Bayelsa" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Bayelsa' ? 'selected' : '' }}>Bayelsa</option>
                                    <option value="Benue" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Benue' ? 'selected' : '' }}>Benue</option>
                                    <option value="Borno" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Borno' ? 'selected' : '' }}>Borno</option>
                                    <option value="Cross River" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Cross River' ? 'selected' : '' }}>Cross River</option>
                                    <option value="Delta" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Delta' ? 'selected' : '' }}>Delta</option>
                                    <option value="Ebonyi" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Ebonyi' ? 'selected' : '' }}>Ebonyi</option>
                                    <option value="Edo" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Edo' ? 'selected' : '' }}>Edo</option>
                                    <option value="Ekiti" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Ekiti' ? 'selected' : '' }}>Ekiti</option>
                                    <option value="Enugu" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Enugu' ? 'selected' : '' }}>Enugu</option>
                                    <option value="FCT" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'FCT' ? 'selected' : '' }}>FCT Abuja</option>
                                    <option value="Gombe" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Gombe' ? 'selected' : '' }}>Gombe</option>
                                    <option value="Imo" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Imo' ? 'selected' : '' }}>Imo</option>
                                    <option value="Jigawa" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Jigawa' ? 'selected' : '' }}>Jigawa</option>
                                    <option value="Kaduna" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Kaduna' ? 'selected' : '' }}>Kaduna</option>
                                    <option value="Kano" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Kano' ? 'selected' : '' }}>Kano</option>
                                    <option value="Katsina" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Katsina' ? 'selected' : '' }}>Katsina</option>
                                    <option value="Kebbi" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Kebbi' ? 'selected' : '' }}>Kebbi</option>
                                    <option value="Kogi" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Kogi' ? 'selected' : '' }}>Kogi</option>
                                    <option value="Kwara" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Kwara' ? 'selected' : '' }}>Kwara</option>
                                    <option value="Lagos" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Lagos' ? 'selected' : '' }}>Lagos</option>
                                    <option value="Nasarawa" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Nasarawa' ? 'selected' : '' }}>Nasarawa</option>
                                    <option value="Niger" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Niger' ? 'selected' : '' }}>Niger</option>
                                    <option value="Ogun" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Ogun' ? 'selected' : '' }}>Ogun</option>
                                    <option value="Ondo" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Ondo' ? 'selected' : '' }}>Ondo</option>
                                    <option value="Osun" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Osun' ? 'selected' : '' }}>Osun</option>
                                    <option value="Oyo" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Oyo' ? 'selected' : '' }}>Oyo</option>
                                    <option value="Plateau" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Plateau' ? 'selected' : '' }}>Plateau</option>
                                    <option value="Rivers" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Rivers' ? 'selected' : '' }}>Rivers</option>
                                    <option value="Sokoto" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Sokoto' ? 'selected' : '' }}>Sokoto</option>
                                    <option value="Taraba" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Taraba' ? 'selected' : '' }}>Taraba</option>
                                    <option value="Yobe" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Yobe' ? 'selected' : '' }}>Yobe</option>
                                    <option value="Zamfara" {{ old('farmer_state', $formData['step1']['farmer_state'] ?? '') == 'Zamfara' ? 'selected' : '' }}>Zamfara</option>
                                </select>
                                @error('farmer_state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="farmer_country" class="block text-sm font-medium text-gray-700 mb-1">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="farmer_country" id="farmer_country" required
                                    value="{{ old('farmer_country', $formData['step1']['farmer_country'] ?? 'Nigeria') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                @error('farmer_country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Household Size -->
                            <div>
                                <label for="household_size" class="block text-sm font-medium text-gray-700 mb-1">
                                    Average Household Size <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="household_size" id="household_size" required min="1"
                                    value="{{ old('household_size', $formData['step1']['household_size'] ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                    placeholder="e.g., 5">
                                <p class="mt-1 text-xs text-gray-500">How many people live in your household?</p>
                                @error('household_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Role in Livestock Sector -->
                    <div class="mb-6">
                        <label for="role_in_sector" class="block text-sm font-medium text-gray-700 mb-1">
                            Role in Livestock Sector <span class="text-red-500">*</span>
                        </label>
                        <select name="role_in_sector" id="role_in_sector" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select your role...</option>
                            <option value="farmer" {{ old('role_in_sector', $formData['step1']['role_in_sector'] ?? '') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                            <option value="veterinarian" {{ old('role_in_sector', $formData['step1']['role_in_sector'] ?? '') == 'veterinarian' ? 'selected' : '' }}>Veterinarian</option>
                            <option value="extension_officer" {{ old('role_in_sector', $formData['step1']['role_in_sector'] ?? '') == 'extension_officer' ? 'selected' : '' }}>Extension Officer</option>
                            <option value="trader" {{ old('role_in_sector', $formData['step1']['role_in_sector'] ?? '') == 'trader' ? 'selected' : '' }}>Trader</option>
                            <option value="ngo" {{ old('role_in_sector', $formData['step1']['role_in_sector'] ?? '') == 'ngo' ? 'selected' : '' }}>NGO Worker</option>
                            <option value="policy_maker" {{ old('role_in_sector', $formData['step1']['role_in_sector'] ?? '') == 'policy_maker' ? 'selected' : '' }}>Policy Maker</option>
                            <option value="other" {{ old('role_in_sector', $formData['step1']['role_in_sector'] ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('role_in_sector')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Info Box -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    <strong>Why we need this:</strong> Your location is critical for geo-targeted alerts about disease outbreaks and vaccine availability in your area.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('individual.dashboard') }}" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Next: Livestock Data
                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</body>
</html>