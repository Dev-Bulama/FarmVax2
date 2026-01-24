<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Successful - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Component -->
        @include('components.data-collector-sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center">
                        <button onclick="toggleSidebar()" class="mr-4 text-gray-500 hover:text-gray-700 md:hidden">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Submission Successful</h1>
                            <p class="text-sm text-gray-600">Your farm record has been submitted</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Data Collector</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6">
                <div class="max-w-3xl mx-auto">
                    
                    <!-- Success Card -->
                    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                        
                        <!-- Success Header -->
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-white mb-6">
                                <svg class="h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-white mb-2">Submission Successful!</h2>
                            <p class="text-lg text-green-50">Your farm record has been submitted for review</p>
                        </div>

                        <!-- Content -->
                        <div class="px-6 py-8">
                            
                            <!-- What Happens Next -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">What Happens Next?</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600 font-semibold">1</div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Admin Review</p>
                                            <p class="text-sm text-gray-600">An administrator will review your submission for accuracy and completeness.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600 font-semibold">2</div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Verification</p>
                                            <p class="text-sm text-gray-600">The data will be verified and cross-checked with our records.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600 font-semibold">3</div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Notification</p>
                                            <p class="text-sm text-gray-600">You'll receive a notification once your submission is approved or if any changes are needed.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 text-blue-600 font-semibold">4</div>
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Record Updated</p>
                                            <p class="text-sm text-gray-600">Approved records will be added to the FarmVax database and made available for veterinary services.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Important Information -->
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Review time typically takes 1-2 business days</li>
                                                <li>You can view the status in "My Submissions"</li>
                                                <li>You'll be notified via email and SMS when reviewed</li>
                                                <li>Approved records cannot be edited afterwards</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                                <h3 class="text-sm font-semibold text-gray-900 mb-4">Your Submission Summary</h3>
                                <dl class="grid grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-xs text-gray-500">Submission Time</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ now()->format('M d, Y - h:i A') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500">Status</dt>
                                        <dd>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Under Review
                                            </span>
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500">Submitted By</dt>
                                        <dd class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-gray-500">Reference ID</dt>
                                        <dd class="text-sm font-medium text-gray-900">#{{ strtoupper(substr(md5(now()), 0, 8)) }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('data-collector.dashboard') }}" 
                                   class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    Back to Dashboard
                                </a>

                                <a href="{{ route('data-collector.farm-records.index') }}" 
                                   class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    View My Submissions
                                </a>

                                <a href="{{ route('data-collector.farm-record.create') }}" 
                                   class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-green-600 text-base font-medium rounded-md text-green-600 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Submit Another
                                </a>
                            </div>

                        </div>

                    </div>

                    <!-- Help Section -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            Need help? Contact our support team at 
                            <a href="mailto:support@farmvax.com" class="text-blue-600 hover:text-blue-500 font-medium">support@farmvax.com</a>
                            or call <a href="tel:+2341234567890" class="text-blue-600 hover:text-blue-500 font-medium">+234 123 456 7890</a>
                        </p>
                    </div>

                </div>
            </main>
        </div>
    </div>

</body>
</html>