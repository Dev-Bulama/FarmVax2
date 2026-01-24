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
                    <span class="text-sm font-medium text-blue-600">Step 4 of 6</span>
                    <span class="text-sm font-medium text-gray-500">67% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 67%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Stakeholder Info</span>
                    <span class="text-green-600">✓ Livestock Profile</span>
                    <span class="text-green-600">✓ Health & Vaccination</span>
                    <span class="font-medium text-blue-600">Service Needs</span>
                    <span>Alert Preferences</span>
                    <span>Consent</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 4: Service Needs</h2>
                    <p class="mt-1 text-sm text-gray-600">Veterinary services and assistance needed</p>
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

                <form method="POST" action="{{ route('data-collector.farm-record.save-step', ['step' => 4]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Preferred Veterinary Services -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Preferred Veterinary Services
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">Select all services needed:</p>

                        @php
                            $services = [
                                'on_site_visits' => ['name' => 'On-Site Visits', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                'mobile_clinics' => ['name' => 'Mobile Clinics', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z'],
                                'tele_vet' => ['name' => 'Tele-Vet Consultations', 'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                                'vaccination' => ['name' => 'Vaccination', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                                'disease_prevention' => ['name' => 'Disease Prevention', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                'animal_nutrition' => ['name' => 'Animal Nutrition', 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
                                'biosecurity' => ['name' => 'Biosecurity Training', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                                'emergency_care' => ['name' => 'Emergency Care', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                            ];
                            $selectedServices = old('service_needs', $farmRecordData['step4']['service_needs'] ?? []);
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($services as $key => $service)
                            <label class="relative flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ in_array($key, $selectedServices) ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="service_needs[]" 
                                    value="{{ $key }}"
                                    {{ in_array($key, $selectedServices) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1"
                                >
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}" />
                                        </svg>
                                        <span class="block text-sm font-medium text-gray-900">{{ $service['name'] }}</span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        @error('service_needs')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urgency Level -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Urgency Level
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @php
                                $urgencyLevels = [
                                    'low' => ['name' => 'Low', 'color' => 'green', 'desc' => 'Routine check-up'],
                                    'medium' => ['name' => 'Medium', 'color' => 'yellow', 'desc' => 'Schedule soon'],
                                    'high' => ['name' => 'High', 'color' => 'orange', 'desc' => 'Needs attention'],
                                    'emergency' => ['name' => 'Emergency', 'color' => 'red', 'desc' => 'Immediate help'],
                                ];
                                $selectedUrgency = old('urgency_level', $farmRecordData['step4']['urgency_level'] ?? '');
                            @endphp

                            @foreach($urgencyLevels as $key => $level)
                            <label class="relative flex flex-col p-4 border-2 rounded-lg cursor-pointer hover:shadow-md transition {{ $selectedUrgency === $key ? 'border-' . $level['color'] . '-500 bg-' . $level['color'] . '-50 ring-2 ring-' . $level['color'] . '-500' : 'border-gray-200' }}">
                                <input 
                                    type="radio" 
                                    name="urgency_level" 
                                    value="{{ $key }}"
                                    {{ $selectedUrgency === $key ? 'checked' : '' }}
                                    class="sr-only"
                                >
                                <span class="text-center">
                                    <span class="block text-lg font-semibold text-gray-900">{{ $level['name'] }}</span>
                                    <span class="block text-xs text-gray-600 mt-1">{{ $level['desc'] }}</span>
                                </span>
                                @if($selectedUrgency === $key)
                                <div class="absolute top-2 right-2">
                                    <svg class="h-5 w-5 text-{{ $level['color'] }}-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </label>
                            @endforeach
                        </div>

                        @error('urgency_level')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Description -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Service Description
                        </h3>

                        <div>
                            <label for="service_description" class="block text-sm font-medium text-gray-700 mb-1">
                                Describe the services needed
                            </label>
                            <textarea 
                                name="service_description" 
                                id="service_description" 
                                rows="4"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Provide details about the services required, specific concerns, number of animals affected, etc."
                            >{{ old('service_description', $farmRecordData['step4']['service_description'] ?? '') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Include any relevant details that will help the veterinarian prepare</p>
                            @error('service_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Preferred Service Date -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Scheduling
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Preferred Date -->
                            <div>
                                <label for="preferred_service_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Preferred Service Date
                                </label>
                                <input 
                                    type="date" 
                                    name="preferred_service_date" 
                                    id="preferred_service_date" 
                                    value="{{ old('preferred_service_date', $farmRecordData['step4']['preferred_service_date'] ?? '') }}"
                                    min="{{ date('Y-m-d') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                                <p class="mt-1 text-xs text-gray-500">When would you like the service?</p>
                                @error('preferred_service_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Immediate Attention -->
                            <div class="flex items-center h-full">
                                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 w-full {{ old('needs_immediate_attention', $farmRecordData['step4']['needs_immediate_attention'] ?? false) ? 'border-red-500 bg-red-50' : 'border-gray-200' }}">
                                    <input 
                                        type="checkbox" 
                                        name="needs_immediate_attention" 
                                        id="needs_immediate_attention" 
                                        value="1"
                                        {{ old('needs_immediate_attention', $farmRecordData['step4']['needs_immediate_attention'] ?? false) ? 'checked' : '' }}
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded mt-1"
                                    >
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">
                                            <svg class="inline h-5 w-5 text-red-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            Needs Immediate Attention
                                        </span>
                                        <span class="block text-xs text-gray-600 mt-1">Check if this is an urgent/emergency situation</span>
                                    </div>
                                </label>
                            </div>

                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a 
                                href="{{ route('data-collector.farm-record.step', ['step' => 3]) }}" 
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
                            Next: Alert Preferences
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