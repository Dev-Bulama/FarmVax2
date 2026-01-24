@extends('layouts.admin')

@section('title', 'Edit Advertisement')
@section('page-title', 'Edit Advertisement')

@section('content')

@php
    $targeting = json_decode($ad->targeting_data, true) ?? [];
@endphp

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.ads.show', $ad->id) }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Ad Details
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

    <form action="{{ route('admin.ads.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <!-- Ad Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Advertisement Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $ad->title) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="e.g., Special Offer on Vaccines">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Brief description of the advertisement...">{{ old('description', $ad->content) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ad Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">-- Select Type --</option>
                                <option value="banner" {{ old('type', $ad->type) == 'banner' ? 'selected' : '' }}>Banner</option>
                                <option value="popup" {{ old('type', $ad->type) == 'popup' ? 'selected' : '' }}>Popup</option>
                                <option value="sidebar" {{ old('type', $ad->type) == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                                <option value="inline" {{ old('type', $ad->type) == 'inline' ? 'selected' : '' }}>Inline</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Priority (0-100)
                            </label>
                            <input type="number" name="priority" min="0" max="100" value="{{ old('priority', $ad->priority ?? 50) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="50">
                            <p class="text-xs text-gray-500 mt-1">Higher priority ads are shown more frequently</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Image <span class="text-gray-500">(Leave empty to keep current)</span>
                        </label>
                        
                        <!-- Current Image -->
                        @if($ad->image_path)
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-2">Current Image:</p>
                                <img src="{{ asset('storage/' . $ad->image_path) }}" alt="{{ $ad->title }}" class="max-w-xs rounded-lg border">
                            </div>
                        @endif

                        <input type="file" name="image" accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               onchange="previewImage(event)">
                        <p class="text-xs text-gray-500 mt-1">Recommended: 1200x628px (JPG, PNG, GIF - Max 2MB)</p>
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3 hidden">
                            <p class="text-xs text-gray-500 mb-2">New Image Preview:</p>
                            <img id="preview" src="" alt="Preview" class="max-w-xs rounded-lg border">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Link URL
                        </label>
                        <input type="url" name="link_url" value="{{ old('link_url', $ad->link_url) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="https://example.com">
                        <p class="text-xs text-gray-500 mt-1">URL to redirect when ad is clicked</p>
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
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Select Target Type --</option>
                        <option value="all" {{ old('target_type', $targeting['target_type'] ?? '') == 'all' ? 'selected' : '' }}>All Users</option>
                        <option value="role" {{ old('target_type', $targeting['target_type'] ?? '') == 'role' ? 'selected' : '' }}>By Role</option>
                        <option value="location" {{ old('target_type', $targeting['target_type'] ?? '') == 'location' ? 'selected' : '' }}>By Location</option>
                    </select>
                </div>

                <!-- Role Selection -->
                <div id="role-fields" class="hidden mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Roles</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="farmer" 
                                   {{ in_array('farmer', $targeting['target_roles'] ?? []) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-900">Farmers</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="animal_health_professional"
                                   {{ in_array('animal_health_professional', $targeting['target_roles'] ?? []) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-900">Animal Health Professionals</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="target_roles[]" value="volunteer"
                                   {{ in_array('volunteer', $targeting['target_roles'] ?? []) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
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
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">-- Loading Countries... --</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <select name="state_id" id="state_id" onchange="loadLgas()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                disabled>
                                <option value="">-- Select Country First --</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">LGA</label>
                            <select name="lga_id" id="lga_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                disabled>
                                <option value="">-- Select State First --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schedule -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Schedule</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date', $ad->start_date ? \Carbon\Carbon::parse($ad->start_date)->format('Y-m-d') : '') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            End Date <span class="text-gray-500">(Optional)</span>
                        </label>
                        <input type="date" name="end_date" value="{{ old('end_date', $ad->end_date ? \Carbon\Carbon::parse($ad->end_date)->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Leave empty for no end date</p>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="active" {{ old('status', $ad->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $ad->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.ads.show', $ad->id) }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                Update Advertisement
            </button>
        </div>
    </form>

</div>

<script>
    // Store current values
    const currentCountryId = {{ $ad->country_id ?? 'null' }};
    const currentStateId = {{ $ad->state_id ?? 'null' }};
    const currentLgaId = {{ $ad->lga_id ?? 'null' }};

    function previewImage(event) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function toggleTargetFields() {
        const targetType = document.getElementById('target_type').value;
        const roleFields = document.getElementById('role-fields');
        const locationFields = document.getElementById('location-fields');

        roleFields.classList.add('hidden');
        locationFields.classList.add('hidden');

        if (targetType === 'role') {
            roleFields.classList.remove('hidden');
        } else if (targetType === 'location') {
            locationFields.classList.remove('hidden');
            loadCountries();
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
                if (country.id == currentCountryId) option.selected = true;
                countrySelect.appendChild(option);
            });

            if (currentCountryId) loadStates();
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
                if (state.id == currentStateId) option.selected = true;
                stateSelect.appendChild(option);
            });
            stateSelect.disabled = false;

            if (currentStateId) loadLgas();
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
                if (lga.id == currentLgaId) option.selected = true;
                lgaSelect.appendChild(option);
            });
            lgaSelect.disabled = false;
        } catch (error) {
            console.error('Error loading LGAs:', error);
            lgaSelect.innerHTML = '<option value="">-- Error Loading LGAs --</option>';
        }
    }

    window.addEventListener('DOMContentLoaded', function() {
        const targetType = document.getElementById('target_type').value;
        if (targetType) {
            toggleTargetFields();
        }
    });
</script>

@endsection