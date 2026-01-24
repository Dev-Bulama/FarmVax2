@extends('layouts.farmer')

@section('title', 'Help & Support')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Help & Support</h1>
        <p class="mt-2 text-gray-600">Get help with using FarmVax platform and managing your livestock</p>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <a href="#getting-started" class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Getting Started</h3>
            </div>
            <p class="text-sm text-gray-600">Learn the basics of using FarmVax</p>
        </a>

        <a href="#livestock" class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Livestock Management</h3>
            </div>
            <p class="text-sm text-gray-600">Add and manage your livestock</p>
        </a>

        <a href="#vaccinations" class="block p-6 bg-white rounded-lg shadow hover:shadow-lg transition">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-gray-900">Vaccinations</h3>
            </div>
            <p class="text-sm text-gray-600">Track and schedule vaccinations</p>
        </a>
    </div>

    <!-- FAQs -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Frequently Asked Questions</h2>

        <div class="space-y-6">
            <!-- FAQ 1 -->
            <div id="getting-started" class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Getting Started</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="font-medium text-gray-800">How do I add my first livestock?</h4>
                        <p class="mt-1 text-sm text-gray-600">Navigate to "My Livestock" from the sidebar menu and click "Add Livestock". Fill in the required information about your animal and click save.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">How do I update my profile?</h4>
                        <p class="mt-1 text-sm text-gray-600">Click on "My Profile" in the sidebar to view and edit your personal information, contact details, and farm location.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div id="livestock" class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Livestock Management</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="font-medium text-gray-800">Can I add multiple types of livestock?</h4>
                        <p class="mt-1 text-sm text-gray-600">Yes! FarmVax supports cattle, goats, sheep, pigs, poultry, fish, and more. You can add as many animals as you have.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">How do I track my herd groups?</h4>
                        <p class="mt-1 text-sm text-gray-600">Use the "Herd Groups" feature to organize your livestock into groups for easier management and vaccination tracking.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div id="vaccinations" class="pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Vaccinations & Health</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="font-medium text-gray-800">How do I record a vaccination?</h4>
                        <p class="mt-1 text-sm text-gray-600">Go to "Vaccinations" and click "Record Vaccination". Select the livestock, vaccine type, date, and any additional details.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Will I receive vaccination reminders?</h4>
                        <p class="mt-1 text-sm text-gray-600">Yes! The system will send you notifications when vaccinations are due for your livestock.</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">What should I do if there's a disease outbreak?</h4>
                        <p class="mt-1 text-sm text-gray-600">You'll receive outbreak alerts on your dashboard. Follow the recommended actions and consider requesting professional veterinary services through "Service Requests".</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-lg p-8 text-white">
        <div class="flex items-center mb-4">
            <svg class="h-8 w-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <h2 class="text-2xl font-bold">Need More Help?</h2>
        </div>
        <p class="mb-6">Can't find what you're looking for? Our support team is here to help!</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white/10 rounded-lg p-4">
                <h3 class="font-semibold mb-2">Email Support</h3>
                <p class="text-sm mb-2">Get help via email</p>
                <a href="mailto:support@farmvax.com" class="text-green-200 hover:text-white font-semibold">support@farmvax.com</a>
            </div>

            <div class="bg-white/10 rounded-lg p-4">
                <h3 class="font-semibold mb-2">Request Professional Service</h3>
                <p class="text-sm mb-2">Need veterinary assistance?</p>
                <a href="{{ route('farmer.service-requests.create') }}" class="inline-block px-4 py-2 bg-white text-green-700 rounded-lg font-semibold hover:bg-green-50 transition">
                    Request Service →
                </a>
            </div>
        </div>
    </div>

    <!-- Video Tutorials -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Video Tutorials</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded mb-3 flex items-center justify-center">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900">Getting Started with FarmVax</h4>
                <p class="text-sm text-gray-600 mt-1">Learn the basics in 5 minutes</p>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded mb-3 flex items-center justify-center">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900">Recording Vaccinations</h4>
                <p class="text-sm text-gray-600 mt-1">Step-by-step vaccination tracking</p>
            </div>
        </div>
    </div>
</div>
@endsection
