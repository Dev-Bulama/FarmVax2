<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step X: [Title] - FarmVax</title>
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
                    <span class="text-sm font-medium text-blue-600">Step 6 of 6 - Final Step!</span>
                    <span class="text-sm font-medium text-green-600">100% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 100%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Stakeholder Info</span>
                    <span class="text-green-600">✓ Livestock Profile</span>
                    <span class="text-green-600">✓ Health & Vaccination</span>
                    <span class="text-green-600">✓ Service Needs</span>
                    <span class="text-green-600">✓ Alert Preferences</span>
                    <span class="font-medium text-green-600">✓ Consent</span>
                </div>
            </div>

            <!-- Completion Notice -->
            <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Almost done!</h3>
                        <p class="mt-1 text-sm text-green-700">This is the final step. Review consent options and submit your farm record.</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 6: Feedback & Consent</h2>
                    <p class="mt-1 text-sm text-gray-600">Data usage consent and feedback</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

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

                <form method="POST" action="{{ route('data-collector.farm-record.save-step', ['step' => 6]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Data Usage Consent -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Data Usage Consent
                        </h3>

                        <p class="text-sm text-gray-600 mb-6">Please review and confirm how your data may be used:</p>

                        <div class="space-y-4">
                            
                            <!-- Data Sharing Consent -->
                            <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                                <label class="flex items-start cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="data_sharing_consent" 
                                        value="1"
                                        {{ old('data_sharing_consent', $farmRecordData['step6']['data_sharing_consent'] ?? false) ? 'checked' : '' }}
                                        class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-0.5"
                                    >
                                    <div class="ml-3 flex-1">
                                        <span class="block text-sm font-semibold text-gray-900">
                                            <svg class="inline h-5 w-5 text-blue-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                            </svg>
                                            Data Sharing for Veterinary Services
                                        </span>
                                        <p class="text-xs text-gray-700 mt-1">I consent to sharing my farm data with veterinary service providers and government agricultural departments to improve livestock health services and disease prevention programs.</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Research Participation Consent -->
                            <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-4">
                                <label class="flex items-start cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="research_participation_consent" 
                                        value="1"
                                        {{ old('research_participation_consent', $farmRecordData['step6']['research_participation_consent'] ?? false) ? 'checked' : '' }}
                                        class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-0.5"
                                    >
                                    <div class="ml-3 flex-1">
                                        <span class="block text-sm font-semibold text-gray-900">
                                            <svg class="inline h-5 w-5 text-purple-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                            Research Participation (Optional)
                                        </span>
                                        <p class="text-xs text-gray-700 mt-1">I agree to allow my anonymized data to be used for agricultural research studies aimed at improving livestock farming practices and disease management strategies.</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Marketing Consent -->
                            <div class="bg-green-50 border-2 border-green-200 rounded-lg p-4">
                                <label class="flex items-start cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="marketing_consent" 
                                        value="1"
                                        {{ old('marketing_consent', $farmRecordData['step6']['marketing_consent'] ?? false) ? 'checked' : '' }}
                                        class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-0.5"
                                    >
                                    <div class="ml-3 flex-1">
                                        <span class="block text-sm font-semibold text-gray-900">
                                            <svg class="inline h-5 w-5 text-green-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Promotional Communications (Optional)
                                        </span>
                                        <p class="text-xs text-gray-700 mt-1">I agree to receive promotional messages about livestock products, services, training opportunities, and special offers from FarmVax and partner organizations.</p>
                                    </div>
                                </label>
                            </div>

                        </div>

                        <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-xs text-gray-600">
                                <strong>Privacy Notice:</strong> Your personal information will be handled in accordance with data protection regulations. You can withdraw consent at any time by contacting us. Your data will be stored securely and never sold to third parties.
                            </p>
                        </div>
                    </div>

                    <!-- Feedback Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Feedback (Optional)
                        </h3>

                        <div class="mb-6">
                            <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">
                                Share Your Experience
                            </label>
                            <textarea 
                                name="feedback" 
                                id="feedback" 
                                rows="4"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="How was your experience using FarmVax? Any suggestions for improvement?"
                            >{{ old('feedback', $farmRecordData['step6']['feedback'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Your feedback helps us improve our services</p>
                            @error('feedback')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="additional_comments" class="block text-sm font-medium text-gray-700 mb-1">
                                Additional Comments
                            </label>
                            <textarea 
                                name="additional_comments" 
                                id="additional_comments" 
                                rows="3"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Any other information you'd like to share?"
                            >{{ old('additional_comments', $farmRecordData['step6']['additional_comments'] ?? '') }}</textarea>
                            @error('additional_comments')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Final Submission Notice -->
                    <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300 rounded-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-base font-semibold text-gray-900">Ready to Submit?</h4>
                                <p class="mt-1 text-sm text-gray-700">By clicking "Submit Farm Record" below, you confirm that:</p>
                                <ul class="mt-2 text-sm text-gray-700 list-disc list-inside space-y-1">
                                    <li>All information provided is accurate to the best of your knowledge</li>
                                    <li>You have reviewed all 6 steps of the farm record</li>
                                    <li>You understand how your data will be used based on your consent selections</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a 
                                href="{{ route('data-collector.farm-record.step', ['step' => 5]) }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </a>

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
                        </div>

                        <button 
                            type="button"
                            onclick="confirmSubmit()"
                            class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-bold rounded-md text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg hover:shadow-xl transition-all duration-200"
                        >
                            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Submit Farm Record
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <!-- Scripts -->
    <script>
        function saveDraft() {
            if (confirm('Save your progress as a draft? You can continue later.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("data-collector.farm-record.draft") }}';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function confirmSubmit() {
            if (confirm('Are you ready to submit this farm record? Once submitted, it will be sent for review.')) {
                // Create form and submit to final submission endpoint
                const mainForm = document.querySelector('form');
                
                // Create a new form for submission
                const submitForm = document.createElement('form');
                submitForm.method = 'POST';
                submitForm.action = '{{ route("data-collector.farm-record.submit") }}';
                
                // Copy CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                submitForm.appendChild(csrfInput);
                
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
    </script>

</body>
</html>