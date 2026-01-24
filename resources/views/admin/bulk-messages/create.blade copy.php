@extends('layouts.admin')

@section('title', 'Create Bulk Message')
@section('page-title', 'Create Bulk Message')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.bulk-messages.index') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Bulk Messages
    </a>
</div>

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

    <form action="{{ route('admin.bulk-messages.store') }}" method="POST">
        @csrf

        <div class="space-y-6">
            
            <!-- Message Details -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Message Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Message Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                               placeholder="e.g., Vaccination Reminder - All Farmers">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Message Content <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" rows="6" required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                                  placeholder="Type your message here...">{{ old('message') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Keep SMS messages under 160 characters for best results</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Message Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent">
                            <option value="">-- Select Type --</option>
                            <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email Only</option>
                            <option value="sms" {{ old('type') == 'sms' ? 'selected' : '' }}>SMS Only</option>
                            <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Both Email & SMS</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Target Audience -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Target Audience</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Target By <span class="text-red-500">*</span>
                    </label>
                    <select name="target_type" id="target_type" required onchange="toggleTargetFields()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent">
                        <option value="">-- Select Target Type --</option>
                        <option value="all" {{ old('target_type') == 'all' ? 'selected' : '' }}>All Users</option>
                        <option value="role" {{ old('target_type') == 'role' ? 'selected' : '' }}>By Role</option>
                        <option value="location" {{ old('target_type') == 'location' ? 'selected' : '' }}>By Location</option>
                        <option value="specific" {{ old('target_type') == 'specific' ? 'selected' : '' }}>Specific Users</option>
                    </select>
                </div>

                <!-- Role Selection -->
                <div id="role-fields" class="hidden mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Roles</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="farmer" class="h-4 w-4 text-[#11455B] focus:ring-[#11455B] border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-900">Farmers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="animal_health_professional" class="h-4 w-4 text-[#11455B] focus:ring-[#11455B] border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-900">Animal Health Professionals</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="volunteer" class="h-4 w-4 text-[#11455B] focus:ring-[#11455B] border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-900">Volunteers</span>
                        </label>
                    </div>
                </div>

                <!-- Location Selection -->
                <div id="location-fields" class="hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                            <select name="country_id" id="country_id" onchange="loadStates()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent">
                                <option value="">-- Loading Countries... --</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <select name="state_id" id="state_id" onchange="loadLgas()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                                disabled>
                                <option value="">-- Select Country First --</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">LGA</label>
                            <select name="lga_id" id="lga_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                                disabled>
                                <option value="">-- Select State First --</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Specific Users -->
                <div id="specific-fields" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User IDs (comma-separated)</label>
                    <input type="text" name="specific_users_input" id="specific_users_input"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent"
                           placeholder="e.g., 1, 5, 10, 25">
                    <p class="text-xs text-gray-500 mt-1">Enter user IDs separated by commas</p>
                </div>
            </div>

            <!-- Schedule Options -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Sending Options</h3>
                
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" name="send_option" value="now" checked
                               class="h-4 w-4 text-[#11455B] focus:ring-[#11455B] border-gray-300">
                        <span class="ml-2 text-sm text-gray-900">Send Now</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="send_option" value="draft"
                               class="h-4 w-4 text-[#11455B] focus:ring-[#11455B] border-gray-300">
                        <span class="ml-2 text-sm text-gray-900">Save as Draft</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="send_option" value="schedule"
                               class="h-4 w-4 text-[#11455B] focus:ring-[#11455B] border-gray-300">
                        <span class="ml-2 text-sm text-gray-900">Schedule for Later</span>
                    </label>
                </div>

                <div id="schedule-datetime" class="mt-4 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Date & Time</label>
                    <input type="datetime-local" name="scheduled_at"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#11455B] focus:border-transparent">
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.bulk-messages.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" name="send_now" value="0"
                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Save as Draft
            </button>
            <button type="submit" name="send_now" value="1"
                    class="px-6 py-2 bg-[#2FCB6E] text-white rounded-lg hover:bg-[#25a356] transition font-semibold">
                Send Now
            </button>
        </div>
    </form>

</div>

<script>
    function toggleTargetFields() {
        const targetType = document.getElementById('target_type').value;
        const roleFields = document.getElementById('role-fields');
        const locationFields = document.getElementById('location-fields');
        const specificFields = document.getElementById('specific-fields');

        // Hide all fields
        roleFields.classList.add('hidden');
        locationFields.classList.add('hidden');
        specificFields.classList.add('hidden');

        // Show relevant field
        if (targetType === 'role') {
            roleFields.classList.remove('hidden');
        } else if (targetType === 'location') {
            locationFields.classList.remove('hidden');
            loadCountries();
        } else if (targetType === 'specific') {
            specificFields.classList.remove('hidden');
        }
    }

    async function loadCountries() {
        const countrySelect = document.getElementById('country_id');
        countrySelect.innerHTML = '<option value="">-- Loading Countries... --</option>';
        
        try {
            const response = await fetch('/api/countries');
            const result = await response.json();
            const countries = result.data || result;
            
            countrySelect.innerHTML = '<option value="">-- Select Country --</option>';
            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.id;
                option.textContent = country.name;
                countrySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading countries:', error);
            countrySelect.innerHTML = '<option value="">-- Error Loading Countries --</option>';
        }
    }

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
            
            stateSelect.innerHTML = '<option value="">-- Select State (Optional) --</option>';
            states.forEach(state => {
                const option = document.createElement('option');
                option.value = state.id;
                option.textContent = state.name;
                stateSelect.appendChild(option);
            });
            stateSelect.disabled = false;
        } catch (error) {
            console.error('Error loading states:', error);
            stateSelect.innerHTML = '<option value="">-- Error Loading States --</option>';
        }
    }

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
            
            lgaSelect.innerHTML = '<option value="">-- Select LGA (Optional) --</option>';
            lgas.forEach(lga => {
                const option = document.createElement('option');
                option.value = lga.id;
                option.textContent = lga.name;
                lgaSelect.appendChild(option);
            });
            lgaSelect.disabled = false;
        } catch (error) {
            console.error('Error loading LGAs:', error);
            lgaSelect.innerHTML = '<option value="">-- Error Loading LGAs --</option>';
        }
    }

    // Handle schedule option
    document.querySelectorAll('input[name="send_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const scheduleDiv = document.getElementById('schedule-datetime');
            if (this.value === 'schedule') {
                scheduleDiv.classList.remove('hidden');
            } else {
                scheduleDiv.classList.add('hidden');
            }
        });
    });

    // Process specific users input
    document.querySelector('form').addEventListener('submit', function() {
        const specificInput = document.getElementById('specific_users_input').value;
        if (specificInput) {
            const ids = specificInput.split(',').map(id => id.trim()).filter(id => id);
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'specific_users[]';
                input.value = id;
                this.appendChild(input);
            });
        }
    });

    // Initialize on page load
    window.addEventListener('DOMContentLoaded', function() {
        const targetType = document.getElementById('target_type').value;
        if (targetType) {
            toggleTargetFields();
        }
    });
</script>

@endsection