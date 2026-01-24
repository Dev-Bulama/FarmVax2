<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Record - Step 1 - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    @include('farmer.partials.sidebar')

    <div class="flex-1 overflow-auto">
        <header class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-2xl font-bold text-green-600">Farm Record Submission</h1>
                <p class="text-sm text-gray-600">Step 1 of 6: Stakeholder Information</p>
            </div>
            
            <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-green-600 text-white hover:bg-green-700">
                <svg id="menu-open-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="menu-close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <main class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-green-600">Step 1 of 6</span>
                    <span class="text-xs text-gray-500">17% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 17%"></div>
                </div>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Stakeholder Information</h2>
                    <p class="text-sm text-gray-600 mb-6">Please provide your basic information and farm details.</p>

                    <form method="POST" action="{{ route('individual.farm-records.step1.store') }}">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-6">
                            <label for="farmer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="farmer_name" 
                                name="farmer_name" 
                                value="{{ old('farmer_name', session('farm_record_step1.farmer_name', auth()->user()->name)) }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Enter your full name"
                            >
                            @error('farmer_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="farmer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input 
                                    type="email" 
                                    id="farmer_email" 
                                    name="farmer_email" 
                                    value="{{ old('farmer_email', session('farm_record_step1.farmer_email', auth()->user()->email)) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="you@example.com"
                                >
                            </div>

                            <div>
                                <label for="farmer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    id="farmer_phone" 
                                    name="farmer_phone" 
                                    value="{{ old('farmer_phone', session('farm_record_step1.farmer_phone', auth()->user()->phone)) }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="+234-800-000-0000"
                                >
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label for="farmer_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                id="farmer_address" 
                                name="farmer_address" 
                                rows="2"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Street address, village/town"
                            >{{ old('farmer_address', session('farm_record_step1.farmer_address', auth()->user()->address)) }}</textarea>
                        </div>

                        <!-- City, State, LGA -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <label for="farmer_city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City/Town <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="farmer_city" 
                                    name="farmer_city" 
                                    value="{{ old('farmer_city', session('farm_record_step1.farmer_city')) }}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="City/Town"
                                >
                            </div>

                            <div>
                                <label for="farmer_state" class="block text-sm font-medium text-gray-700 mb-2">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="farmer_state" 
                                    name="farmer_state" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                >
                                    <option value="">Select State</option>
                                    <option value="Abia" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Abia' ? 'selected' : '' }}>Abia</option>
                                    <option value="Adamawa" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Adamawa' ? 'selected' : '' }}>Adamawa</option>
                                    <option value="Akwa Ibom" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Akwa Ibom' ? 'selected' : '' }}>Akwa Ibom</option>
                                    <option value="Anambra" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Anambra' ? 'selected' : '' }}>Anambra</option>
                                    <option value="Bauchi" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Bauchi' ? 'selected' : '' }}>Bauchi</option>
                                    <option value="FCT Abuja" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'FCT Abuja' ? 'selected' : '' }}>FCT Abuja</option>
                                    <option value="Kaduna" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Kaduna' ? 'selected' : '' }}>Kaduna</option>
                                    <option value="Kano" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Kano' ? 'selected' : '' }}>Kano</option>
                                    <option value="Lagos" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Lagos' ? 'selected' : '' }}>Lagos</option>
                                    <option value="Plateau" {{ old('farmer_state', session('farm_record_step1.farmer_state')) == 'Plateau' ? 'selected' : '' }}>Plateau</option>
                                </select>
                            </div>

                            <div>
                                <label for="farmer_lga" class="block text-sm font-medium text-gray-700 mb-2">
                                    LGA
                                </label>
                                <input 
                                    type="text" 
                                    id="farmer_lga" 
                                    name="farmer_lga" 
                                    value="{{ old('farmer_lga', session('farm_record_step1.farmer_lga')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="Local Government Area"
                                >
                            </div>
                        </div>

                        <!-- Farm Name & Size -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="farm_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Farm Name
                                </label>
                                <input 
                                    type="text" 
                                    id="farm_name" 
                                    name="farm_name" 
                                    value="{{ old('farm_name', session('farm_record_step1.farm_name')) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., Green Valley Farm"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Farm Size
                                </label>
                                <div class="flex gap-2">
                                    <input 
                                        type="number" 
                                        name="farm_size" 
                                        value="{{ old('farm_size', session('farm_record_step1.farm_size')) }}"
                                        step="0.01"
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        placeholder="0.00"
                                    >
                                    <select 
                                        name="farm_size_unit" 
                                        class="w-32 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    >
                                        <option value="hectares" {{ old('farm_size_unit', session('farm_record_step1.farm_size_unit')) == 'hectares' ? 'selected' : '' }}>Hectares</option>
                                        <option value="acres" {{ old('farm_size_unit', session('farm_record_step1.farm_size_unit')) == 'acres' ? 'selected' : '' }}>Acres</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Farm Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Farm Type <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ old('farm_type', session('farm_record_step1.farm_type')) == 'subsistence' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="farm_type" value="subsistence" {{ old('farm_type', session('farm_record_step1.farm_type')) == 'subsistence' ? 'checked' : '' }} class="h-4 w-4 text-green-600" required>
                                    <span class="ml-3 text-sm font-medium">Subsistence</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ old('farm_type', session('farm_record_step1.farm_type')) == 'commercial' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="farm_type" value="commercial" {{ old('farm_type', session('farm_record_step1.farm_type')) == 'commercial' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-3 text-sm font-medium">Commercial</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ old('farm_type', session('farm_record_step1.farm_type')) == 'mixed' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="farm_type" value="mixed" {{ old('farm_type', session('farm_record_step1.farm_type')) == 'mixed' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-3 text-sm font-medium">Mixed</span>
                                </label>
                            </div>
                        </div>

                        <!-- Household Size -->
                        <div class="mb-6">
                            <label for="average_household_size" class="block text-sm font-medium text-gray-700 mb-2">
                                Average Household Size
                            </label>
                            <input 
                                type="number" 
                                id="average_household_size" 
                                name="average_household_size" 
                                value="{{ old('average_household_size', session('farm_record_step1.average_household_size')) }}"
                                min="1"
                                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Number of people in household"
                            >
                            <p class="mt-1 text-xs text-gray-500">How many people live in your household?</p>
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center pt-6 border-t mt-8">
                            <a href="{{ route('individual.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center">
                                Next Step
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </form>
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
    const menuOpenIcon = document.getElementById('menu-open-icon');
    const menuCloseIcon = document.getElementById('menu-close-icon');

    if (menuButton) {
        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            menuOpenIcon.classList.toggle('hidden');
            menuCloseIcon.classList.toggle('hidden');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            menuOpenIcon.classList.remove('hidden');
            menuCloseIcon.classList.add('hidden');
        });
    }
});
</script>

</body>
</html>