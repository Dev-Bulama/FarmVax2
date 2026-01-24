@extends('layouts.farmer')

@section('title', 'New Farm Record - Step 3')

@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        {{-- Progress Bar --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold" style="color: #11455b;">Step 3 of 3</span>
                <span class="text-sm text-gray-600">Health & Vaccination</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-500" style="width: 100%; background-color: #2fcb6e;"></div>
            </div>
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

        {{-- Form Card --}}
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold" style="color: #11455b;">Health & Vaccination</h2>
                <p class="text-gray-600 mt-1">Final step - Tell us about your livestock's health status</p>
            </div>

            <form action="{{ route('farmer.farm-records.step3.post') }}" method="POST">
                @csrf

                {{-- Vaccination Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4" style="color: #11455b;">💉 Vaccination History</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        
                        {{-- Last Vaccination Date --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Last Vaccination Date
                            </label>
                            <input type="date" name="last_vaccination_date" value="{{ old('last_vaccination_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                        </div>

                        {{-- Vaccination History --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Vaccination History
                            </label>
                            <textarea name="vaccination_history" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                      placeholder="List previous vaccinations (e.g., CBPP vaccine - January 2024, PPR vaccine - March 2024)">{{ old('vaccination_history') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Optional: List the vaccines your animals have received</p>
                        </div>

                    </div>
                </div>

                {{-- Health Status --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4" style="color: #11455b;">🏥 Current Health Status</h3>
                    
                    {{-- Has Health Issues Checkbox --}}
                    <div class="mb-4">
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-red-50">
                            <input type="checkbox" name="has_health_issues" value="1" 
                                   {{ old('has_health_issues') ? 'checked' : '' }}
                                   class="mr-3 h-5 w-5 rounded"
                                   onchange="toggleHealthIssues()">
                            <span class="font-semibold text-gray-900">My livestock currently has health issues</span>
                        </label>
                    </div>

                    {{-- Health Issues Details (Hidden by default) --}}
                    <div id="health-issues-section" class="{{ old('has_health_issues') ? '' : 'hidden' }}">
                        <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-400 space-y-4">
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Describe the Health Issues <span class="text-red-500">*</span>
                                </label>
                                <textarea name="current_health_issues" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                          placeholder="Describe symptoms, affected animals, when it started...">{{ old('current_health_issues') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Additional Health Notes
                                </label>
                                <textarea name="health_notes" rows="2"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                          placeholder="Any other health-related information...">{{ old('health_notes') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Veterinarian Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4" style="color: #11455b;">👨‍⚕️ Veterinarian (Optional)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Veterinarian Name
                            </label>
                            <input type="text" name="veterinarian_name" value="{{ old('veterinarian_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter vet's name">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Veterinarian Phone
                            </label>
                            <input type="tel" name="veterinarian_phone" value="{{ old('veterinarian_phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter vet's phone">
                        </div>

                    </div>
                </div>

                {{-- Service Needs --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4" style="color: #11455b;">🔧 Service Needs</h3>
                    
                    <div class="space-y-4">
                        
                        {{-- Service Description --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                What services do you need?
                            </label>
                            <textarea name="service_needs" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                      placeholder="Describe the veterinary services you need (e.g., vaccination, treatment, consultation, routine checkup)">{{ old('service_needs') }}</textarea>
                        </div>

                        {{-- Urgency Level --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Urgency Level <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                
                                <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition hover:border-[#2fcb6e]">
                                    <input type="radio" name="urgency_level" value="low" {{ old('urgency_level', 'medium') == 'low' ? 'checked' : '' }} class="sr-only">
                                    <div class="text-center">
                                        <div class="text-2xl mb-1">🟢</div>
                                        <span class="text-sm font-semibold">Low</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition hover:border-[#2fcb6e]">
                                    <input type="radio" name="urgency_level" value="medium" {{ old('urgency_level', 'medium') == 'medium' ? 'checked' : '' }} class="sr-only">
                                    <div class="text-center">
                                        <div class="text-2xl mb-1">🟡</div>
                                        <span class="text-sm font-semibold">Medium</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition hover:border-[#2fcb6e]">
                                    <input type="radio" name="urgency_level" value="high" {{ old('urgency_level') == 'high' ? 'checked' : '' }} class="sr-only">
                                    <div class="text-center">
                                        <div class="text-2xl mb-1">🟠</div>
                                        <span class="text-sm font-semibold">High</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition hover:border-red-500">
                                    <input type="radio" name="urgency_level" value="emergency" {{ old('urgency_level') == 'emergency' ? 'checked' : '' }} class="sr-only">
                                    <div class="text-center">
                                        <div class="text-2xl mb-1">🔴</div>
                                        <span class="text-sm font-semibold">Emergency</span>
                                    </div>
                                </label>

                            </div>
                        </div>

                        {{-- Immediate Attention --}}
                        <div>
                            <label class="flex items-center p-4 border-2 border-red-300 bg-red-50 rounded-lg cursor-pointer">
                                <input type="checkbox" name="needs_immediate_attention" value="1" 
                                       {{ old('needs_immediate_attention') ? 'checked' : '' }}
                                       class="mr-3 h-5 w-5 rounded">
                                <span class="font-semibold text-red-900">⚠️ This requires immediate professional attention</span>
                            </label>
                        </div>

                    </div>
                </div>

                {{-- Alert Preferences --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4" style="color: #11455b;">🔔 Alert Preferences</h3>
                    <p class="text-sm text-gray-600 mb-3">Choose how you want to receive updates and alerts</p>
                    
                    <div class="space-y-3">
                        
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-green-50">
                            <input type="checkbox" name="sms_alerts" value="1" 
                                   {{ old('sms_alerts', true) ? 'checked' : '' }}
                                   class="mr-3 h-5 w-5 rounded">
                            <div>
                                <span class="font-semibold text-gray-900">📱 SMS Alerts</span>
                                <p class="text-sm text-gray-600">Receive text messages for important updates</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:bg-green-50">
                            <input type="checkbox" name="email_alerts" value="1" 
                                   {{ old('email_alerts') ? 'checked' : '' }}
                                   class="mr-3 h-5 w-5 rounded">
                            <div>
                                <span class="font-semibold text-gray-900">📧 Email Alerts</span>
                                <p class="text-sm text-gray-600">Receive email notifications and reports</p>
                            </div>
                        </label>

                    </div>
                </div>

                {{-- Final Info Box --}}
                <div class="mb-8 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Almost done! 🎉</p>
                            <p class="text-sm text-blue-800 mt-1">
                                Click "Submit" to complete your farm record registration. Our team will review your information and connect you with veterinary services.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('farmer.farm-records.step2') }}" 
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        ← Back
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl flex items-center"
                            style="background-color: #2fcb6e;">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit Farm Record
                    </button>
                </div>

            </form>

        </div>

        {{-- Help Text --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Need help? <a href="mailto:support@farmvax.com" class="font-semibold" style="color: #2fcb6e;">Contact Support</a>
            </p>
        </div>

    </div>
</div>

<script>
function toggleHealthIssues() {
    const checkbox = document.querySelector('input[name="has_health_issues"]');
    const section = document.getElementById('health-issues-section');
    
    if (checkbox.checked) {
        section.classList.remove('hidden');
    } else {
        section.classList.add('hidden');
    }
}

// Style radio buttons
document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Remove active class from all labels
        document.querySelectorAll('input[name="' + this.name + '"]').forEach(r => {
            r.parentElement.classList.remove('border-[#2fcb6e]', 'bg-green-50');
        });
        // Add active class to selected
        if (this.checked) {
            this.parentElement.classList.add('border-[#2fcb6e]', 'bg-green-50');
        }
    });
    
    // Initialize on page load
    if (radio.checked) {
        radio.parentElement.classList.add('border-[#2fcb6e]', 'bg-green-50');
    }
});
</script>

@endsection