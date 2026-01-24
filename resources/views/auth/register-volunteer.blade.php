<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Registration - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Volunteer Registration</h1>
                <p class="text-gray-600 mt-2">Join FarmVax as a volunteer and help farmers in your community</p>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Registration Progress</span>
                    <span class="text-sm font-medium text-blue-600" id="progressText">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Registration Form -->
            <form action="{{ route('register.volunteer') }}" method="POST" id="registrationForm" class="bg-white rounded-lg shadow-lg p-8">
                @csrf
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <!-- Personal Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full mr-3">1</span>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Enter your full name">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="your@email.com">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="+234 xxx xxx xxxx">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required minlength="8"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Minimum 8 characters">
                                <button type="button" onclick="togglePassword('password')" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Confirm your password">
                                <button type="button" onclick="togglePassword('password_confirmation')" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full mr-3">2</span>
                        Location Information
                    </h3>

                    <!-- GPS Auto-Detection -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-green-900 mb-2">Detect My Location Automatically</p>
                                <p class="text-xs text-green-700 mb-3">Click the button below to automatically fill your location using GPS</p>
                                <button type="button" onclick="detectLocation()" id="detectBtn"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition flex items-center text-sm">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    Detect My Location
                                </button>
                                <p id="locationStatus" class="text-xs text-gray-600 mt-2"></p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <select name="country_id" id="countrySelect" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Select Country --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                State <span class="text-red-500">*</span>
                            </label>
                            <select name="state_id" id="stateSelect" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Select State --</option>
                            </select>
                        </div>

                        <!-- LGA -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                LGA (Local Government Area) <span class="text-red-500">*</span>
                            </label>
                            <select name="lga_id" id="lgaSelect" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Select LGA --</option>
                            </select>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Street Address
                            </label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Enter your address">
                        </div>
                    </div>
                </div>

                <!-- Volunteer Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full mr-3">3</span>
                        Volunteer Information
                    </h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Organization -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Organization (Optional)
                            </label>
                            <input type="text" name="organization" value="{{ old('organization') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="e.g., Local Youth Group, NGO, etc.">
                            <p class="text-xs text-gray-500 mt-1">If you're volunteering through an organization</p>
                        </div>

                        <!-- Motivation -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Why do you want to volunteer with FarmVax? (Optional)
                            </label>
                            <textarea name="motivation" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Tell us what motivates you to help farmers in your community...">{{ old('motivation') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">This helps us understand your interests and match you with appropriate activities</p>
                        </div>
                    </div>
                </div>

                <!-- What Volunteers Do -->
                <div class="mb-8">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h4 class="font-bold text-gray-900 mb-3">🎯 What You'll Do as a Volunteer:</h4>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Help register farmers in your community
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Educate farmers about vaccination and animal health
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Earn points and rewards for your contributions
                            </li>
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Make a positive impact in your community
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="terms" required class="mt-1 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">
                            I agree to the <a href="#" class="text-green-600 hover:underline">Terms and Conditions</a> and 
                            <a href="#" class="text-green-600 hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-600 text-white px-6 py-4 rounded-lg hover:bg-green-700 font-bold transition flex items-center justify-center shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Join as Volunteer
                </button>

                <p class="text-center text-sm text-gray-600 mt-4">
                    ✨ Your account will be activated immediately!
                </p>

            </form>

            <!-- Already have account -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-green-600 hover:underline font-semibold">Login here</a>
                </p>
            </div>

        </div>
    </div>

    <!-- JavaScript (Same as other forms) -->
    <script>
        // Same JavaScript as farmer/professional registration
        // (Copy the entire script section from register-farmer.blade.php)
        
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            field.type = field.type === 'password' ? 'text' : 'password';
        }

        const form = document.getElementById('registrationForm');
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        function updateProgress() {
            let filled = 0;
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    if (input.checked) filled++;
                } else {
                    if (input.value.trim() !== '') filled++;
                }
            });
            const percentage = Math.round((filled / inputs.length) * 100);
            progressBar.style.width = percentage + '%';
            progressText.textContent = percentage + '%';
        }
        
        inputs.forEach(input => {
            input.addEventListener('input', updateProgress);
            input.addEventListener('change', updateProgress);
        });

       // Cascading dropdowns - FIXED FOR PRODUCTION
