<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Record - Step 3 - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    @include('farmer.partials.sidebar')

    <div class="flex-1 overflow-auto">
        <header class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-2xl font-bold text-green-600">Farm Record Submission</h1>
                <p class="text-sm text-gray-600">Step 3 of 6: Health & Vaccination History</p>
            </div>
            
            <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-green-600 text-white hover:bg-green-700">
                <svg id="menu-open-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="menu-close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <main class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-green-600">Step 3 of 6</span>
                    <span class="text-xs text-gray-500">50% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 50%"></div>
                </div>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Health & Vaccination History</h2>
                    <p class="text-sm text-gray-600 mb-6">Provide information about livestock health and disease history.</p>

                    <form method="POST" action="{{ route('individual.farm-records.step3.store') }}">
                        @csrf

                        <!-- Last Vaccination Date -->
                        <div class="mb-6">
                            <label for="last_vaccination_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Vaccination Date
                            </label>
                            <input 
                                type="date" 
                                id="last_vaccination_date" 
                                name="last_vaccination_date" 
                                value="{{ old('last_vaccination_date', session('farm_record_step3.last_vaccination_date')) }}"
                                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <!-- Common Diseases Experienced -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Common Diseases Experienced
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @php
                                    $diseases = [
                                        'fmd' => 'Foot-and-Mouth Disease (FMD)',
                                        'newcastle' => 'Newcastle',
                                        'anthrax' => 'Anthrax',
                                        'cbpp' => 'CBPP',
                                        'lsd' => 'Lumpy Skin Disease (LSD)',
                                        'ppr' => 'PPR',
                                        'fasciolosis' => 'Fasciolosis',
                                        'trypanosomosis' => 'Trypanosomosis',
                                        'mastitis' => 'Mastitis',
                                        'brucellosis' => 'Brucellosis',
                                        'other' => 'Other'
                                    ];
                                    $selectedDiseases = old('past_diseases', session('farm_record_step3.past_diseases', []));
                                @endphp
                                @foreach($diseases as $value => $label)
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="past_diseases[]" value="{{ $value }}" {{ in_array($value, (array)$selectedDiseases) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Current Health Issues -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Any Current Health Issues?
                            </label>
                            <div class="flex gap-4 mb-3">
                                <label class="flex items-center">
                                    <input type="radio" name="has_health_issues" value="1" {{ old('has_health_issues', session('farm_record_step3.has_health_issues')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-2 text-sm">Yes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="has_health_issues" value="0" {{ old('has_health_issues', session('farm_record_step3.has_health_issues', '0')) == '0' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-2 text-sm">No</span>
                                </label>
                            </div>
                            <textarea 
                                name="health_notes" 
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Describe any current health concerns..."
                            >{{ old('health_notes', session('farm_record_step3.health_notes')) }}</textarea>
                        </div>

                        <!-- Veterinarian Information -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Veterinarian Information</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="veterinarian_name" class="block text-xs text-gray-600 mb-1">Vet Name</label>
                                    <input 
                                        type="text" 
                                        id="veterinarian_name" 
                                        name="veterinarian_name" 
                                        value="{{ old('veterinarian_name', session('farm_record_step3.veterinarian_name')) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        placeholder="Dr. Name"
                                    >
                                </div>
                                <div>
                                    <label for="veterinarian_phone" class="block text-xs text-gray-600 mb-1">Vet Phone</label>
                                    <input 
                                        type="tel" 
                                        id="veterinarian_phone" 
                                        name="veterinarian_phone" 
                                        value="{{ old('veterinarian_phone', session('farm_record_step3.veterinarian_phone')) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                        placeholder="+234-800-000-0000"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Last Vet Visit -->
                        <div class="mb-6">
                            <label for="last_vet_visit" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Veterinary Visit
                            </label>
                            <input 
                                type="date" 
                                id="last_vet_visit" 
                                name="last_vet_visit" 
                                value="{{ old('last_vet_visit', session('farm_record_step3.last_vet_visit')) }}"
                                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <!-- Disease Outbreak History -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Disease Outbreak History
                            </label>
                            <div class="flex gap-4 mb-3">
                                <label class="flex items-center">
                                    <input type="radio" name="disease_outbreak_history" value="1" {{ old('disease_outbreak_history', session('farm_record_step3.disease_outbreak_history')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-2 text-sm">Yes, we've experienced outbreaks</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="disease_outbreak_history" value="0" {{ old('disease_outbreak_history', session('farm_record_step3.disease_outbreak_history', '0')) == '0' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-2 text-sm">No outbreaks</span>
                                </label>
                            </div>
                            <textarea 
                                name="disease_notes" 
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Describe any past disease outbreaks..."
                            >{{ old('disease_notes', session('farm_record_step3.disease_notes')) }}</textarea>
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center pt-6 border-t mt-8">
                            <a href="{{ route('individual.farm-records.step2') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition flex items-center">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Previous
                            </a>
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center">
                                Next Step
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    const menuButton = document.getElementById('mobile-menu-button');
    const menuOpenIcon = document.getElementById('menu-open-icon');
    const menuCloseIcon = document.getElementById('menu-close-icon');

    if (menuButton) {
        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            menuOpenIcon.classList.toggle('hidden');
            menuCloseIcon.classList.toggle('hidden');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            menuOpenIcon.classList.remove('hidden');
            menuCloseIcon.classList.add('hidden');
        });
    }
});
</script>

</body>
</html>