<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approval - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    @include('professional.partials.sidebar')

    <div class="flex-1 overflow-auto">
        <header class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-2xl font-bold text-blue-600">Application Status</h1>
                <p class="text-sm text-gray-600">Your professional application is under review</p>
            </div>
            
            <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                <svg id="menu-open-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="menu-close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <main class="px-4 sm:px-6 lg:px-8 py-8">
            @if(session('info'))
            <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 rounded-md p-4">
                {{ session('info') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
                {{ session('error') }}
            </div>
            @endif

            <div class="max-w-3xl mx-auto">
                <!-- Pending Status Card -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-8 text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Application Under Review</h2>
                        <p class="text-gray-600 mb-6">Your professional application has been submitted and is currently being reviewed by our team.</p>
                        
                        @if(auth()->user()->animalHealthProfessional)
                        <div class="inline-flex items-center px-4 py-2 bg-yellow-50 border border-yellow-200 rounded-full">
                            <span class="text-sm font-medium text-yellow-800">Status: {{ ucfirst(auth()->user()->animalHealthProfessional->approval_status) }}</span>
                        </div>
                        
                        @if(auth()->user()->animalHealthProfessional->submitted_at)
                        <p class="text-xs text-gray-500 mt-4">Submitted {{ auth()->user()->animalHealthProfessional->submitted_at->diffForHumans() }}</p>
                        @endif
                        @endif
                    </div>
                </div>

                <!-- What to Expect -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">What to Expect</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                        <span class="text-sm font-medium text-blue-600">1</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Document Verification</h4>
                                    <p class="text-sm text-gray-600">Our team is verifying your professional credentials and documents.</p>
                                </div>
                            </div>

                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                        <span class="text-sm font-medium text-blue-600">2</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Background Check</h4>
                                    <p class="text-sm text-gray-600">We're conducting a standard background verification.</p>
                                </div>
                            </div>

                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-100">
                                        <span class="text-sm font-medium text-blue-600">3</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900">Approval Notification</h4>
                                    <p class="text-sm text-gray-600">You'll receive an email once your application is approved.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Review Timeline</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">Typical approval process takes 2-5 business days.</p>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-800">We'll notify you via email and SMS once your application has been reviewed.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Need Help -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Need Help?</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">If you have questions about your application status, please contact us:</p>
                        <div class="space-y-2">
                            <p class="text-sm">
                                <span class="font-medium text-gray-900">Email:</span>
                                <a href="mailto:support@farmvax.com" class="text-blue-600 hover:text-blue-800 ml-2">support@farmvax.com</a>
                            </p>
                            <p class="text-sm">
                                <span class="font-medium text-gray-900">Phone:</span>
                                <a href="tel:+2348000000000" class="text-blue-600 hover:text-blue-800 ml-2">+234-800-000-0000</a>
                            </p>
                        </div>
                    </div>
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