const countrySelect = document.getElementById('countrySelect');
const stateSelect = document.getElementById('stateSelect');
const lgaSelect = document.getElementById('lgaSelect');

countrySelect.addEventListener('change', function() {
    const countryId = this.value;
    stateSelect.innerHTML = '<option value="">-- Select State --</option>';
    stateSelect.disabled = true;
    lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
    lgaSelect.disabled = true;
    
    if (countryId) {
        stateSelect.innerHTML = '<option value="">Loading states...</option>';
        
        // FIXED: Correct API endpoint
        fetch(`/api/states/${countryId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('States loaded:', data);
                stateSelect.innerHTML = '<option value="">-- Select State --</option>';
                
                if (data && data.length > 0) {
                    data.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.id;
                        option.textContent = state.name;
                        stateSelect.appendChild(option);
                    });
                    stateSelect.disabled = false;
                } else {
                    stateSelect.innerHTML = '<option value="">No states available</option>';
                }
            })
            .catch(error => {
                console.error('Error loading states:', error);
                stateSelect.innerHTML = '<option value="">Error loading states</option>';
                alert('Failed to load states. Please refresh the page and try again.');
            });
    }
});

stateSelect.addEventListener('change', function() {
    const stateId = this.value;
    lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
    lgaSelect.disabled = true;
    
    if (stateId) {
        lgaSelect.innerHTML = '<option value="">Loading LGAs...</option>';
        
        // FIXED: Correct API endpoint
        fetch(`/api/lgas/${stateId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('LGAs loaded:', data);
                lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
                
                if (data && data.length > 0) {
                    data.forEach(lga => {
                        const option = document.createElement('option');
                        option.value = lga.id;
                        option.textContent = lga.name;
                        lgaSelect.appendChild(option);
                    });
                    lgaSelect.disabled = false;
                } else {
                    lgaSelect.innerHTML = '<option value="">No LGAs available</option>';
                }
            })
            .catch(error => {
                console.error('Error loading LGAs:', error);
                lgaSelect.innerHTML = '<option value="">Error loading LGAs</option>';
                alert('Failed to load LGAs. Please refresh the page and try again.');
            });
    }
});

// Auto-trigger on page load if country is pre-selected
if (countrySelect.value) {
    countrySelect.dispatchEvent(new Event('change'));
}
        function detectLocation() {
            const btn = document.getElementById('detectBtn');
            const status = document.getElementById('locationStatus');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Detecting...';
            status.textContent = 'Requesting location permission...';
            status.className = 'text-xs text-blue-600 mt-2';

            if (!navigator.geolocation) {
                status.textContent = '❌ Geolocation is not supported';
                status.className = 'text-xs text-red-600 mt-2';
                btn.disabled = false;
                btn.innerHTML = '<svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>Detect My Location';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    status.textContent = '📍 Location detected! Fetching address...';
                    
                    fetch('/api/reverse-geocode', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ 
                            latitude: position.coords.latitude, 
                            longitude: position.coords.longitude 
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.matches) {
                            if (data.matches.country_id) {
                                countrySelect.value = data.matches.country_id;
                                countrySelect.dispatchEvent(new Event('change'));
                                setTimeout(() => {
                                    if (data.matches.state_id) {
                                        stateSelect.value = data.matches.state_id;
                                        stateSelect.dispatchEvent(new Event('change'));
                                        setTimeout(() => {
                                            if (data.matches.lga_id) {
                                                lgaSelect.value = data.matches.lga_id;
                                            }
                                        }, 500);
                                    }
                                }, 500);
                            }
                            status.textContent = '✅ Location set successfully!';
                            status.className = 'text-xs text-green-600 mt-2';
                        } else {
                            status.textContent = '⚠️ Please select location manually';
                            status.className = 'text-xs text-yellow-600 mt-2';
                        }
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>Detect Again';
                        updateProgress();
                    });
                },
                function(error) {
                    status.textContent = '❌ Location access denied';
                    status.className = 'text-xs text-red-600 mt-2';
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>Try Again';
                }
            );
        }

        updateProgress();
    </script>
</body>
</html>