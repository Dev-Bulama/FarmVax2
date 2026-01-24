<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 1: Basic Information - FarmVax</title>
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
                <h1 class="text-3xl font-bold text-gray-900">Register New Livestock</h1>
                <p class="mt-2 text-sm text-gray-600">Complete all 6 steps to register your animal</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-green-600">Step 1 of 6</span>
                    <span class="text-sm font-medium text-gray-500">17% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: 17%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="font-medium text-green-600">Basic Info</span>
                    <span>Physical</span>
                    <span>Health</span>
                    <span>Vaccination</span>
                    <span>Production</span>
                    <span>Origin</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 1: Basic Information</h2>
                    <p class="mt-1 text-sm text-gray-600">Animal type, identification, and age details</p>
                </div>

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

                <form method="POST" action="{{ route('individual.livestock.save-step', ['step' => 1]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Animal Type Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Animal Type
                        </h3>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @php
                                $livestockTypes = [
                                    'cattle' => ['name' => 'Cattle', 'icon' => '🐄'],
                                    'goat' => ['name' => 'Goat', 'icon' => '🐐'],
                                    'sheep' => ['name' => 'Sheep', 'icon' => '🐑'],
                                    'pig' => ['name' => 'Pig', 'icon' => '🐷'],
                                    'chicken' => ['name' => 'Chicken', 'icon' => '🐔'],
                                    'duck' => ['name' => 'Duck', 'icon' => '🦆'],
                                    'turkey' => ['name' => 'Turkey', 'icon' => '🦃'],
                                    'rabbit' => ['name' => 'Rabbit', 'icon' => '🐰'],
                                    'horse' => ['name' => 'Horse', 'icon' => '🐴'],
                                    'donkey' => ['name' => 'Donkey', 'icon' => '🫏'],
                                    'other' => ['name' => 'Other', 'icon' => '❓'],
                                ];
                                $selectedType = old('livestock_type', $livestockData['step1']['livestock_type'] ?? '');
                            @endphp

                            @foreach($livestockTypes as $key => $type)
                            <label class="relative flex flex-col items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ $selectedType === $key ? 'border-green-500 bg-green-50 ring-2 ring-green-500' : 'border-gray-200' }}">
                                <input 
                                    type="radio" 
                                    name="livestock_type" 
                                    value="{{ $key }}"
                                    {{ $selectedType === $key ? 'checked' : '' }}
                                    required
                                    class="sr-only"
                                    onchange="toggleOtherField()"
                                >
                                <span class="text-3xl mb-2">{{ $type['icon'] }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $type['name'] }}</span>
                                @if($selectedType === $key)
                                <div class="absolute top-2 right-2">
                                    <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </label>
                            @endforeach
                        </div>

                        <!-- Other Type (Hidden by default) -->
                        <div id="otherTypeField" class="mt-4 {{ $selectedType === 'other' ? '' : 'hidden' }}">
                            <label for="other_type" class="block text-sm font-medium text-gray-700 mb-1">
                                Specify Animal Type <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="other_type" 
                                id="other_type" 
                                value="{{ old('other_type', $livestockData['step1']['other_type'] ?? '') }}"
                                class="block w-full max-w-md px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., Guinea Fowl, Peacock"
                            >
                        </div>

                        @error('livestock_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Identification Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Identification
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Tag Number -->
                            <div>
                                <label for="tag_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tag Number / ID
                                </label>
                                <input 
                                    type="text" 
                                    name="tag_number" 
                                    id="tag_number" 
                                    value="{{ old('tag_number', $livestockData['step1']['tag_number'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., COW-001, GT-042"
                                >
                                <p class="mt-1 text-xs text-gray-500">Unique identifier for this animal</p>
                                @error('tag_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Animal Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Animal Name (Optional)
                                </label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    value="{{ old('name', $livestockData['step1']['name'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., Bessie, Thunder"
                                >
                                <p class="mt-1 text-xs text-gray-500">Give your animal a name</p>
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Breed & Gender Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Breed & Gender
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Breed -->
                            <div>
                                <label for="breed" class="block text-sm font-medium text-gray-700 mb-1">
                                    Breed
                                </label>
                                <input 
                                    type="text" 
                                    name="breed" 
                                    id="breed" 
                                    value="{{ old('breed', $livestockData['step1']['breed'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., Holstein, Boer, Rhode Island Red"
                                >
                                <p class="mt-1 text-xs text-gray-500">Animal breed or variety</p>
                                @error('breed')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                                    Gender <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="gender" 
                                    id="gender" 
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                >
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $livestockData['step1']['gender'] ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $livestockData['step1']['gender'] ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="unknown" {{ old('gender', $livestockData['step1']['gender'] ?? '') == 'unknown' ? 'selected' : '' }}>Unknown</option>
                                </select>
                                @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Age Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">
                            Age Information
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Date of Birth -->
                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                                    Date of Birth
                                </label>
                                <input 
                                    type="date" 
                                    name="date_of_birth" 
                                    id="date_of_birth" 
                                    value="{{ old('date_of_birth', $livestockData['step1']['date_of_birth'] ?? '') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                >
                                @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Age Years -->
                            <div>
                                <label for="age_years" class="block text-sm font-medium text-gray-700 mb-1">
                                    Age (Years)
                                </label>
                                <input 
                                    type="number" 
                                    name="age_years" 
                                    id="age_years" 
                                    value="{{ old('age_years', $livestockData['step1']['age_years'] ?? '') }}"
                                    min="0"
                                    max="50"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="0"
                                >
                                @error('age_years')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Age Months -->
                            <div>
                                <label for="age_months" class="block text-sm font-medium text-gray-700 mb-1">
                                    Additional Months
                                </label>
                                <input 
                                    type="number" 
                                    name="age_months" 
                                    id="age_months" 
                                    value="{{ old('age_months', $livestockData['step1']['age_months'] ?? '') }}"
                                    min="0"
                                    max="11"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="0"
                                >
                                <p class="mt-1 text-xs text-gray-500">0-11 months</p>
                                @error('age_months')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800">
                                <strong>Tip:</strong> Provide either the date of birth OR the age in years/months. The system can calculate one from the other.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <button 
                            type="button" 
                            onclick="saveDraft()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            <svg class="h-5 w-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Save as Draft
                        </button>

                        <button 
                            type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                        >
                            Next: Physical Characteristics
                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

    <!-- JavaScript -->
    <script>
        // Toggle "Other" type field
        function toggleOtherField() {
            const radios = document.querySelectorAll('input[name="livestock_type"]');
            const otherField = document.getElementById('otherTypeField');
            
            let selectedValue = '';
            radios.forEach(radio => {
                if (radio.checked) {
                    selectedValue = radio.value;
                }
            });
            
            if (selectedValue === 'other') {
                otherField.classList.remove('hidden');
                document.getElementById('other_type').required = true;
            } else {
                otherField.classList.add('hidden');
                document.getElementById('other_type').required = false;
            }
        }

        // Check on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleOtherField();
        });

        // Save draft function
        function saveDraft() {
            if (confirm('Save your progress as a draft? You can continue later.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                
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