@extends('layouts.farmer')

@section('title', 'New Farm Record - Step 2')

@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        {{-- Progress Bar --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold" style="color: #11455b;">Step 2 of 3</span>
                <span class="text-sm text-gray-600">Livestock Information</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-500" style="width: 66.66%; background-color: #2fcb6e;"></div>
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

        {{-- Success Message --}}
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold" style="color: #11455b;">Livestock Information</h2>
                <p class="text-gray-600 mt-1">Tell us about your animals and their population</p>
            </div>

            <form action="{{ route('farmer.farm-records.step2.post') }}" method="POST">
                @csrf

                {{-- Livestock Types --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Livestock Types <span class="text-red-500">*</span>
                    </label>
                    <p class="text-sm text-gray-600 mb-3">Select all types of animals you have on your farm</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @php
                            $livestockTypes = [
                                'cattle' => '🐄 Cattle',
                                'goats' => '🐐 Goats',
                                'sheep' => '🐑 Sheep',
                                'pigs' => '🐷 Pigs',
                                'poultry' => '🐔 Poultry',
                                'fish' => '🐟 Fish',
                                'rabbits' => '🐰 Rabbits',
                                'horses' => '🐴 Horses',
                                'others' => '🦙 Others'
                            ];
                        @endphp
                        
                        @foreach($livestockTypes as $value => $label)
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer transition hover:border-[#2fcb6e] hover:bg-green-50">
                                <input type="checkbox" name="livestock_types[]" value="{{ $value }}" 
                                       class="mr-2 h-4 w-4 rounded"
                                       style="color: #2fcb6e;"
                                       onchange="updateLivestockTypesInput()">
                                <span class="text-sm font-medium">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    
                    {{-- Hidden input to store selected types as comma-separated string --}}
                    <input type="hidden" name="livestock_types" id="livestock_types_input" value="{{ old('livestock_types') }}">
                </div>

                {{-- Total Livestock Count --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Total Number of Animals <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="total_livestock_count" value="{{ old('total_livestock_count') }}" required min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent text-lg font-semibold"
                           placeholder="Enter total count"
                           onchange="calculateTotal()">
                </div>

                {{-- Age Distribution --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Age Distribution (Optional)
                    </label>
                    <p class="text-sm text-gray-600 mb-3">Break down your animals by age group</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        
                        {{-- Young --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Young (0-1 year)</label>
                            <input type="number" name="young_count" value="{{ old('young_count', 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="0"
                                   onchange="calculateTotal()">
                        </div>

                        {{-- Adult --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Adult (1-7 years)</label>
                            <input type="number" name="adult_count" value="{{ old('adult_count', 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="0"
                                   onchange="calculateTotal()">
                        </div>

                        {{-- Old --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Old (7+ years)</label>
                            <input type="number" name="old_count" value="{{ old('old_count', 0) }}" min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="0"
                                   onchange="calculateTotal()">
                        </div>

                    </div>

                    <div id="age-total-display" class="mt-3 text-sm text-gray-600 hidden">
                        <strong>Age Distribution Total:</strong> <span id="age-total">0</span> animals
                    </div>
                </div>

                {{-- Breed Information --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Breed Information
                    </label>
                    <textarea name="breed_information" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                              placeholder="Describe the breeds you have (e.g., Holstein cattle, Boer goats, Rhode Island Red chickens)">{{ old('breed_information') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Optional: List the specific breeds of your livestock</p>
                </div>

                {{-- Additional Livestock Details --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Additional Details
                    </label>
                    <textarea name="livestock_details" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                              placeholder="Any other information about your livestock (feeding practices, housing, management system, etc.)">{{ old('livestock_details') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Optional: Share any additional information that might be helpful</p>
                </div>

                {{-- Info Box --}}
                <div class="mb-8 bg-green-50 p-4 rounded-lg border-l-4" style="border-color: #2fcb6e;">
                    <div class="flex">
                        <svg class="h-5 w-5 mr-2" style="color: #2fcb6e;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold" style="color: #11455b;">Why do we need this information?</p>
                            <p class="text-sm text-gray-700 mt-1">
                                Knowing your livestock population helps veterinary professionals prepare appropriate vaccines and medications for your animals.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('farmer.farm-records.step1') }}" 
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        ← Back
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
                            style="background-color: #2fcb6e;">
                        Next Step →
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
// Update hidden input when checkboxes change
function updateLivestockTypesInput() {
    const checkboxes = document.querySelectorAll('input[name="livestock_types[]"]:checked');
    const values = Array.from(checkboxes).map(cb => cb.value);
    document.getElementById('livestock_types_input').value = values.join(', ');
}

// Calculate total from age distribution
function calculateTotal() {
    const young = parseInt(document.querySelector('input[name="young_count"]').value) || 0;
    const adult = parseInt(document.querySelector('input[name="adult_count"]').value) || 0;
    const old = parseInt(document.querySelector('input[name="old_count"]').value) || 0;
    const total = young + adult + old;
    
    if (total > 0) {
        document.getElementById('age-total').textContent = total;
        document.getElementById('age-total-display').classList.remove('hidden');
    } else {
        document.getElementById('age-total-display').classList.add('hidden');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Pre-select checkboxes if old input exists
    const oldValue = "{{ old('livestock_types') }}";
    if (oldValue) {
        const selectedTypes = oldValue.split(', ');
        selectedTypes.forEach(type => {
            const checkbox = document.querySelector(`input[value="${type}"]`);
            if (checkbox) checkbox.checked = true;
        });
    }
    
    calculateTotal();
});
</script>

@endsection