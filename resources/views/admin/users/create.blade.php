@extends('layouts.admin')

@section('title', 'Create User')
@section('page-title', 'Create New User')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="space-y-6">
            
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                            <option value="">-- Select Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                            <option value="animal_health_professional" {{ old('role') == 'animal_health_professional' ? 'selected' : '' }}>Animal Health Professional</option>
                            <option value="volunteer" {{ old('role') == 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                               placeholder="Minimum 8 characters">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                               placeholder="Re-enter password">
                    </div>
                </div>
            </div>

            <!-- Location Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Location Information</h3>
                
                <!-- GPS Detection Button -->
                <div class="mb-4">
                    <button type="button" id="detectLocationBtn" onclick="detectLocation()"
                        class="px-4 py-2 bg-[#11455B] text-white rounded-lg hover:bg-[#0d3345] transition flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span id="detectLocationText">Auto-Detect Location (GPS)</span>
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Click to automatically fill location fields</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Country <span class="text-red-500">*</span>
                        </label>
                        <select name="country_id" id="country_id" required onchange="loadStates()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                            <option value="">-- Loading Countries... --</option>
                        </select>
                    </div>

                    <!-- State -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            State/Province <span class="text-red-500">*</span>
                        </label>
                        <select name="state_id" id="state_id" required onchange="loadLgas()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                            disabled>
                            <option value="">-- Select Country First --</option>
                        </select>
                    </div>

                    <!-- LGA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            LGA/City
                        </label>
                        <select name="lga_id" id="lga_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                            disabled>
                            <option value="">-- Select State First --</option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Street Address
                    </label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                        placeholder="Enter street address">{{ old('address') }}</textarea>
                </div>

                <!-- GPS Coordinates (Hidden) -->
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                
                <div id="gpsStatus" class="hidden mt-2 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700"></div>
            </div>

            <!-- Account Status -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Account Status</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Account Status
                        </label>
                        <select name="account_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                            <option value="banned">Banned</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.users.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-[#2FCB6E] text-white rounded-lg hover:bg-[#25a356] transition">
                Create User
            </button>
        </div>
    </form>

</div>

<script>
    // Load Countries
    async function loadCountries() {
        const countrySelect = document.getElementById('country_id');
        countrySelect.innerHTML = '<option value="">-- Loading Countries... --</option>';
        
        try {
            const response = await fetch('/api/countries');
            const result = await response.json();
            const countries = result.data || result;
            
            if (!Array.isArray(countries)) {
                throw new Error('Invalid response format');
            }
            
            countrySelect.innerHTML = '<option value="">-- Select Country --</option>';
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.id;
                option.textContent = country.name;
                if ("{{ old('country_id') }}" == country.id) {
                    option.selected = true;
                }
                countrySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading countries:', error);
            countrySelect.innerHTML = '<option value="">-- Error Loading Countries --</option>';
        }
    }

    // Load States
    async function loadStates() {
        const countryId = document.getElementById('country_id').value;
        const stateSelect = document.getElementById('state_id');
        const lgaSelect = document.getElementById('lga_id');
        
        stateSelect.innerHTML = '<option value="">-- Loading States... --</option>';
        stateSelect.disabled = true;
        lgaSelect.innerHTML = '<option value="">-- Select State First --</option>';
        lgaSelect.disabled = true;
        
        if (!countryId) {
            stateSelect.innerHTML = '<option value="">-- Select Country First --</option>';
            return;
        }
        
        try {
            const response = await fetch(`/api/states/${countryId}`);
            const result = await response.json();
            const states = result.data || result;
            
            if (!Array.isArray(states)) {
                throw new Error('Invalid response format');
            }
            
            stateSelect.innerHTML = '<option value="">-- Select State --</option>';
            states.forEach(state => {
                const option = document.createElement('option');
                option.value = state.id;
                option.textContent = state.name;
                if ("{{ old('state_id') }}" == state.id) {
                    option.selected = true;
                }
                stateSelect.appendChild(option);
            });
            stateSelect.disabled = false;
        } catch (error) {
            console.error('Error loading states:', error);
            stateSelect.innerHTML = '<option value="">-- Error Loading States --</option>';
        }
    }

    // Load LGAs
    async function loadLgas() {
        const stateId = document.getElementById('state_id').value;
        const lgaSelect = document.getElementById('lga_id');
        
        lgaSelect.innerHTML = '<option value="">-- Loading LGAs... --</option>';
        lgaSelect.disabled = true;
        
        if (!stateId) {
            lgaSelect.innerHTML = '<option value="">-- Select State First --</option>';
            return;
        }
        
        try {
            const response = await fetch(`/api/lgas/${stateId}`);
            const result = await response.json();
            const lgas = result.data || result;
            
            if (!Array.isArray(lgas)) {
                throw new Error('Invalid response format');
            }
            
            lgaSelect.innerHTML = '<option value="">-- Select LGA (Optional) --</option>';
            lgas.forEach(lga => {
                const option = document.createElement('option');
                option.value = lga.id;
                option.textContent = lga.name;
                if ("{{ old('lga_id') }}" == lga.id) {
                    option.selected = true;
                }
                lgaSelect.appendChild(option);
            });
            lgaSelect.disabled = false;
        } catch (error) {
            console.error('Error loading LGAs:', error);
            lgaSelect.innerHTML = '<option value="">-- Error Loading LGAs --</option>';
        }
    }

    // GPS Detection
    function detectLocation() {
        const btn = document.getElementById('detectLocationBtn');
        const btnText = document.getElementById('detectLocationText');
        const gpsStatus = document.getElementById('gpsStatus');
        
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser');
            return;
        }
        
        btn.disabled = true;
        btnText.textContent = 'Detecting...';
        gpsStatus.classList.add('hidden');
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                
                gpsStatus.textContent = `✓ GPS Location Detected: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                gpsStatus.classList.remove('hidden');
                
                btn.disabled = false;
                btnText.textContent = '✓ Location Detected';
                setTimeout(() => {
                    btnText.textContent = 'Auto-Detect Location (GPS)';
                }, 3000);
            },
            function(error) {
                console.error('Geolocation error:', error);
                gpsStatus.textContent = '✗ Could not detect location. Please select manually.';
                gpsStatus.classList.remove('hidden', 'bg-green-50', 'border-green-200', 'text-green-700');
                gpsStatus.classList.add('bg-yellow-50', 'border-yellow-200', 'text-yellow-700');
                
                btn.disabled = false;
                btnText.textContent = 'Auto-Detect Location (GPS)';
            }
        );
    }

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        loadCountries();
        
        const oldCountryId = "{{ old('country_id') }}";
        const oldStateId = "{{ old('state_id') }}";
        
        if (oldCountryId) {
            setTimeout(() => {
                loadStates();
                if (oldStateId) {
                    setTimeout(() => {
                        loadLgas();
                    }, 500);
                }
            }, 500);
        }
    });
</script>

@endsection