<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Record - Step 4 - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    @include('farmer.partials.sidebar')

    <div class="flex-1 overflow-auto">
        <header class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-2xl font-bold text-green-600">Farm Record Submission</h1>
                <p class="text-sm text-gray-600">Step 4 of 6: Service Needs</p>
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
                    <span class="text-xs font-medium text-green-600">Step 4 of 6</span>
                    <span class="text-xs text-gray-500">67% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 67%"></div>
                </div>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Service Needs</h2>
                    <p class="text-sm text-gray-600 mb-6">What veterinary services do you need?</p>

                    <form method="POST" action="{{ route('individual.farm-records.step4.store') }}">
                        @csrf

                        <!-- Service Requirements -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                What Services Do You Need?
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @php
                                    $services = [
                                        'vaccination' => 'Vaccination',
                                        'treatment' => 'Treatment',
                                        'consultation' => 'Consultation',
                                        'emergency' => 'Emergency Care',
                                        'breeding' => 'Breeding Services',
                                        'nutrition' => 'Nutritional Advice'
                                    ];
                                    $selectedServices = old('service_needs', session('farm_record_step4.service_needs', []));
                                @endphp
                                @foreach($services as $value => $label)
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="service_needs[]" value="{{ $value }}" {{ in_array($value, (array)$selectedServices) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Urgency Level -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Urgency Level <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @php
                                    $urgency = old('urgency_level', session('farm_record_step4.urgency_level', 'low'));
                                @endphp
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ $urgency == 'low' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="radio" name="urgency_level" value="low" {{ $urgency == 'low' ? 'checked' : '' }} class="h-4 w-4 text-green-600" required>
                                    <span class="ml-2 text-sm font-medium">Low</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-yellow-50 {{ $urgency == 'medium' ? 'border-yellow-600 bg-yellow-50' : 'border-gray-300' }}">
                                    <input type="radio" name="urgency_level" value="medium" {{ $urgency == 'medium' ? 'checked' : '' }} class="h-4 w-4 text-yellow-600">
                                    <span class="ml-2 text-sm font-medium">Medium</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-orange-50 {{ $urgency == 'high' ? 'border-orange-600 bg-orange-50' : 'border-gray-300' }}">
                                    <input type="radio" name="urgency_level" value="high" {{ $urgency == 'high' ? 'checked' : '' }} class="h-4 w-4 text-orange-600">
                                    <span class="ml-2 text-sm font-medium">High</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-red-50 {{ $urgency == 'emergency' ? 'border-red-600 bg-red-50' : 'border-gray-300' }}">
                                    <input type="radio" name="urgency_level" value="emergency" {{ $urgency == 'emergency' ? 'checked' : '' }} class="h-4 w-4 text-red-600">
                                    <span class="ml-2 text-sm font-medium">Emergency</span>
                                </label>
                            </div>
                        </div>

                        <!-- Service Description -->
                        <div class="mb-6">
                            <label for="service_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Service Description
                            </label>
                            <textarea 
                                id="service_description" 
                                name="service_description" 
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Describe what services you need and why..."
                            >{{ old('service_description', session('farm_record_step4.service_description')) }}</textarea>
                        </div>

                        <!-- Preferred Service Date -->
                        <div class="mb-6">
                            <label for="preferred_service_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Preferred Service Date
                            </label>
                            <input 
                                type="date" 
                                id="preferred_service_date" 
                                name="preferred_service_date" 
                                value="{{ old('preferred_service_date', session('farm_record_step4.preferred_service_date')) }}"
                                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                            >
                        </div>

                        <!-- Preferred Veterinary Services -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Preferred Veterinary Services
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @php
                                    $prefServices = old('preferred_vet_services', session('farm_record_step4.preferred_vet_services', []));
                                @endphp
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="preferred_vet_services[]" value="on_site" {{ in_array('on_site', (array)$prefServices) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">On-site Visits</span>
                                </label>
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="preferred_vet_services[]" value="mobile_clinic" {{ in_array('mobile_clinic', (array)$prefServices) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">Mobile Clinics</span>
                                </label>
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="preferred_vet_services[]" value="tele_vet" {{ in_array('tele_vet', (array)$prefServices) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">Tele-vet</span>
                                </label>
                            </div>
                        </div>

                        <!-- Cold Chain Access -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Access to Cold Chain Facilities?
                            </label>
                            <p class="text-xs text-gray-500 mb-3">For vaccine storage</p>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="cold_chain_access" value="1" {{ old('cold_chain_access', session('farm_record_step4.cold_chain_access')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-2 text-sm">Yes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="cold_chain_access" value="0" {{ old('cold_chain_access', session('farm_record_step4.cold_chain_access', '0')) == '0' ? 'checked' : '' }} class="h-4 w-4 text-green-600">
                                    <span class="ml-2 text-sm">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Training & Awareness Needs -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Training & Awareness Needs
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @php
                                    $training = old('training_needs', session('farm_record_step4.training_needs', []));
                                @endphp
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="training_needs[]" value="disease_prevention" {{ in_array('disease_prevention', (array)$training) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">Disease Prevention</span>
                                </label>
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="training_needs[]" value="animal_nutrition" {{ in_array('animal_nutrition', (array)$training) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">Animal Nutrition</span>
                                </label>
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="training_needs[]" value="biosecurity" {{ in_array('biosecurity', (array)$training) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <span class="ml-2 text-sm">Biosecurity</span>
                                </label>
                            </div>
                        </div>

                        <!-- Immediate Attention -->
                        <div class="mb-6">
                            <label class="flex items-center p-4 bg-yellow-50 border border-yellow-300 rounded-md">
                                <input type="checkbox" name="needs_immediate_attention" value="1" {{ old('needs_immediate_attention', session('farm_record_step4.needs_immediate_attention')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-yellow-600 rounded">
                                <span class="ml-3 text-sm font-medium text-yellow-900">This requires immediate attention</span>
                            </label>
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center pt-6 border-t mt-8">
                            <a href="{{ route('individual.farm-records.step3') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition flex items-center">
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