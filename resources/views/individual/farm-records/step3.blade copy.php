<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Farm Data - Step 3 - FarmVax</title>
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
                    <span class="text-sm font-medium text-green-600">Step 3 of 3 - Final Step!</span>
                    <span class="text-sm font-medium text-green-600">100% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 100%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Personal Info</span>
                    <span class="text-green-600">✓ Livestock Data</span>
                    <span class="font-medium text-green-600">Services</span>
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
                        <p class="mt-1 text-sm text-green-700">One final step to customize your service needs and alert preferences.</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 3: Services & Alert Preferences</h2>
                    <p class="mt-1 text-sm text-gray-600">Customize how we can help and communicate with you</p>
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

                <form method="POST" action="{{ route('individual.farm-records.submit') }}" class="px-6 py-8">
                    @csrf

                    <!-- Preferred Veterinary Services -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Preferred Veterinary Services <span class="text-red-500">*</span>
                        </label>
                        <p class="text-sm text-gray-600 mb-4">Select all that apply</p>
                        
                        <div class="space-y-3">
                            @php
                                $services = [
                                    'on_site_visits' => 'On-site Visits - Vet comes to my farm',
                                    'mobile_clinics' => 'Mobile Clinics - Visit regional mobile vet clinics',
                                    'tele_vet' => 'Tele-vet Consultations - Remote consultations via phone/video'
                                ];
                                $selectedServices = old('preferred_services', $formData['step3']['preferred_services'] ?? []);
                            @endphp

                            @foreach($services as $key => $label)
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array($key, (array)$selectedServices) ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input type="checkbox" name="preferred_services[]" value="{{ $key }}"
                                    {{ in_array($key, (array)$selectedServices) ? 'checked' : '' }}
                                    class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-0.5">
                                <span class="ml-3 text-sm text-gray-900">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('preferred_services')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cold Chain Access -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Access to Cold Chain Facilities <span class="text-red-500">*</span>
                        </label>
                        <p class="text-sm text-gray-600 mb-4">Do you have access to cold storage for vaccine storage?</p>
                        
                        <div class="flex space-x-4">
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 flex-1 {{ old('has_cold_chain', $formData['step3']['has_cold_chain'] ?? '') == '1' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input type="radio" name="has_cold_chain" value="1" required
                                    {{ old('has_cold_chain', $formData['step3']['has_cold_chain'] ?? '') == '1' ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-900">Yes, I have access</span>
                            </label>
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 flex-1 {{ old('has_cold_chain', $formData['step3']['has_cold_chain'] ?? '') == '0' ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input type="radio" name="has_cold_chain" value="0" required
                                    {{ old('has_cold_chain', $formData['step3']['has_cold_chain'] ?? '') == '0' ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-900">No, I don't have access</span>
                            </label>
                        </div>
                        @error('has_cold_chain')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Training & Awareness Needs -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Training & Awareness Needs
                        </label>
                        <p class="text-sm text-gray-600 mb-4">What topics would you like to learn more about?</p>
                        
                        <div class="space-y-3">
                            @php
                                $trainingTopics = [
                                    'disease_prevention' => 'Disease Prevention & Control',
                                    'animal_nutrition' => 'Animal Nutrition & Feeding',
                                    'biosecurity' => 'Biosecurity Practices',
                                    'breeding' => 'Breeding & Reproduction',
                                    'record_keeping' => 'Record Keeping & Farm Management'
                                ];
                                $selectedTraining = old('training_needs', $formData['step3']['training_needs'] ?? []);
                            @endphp

                            @foreach($trainingTopics as $key => $label)
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 {{ in_array($key, (array)$selectedTraining) ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input type="checkbox" name="training_needs[]" value="{{ $key }}"
                                    {{ in_array($key, (array)$selectedTraining) ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm text-gray-900">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Alert Preferences -->
                    <div class="mb-8 p-5 bg-yellow-50 rounded-lg border-2 border-yellow-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">📢 Alert Preferences</h3>
                        <p class="text-sm text-gray-600 mb-4">Choose how you'd like to receive important updates</p>
                        
                        <div class="space-y-4">
                            
                            <!-- Outbreak Alerts -->
                            <label class="flex items-start p-3 bg-white border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('alerts_outbreak', $formData['step3']['alerts_outbreak'] ?? true) ? 'border-red-500' : 'border-gray-200' }}">
                                <input type="checkbox" name="alerts_outbreak" value="1"
                                    {{ old('alerts_outbreak', $formData['step3']['alerts_outbreak'] ?? true) ? 'checked' : '' }}
                                    class="h-5 w-5 text-red-600 focus:ring-red-500 border-gray-300 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-900">🚨 Outbreak Alerts</span>
                                    <p class="text-xs text-gray-600 mt-1">Immediate SMS/WhatsApp notifications about disease outbreaks in your area</p>
                                </div>
                            </label>

                            <!-- Vaccine Availability -->
                            <label class="flex items-start p-3 bg-white border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('alerts_vaccine', $formData['step3']['alerts_vaccine'] ?? true) ? 'border-blue-500' : 'border-gray-200' }}">
                                <input type="checkbox" name="alerts_vaccine" value="1"
                                    {{ old('alerts_vaccine', $formData['step3']['alerts_vaccine'] ?? true) ? 'checked' : '' }}
                                    class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-900">💉 Vaccine Availability Alerts</span>
                                    <p class="text-xs text-gray-600 mt-1">Location-based updates when vaccines arrive nearby</p>
                                </div>
                            </label>

                            <!-- Awareness Campaigns -->
                            <label class="flex items-start p-3 bg-white border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('alerts_awareness', $formData['step3']['alerts_awareness'] ?? true) ? 'border-green-500' : 'border-gray-200' }}">
                                <input type="checkbox" name="alerts_awareness" value="1"
                                    {{ old('alerts_awareness', $formData['step3']['alerts_awareness'] ?? true) ? 'checked' : '' }}
                                    class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-900">📚 Awareness Campaigns</span>
                                    <p class="text-xs text-gray-600 mt-1">Invitations to workshops, radio programs, or online webinars</p>
                                </div>
                            </label>

                            <!-- Public Announcements -->
                            <label class="flex items-start p-3 bg-white border-2 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('alerts_public', $formData['step3']['alerts_public'] ?? true) ? 'border-purple-500' : 'border-gray-200' }}">
                                <input type="checkbox" name="alerts_public" value="1"
                                    {{ old('alerts_public', $formData['step3']['alerts_public'] ?? true) ? 'checked' : '' }}
                                    class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-0.5">
                                <div class="ml-3">
                                    <span class="text-sm font-semibold text-gray-900">📣 Public Announcements</span>
                                    <p class="text-xs text-gray-600 mt-1">Government/NGO directives, market closures, movement restrictions</p>
                                </div>
                            </label>

                        </div>
                    </div>

                    <!-- Preferred Language -->
                    <div class="mb-8">
                        <label for="preferred_language" class="block text-sm font-medium text-gray-700 mb-1">
                            Preferred Language <span class="text-red-500">*</span>
                        </label>
                        <select name="preferred_language" id="preferred_language" required
                            class="w-full md:w-1/2 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select language...</option>
                            <option value="english" {{ old('preferred_language', $formData['step3']['preferred_language'] ?? 'english') == 'english' ? 'selected' : '' }}>English</option>
                            <option value="hausa" {{ old('preferred_language', $formData['step3']['preferred_language'] ?? '') == 'hausa' ? 'selected' : '' }}>Hausa</option>
                            <option value="yoruba" {{ old('preferred_language', $formData['step3']['preferred_language'] ?? '') == 'yoruba' ? 'selected' : '' }}>Yoruba</option>
                            <option value="igbo" {{ old('preferred_language', $formData['step3']['preferred_language'] ?? '') == 'igbo' ? 'selected' : '' }}>Igbo</option>
                            <option value="fulfulde" {{ old('preferred_language', $formData['step3']['preferred_language'] ?? '') == 'fulfulde' ? 'selected' : '' }}>Fulfulde</option>
                            <option value="french" {{ old('preferred_language', $formData['step3']['preferred_language'] ?? '') == 'french' ? 'selected' : '' }}>French</option>
                            <option value="swahili" {{ old('preferred_language', $formData['step3']['preferred_language'] ?? '') == 'swahili' ? 'selected' : '' }}>Swahili</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Language for notifications and communications</p>
                        @error('preferred_language')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Feedback & Comments -->
                    <div class="mb-8">
                        <label for="feedback" class="block text-sm font-medium text-gray-700 mb-1">
                            Feedback & Comments (Optional)
                        </label>
                        <textarea name="feedback" id="feedback" rows="4"
                            placeholder="Any feedback about veterinary services or suggestions for improvement..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('feedback', $formData['step3']['feedback'] ?? '') }}</textarea>
                    </div>

                    <!-- Consent -->
                    <div class="mb-8 p-5 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-start">
                            <input type="checkbox" name="consent_data_use" id="consent_data_use" value="1" required
                                {{ old('consent_data_use', $formData['step3']['consent_data_use'] ?? false) ? 'checked' : '' }}
                                class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-0.5">
                            <label for="consent_data_use" class="ml-3 text-sm text-gray-700">
                                <span class="font-semibold text-gray-900">Data Use Consent <span class="text-red-500">*</span></span>
                                <p class="mt-1">I consent to FarmVax using my data to:</p>
                                <ul class="mt-2 ml-4 space-y-1 text-xs text-gray-600">
                                    <li>• Monitor livestock health trends in my region</li>
                                    <li>• Send disease outbreak alerts and updates</li>
                                    <li>• Improve veterinary service delivery</li>
                                    <li>• Share anonymized data with agricultural authorities</li>
                                </ul>
                                <p class="mt-2 text-xs text-gray-500">Your personal information will be kept confidential and secure.</p>
                            </label>
                        </div>
                        @error('consent_data_use')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Final Submission Notice -->
                    <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300 rounded-lg">
                        <div class="flex items-start">
                            <svg class="h-8 w-8 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="ml-4 flex-1">
                                <h4 class="text-base font-semibold text-gray-900">Ready to Submit?</h4>
                                <p class="mt-1 text-sm text-gray-700">Thank you for sharing your farm data! This information will help:</p>
                                <ul class="mt-2 text-sm text-gray-700 list-disc list-inside space-y-1">
                                    <li>Rapid outbreak response in your area</li>
                                    <li>Better vaccine distribution and availability</li>
                                    <li>Improved veterinary services</li>
                                    <li>Data-driven agricultural policies</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('individual.farm-records.step', ['step' => 2]) }}" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </a>
                        <button type="submit" 
                            class="inline-flex items-center px-10 py-4 border border-transparent text-xl font-bold rounded-md text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 shadow-lg hover:shadow-xl transition-all duration-200">
                            <svg class="h-7 w-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            SUBMIT FARM DATA
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</body>
</html>