<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Record - Step 2 - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    @include('farmer.partials.sidebar')

    <div class="flex-1 overflow-auto">
        <header class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-2xl font-bold text-green-600">Farm Record Submission</h1>
                <p class="text-sm text-gray-600">Step 2 of 6: Livestock Data</p>
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
                    <span class="text-xs font-medium text-green-600">Step 2 of 6</span>
                    <span class="text-xs text-gray-500">33% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 33%"></div>
                </div>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Livestock Data</h2>
                    <p class="text-sm text-gray-600 mb-6">Tell us about your animals.</p>

                    <form method="POST" action="{{ route('individual.farm-records.step2.store') }}">
                        @csrf

                        <!-- Total Livestock Count -->
                        <div class="mb-6">
                            <label for="total_livestock_count" class="block text-sm font-medium text-gray-700 mb-2">
                                Total Number of Animals <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                id="total_livestock_count" 
                                name="total_livestock_count" 
                                value="{{ old('total_livestock_count', session('farm_record_step2.total_livestock_count')) }}"
                                required
                                min="0"
                                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Total count"
                            >
                        </div>

                        <!-- Livestock Types -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Type of Livestock <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @php
                                    $types = ['cattle' => 'Cattle', 'goats' => 'Goats', 'sheep' => 'Sheep', 'poultry' => 'Poultry', 'pigs' => 'Pigs', 'other' => 'Other'];
                                    $selected = old('livestock_types', session('farm_record_step2.livestock_types', []));
                                @endphp
                                @foreach($types as $value => $label)
                                <label class="flex items-center p-3 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ in_array($value, (array)$selected) ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="checkbox" name="livestock_types[]" value="{{ $value }}" {{ in_array($value, (array)$selected) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Age Distribution -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Age Distribution</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="young_count" class="block text-xs text-gray-600 mb-1">Young (< 1 year)</label>
                                    <input 
                                        type="number" 
                                        id="young_count" 
                                        name="young_count" 
                                        value="{{ old('young_count', session('farm_record_step2.young_count', 0)) }}"
                                        min="0"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        placeholder="0"
                                    >
                                </div>
                                <div>
                                    <label for="adult_count" class="block text-xs text-gray-600 mb-1">Adult (1-5 years)</label>
                                    <input 
                                        type="number" 
                                        id="adult_count" 
                                        name="adult_count" 
                                        value="{{ old('adult_count', session('farm_record_step2.adult_count', 0)) }}"
                                        min="0"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        placeholder="0"
                                    >
                                </div>
                                <div>
                                    <label for="old_count" class="block text-xs text-gray-600 mb-1">Old (> 5 years)</label>
                                    <input 
                                        type="number" 
                                        id="old_count" 
                                        name="old_count" 
                                        value="{{ old('old_count', session('farm_record_step2.old_count', 0)) }}"
                                        min="0"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        placeholder="0"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Breed Information -->
                        <div class="mb-6">
                            <label for="breed_information" class="block text-sm font-medium text-gray-700 mb-2">
                                Breed Information
                            </label>
                            <textarea 
                                id="breed_information" 
                                name="breed_information" 
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="List the breeds of your livestock (e.g., Holstein cattle, Boer goats)"
                            >{{ old('breed_information', session('farm_record_step2.breed_information')) }}</textarea>
                        </div>

                        <!-- Vaccination Status -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Vaccination Status <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @php
                                    $vaxStatus = old('vaccination_status', session('farm_record_step2.vaccination_status'));
                                @endphp
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ $vaxStatus == 'up_to_date' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="vaccination_status" value="up_to_date" {{ $vaxStatus == 'up_to_date' ? 'checked' : '' }} class="h-4 w-4 text-green-600" required>
                                    <span class="ml-3 text-sm font-medium">Up-to-date</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ $vaxStatus == 'partially_vaccinated' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="vaccination_status" value="partially_vaccinated" {{ $vaxStatus == 'partially_vaccinated' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-3 text-sm font-medium">Partially Vaccinated</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ $vaxStatus == 'not_vaccinated' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="vaccination_status" value="not_vaccinated" {{ $vaxStatus == 'not_vaccinated' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-3 text-sm font-medium">Not Vaccinated</span>
                                </label>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center pt-6 border-t mt-8">
                            <a href="{{ route('individual.farm-records.step1') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition flex items-center">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Previous
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