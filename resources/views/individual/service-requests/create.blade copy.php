<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Service - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('individual.dashboard') }}" class="text-green-600 hover:text-green-700 text-sm font-medium mb-4 inline-block">
                    ← Back to Dashboard
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Request Veterinary Service</h1>
                <p class="mt-2 text-sm text-gray-600">Submit a request for veterinary care or consultation</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Service Request Details</h2>
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

                <form method="POST" action="{{ route('individual.service-requests.store') }}" class="px-6 py-8">
                    @csrf

                    <!-- Service Type -->
                    <div class="mb-6">
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-1">
                            Service Type <span class="text-red-500">*</span>
                        </label>
                        <select name="service_type" id="service_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select service type...</option>
                            <option value="vaccination" {{ old('service_type') == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                            <option value="health_checkup" {{ old('service_type') == 'health_checkup' ? 'selected' : '' }}>Health Checkup</option>
                            <option value="emergency_care" {{ old('service_type') == 'emergency_care' ? 'selected' : '' }}>Emergency Care</option>
                            <option value="breeding_consultation" {{ old('service_type') == 'breeding_consultation' ? 'selected' : '' }}>Breeding Consultation</option>
                            <option value="nutrition_advice" {{ old('service_type') == 'nutrition_advice' ? 'selected' : '' }}>Nutrition Advice</option>
                            <option value="disease_diagnosis" {{ old('service_type') == 'disease_diagnosis' ? 'selected' : '' }}>Disease Diagnosis</option>
                            <option value="surgery" {{ old('service_type') == 'surgery' ? 'selected' : '' }}>Surgery</option>
                            <option value="deworming" {{ old('service_type') == 'deworming' ? 'selected' : '' }}>Deworming</option>
                            <option value="pregnancy_check" {{ old('service_type') == 'pregnancy_check' ? 'selected' : '' }}>Pregnancy Check</option>
                            <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('service_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Select Livestock (Optional) -->
                    <div class="mb-6">
                        <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Select Livestock (Optional)
                        </label>
                        <select name="livestock_id" id="livestock_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">General service / No specific animal</option>
                            @if(isset($livestock) && $livestock->count() > 0)
                                @foreach($livestock as $animal)
                                <option value="{{ $animal->id }}" {{ old('livestock_id') == $animal->id ? 'selected' : '' }}>
                                    {{ $animal->display_name }} - {{ ucfirst($animal->livestock_type) }} 
                                    @if($animal->tag_number)
                                        (Tag: {{ $animal->tag_number }})
                                    @endif
                                </option>
                                @endforeach
                            @else
                                <option value="" disabled>No livestock registered yet</option>
                            @endif
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Select a specific animal if this service is for one of your livestock</p>
                        @error('livestock_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="5" required
                            placeholder="Please describe the issue, symptoms, or what service you need in detail..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Minimum 10 characters. Be as detailed as possible to help us serve you better.</p>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority Level -->
                    <div class="mb-6">
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority Level <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('priority') == 'low' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input type="radio" name="priority" value="low" required
                                    {{ old('priority') == 'low' ? 'checked' : '' }}
                                    class="sr-only">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">🟢</div>
                                    <div class="text-sm font-medium text-gray-900">Low</div>
                                    <div class="text-xs text-gray-500">Routine care</div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('priority') == 'medium' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200' }}">
                                <input type="radio" name="priority" value="medium" required
                                    {{ old('priority') == 'medium' ? 'checked' : '' }}
                                    class="sr-only">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">🟡</div>
                                    <div class="text-sm font-medium text-gray-900">Medium</div>
                                    <div class="text-xs text-gray-500">Soon needed</div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('priority') == 'high' ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }}">
                                <input type="radio" name="priority" value="high" required
                                    {{ old('priority') == 'high' ? 'checked' : '' }}
                                    class="sr-only">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">🟠</div>
                                    <div class="text-sm font-medium text-gray-900">High</div>
                                    <div class="text-xs text-gray-500">Urgent</div>
                                </div>
                            </label>

                            <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('priority') == 'emergency' ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                <input type="radio" name="priority" value="emergency" required
                                    {{ old('priority') == 'emergency' ? 'checked' : '' }}
                                    class="sr-only">
                                <div class="text-center">
                                    <div class="text-2xl mb-1">🔴</div>
                                    <div class="text-sm font-medium text-gray-900">Emergency</div>
                                    <div class="text-xs text-gray-500">Immediate</div>
                                </div>
                            </label>
                        </div>
                        @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Date -->
                    <div class="mb-6">
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Preferred Date (Optional)
                        </label>
                        <input type="date" name="preferred_date" id="preferred_date"
                            value="{{ old('preferred_date') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <p class="mt-1 text-xs text-gray-500">When would you like the service to be provided?</p>
                        @error('preferred_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Phone -->
                    <div class="mb-6">
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Contact Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" required
                            value="{{ old('contact_phone', auth()->user()->phone ?? '') }}"
                            class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            placeholder="+234 123 456 7890">
                        <p class="mt-1 text-xs text-gray-500">We'll use this to contact you about the service</p>
                        @error('contact_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location/Address -->
                    <div class="mb-6">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                            Location/Address (Optional)
                        </label>
                        <textarea name="location" id="location" rows="3"
                            placeholder="Provide your farm location or address for on-site visits..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('location') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Helpful for veterinarians visiting your farm</p>
                        @error('location')
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
                                    <strong>What happens next?</strong> After you submit this request, our veterinary team will review it and contact you within 24 hours to schedule the service or provide guidance.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('individual.dashboard') }}" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Submit Request
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
        // Update border colors when radio buttons are selected
        document.querySelectorAll('input[name="priority"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="priority"]').forEach(r => {
                    const label = r.closest('label');
                    label.classList.remove('border-green-500', 'bg-green-50', 'border-yellow-500', 'bg-yellow-50', 'border-orange-500', 'bg-orange-50', 'border-red-500', 'bg-red-50');
                    label.classList.add('border-gray-200');
                });
                
                const label = this.closest('label');
                label.classList.remove('border-gray-200');
                
                if (this.value === 'low') {
                    label.classList.add('border-green-500', 'bg-green-50');
                } else if (this.value === 'medium') {
                    label.classList.add('border-yellow-500', 'bg-yellow-50');
                } else if (this.value === 'high') {
                    label.classList.add('border-orange-500', 'bg-orange-50');
                } else if (this.value === 'emergency') {
                    label.classList.add('border-red-500', 'bg-red-50');
                }
            });
        });
    </script>

</body>
</html>