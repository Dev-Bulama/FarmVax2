<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 5: Production & Purpose - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('individual.dashboard') }}" class="text-green-600 hover:text-green-700 text-sm font-medium mb-4 inline-block">← Back to Dashboard</a>
                <h1 class="text-3xl font-bold text-gray-900">Register New Livestock</h1>
                <p class="mt-2 text-sm text-gray-600">Complete all 6 steps to register your animal</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-green-600">Step 5 of 6</span>
                    <span class="text-sm font-medium text-gray-500">83% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 83%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Basic Info</span>
                    <span class="text-green-600">✓ Physical</span>
                    <span class="text-green-600">✓ Health</span>
                    <span class="text-green-600">✓ Vaccination</span>
                    <span class="font-medium text-green-600">Production</span>
                    <span>Origin</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 5: Production & Purpose</h2>
                    <p class="mt-1 text-sm text-gray-600">How this animal contributes to your farm</p>
                </div>

                @if(session('success'))
                <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                @endif

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

                <form method="POST" action="{{ route('individual.livestock.save-step', ['step' => 5]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Production Purpose Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Production Purpose</h3>
                        
                        <p class="text-sm text-gray-600 mb-4">What is the primary purpose of this animal on your farm?</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @php
                                $purposes = [
                                    'meat' => ['name' => 'Meat Production', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                                    'dairy' => ['name' => 'Dairy/Milk', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                                    'eggs' => ['name' => 'Egg Production', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9'],
                                    'breeding' => ['name' => 'Breeding', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                                    'work' => ['name' => 'Work/Labor', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                    'mixed' => ['name' => 'Mixed Purpose', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                                    'other' => ['name' => 'Other', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
                                ];
                                $selectedPurpose = old('production_purpose', $livestockData['step5']['production_purpose'] ?? '');
                            @endphp

                            @foreach($purposes as $key => $purpose)
                            <label class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ $selectedPurpose === $key ? 'border-green-500 bg-green-50 ring-2 ring-green-500' : 'border-gray-200' }}">
                                <input type="radio" name="production_purpose" value="{{ $key }}"
                                    {{ $selectedPurpose === $key ? 'checked' : '' }}
                                    onchange="toggleProductionFields()"
                                    class="sr-only">
                                <svg class="h-8 w-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $purpose['icon'] }}" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900 text-center">{{ $purpose['name'] }}</span>
                                @if($selectedPurpose === $key)
                                <div class="absolute top-2 right-2">
                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Production Details (conditional based on purpose) -->
                    <div id="productionDetails" class="mb-8 hidden">
                        
                        <!-- Dairy Production -->
                        <div id="dairyDetails" class="hidden">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Dairy Production Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="daily_milk_production" class="block text-sm font-medium text-gray-700 mb-1">Daily Milk Production (liters)</label>
                                    <input type="number" name="daily_milk_production" id="daily_milk_production"
                                        value="{{ old('daily_milk_production', $livestockData['step5']['daily_milk_production'] ?? '') }}"
                                        step="0.1" min="0"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                        placeholder="e.g., 5.5">
                                    <p class="mt-1 text-xs text-gray-500">Average liters of milk produced per day</p>
                                </div>
                                <div>
                                    <label for="last_production_date" class="block text-sm font-medium text-gray-700 mb-1">Last Production Date</label>
                                    <input type="date" name="last_production_date" id="last_production_date"
                                        value="{{ old('last_production_date', $livestockData['step5']['last_production_date'] ?? '') }}"
                                        max="{{ date('Y-m-d') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                </div>
                            </div>
                        </div>

                        <!-- Egg Production -->
                        <div id="eggDetails" class="hidden">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Egg Production Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="monthly_egg_production" class="block text-sm font-medium text-gray-700 mb-1">Monthly Egg Production</label>
                                    <input type="number" name="monthly_egg_production" id="monthly_egg_production"
                                        value="{{ old('monthly_egg_production', $livestockData['step5']['monthly_egg_production'] ?? '') }}"
                                        min="0"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                        placeholder="e.g., 25">
                                    <p class="mt-1 text-xs text-gray-500">Average number of eggs per month</p>
                                </div>
                                <div>
                                    <label for="last_production_date" class="block text-sm font-medium text-gray-700 mb-1">Last Production Date</label>
                                    <input type="date" name="last_production_date" id="last_production_date"
                                        value="{{ old('last_production_date', $livestockData['step5']['last_production_date'] ?? '') }}"
                                        max="{{ date('Y-m-d') }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Feeding Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Feeding Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="daily_feed_amount" class="block text-sm font-medium text-gray-700 mb-1">Daily Feed Amount (kg)</label>
                                <input type="number" name="daily_feed_amount" id="daily_feed_amount"
                                    value="{{ old('daily_feed_amount', $livestockData['step5']['daily_feed_amount'] ?? '') }}"
                                    step="0.1" min="0"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., 3.5">
                                <p class="mt-1 text-xs text-gray-500">Approximate amount of feed per day</p>
                            </div>
                            <div>
                                <label for="feeding_schedule" class="block text-sm font-medium text-gray-700 mb-1">Feeding Schedule</label>
                                <select name="feeding_schedule" id="feeding_schedule"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Schedule</option>
                                    <option value="once_daily" {{ old('feeding_schedule', $livestockData['step5']['feeding_schedule'] ?? '') == 'once_daily' ? 'selected' : '' }}>Once Daily</option>
                                    <option value="twice_daily" {{ old('feeding_schedule', $livestockData['step5']['feeding_schedule'] ?? '') == 'twice_daily' ? 'selected' : '' }}>Twice Daily</option>
                                    <option value="three_times_daily" {{ old('feeding_schedule', $livestockData['step5']['feeding_schedule'] ?? '') == 'three_times_daily' ? 'selected' : '' }}>Three Times Daily</option>
                                    <option value="free_range" {{ old('feeding_schedule', $livestockData['step5']['feeding_schedule'] ?? '') == 'free_range' ? 'selected' : '' }}>Free Range/Grazing</option>
                                </select>
                            </div>
                        </div>

                        <!-- Dietary Notes -->
                        <div>
                            <label for="dietary_notes" class="block text-sm font-medium text-gray-700 mb-1">Dietary Notes (Optional)</label>
                            <textarea name="dietary_notes" id="dietary_notes" rows="3"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Special diet requirements, supplements, feed types, etc...">{{ old('dietary_notes', $livestockData['step5']['dietary_notes'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('individual.livestock.step', ['step' => 4]) }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </a>
                            <button type="button" onclick="saveDraft()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Save as Draft
                            </button>
                        </div>
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Next: Origin & Documents
                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleProductionFields() {
            const selectedPurpose = document.querySelector('input[name="production_purpose"]:checked');
            const productionDetails = document.getElementById('productionDetails');
            const dairyDetails = document.getElementById('dairyDetails');
            const eggDetails = document.getElementById('eggDetails');
            
            // Hide all by default
            productionDetails.classList.add('hidden');
            dairyDetails.classList.add('hidden');
            eggDetails.classList.add('hidden');
            
            if (selectedPurpose) {
                const value = selectedPurpose.value;
                
                if (value === 'dairy' || value === 'mixed') {
                    productionDetails.classList.remove('hidden');
                    dairyDetails.classList.remove('hidden');
                }
                
                if (value === 'eggs' || value === 'mixed') {
                    productionDetails.classList.remove('hidden');
                    eggDetails.classList.remove('hidden');
                }
            }
        }

        function saveDraft() {
            if (confirm('Save your progress as a draft? You can continue later.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("individual.livestock.draft") }}';
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleProductionFields();
        });
    </script>
</body>
</html>