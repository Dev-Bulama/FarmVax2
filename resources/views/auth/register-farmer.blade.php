<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registration - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Farmer Registration</h1>
                <p class="text-gray-600 mt-2">Join FarmVax to manage your livestock and access veterinary services</p>
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
<form action="{{ route('register.farmer') }}" method="POST" id="registrationForm" class="bg-white rounded-lg shadow-lg p-8">
                @csrf
                <input type="hidden" name="role" value="farmer">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                <!-- Personal Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full mr-3">1</span>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter your full name">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="your@email.com">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="+234 xxx xxx xxxx">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required minlength="8"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full mr-3">2</span>
                        Location Information
                    </h3>

                    <!-- GPS Auto-Detection -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <svg class="h-6 w-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-blue-900 mb-2">Detect My Location Automatically</p>
                                <p class="text-xs text-blue-700 mb-3">Click the button below to automatically fill your location using GPS</p>
                                <button type="button" onclick="detectLocation()" id="detectBtn"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition flex items-center text-sm">
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Select Country --</option>
                                @foreach(\App\Models\Country::orderBy('name')->get() as $country)
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
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Select State --</option>
                            </select>
                        </div>

                        <!-- LGA -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                LGA (Local Government Area) <span class="text-red-500">*</span>
                            </label>
                            <select name="lga_id" id="lgaSelect" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Select LGA --</option>
                            </select>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Street Address/Village
                            </label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter your address">
                        </div>
                    </div>
                </div>

                <!-- Farm Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full mr-3">3</span>
                        Farm Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Farm Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Farm Name
                            </label>
                            <input type="text" name="farm_name" value="{{ old('farm_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Enter your farm name">
                        </div>

                        <!-- Farm Size -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Farm Size (Hectares)
                            </label>
                            <input type="number" step="0.01" name="farm_size" value="{{ old('farm_size') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="e.g., 5.5">
                        </div>
                    </div>
                </div>
                <!-- Referral Information (Optional) -->
<div class="mb-8">
    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full mr-3">4</span>
        Referral Information (Optional)
    </h3>

    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
        <div class="flex items-start">
            <svg class="h-6 w-6 text-purple-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-semibold text-purple-900 mb-1">Were you referred by a volunteer?</p>
                <p class="text-xs text-purple-700">If a FarmVax volunteer invited you, enter their referral code below to help them earn rewards!</p>
            </div>
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">
            Referral Code (Optional)
        </label>
        <div class="relative">
            <input type="text" name="referral_code" id="referral_code" value="{{ old('referral_code') }}"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                   placeholder="e.g., FV-12345678"
                   maxlength="20">
            <div id="referralStatus" class="absolute right-3 top-3"></div>
        </div>
        <p id="referralMessage" class="text-xs text-gray-500 mt-1">Enter the code provided by your volunteer</p>
    </div>
