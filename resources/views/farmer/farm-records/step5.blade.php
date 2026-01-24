<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Record - Step 5 - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    @include('farmer.partials.sidebar')

    <div class="flex-1 overflow-auto">
        <header class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-2xl font-bold text-green-600">Farm Record Submission</h1>
                <p class="text-sm text-gray-600">Step 5 of 6: Alert Preferences</p>
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
                    <span class="text-xs font-medium text-green-600">Step 5 of 6</span>
                    <span class="text-xs text-gray-500">83% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 83%"></div>
                </div>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Alert Preferences</h2>
                    <p class="text-sm text-gray-600 mb-6">How would you like to receive alerts and notifications?</p>

                    <form method="POST" action="{{ route('individual.farm-records.step5.store') }}">
                        @csrf

                        <!-- Alert Methods -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Alert Methods <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ old('sms_alerts', session('farm_record_step5.sms_alerts', '1')) == '1' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="checkbox" name="sms_alerts" value="1" {{ old('sms_alerts', session('farm_record_step5.sms_alerts', '1')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">SMS Alerts</span>
                                        <p class="text-xs text-gray-500">Receive text messages for important updates</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ old('email_alerts', session('farm_record_step5.email_alerts')) == '1' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="checkbox" name="email_alerts" value="1" {{ old('email_alerts', session('farm_record_step5.email_alerts')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Email Alerts</span>
                                        <p class="text-xs text-gray-500">Get detailed updates via email</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ old('phone_alerts', session('farm_record_step5.phone_alerts')) == '1' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="checkbox" name="phone_alerts" value="1" {{ old('phone_alerts', session('farm_record_step5.phone_alerts')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Phone Calls</span>
                                        <p class="text-xs text-gray-500">Receive phone calls for urgent matters</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Alert Types -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                What Alerts Do You Want to Receive?
                            </label>
                            <div class="space-y-3">
                                @php
                                    $alertTypes = old('alert_types', session('farm_record_step5.alert_types', []));
                                @endphp
                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="alert_types[]" value="outbreak" {{ in_array('outbreak', (array)$alertTypes) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Outbreak Alerts</span>
                                        <p class="text-xs text-gray-500">Immediate SMS/WhatsApp for disease outbreaks</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="alert_types[]" value="vaccine_availability" {{ in_array('vaccine_availability', (array)$alertTypes) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Vaccine Availability</span>
                                        <p class="text-xs text-gray-500">Location-based updates when vaccines arrive</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="alert_types[]" value="awareness_campaigns" {{ in_array('awareness_campaigns', (array)$alertTypes) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Awareness Campaigns</span>
                                        <p class="text-xs text-gray-500">Workshops, radio programs, webinars</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="alert_types[]" value="public_announcements" {{ in_array('public_announcements', (array)$alertTypes) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Public Announcements</span>
                                        <p class="text-xs text-gray-500">Government directives, market closures, movement restrictions</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-3 border rounded-md cursor-pointer hover:bg-green-50">
                                    <input type="checkbox" name="alert_types[]" value="vaccination_reminders" {{ in_array('vaccination_reminders', (array)$alertTypes) ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Vaccination Reminders</span>
                                        <p class="text-xs text-gray-500">Reminders for upcoming vaccinations</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Preferred Contact Method -->
                        <div class="mb-6">
                            <label for="preferred_contact_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Preferred Contact Method <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="preferred_contact_method" 
                                name="preferred_contact_method" 
                                required
                                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                            >
                                @php
                                    $prefMethod = old('preferred_contact_method', session('farm_record_step5.preferred_contact_method', 'sms'));
                                @endphp
                                <option value="sms" {{ $prefMethod == 'sms' ? 'selected' : '' }}>SMS</option>
                                <option value="email" {{ $prefMethod == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="phone" {{ $prefMethod == 'phone' ? 'selected' : '' }}>Phone Call</option>
                                <option value="whatsapp" {{ $prefMethod == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                            </select>
                        </div>

                        <!-- Alternative Phone -->
                        <div class="mb-6">
                            <label for="alternative_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Alternative Phone Number
                            </label>
                            <input 
                                type="tel" 
                                id="alternative_phone" 
                                name="alternative_phone" 
                                value="{{ old('alternative_phone', session('farm_record_step5.alternative_phone')) }}"
                                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="+234-800-000-0000"
                            >
                            <p class="mt-1 text-xs text-gray-500">In case we can't reach you on your primary number</p>
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center pt-6 border-t mt-8">
                            <a href="{{ route('individual.farm-records.step4') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition flex items-center">
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