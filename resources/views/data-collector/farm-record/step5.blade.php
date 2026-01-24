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
                    <span class="text-sm font-medium text-blue-600">Step 5 of 6</span>
                    <span class="text-sm font-medium text-gray-500">83% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 83%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Stakeholder Info</span>
                    <span class="text-green-600">✓ Livestock Profile</span>
                    <span class="text-green-600">✓ Health & Vaccination</span>
                    <span class="text-green-600">✓ Service Needs</span>
                    <span class="font-medium text-blue-600">Alert Preferences</span>
                    <span>Consent</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 5: Alert Preferences</h2>
                    <p class="mt-1 text-sm text-gray-600">How would you like to receive outbreak alerts and notifications?</p>
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

                <form method="POST" action="{{ route('data-collector.farm-record.save-step', ['step' => 5]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Alert Channels -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Preferred Alert Channels
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">Select how you want to receive alerts (you can choose multiple):</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <!-- SMS Alerts -->
                            <label class="relative flex flex-col items-center p-6 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('sms_alerts', $farmRecordData['step5']['sms_alerts'] ?? false) ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="sms_alerts" 
                                    value="1"
                                    {{ old('sms_alerts', $farmRecordData['step5']['sms_alerts'] ?? false) ? 'checked' : '' }}
                                    class="sr-only"
                                >
                                <svg class="h-12 w-12 text-green-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                <span class="text-base font-semibold text-gray-900">SMS Alerts</span>
                                <span class="text-xs text-gray-600 mt-1 text-center">Immediate text messages</span>
                                @if(old('sms_alerts', $farmRecordData['step5']['sms_alerts'] ?? false))
                                <div class="absolute top-2 right-2">
                                    <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </label>

                            <!-- Email Alerts -->
                            <label class="relative flex flex-col items-center p-6 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('email_alerts', $farmRecordData['step5']['email_alerts'] ?? false) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="email_alerts" 
                                    value="1"
                                    {{ old('email_alerts', $farmRecordData['step5']['email_alerts'] ?? false) ? 'checked' : '' }}
                                    class="sr-only"
                                >
                                <svg class="h-12 w-12 text-blue-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-base font-semibold text-gray-900">Email Alerts</span>
                                <span class="text-xs text-gray-600 mt-1 text-center">Detailed email notifications</span>
                                @if(old('email_alerts', $farmRecordData['step5']['email_alerts'] ?? false))
                                <div class="absolute top-2 right-2">
                                    <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </label>

                            <!-- Phone Call Alerts -->
                            <label class="relative flex flex-col items-center p-6 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('phone_alerts', $farmRecordData['step5']['phone_alerts'] ?? false) ? 'border-purple-500 bg-purple-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="phone_alerts" 
                                    value="1"
                                    {{ old('phone_alerts', $farmRecordData['step5']['phone_alerts'] ?? false) ? 'checked' : '' }}
                                    class="sr-only"
                                >
                                <svg class="h-12 w-12 text-purple-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <span class="text-base font-semibold text-gray-900">Phone Calls</span>
                                <span class="text-xs text-gray-600 mt-1 text-center">Voice call notifications</span>
                                @if(old('phone_alerts', $farmRecordData['step5']['phone_alerts'] ?? false))
                                <div class="absolute top-2 right-2">
                                    <svg class="h-6 w-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </label>

                        </div>

                        @error('sms_alerts')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Types of Alerts -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Types of Alerts to Receive
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">Choose which types of alerts you want to receive:</p>

                        @php
                            $alertTypes = [
                                'outbreak_alerts' => ['name' => 'Outbreak Alerts', 'desc' => 'Disease outbreaks in your area', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'red'],
                                'vaccine_availability' => ['name' => 'Vaccine Availability', 'desc' => 'When vaccines are available nearby', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'color' => 'green'],
                                'awareness_campaigns' => ['name' => 'Awareness Campaigns', 'desc' => 'Educational programs and workshops', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'blue'],
                                'public_announcements' => ['name' => 'Public Announcements', 'desc' => 'Government directives and updates', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'color' => 'yellow'],
                            ];
                            $selectedAlertTypes = old('alert_types', $farmRecordData['step5']['alert_types'] ?? []);
                        @endphp

                        <div class="space-y-3">
                            @foreach($alertTypes as $key => $alert)
                            <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ in_array($key, $selectedAlertTypes) ? 'border-' . $alert['color'] . '-500 bg-' . $alert['color'] . '-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="alert_types[]" 
                                    value="{{ $key }}"
                                    {{ in_array($key, $selectedAlertTypes) ? 'checked' : '' }}
                                    class="h-5 w-5 text-{{ $alert['color'] }}-600 focus:ring-{{ $alert['color'] }}-500 border-gray-300 rounded mt-0.5"
                                >
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-{{ $alert['color'] }}-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $alert['icon'] }}" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-900">{{ $alert['name'] }}</span>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-1 ml-7">{{ $alert['desc'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        @error('alert_types')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Contact Method -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Preferred Contact Method
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">What's the best way to reach you?</p>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @php
                                $contactMethods = [
                                    'sms' => ['name' => 'SMS', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                                    'email' => ['name' => 'Email', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                                    'phone' => ['name' => 'Phone', 'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                                    'whatsapp' => ['name' => 'WhatsApp', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                                ];
                                $selectedMethod = old('preferred_contact_method', $farmRecordData['step5']['preferred_contact_method'] ?? '');
                            @endphp

                            @foreach($contactMethods as $key => $method)
                            <label class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ $selectedMethod === $key ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-500' : 'border-gray-200' }}">
                                <input 
                                    type="radio" 
                                    name="preferred_contact_method" 
                                    value="{{ $key }}"
                                    {{ $selectedMethod === $key ? 'checked' : '' }}
                                    class="sr-only"
                                >
                                <svg class="h-8 w-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $method['icon'] }}" />
                                </svg>
                                <span class="text-sm font-medium text-gray-900">{{ $method['name'] }}</span>
                                @if($selectedMethod === $key)
                                <div class="absolute top-2 right-2">
                                    <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </label>
                            @endforeach
                        </div>

                        @error('preferred_contact_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alternative Phone Number -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Alternative Contact
                        </h3>

                        <div class="max-w-md">
                            <label for="alternative_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                Alternative Phone Number (Optional)
                            </label>
                            <input 
                                type="tel" 
                                name="alternative_phone" 
                                id="alternative_phone" 
                                value="{{ old('alternative_phone', $farmRecordData['step5']['alternative_phone'] ?? '') }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="+234 123 456 7890"
                            >
                            <p class="mt-1 text-xs text-gray-500">Backup contact number in case primary is unreachable</p>
                            @error('alternative_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a 
                                href="{{ route('data-collector.farm-record.step', ['step' => 4]) }}" 
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
                            type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Next: Feedback & Consent
                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <!-- Save Draft Script -->
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
    </script>

</body>
</html>