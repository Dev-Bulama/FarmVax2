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
                    <span class="text-sm font-medium text-blue-600">Step 3 of 6</span>
                    <span class="text-sm font-medium text-gray-500">50% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 50%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Stakeholder Info</span>
                    <span class="text-green-600">✓ Livestock Profile</span>
                    <span class="font-medium text-blue-600">Health & Vaccination</span>
                    <span>Service Needs</span>
                    <span>Alert Preferences</span>
                    <span>Consent</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 3: Health & Vaccination</h2>
                    <p class="mt-1 text-sm text-gray-600">Vaccination history and current health status</p>
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

                <form method="POST" action="{{ route('data-collector.farm-record.save-step', ['step' => 3]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Vaccination Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Vaccination Information
                        </h3>

                        <div class="max-w-md">
                            <label for="last_vaccination_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Last Vaccination Date
                            </label>
                            <input 
                                type="date" 
                                name="last_vaccination_date" 
                                id="last_vaccination_date" 
                                value="{{ old('last_vaccination_date', $farmRecordData['step3']['last_vaccination_date'] ?? '') }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            >
                            <p class="mt-1 text-xs text-gray-500">When was the last time animals were vaccinated?</p>
                            @error('last_vaccination_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Current Health Status -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Current Health Status
                        </h3>

                        <!-- Health Issues Toggle -->
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    name="has_health_issues" 
                                    id="has_health_issues" 
                                    value="1"
                                    {{ old('has_health_issues', $farmRecordData['step3']['has_health_issues'] ?? false) ? 'checked' : '' }}
                                    onchange="toggleHealthIssues()"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <span class="ml-2 text-sm font-medium text-gray-700">
                                    Animals currently have health issues
                                </span>
                            </label>
                        </div>

                        <!-- Health Issues Details (Hidden by default) -->
                        <div id="healthIssuesSection" class="hidden space-y-4">
                            
                            <!-- Common Diseases -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Health Issues / Diseases
                                </label>
                                
                                @php
                                    $commonDiseases = [
                                        'foot_and_mouth' => 'Foot-and-Mouth Disease (FMD)',
                                        'newcastle' => 'Newcastle Disease',
                                        'anthrax' => 'Anthrax',
                                        'cbpp' => 'CBPP (Contagious Bovine Pleuropneumonia)',
                                        'lumpy_skin' => 'Lumpy Skin Disease',
                                        'ppr' => 'PPR (Peste des Petits Ruminants)',
                                        'fasciolosis' => 'Fasciolosis',
                                        'trypanosomosis' => 'Trypanosomosis',
                                        'mastitis' => 'Mastitis',
                                        'brucellosis' => 'Brucellosis',
                                    ];
                                    $selectedIssues = old('current_health_issues', $farmRecordData['step3']['current_health_issues'] ?? []);
                                @endphp

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($commonDiseases as $key => $disease)
                                    <label class="flex items-start p-3 border rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array($key, $selectedIssues) ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                                        <input 
                                            type="checkbox" 
                                            name="current_health_issues[]" 
                                            value="{{ $key }}"
                                            {{ in_array($key, $selectedIssues) ? 'checked' : '' }}
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded mt-0.5"
                                        >
                                        <span class="ml-2 text-sm text-gray-900">{{ $disease }}</span>
                                    </label>
                                    @endforeach

                                    <!-- Other -->
                                    <label class="flex items-start p-3 border rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array('other', $selectedIssues) ? 'border-red-300 bg-red-50' : 'border-gray-200' }}">
                                        <input 
                                            type="checkbox" 
                                            name="current_health_issues[]" 
                                            value="other"
                                            {{ in_array('other', $selectedIssues) ? 'checked' : '' }}
                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded mt-0.5"
                                        >
                                        <span class="ml-2 text-sm text-gray-900">Other</span>
                                    </label>
                                </div>

                                @error('current_health_issues')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Health Notes -->
                            <div>
                                <label for="health_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Additional Health Notes
                                </label>
                                <textarea 
                                    name="health_notes" 
                                    id="health_notes" 
                                    rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Describe symptoms, affected animals, severity, etc."
                                >{{ old('health_notes', $farmRecordData['step3']['health_notes'] ?? '') }}</textarea>
                                @error('health_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Veterinarian Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Veterinarian Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Vet Name -->
                            <div>
                                <label for="veterinarian_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Veterinarian Name
                                </label>
                                <input 
                                    type="text" 
                                    name="veterinarian_name" 
                                    id="veterinarian_name" 
                                    value="{{ old('veterinarian_name', $farmRecordData['step3']['veterinarian_name'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter vet's name"
                                >
                                @error('veterinarian_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vet Phone -->
                            <div>
                                <label for="veterinarian_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Veterinarian Phone
                                </label>
                                <input 
                                    type="tel" 
                                    name="veterinarian_phone" 
                                    id="veterinarian_phone" 
                                    value="{{ old('veterinarian_phone', $farmRecordData['step3']['veterinarian_phone'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="+234 123 456 7890"
                                >
                                @error('veterinarian_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Vet Visit -->
                            <div>
                                <label for="last_vet_visit" class="block text-sm font-medium text-gray-700 mb-1">
                                    Last Veterinary Visit
                                </label>
                                <input 
                                    type="date" 
                                    name="last_vet_visit" 
                                    id="last_vet_visit" 
                                    value="{{ old('last_vet_visit', $farmRecordData['step3']['last_vet_visit'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                >
                                @error('last_vet_visit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Past Disease History -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Past Disease History
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">Select diseases that have affected the animals in the past:</p>

                        @php
                            $pastDiseasesList = [
                                'foot_and_mouth' => 'Foot-and-Mouth Disease (FMD)',
                                'newcastle' => 'Newcastle Disease',
                                'anthrax' => 'Anthrax',
                                'cbpp' => 'CBPP',
                                'lumpy_skin' => 'Lumpy Skin Disease',
                                'ppr' => 'PPR',
                                'fasciolosis' => 'Fasciolosis',
                                'trypanosomosis' => 'Trypanosomosis',
                                'mastitis' => 'Mastitis',
                                'brucellosis' => 'Brucellosis',
                            ];
                            $selectedPastDiseases = old('past_diseases', $farmRecordData['step3']['past_diseases'] ?? []);
                        @endphp

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($pastDiseasesList as $key => $disease)
                            <label class="flex items-start p-3 border rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array($key, $selectedPastDiseases) ? 'border-orange-300 bg-orange-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="past_diseases[]" 
                                    value="{{ $key }}"
                                    {{ in_array($key, $selectedPastDiseases) ? 'checked' : '' }}
                                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded mt-0.5"
                                >
                                <span class="ml-2 text-sm text-gray-900">{{ $disease }}</span>
                            </label>
                            @endforeach

                            <!-- Other -->
                            <label class="flex items-start p-3 border rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array('other', $selectedPastDiseases) ? 'border-orange-300 bg-orange-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="past_diseases[]" 
                                    value="other"
                                    {{ in_array('other', $selectedPastDiseases) ? 'checked' : '' }}
                                    class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded mt-0.5"
                                >
                                <span class="ml-2 text-sm text-gray-900">Other</span>
                            </label>

                            <!-- None -->
                            <label class="flex items-start p-3 border rounded-lg hover:bg-gray-50 cursor-pointer {{ in_array('none', $selectedPastDiseases) ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                                <input 
                                    type="checkbox" 
                                    name="past_diseases[]" 
                                    value="none"
                                    {{ in_array('none', $selectedPastDiseases) ? 'checked' : '' }}
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded mt-0.5"
                                >
                                <span class="ml-2 text-sm text-gray-900">None</span>
                            </label>
                        </div>

                        @error('past_diseases')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a 
                                href="{{ route('data-collector.farm-record.step', ['step' => 2]) }}" 
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
                            Next: Service Needs
                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <!-- Scripts -->
    <script>
        // Toggle health issues section
        function toggleHealthIssues() {
            const checkbox = document.getElementById('has_health_issues');
            const section = document.getElementById('healthIssuesSection');
            
            if (checkbox.checked) {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        }

        // Check on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleHealthIssues();
        });

        // Save draft function
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