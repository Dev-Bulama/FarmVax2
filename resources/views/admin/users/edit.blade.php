@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')

<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-[#11455B] hover:text-[#0d3345] flex items-center">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Users
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

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            
            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select name="role" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                            <option value="">-- Select Role --</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="farmer" {{ old('role', $user->role) == 'farmer' ? 'selected' : '' }}>Farmer</option>
                            <option value="animal_health_professional" {{ old('role', $user->role) == 'animal_health_professional' ? 'selected' : '' }}>Animal Health Professional</option>
                            <option value="volunteer" {{ old('role', $user->role) == 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-gray-500">(Leave blank to keep current password)</span>
                    </label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent"
                           placeholder="Enter new password (optional)">
                    <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                </div>
            </div>

            <!-- Location Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Location Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Country -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <select name="country_id" id="countrySelect"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                            <option value="">-- Select Country --</option>
                            @foreach(\App\Models\Country::orderBy('name')->get() as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- State -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                        <select name="state_id" id="stateSelect" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent bg-gray-100">
                            <option value="">-- Select State --</option>
                        </select>
                    </div>

                    <!-- LGA -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">LGA</label>
                        <select name="lga_id" id="lgaSelect" disabled
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent bg-gray-100">
                            <option value="">-- Select LGA --</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>

            <!-- Account Status -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Account Status</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="account_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                        <option value="active" {{ old('account_status', $user->account_status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('account_status', $user->account_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ old('account_status', $user->account_status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="banned" {{ old('account_status', $user->account_status) == 'banned' ? 'selected' : '' }}>Banned</option>
                    </select>
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
                Update User
            </button>
        </div>
    </form>

</div>

{{-- SINGLE UNIFIED JAVASCRIPT FOR LOCATION DROPDOWNS --}}
<script>
// Store initial values
const currentCountryId = "{{ old('country_id', $user->country_id) }}";
const currentStateId = "{{ old('state_id', $user->state_id) }}";
const currentLgaId = "{{ old('lga_id', $user->lga_id) }}";

const countrySelect = document.getElementById('countrySelect');
const stateSelect = document.getElementById('stateSelect');
const lgaSelect = document.getElementById('lgaSelect');

// Country change event
countrySelect.addEventListener('change', async function() {
    const countryId = this.value;
    
    // Reset dependent dropdowns
    stateSelect.innerHTML = '<option value="">-- Select State --</option>';
    stateSelect.disabled = true;
    stateSelect.classList.add('bg-gray-100');
    lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
    lgaSelect.disabled = true;
    lgaSelect.classList.add('bg-gray-100');
    
    if (countryId) {
        stateSelect.innerHTML = '<option value="">Loading states...</option>';
        
        try {
            const response = await fetch(`/api/states/${countryId}`);
            const states = await response.json();
            
            stateSelect.innerHTML = '<option value="">-- Select State --</option>';
            
            states.forEach(state => {
                const option = document.createElement('option');
                option.value = state.id;
                option.textContent = state.name;
                if (currentStateId && currentStateId == state.id) {
                    option.selected = true;
                }
                stateSelect.appendChild(option);
            });
            
            stateSelect.disabled = false;
            stateSelect.classList.remove('bg-gray-100');
            
            // If state was pre-selected, trigger load LGAs
            if (currentStateId) {
                stateSelect.dispatchEvent(new Event('change'));
            }
        } catch (error) {
            console.error('Error loading states:', error);
            stateSelect.innerHTML = '<option value="">Error loading states</option>';
        }
    }
});

// State change event
stateSelect.addEventListener('change', async function() {
    const stateId = this.value;
    
    // Reset LGA dropdown
    lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
    lgaSelect.disabled = true;
    lgaSelect.classList.add('bg-gray-100');
    
    if (stateId) {
        lgaSelect.innerHTML = '<option value="">Loading LGAs...</option>';
        
        try {
            const response = await fetch(`/api/lgas/${stateId}`);
            const lgas = await response.json();
            
            lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
            
            lgas.forEach(lga => {
                const option = document.createElement('option');
                option.value = lga.id;
                option.textContent = lga.name;
                if (currentLgaId && currentLgaId == lga.id) {
                    option.selected = true;
                }
                lgaSelect.appendChild(option);
            });
            
            lgaSelect.disabled = false;
            lgaSelect.classList.remove('bg-gray-100');
        } catch (error) {
            console.error('Error loading LGAs:', error);
            lgaSelect.innerHTML = '<option value="">Error loading LGAs</option>';
        }
    }
});

// Initialize on page load
window.addEventListener('DOMContentLoaded', function() {
    if (currentCountryId) {
        // Trigger cascade if country is already selected
        countrySelect.dispatchEvent(new Event('change'));
    }
});
</script>

@endsection