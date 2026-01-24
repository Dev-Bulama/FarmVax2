@extends('layouts.farmer')

@section('title', 'Request Service')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('farmer.service-requests.index') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Service Requests
        </a>
        <h1 class="text-3xl font-bold mt-2" style="color: #11455b;">Request Veterinary Service</h1>
        <p class="text-gray-600 mt-1">Get professional help for your livestock</p>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
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

    <form action="{{ route('farmer.service-requests.store') }}" method="POST">
        @csrf

        {{-- Service Information --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🏥 Service Details</h2>
            
            <div class="space-y-4">
                
                {{-- Service Type --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Service Type <span class="text-red-500">*</span>
                    </label>
                    <select name="service_type" id="service_type" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        <option value="">Select Service Type</option>
                        <option value="vaccination" {{ old('service_type') == 'vaccination' ? 'selected' : '' }}>💉 Vaccination</option>
                        <option value="treatment" {{ old('service_type') == 'treatment' ? 'selected' : '' }}>🏥 Treatment</option>
                        <option value="consultation" {{ old('service_type') == 'consultation' ? 'selected' : '' }}>👨‍⚕️ Consultation</option>
                        <option value="emergency" {{ old('service_type') == 'emergency' ? 'selected' : '' }}>🚨 Emergency</option>
                        <option value="routine_checkup" {{ old('service_type') == 'routine_checkup' ? 'selected' : '' }}>🩺 Routine Checkup</option>
                        <option value="breeding" {{ old('service_type') == 'breeding' ? 'selected' : '' }}>🐾 Breeding</option>
                        <option value="deworming" {{ old('service_type') == 'deworming' ? 'selected' : '' }}>💊 Deworming</option>
                        <option value="pregnancy_check" {{ old('service_type') == 'pregnancy_check' ? 'selected' : '' }}>🤰 Pregnancy Check</option>
                        <option value="disease_diagnosis" {{ old('service_type') == 'disease_diagnosis' ? 'selected' : '' }}>🔬 Disease Diagnosis</option>
                        <option value="other" {{ old('service_type') == 'other' ? 'selected' : '' }}>📋 Other</option>
                    </select>
                </div>

                {{-- Other Service Type (shown when Other is selected) --}}
                <div id="other_service_div" style="display: none;">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Specify Service Type</label>
                    <input type="text" name="other_service_type" value="{{ old('other_service_type') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                </div>

                {{-- Service Title --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Service Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="service_title" value="{{ old('service_title') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="e.g., Vaccination for 10 cattle">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                              placeholder="Describe the service you need...">{{ old('description') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Livestock Information --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🐄 Livestock Information</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Livestock Type --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Livestock Type</label>
                    <select name="livestock_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        <option value="">Select Type</option>
                        <option value="cattle" {{ old('livestock_type') == 'cattle' ? 'selected' : '' }}>🐄 Cattle</option>
                        <option value="goats" {{ old('livestock_type') == 'goats' ? 'selected' : '' }}>🐐 Goats</option>
                        <option value="sheep" {{ old('livestock_type') == 'sheep' ? 'selected' : '' }}>🐑 Sheep</option>
                        <option value="pigs" {{ old('livestock_type') == 'pigs' ? 'selected' : '' }}>🐷 Pigs</option>
                        <option value="poultry" {{ old('livestock_type') == 'poultry' ? 'selected' : '' }}>🐔 Poultry</option>
                    </select>
                </div>

                {{-- Number of Animals --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Animals</label>
                    <input type="number" name="number_of_animals" value="{{ old('number_of_animals', 1) }}" min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                </div>

            </div>
        </div>

        {{-- Urgency & Scheduling --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">⏰ Urgency & Scheduling</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Priority --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Priority Level</label>
                    <select name="priority"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - Routine</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium - Important</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Urgent</option>
                        <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical - Emergency</option>
                    </select>
                </div>

                {{-- Preferred Date --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Date</label>
                    <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                </div>

                {{-- Preferred Time --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time</label>
                    <select name="time_preference"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        <option value="anytime" {{ old('time_preference') == 'anytime' ? 'selected' : '' }}>Anytime</option>
                        <option value="morning" {{ old('time_preference') == 'morning' ? 'selected' : '' }}>Morning (8am - 12pm)</option>
                        <option value="afternoon" {{ old('time_preference') == 'afternoon' ? 'selected' : '' }}>Afternoon (12pm - 5pm)</option>
                        <option value="evening" {{ old('time_preference') == 'evening' ? 'selected' : '' }}>Evening (5pm - 8pm)</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- Location & Contact --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">📍 Location & Contact</h2>
            
            <div class="space-y-4">
                
                {{-- Service Location --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Service Location</label>
                    <textarea name="service_location" rows="2"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                              placeholder="Where should the veterinarian visit?">{{ old('service_location', auth()->user()->address) }}</textarea>
                </div>

                {{-- Contact Phone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Phone</label>
                    <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                </div>

            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-between bg-white rounded-lg shadow p-6">
            <a href="{{ route('farmer.service-requests.index') }}" 
               class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
                    style="background-color: #2fcb6e;">
                Submit Request
            </button>
        </div>

    </form>

</div>

<script>
    // Show/hide other service type field
    document.getElementById('service_type').addEventListener('change', function() {
        const otherDiv = document.getElementById('other_service_div');
        if (this.value === 'other') {
            otherDiv.style.display = 'block';
        } else {
            otherDiv.style.display = 'none';
        }
    });
    
    // Check on page load
    if (document.getElementById('service_type').value === 'other') {
        document.getElementById('other_service_div').style.display = 'block';
    }
</script>

@endsection