</div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="terms" required class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> and 
                            <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-600 text-white px-6 py-4 rounded-lg hover:bg-blue-700 font-bold transition flex items-center justify-center shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Complete Registration
                </button>

            </form>

            <!-- Already have account -->
            <div class="text-center mt-6">
                <p class="text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Login here</a>
                </p>
            </div>

        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            field.type = field.type === 'password' ? 'text' : 'password';
        }

        // Progress tracking
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

        // Cascading dropdowns
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

        // GPS Location Detection
        function detectLocation() {
            const btn = document.getElementById('detectBtn');
            const status = document.getElementById('locationStatus');
            
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Detecting...';
            status.textContent = 'Requesting location permission...';
            status.className = 'text-xs text-blue-600 mt-2';

            if (!navigator.geolocation) {
                status.textContent = '❌ Geolocation is not supported by your browser';
                status.className = 'text-xs text-red-600 mt-2';
                btn.disabled = false;
                btn.innerHTML = '<svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>Detect My Location';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                    
                    status.textContent = '📍 Location detected! Fetching address...';
                    status.className = 'text-xs text-blue-600 mt-2';

                    // Reverse geocode
                    fetch('/api/reverse-geocode', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ latitude, longitude })
                    })
                    .then(response => response.json())
                    .then(data => {
    console.log('Geocode response:', data);
    
    if (data.success && data.matches) {
        // Set location dropdowns
        if (data.matches.country_id) {
            countrySelect.value = data.matches.country_id;
            countrySelect.dispatchEvent(new Event('change'));
            
            // Wait for states to load
            setTimeout(() => {
                if (data.matches.state_id) {
                    stateSelect.value = data.matches.state_id;
                    stateSelect.dispatchEvent(new Event('change'));
                    
                    // Wait for LGAs to load
                    setTimeout(() => {
                        if (data.matches.lga_id) {
                            lgaSelect.value = data.matches.lga_id;
                            updateProgress();
                        }
                    }, 1000);
                }
            }, 1000);
        }
        
        // Display appropriate message
        let message = '✅ Location detected!';
        if (data.address && data.address.formatted) {
            message = '✅ ' + data.address.formatted;
        }
        
        status.textContent = message;
        status.className = 'text-xs text-green-600 mt-2';
    } else {
        status.textContent = '⚠️ Coordinates saved (' + latitude.toFixed(4) + ', ' + longitude.toFixed(4) + '). Please select your location manually.';
        status.className = 'text-xs text-yellow-600 mt-2';
    }

                        
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>Detect Again';
                        updateProgress();
                    })
                    .catch(error => {
                        console.error('Geocoding error:', error);
                        status.textContent = '⚠️ Could not determine address. Coordinates saved. Please select location manually.';
                        status.className = 'text-xs text-yellow-600 mt-2';
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>Detect Again';
                    });
                },
                function(error) {
                    let errorMessage = '';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = '❌ Location access denied. Please enable location in your browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = '❌ Location information unavailable.';
                            break;
                        case error.TIMEOUT:
                            errorMessage = '❌ Location request timed out.';
                            break;
                        default:
                            errorMessage = '❌ An unknown error occurred.';
                    }
                    
                    status.textContent = errorMessage;
                    status.className = 'text-xs text-red-600 mt-2';
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>Try Again';
                }
            );
        }

        // Initialize progress on load
        updateProgress();
        // Referral Code Validation
const referralInput = document.getElementById('referral_code');
const referralStatus = document.getElementById('referralStatus');
const referralMessage = document.getElementById('referralMessage');

if (referralInput) {
    let debounceTimer;
    referralInput.addEventListener('input', function() {
        const code = this.value.trim().toUpperCase();
        this.value = code;
        
        clearTimeout(debounceTimer);
        
        if (code.length === 0) {
            referralStatus.innerHTML = '';
            referralMessage.textContent = 'Enter the code provided by your volunteer';
            referralMessage.className = 'text-xs text-gray-500 mt-1';
            return;
        }
        
        if (code.length < 5) {
            referralStatus.innerHTML = '';
            referralMessage.textContent = 'Code too short';
            referralMessage.className = 'text-xs text-gray-500 mt-1';
            return;
        }
        
        referralStatus.innerHTML = '<svg class="animate-spin h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        referralMessage.textContent = 'Checking...';
        referralMessage.className = 'text-xs text-blue-600 mt-1';
        
        debounceTimer = setTimeout(() => {
            fetch(`/api/validate-referral/${code}`)
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        referralStatus.innerHTML = '<svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>';
                        referralMessage.textContent = '✓ Valid code from ' + data.volunteer_name;
                        referralMessage.className = 'text-xs text-green-600 mt-1 font-semibold';
                    } else {
                        referralStatus.innerHTML = '<svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>';
                        referralMessage.textContent = '✗ Invalid referral code';
                        referralMessage.className = 'text-xs text-red-600 mt-1';
                    }
                })
                .catch(error => {
                    console.error('Error validating referral:', error);
                    referralStatus.innerHTML = '';
                    referralMessage.textContent = 'Could not validate code';
                    referralMessage.className = 'text-xs text-yellow-600 mt-1';
                });
        }, 500);
    });
}
    </script>
</body>
</html>