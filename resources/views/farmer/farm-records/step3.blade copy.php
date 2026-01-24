@extends('layouts.farmer')

@section('title', 'Farm Record - Step 3')

@section('content')

<div class="max-w-4xl mx-auto">
    
    <!-- Progress Bar -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-green-600">Step 3 of 3</span>
            <span class="text-sm text-gray-500">100% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: 100%"></div>
        </div>
        
        <!-- Step Indicators -->
        <div class="mt-4 grid grid-cols-3 gap-2">
            <div class="text-center">
                <div class="w-10 h-10 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Basic Info</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Livestock</p>
            </div>
            <div class="text-center">
                <div class="w-10 h-10 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Health</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Health Information</h2>
            <p class="text-sm text-gray-600 mt-1">Final step - health and veterinary information</p>
        </div>

        <form method="POST" action="{{ route('farmer.farm-records.step3.store') }}" class="p-6">
            @csrf

            <div class="space-y-6">
                
                <!-- Last Vaccination Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Last Vaccination Date
                    </label>
                    <input type="date" name="last_vaccination_date" value="{{ old('last_vaccination_date', $farmRecordData['step3']['last_vaccination_date'] ?? '') }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    @error('last_vaccination_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Has Health Issues -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="has_health_issues" value="1"
                               {{ old('has_health_issues', $farmRecordData['step3']['has_health_issues'] ?? false) ? 'checked' : '' }}
                               onclick="toggleHealthIssues(this)"
                               class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <span class="ml-3 text-sm font-semibold text-gray-700">My livestock currently has health issues</span>
                    </label>
                </div>

                <!-- Current Health Issues (shown if checkbox checked) -->
                <div id="healthIssuesSection" style="display: {{ old('has_health_issues', $farmRecordData['step3']['has_health_issues'] ?? false) ? 'block' : 'none' }};">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Current Health Issues</label>
                        <div class="space-y-2">
                            @php
                                $healthIssues = [
                                    'fever' => 'Fever',
                                    'diarrhea' => 'Diarrhea',
                                    'coughing' => 'Coughing/Respiratory Issues',
                                    'lameness' => 'Lameness/Difficulty Walking',
                                    'skin_problems' => 'Skin Problems',
                                    'weight_loss' => 'Weight Loss',
                                    'reduced_appetite' => 'Reduced Appetite',
                                    'abnormal_discharge' => 'Abnormal Discharge',
                                    'other' => 'Other',
                                ];
                                $selectedIssues = old('current_health_issues', $farmRecordData['step3']['current_health_issues'] ?? []);
                            @endphp
                            
                            @foreach($healthIssues as $key => $label)
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" name="current_health_issues[]" value="{{ $key }}"
                                       {{ in_array($key, $selectedIssues) ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('current_health_issues')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Health Notes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Additional Health Notes
                    </label>
                    <textarea name="health_notes" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Provide any additional health information or concerns...">{{ old('health_notes', $farmRecordData['step3']['health_notes'] ?? '') }}</textarea>
                    @error('health_notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Veterinarian Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-bold text-gray-900 mb-4">🏥 Veterinarian Information (Optional)</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Veterinarian Name</label>
                            <input type="text" name="veterinarian_name" value="{{ old('veterinarian_name', $farmRecordData['step3']['veterinarian_name'] ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Dr. John Doe">
                            @error('veterinarian_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Veterinarian Phone</label>
                            <input type="text" name="veterinarian_phone" value="{{ old('veterinarian_phone', $farmRecordData['step3']['veterinarian_phone'] ?? '') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="+234 XXX XXX XXXX">
                            @error('veterinarian_phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Vet Visit</label>
                            <input type="date" name="last_vet_visit" value="{{ old('last_vet_visit', $farmRecordData['step3']['last_vet_visit'] ?? '') }}"
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            @error('last_vet_visit')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('farmer.farm-records.step2') }}" class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Previous
                </a>
                <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition flex items-center shadow-lg">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Submit Farm Record
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function toggleHealthIssues(checkbox) {
    const section = document.getElementById('healthIssuesSection');
    if (checkbox.checked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
}
</script>

@endsection