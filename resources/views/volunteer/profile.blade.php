@extends('layouts.volunteer')

@section('title', 'My Profile')

@section('content')

<div class="p-6">
    
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold" style="color: #11455b;">My Profile</h1>
        <p class="text-gray-600 mt-1">Manage your account information</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <p class="text-green-800 font-semibold">✓ {{ session('success') }}</p>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <p class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</p>
            <ul class="text-sm text-red-700 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Personal Information --}}
            <form action="{{ route('volunteer.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4" style="color: #11455b;">👤 Personal Information</h2>
                    
                    <div class="space-y-4">
                        
                        {{-- Full Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   style="focus:ring-color: #2fcb6e;">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        </div>

                        {{-- Location Section - NEW --}}
                        <div class="border-t pt-4 mt-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">📍 Location Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Country --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                                    <select name="country_id" id="countrySelect"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                        <option value="">-- Select Country --</option>
                                        @foreach(\App\Models\Country::orderBy('name')->get() as $country)
                                            <option value="{{ $country->id }}" 
                                                    {{ old('country_id', auth()->user()->country_id) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- State --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">State</label>
                                    <select name="state_id" id="stateSelect" disabled
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent bg-gray-100">
                                        <option value="">-- Select State --</option>
                                    </select>
                                </div>

                                {{-- LGA --}}
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">LGA</label>
                                    <select name="lga_id" id="lgaSelect" disabled
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent bg-gray-100">
                                        <option value="">-- Select LGA --</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">{{ old('address', auth()->user()->address) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- Volunteer Information --}}
                @if(auth()->user()->volunteer)
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🤝 Volunteer Details</h2>
                        
                        <div class="space-y-4">
                            
                            {{-- Organization --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Organization (Optional)</label>
                                <input type="text" name="organization" 
                                       value="{{ old('organization', auth()->user()->volunteer->organization) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                       placeholder="e.g., FarmVax Community Volunteers">
                            </div>

                            {{-- Assigned Area --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Assigned Area</label>
                                <input type="text" name="assigned_area" 
                                       value="{{ old('assigned_area', auth()->user()->volunteer->assigned_area) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                       placeholder="e.g., Kano State">
                            </div>

                            {{-- Motivation --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Motivation</label>
                                <textarea name="motivation" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                          placeholder="Why did you become a volunteer?">{{ old('motivation', auth()->user()->volunteer->motivation) }}</textarea>
                            </div>

                        </div>
                    </div>
                @endif

                {{-- Change Password --}}
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🔒 Change Password</h2>
                    
                    <div class="space-y-4">
                        
                        {{-- Current Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter current password">
                            <p class="text-xs text-gray-500 mt-1">Required only if changing password</p>
                        </div>

                        {{-- New Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter new password">
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Confirm new password">
                        </div>

                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-3 text-white font-semibold rounded-lg transition"
                            style="background-color: #2fcb6e;">
                        💾 Save Changes
                    </button>
                </div>

            </form>

        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            
            {{-- Profile Stats --}}
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-bold mb-4" style="color: #11455b;">Profile Stats</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Farmers Enrolled</p>
                        <p class="text-2xl font-bold" style="color: #2fcb6e;">{{ auth()->user()->volunteer->farmers_enrolled ?? 0 }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Referral Code</p>
                        <p class="text-lg font-mono font-bold text-purple-600">{{ auth()->user()->volunteer->referral_code ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Member Since</p>
                        <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

{{-- JavaScript for Location Dropdowns --}}
<script>
const currentCountryId = "{{ old('country_id', auth()->user()->country_id) }}";
const currentStateId = "{{ old('state_id', auth()->user()->state_id) }}";
const currentLgaId = "{{ old('lga_id', auth()->user()->lga_id) }}";

const countrySelect = document.getElementById('countrySelect');
const stateSelect = document.getElementById('stateSelect');
const lgaSelect = document.getElementById('lgaSelect');

// Load states when country changes
countrySelect.addEventListener('change', async function() {
    const countryId = this.value;
    
    stateSelect.innerHTML = '<option value="">-- Select State --</option>';
    stateSelect.disabled = true;
    lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
    lgaSelect.disabled = true;
    
    if (countryId) {
        stateSelect.innerHTML = '<option value="">Loading states...</option>';
        stateSelect.classList.add('bg-gray-100');
        
        try {
            const response = await fetch(`/api/states/${countryId}`);
            const states = await response.json();
            
            stateSelect.innerHTML = '<option value="">-- Select State --</option>';
            stateSelect.classList.remove('bg-gray-100');
            
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
            
            // If state was pre-selected, trigger LGA load
            if (currentStateId) {
                stateSelect.dispatchEvent(new Event('change'));
            }
        } catch (error) {
            console.error('Error loading states:', error);
            stateSelect.innerHTML = '<option value="">Error loading states</option>';
        }
    }
});

// Load LGAs when state changes
stateSelect.addEventListener('change', async function() {
    const stateId = this.value;
    
    lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
    lgaSelect.disabled = true;
    
    if (stateId) {
        lgaSelect.innerHTML = '<option value="">Loading LGAs...</option>';
        lgaSelect.classList.add('bg-gray-100');
        
        try {
            const response = await fetch(`/api/lgas/${stateId}`);
            const lgas = await response.json();
            
            lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
            lgaSelect.classList.remove('bg-gray-100');
            
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
        } catch (error) {
            console.error('Error loading LGAs:', error);
            lgaSelect.innerHTML = '<option value="">Error loading LGAs</option>';
        }
    }
});

// Load states on page load if country is pre-selected
window.addEventListener('DOMContentLoaded', function() {
    if (currentCountryId) {
        countrySelect.dispatchEvent(new Event('change'));
    }
});
</script>

@endsection