<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Pending - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/farmvax-brand.css') }}">
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            
            <!-- Card -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-6">
                    <svg class="h-10 w-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Title -->
                <h2 class="text-2xl font-bold text-center brand-teal mb-4">
                    Application Pending Review
                </h2>

                <!-- Message -->
                <div class="text-center text-gray-600 mb-6">
                    <p class="mb-4">
                        Thank you for applying to be an <strong>Animal Health Professional</strong> on FarmVax!
                    </p>
                    <p class="mb-4">
                        Your application is currently being reviewed by our admin team. You will receive an email notification once your account has been approved.
                    </p>
                    <p>
                        This typically takes <strong>1-2 business days</strong>.
                    </p>
                </div>

                <!-- Status Badge -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-medium text-yellow-800">Status: Pending Approval</span>
                    </div>
                </div>

                <!-- User Info -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h3 class="font-bold text-gray-900 mb-3">Your Information</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Name:</span>
                            <span class="font-medium">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium">{{ auth()->user()->email }}</span>
                        </div>
                        @if(auth()->user()->animalHealthProfessional)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium">{{ auth()->user()->animalHealthProfessional->professional_type_text }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Submitted:</span>
                            <span class="font-medium">{{ auth()->user()->animalHealthProfessional->submitted_at->format('M d, Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- What Happens Next -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-bold text-blue-900 mb-2">What Happens Next?</h4>
                    <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800">
                        <li>Our admin team reviews your application</li>
                        <li>We verify your credentials and information</li>
                        <li>You receive an email with the decision</li>
                        <li>Once approved, you gain full access to the platform</li>
                    </ol>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-gray-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-gray-700 transition">
                            Logout
                        </button>
                    </form>
                    
                    <a href="{{ route('home') }}" class="block text-center text-brand-teal hover:underline font-medium">
                        Return to Home
                    </a>
                </div>

            </div>

            <!-- Help Text -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Need help? Contact us at <a href="mailto:support@farmvax.com" class="text-brand-teal hover:underline">support@farmvax.com</a></p>
            </div>

        </div>
    </div>

</body>
</html>