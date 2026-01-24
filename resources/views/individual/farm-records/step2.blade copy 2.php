<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Farm Data - Step 2 - FarmVax</title>
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
                    <span class="text-sm font-medium text-green-600">Step 2 of 3</span>
                    <span class="text-sm font-medium text-gray-500">67% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 67%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Personal Info</span>
                    <span class="font-medium text-green-600">Livestock Data</span>
                    <span>Services</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 2: Livestock Information</h2>
                    <p class="mt-1 text-sm text-gray-600">Details about your animals and their health</p>
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

                <form method="POST" action="{{ route('individual.farm-records.save-step', ['step' => 2]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Types of Livestock -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Types of Livestock <span class="text-red-500">*</span>
                        </label>
                        <p class="text-sm text-gray-600 mb-4">Select all that apply</p>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @php
                                $livestockTypes = [
                                    'cattle' => '🐄 Cattle',
                                    'goats' => '🐐 Goats',
                                    'sheep' => '🐑 Sheep',
                                    'poultry' => '🐔 Poultry',
                                    'pigs' => '🐖 Pigs',
                                    'rabbits' => '🐰 Rabbits',
                                    'fish' => '🐟 Fish',
                                    'other' => '🦆 Other'
                                ];
                                $selectedTypes = old('livestock_types', $formData['step2']['livestock_types'] ?? []);
                            @endphp

                            @foreach($livestockTypes as $key => $label)
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array($key, (array)$selectedTypes) ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input type="checkbox" name="livestock_types[]" value="{{ $key }}"
                                    {{ in_array($key, (array)$selectedTypes) ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm font-medium text-gray-900">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('livestock_types')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total Livestock Count -->
                    <div class="mb-8">
                        <label for="total_livestock_count" class="block text-sm font-medium text-gray-700 mb-1">
                            Total Number of Animals <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="total_livestock_count" id="total_livestock_count" required min="1"
                            value="{{ old('total_livestock_count', $formData['step2']['total_livestock_count'] ?? '') }}"
                            class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="e.g., 25">
                        <p class="mt-1 text-xs text-gray-500">Total count across all livestock types</p>
                        @error('total_livestock_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Vaccination Status -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Overall Vaccination Status <span class="text-red-500">*</span>
                        </label>
                        <p class="text-sm text-gray-600 mb-4">What's the general vaccination status of your livestock?</p>
                        
                        <div class="space-y-3">
                            @php
                                $vaccinationStatuses = [
                                    'up_to_date' => ['label' => 'Up-to-date', 'desc' => 'All animals have received required vaccinations', 'color' => 'green'],
                                    'partially_vaccinated' => ['label' => 'Partially Vaccinated', 'desc' => 'Some animals vaccinated, others pending', 'color' => 'yellow'],
                                    'not_vaccinated' => ['label' => 'Not Vaccinated', 'desc' => 'No animals have been vaccinated', 'color' => 'red']
                                ];
                                $selectedStatus = old('vaccination_status', $formData['step2']['vaccination_status'] ?? '');
                            @endphp

                            @foreach($vaccinationStatuses as $key => $info)
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ $selectedStatus == $key ? 'border-'.$info['color'].'-500 bg-'.$info['color'].'-50' : 'border-gray-200' }}">
                                <input type="radio" name="vaccination_status" value="{{ $key }}" required
                                    {{ $selectedStatus == $key ? 'checked' : '' }}
                                    class="h-4 w-4 text-{{ $info['color'] }}-600 focus:ring-{{ $info['color'] }}-500 border-gray-300 mt-0.5">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $info['label'] }}</div>
                                    <div class="text-xs text-gray-600">{{ $info['desc'] }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('vaccination_status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Common Diseases Experienced -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Common Diseases Experienced (Optional)
                        </label>
                        <p class="text-sm text-gray-600 mb-4">Select any diseases your livestock have experienced</p>
                        
                        <div class="space-y-2">
                            @php
                                $diseases = [
                                    'fmd' => 'Foot-and-Mouth Disease (FMD)',
                                    'newcastle' => 'Newcastle Disease',
                                    'anthrax' => 'Anthrax',
                                    'cbpp' => 'Contagious Bovine Pleuropneumonia (CBPP)',
                                    'lsd' => 'Lumpy Skin Disease (LSD)',
                                    'ppr' => 'Peste des Petits Ruminants (PPR)',
                                    'fasciolosis' => 'Fasciolosis',
                                    'trypanosomosis' => 'Trypanosomosis',
                                    'mastitis' => 'Mastitis',
                                    'brucellosis' => 'Brucellosis'
                                ];
                                $selectedDiseases = old('common_diseases', $formData['step2']['common_diseases'] ?? []);
                            @endphp

                            @foreach($diseases as $key => $label)
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array($key, (array)$selectedDiseases) ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                <input type="checkbox" name="common_diseases[]" value="{{ $key }}"
                                    {{ in_array($key, (array)$selectedDiseases) ? 'checked' : '' }}
                                    class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm text-gray-900">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Other Diseases (Text) -->
                    <div class="mb-8">
                        <label for="other_diseases_text" class="block text-sm font-medium text-gray-700 mb-1">
                            Other Diseases Not Listed Above (Optional)
                        </label>
                        <textarea name="other_diseases_text" id="other_diseases_text" rows="3"
                            placeholder="Describe any other diseases or health issues..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('other_diseases_text', $formData['step2']['other_diseases_text'] ?? '') }}</textarea>
                        @error('other_diseases_text')
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
                                    <strong>Why we need this:</strong> Disease tracking helps us predict outbreaks, improve vaccination campaigns, and provide timely alerts to your area.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('individual.farm-records.step', ['step' => 1]) }}" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Next: Services & Alerts
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