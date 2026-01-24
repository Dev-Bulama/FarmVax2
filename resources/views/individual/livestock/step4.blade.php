<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 4: Vaccination History - FarmVax</title>
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
                    <span class="text-sm font-medium text-green-600">Step 4 of 6</span>
                    <span class="text-sm font-medium text-gray-500">67% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 67%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Basic Info</span>
                    <span class="text-green-600">✓ Physical</span>
                    <span class="text-green-600">✓ Health</span>
                    <span class="font-medium text-green-600">Vaccination</span>
                    <span>Production</span>
                    <span>Origin</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 4: Vaccination History</h2>
                    <p class="mt-1 text-sm text-gray-600">Vaccination records and immunization status</p>
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

                <form method="POST" action="{{ route('individual.livestock.save-step', ['step' => 4]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Vaccination Status Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Vaccination Status</h3>
                        
                        <!-- Is Vaccinated Toggle -->
                        <div class="mb-6 p-5 bg-green-50 rounded-lg border-2 border-green-200">
                            <div class="flex items-start">
                                <input type="checkbox" name="is_vaccinated" id="is_vaccinated" value="1"
                                    {{ old('is_vaccinated', $livestockData['step4']['is_vaccinated'] ?? false) ? 'checked' : '' }}
                                    onchange="toggleVaccinationDetails()"
                                    class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-1">
                                <label for="is_vaccinated" class="ml-3 block">
                                    <span class="text-base font-semibold text-gray-900">
                                        <svg class="inline h-6 w-6 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        This animal has been vaccinated
                                    </span>
                                    <p class="text-sm text-gray-600 mt-1">Check this box if the animal has received any vaccinations</p>
                                </label>
                            </div>
                        </div>

                        <!-- Vaccination Details (shown when checkbox is checked) -->
                        <div id="vaccinationDetails" class="hidden space-y-6">
                            
                            <!-- Last Vaccination Date -->
                            <div>
                                <label for="last_vaccination_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Last Vaccination Date
                                </label>
                                <input type="date" name="last_vaccination_date" id="last_vaccination_date"
                                    value="{{ old('last_vaccination_date', $livestockData['step4']['last_vaccination_date'] ?? '') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="block w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <p class="mt-1 text-xs text-gray-500">Date of the most recent vaccination</p>
                            </div>

                            <!-- Total Vaccinations -->
                            <div>
                                <label for="total_vaccinations" class="block text-sm font-medium text-gray-700 mb-1">
                                    Total Number of Vaccinations Received
                                </label>
                                <input type="number" name="total_vaccinations" id="total_vaccinations"
                                    value="{{ old('total_vaccinations', $livestockData['step4']['total_vaccinations'] ?? '') }}"
                                    min="0"
                                    class="block w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., 3">
                                <p class="mt-1 text-xs text-gray-500">How many times has this animal been vaccinated?</p>
                            </div>

                        </div>
                    </div>

                    <!-- Upcoming Vaccinations Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Upcoming Vaccinations</h3>
                        
                        <div class="mb-4">
                            <label class="flex items-start">
                                <input type="checkbox" name="has_due_vaccinations" id="has_due_vaccinations" value="1"
                                    {{ old('has_due_vaccinations', $livestockData['step4']['has_due_vaccinations'] ?? false) ? 'checked' : '' }}
                                    onchange="toggleDueVaccinations()"
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-1">
                                <span class="ml-3 text-sm text-gray-700">
                                    <span class="font-medium">There are upcoming vaccinations due</span>
                                    <p class="text-xs text-gray-500 mt-1">Check if this animal needs vaccinations soon</p>
                                </span>
                            </label>
                        </div>

                        <!-- Due Vaccinations Details -->
                        <div id="dueVaccinationsDetails" class="hidden mt-4">
                            <label for="next_vaccination_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Next Vaccination Due Date
                            </label>
                            <input type="date" name="next_vaccination_date" id="next_vaccination_date"
                                value="{{ old('next_vaccination_date', $livestockData['step4']['next_vaccination_date'] ?? '') }}"
                                min="{{ date('Y-m-d') }}"
                                class="block w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                            <p class="mt-1 text-xs text-gray-500">When is the next vaccination scheduled?</p>
                        </div>
                    </div>

                    <!-- Vaccination Notes -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Additional Vaccination Information</h3>
                        
                        <div>
                            <label for="vaccination_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Vaccination Notes (Optional)
                            </label>
                            <textarea name="vaccination_notes" id="vaccination_notes" rows="4"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="List specific vaccines received, veterinarian details, batch numbers, or any reactions observed...">{{ old('vaccination_notes', $livestockData['step4']['vaccination_notes'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Include vaccine names, veterinarian info, or any special notes</p>
                        </div>
                    </div>

                    <!-- Information Box -->
                    <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    <strong>Vaccination Reminder:</strong> Keep your animals protected! Regular vaccinations prevent diseases and keep your livestock healthy. We'll send you reminders for upcoming vaccinations.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('individual.livestock.step', ['step' => 3]) }}" 
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
                            Next: Production & Purpose
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
        function toggleVaccinationDetails() {
            const checkbox = document.getElementById('is_vaccinated');
            const details = document.getElementById('vaccinationDetails');
            details.classList.toggle('hidden', !checkbox.checked);
        }

        function toggleDueVaccinations() {
            const checkbox = document.getElementById('has_due_vaccinations');
            const details = document.getElementById('dueVaccinationsDetails');
            details.classList.toggle('hidden', !checkbox.checked);
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
            toggleVaccinationDetails();
            toggleDueVaccinations();
        });
    </script>
</body>
</html>