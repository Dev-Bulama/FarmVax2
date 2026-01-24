<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 6: Origin & Documents - FarmVax</title>
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
                    <span class="text-sm font-medium text-green-600">Step 6 of 6 - Final Step!</span>
                    <span class="text-sm font-medium text-green-600">100% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 100%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Basic Info</span>
                    <span class="text-green-600">✓ Physical</span>
                    <span class="text-green-600">✓ Health</span>
                    <span class="text-green-600">✓ Vaccination</span>
                    <span class="text-green-600">✓ Production</span>
                    <span class="font-medium text-green-600">✓ Origin</span>
                </div>
            </div>

            <!-- Completion Notice -->
            <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Almost done!</h3>
                        <p class="mt-1 text-sm text-green-700">This is the final step. Add origin information and submit your livestock registration.</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 6: Origin & Documents</h2>
                    <p class="mt-1 text-sm text-gray-600">How you acquired this animal</p>
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

                <form method="POST" action="{{ route('individual.livestock.save-step', ['step' => 6]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Acquisition Method Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">How Did You Acquire This Animal?</h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            @php
                                $methods = [
                                    'birth' => ['name' => 'Birth', 'icon' => 'M12 4v16m8-8H4', 'desc' => 'Born on farm'],
                                    'purchase' => ['name' => 'Purchase', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'desc' => 'Bought'],
                                    'gift' => ['name' => 'Gift', 'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7', 'desc' => 'Received as gift'],
                                    'inheritance' => ['name' => 'Inheritance', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'desc' => 'Inherited'],
                                    'other' => ['name' => 'Other', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'desc' => 'Other method'],
                                ];
                                $selectedMethod = old('acquisition_method', $livestockData['step6']['acquisition_method'] ?? '');
                            @endphp

                            @foreach($methods as $key => $method)
                            <label class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ $selectedMethod === $key ? 'border-green-500 bg-green-50 ring-2 ring-green-500' : 'border-gray-200' }}">
                                <input type="radio" name="acquisition_method" value="{{ $key }}"
                                    {{ $selectedMethod === $key ? 'checked' : '' }}
                                    onchange="togglePurchaseFields()"
                                    class="sr-only">
                                <svg class="h-8 w-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $method['icon'] }}" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900">{{ $method['name'] }}</span>
                                <span class="text-xs text-gray-500 text-center mt-1">{{ $method['desc'] }}</span>
                                @if($selectedMethod === $key)
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

                    <!-- Acquisition Details Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Acquisition Details</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Acquisition Date -->
                            <div>
                                <label for="acquisition_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Acquisition Date
                                </label>
                                <input type="date" name="acquisition_date" id="acquisition_date"
                                    value="{{ old('acquisition_date', $livestockData['step6']['acquisition_date'] ?? '') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <p class="mt-1 text-xs text-gray-500">When did you acquire this animal?</p>
                            </div>

                            <!-- Purchase Price (conditional) -->
                            <div id="purchasePriceField" class="hidden">
                                <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-1">
                                    Purchase Price (NGN)
                                </label>
                                <input type="number" name="purchase_price" id="purchase_price"
                                    value="{{ old('purchase_price', $livestockData['step6']['purchase_price'] ?? '') }}"
                                    min="0" step="0.01"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., 50000">
                                <p class="mt-1 text-xs text-gray-500">How much did you pay for this animal?</p>
                            </div>

                        </div>

                        <!-- Previous Owner (conditional) -->
                        <div id="previousOwnerField" class="hidden mt-6">
                            <label for="previous_owner" class="block text-sm font-medium text-gray-700 mb-1">
                                Previous Owner / Seller Name
                            </label>
                            <input type="text" name="previous_owner" id="previous_owner"
                                value="{{ old('previous_owner', $livestockData['step6']['previous_owner'] ?? '') }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Name of previous owner or seller">
                        </div>
                    </div>

                    <!-- Additional Notes Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Additional Notes</h3>
                        
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="5"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="Any additional information about this animal, special characteristics, history, behavioral notes, etc...">{{ old('notes', $livestockData['step6']['notes'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Include any other relevant information about this animal</p>
                        </div>
                    </div>

                    <!-- Final Submission Notice -->
                    <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-8 w-8 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-4 flex-1">
                                <h4 class="text-base font-semibold text-gray-900">Ready to Submit?</h4>
                                <p class="mt-1 text-sm text-gray-700">By clicking "Register Livestock" below, you confirm that:</p>
                                <ul class="mt-2 text-sm text-gray-700 list-disc list-inside space-y-1">
                                    <li>All information provided is accurate to the best of your knowledge</li>
                                    <li>You have completed all 6 steps of the registration</li>
                                    <li>This animal is under your ownership and care</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('individual.livestock.step', ['step' => 5]) }}" 
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
                        <button type="button" onclick="confirmSubmit()" 
                            class="inline-flex items-center px-10 py-4 border border-transparent text-xl font-bold rounded-md text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="h-7 w-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            REGISTER LIVESTOCK
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePurchaseFields() {
            const selectedMethod = document.querySelector('input[name="acquisition_method"]:checked');
            const purchasePriceField = document.getElementById('purchasePriceField');
            const previousOwnerField = document.getElementById('previousOwnerField');
            
            if (selectedMethod) {
                const value = selectedMethod.value;
                
                // Show purchase price for 'purchase' method
                if (value === 'purchase') {
                    purchasePriceField.classList.remove('hidden');
                    previousOwnerField.classList.remove('hidden');
                } else if (value === 'gift' || value === 'inheritance') {
                    purchasePriceField.classList.add('hidden');
                    previousOwnerField.classList.remove('hidden');
                } else {
                    purchasePriceField.classList.add('hidden');
                    previousOwnerField.classList.add('hidden');
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

        function confirmSubmit() {
            if (confirm('Are you ready to register this livestock? Once submitted, the animal will be added to your records.')) {
                // Submit to the final submission endpoint
                const mainForm = document.querySelector('form');
                
                // Create submission form
                const submitForm = document.createElement('form');
                submitForm.method = 'POST';
                submitForm.action = '{{ route("individual.livestock.submit") }}';
                
                // Add CSRF token
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                submitForm.appendChild(csrf);
                
                // Copy all form data
                const formData = new FormData(mainForm);
                formData.forEach((value, key) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    submitForm.appendChild(input);
                });
                
                document.body.appendChild(submitForm);
                submitForm.submit();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePurchaseFields();
        });
    </script>
</body>
</html>