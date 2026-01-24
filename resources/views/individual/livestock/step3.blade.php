<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 3: Health Status - FarmVax</title>
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
                    <span class="text-sm font-medium text-green-600">Step 3 of 6</span>
                    <span class="text-sm font-medium text-gray-500">50% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 50%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Basic Info</span>
                    <span class="text-green-600">✓ Physical</span>
                    <span class="font-medium text-green-600">Health</span>
                    <span>Vaccination</span>
                    <span>Production</span>
                    <span>Origin</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 3: Health Status</h2>
                    <p class="mt-1 text-sm text-gray-600">Current health condition and veterinary information</p>
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

                <form method="POST" action="{{ route('individual.livestock.save-step', ['step' => 3]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Current Health Status Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Current Health Status</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Health Status -->
                            <div>
                                <label for="health_status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Health Status <span class="text-red-500">*</span>
                                </label>
                                <select name="health_status" id="health_status" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                    <option value="">Select Status</option>
                                    <option value="healthy" {{ old('health_status', $livestockData['step3']['health_status'] ?? '') == 'healthy' ? 'selected' : '' }}>Healthy</option>
                                    <option value="sick" {{ old('health_status', $livestockData['step3']['health_status'] ?? '') == 'sick' ? 'selected' : '' }}>Sick</option>
                                    <option value="recovering" {{ old('health_status', $livestockData['step3']['health_status'] ?? '') == 'recovering' ? 'selected' : '' }}>Recovering</option>
                                    <option value="deceased" {{ old('health_status', $livestockData['step3']['health_status'] ?? '') == 'deceased' ? 'selected' : '' }}>Deceased</option>
                                </select>
                            </div>

                            <!-- Last Health Check -->
                            <div>
                                <label for="last_health_check" class="block text-sm font-medium text-gray-700 mb-1">Last Health Check</label>
                                <input type="date" name="last_health_check" id="last_health_check"
                                    value="{{ old('last_health_check', $livestockData['step3']['last_health_check'] ?? '') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <p class="mt-1 text-xs text-gray-500">When was the last veterinary examination?</p>
                            </div>

                        </div>

                        <!-- Current Conditions -->
                        <div class="mt-6">
                            <label for="current_conditions" class="block text-sm font-medium text-gray-700 mb-1">Current Health Conditions</label>
                            <textarea name="current_conditions" id="current_conditions" rows="4"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Describe any current health issues, symptoms, or conditions...">{{ old('current_conditions', $livestockData['step3']['current_conditions'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Include symptoms, injuries, chronic conditions, etc.</p>
                        </div>
                    </div>

                    <!-- Veterinarian Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Veterinarian Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Veterinarian Name -->
                            <div>
                                <label for="veterinarian_name" class="block text-sm font-medium text-gray-700 mb-1">Veterinarian Name</label>
                                <input type="text" name="veterinarian_name" id="veterinarian_name"
                                    value="{{ old('veterinarian_name', $livestockData['step3']['veterinarian_name'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="Dr. John Smith">
                                <p class="mt-1 text-xs text-gray-500">Regular veterinarian or clinic</p>
                            </div>

                            <!-- Veterinarian Phone -->
                            <div>
                                <label for="veterinarian_phone" class="block text-sm font-medium text-gray-700 mb-1">Veterinarian Phone</label>
                                <input type="tel" name="veterinarian_phone" id="veterinarian_phone"
                                    value="{{ old('veterinarian_phone', $livestockData['step3']['veterinarian_phone'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="+234 123 456 7890">
                            </div>

                        </div>
                    </div>

                    <!-- Quarantine Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Quarantine Status</h3>
                        
                        <div class="flex items-start">
                            <input type="checkbox" name="quarantine_status" id="quarantine_status" value="1"
                                {{ old('quarantine_status', $livestockData['step3']['quarantine_status'] ?? false) ? 'checked' : '' }}
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-1">
                            <label for="quarantine_status" class="ml-3 block text-sm text-gray-700">
                                <span class="font-medium">Animal is currently under quarantine</span>
                                <p class="text-xs text-gray-500 mt-1">Check this if the animal is isolated due to illness or disease prevention</p>
                            </label>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">
                                        <strong>Health Tip:</strong> Regular health checks help detect problems early. Keep detailed records of any symptoms, treatments, and veterinary visits.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('individual.livestock.step', ['step' => 2]) }}" 
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
                            Next: Vaccination History
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
    </script>
</body>
</html>