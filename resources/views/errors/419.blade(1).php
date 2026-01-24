<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-lg w-full">
            <!-- Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full mb-4">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Session Expired</h1>
                <p class="text-lg text-gray-600">Your page has been idle for too long</p>
            </div>

            <!-- Explanation -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-3">What happened?</h2>
                <p class="text-gray-700 mb-4">
                    For your security, we automatically expire pages that have been open for more than 2 hours without activity. 
                    This prevents unauthorized access if you leave your device unattended.
                </p>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Don't worry!</p>
                            <p class="text-sm text-blue-700 mt-1">Your data is safe. Simply refresh the page to continue.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-3">How to fix this:</h2>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-bold">1</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Click the refresh button below</p>
                            <p class="text-sm text-gray-600">This will reload the page with a fresh session</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-bold">2</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Re-enter your information</p>
                            <p class="text-sm text-gray-600">Your previous data was not saved for security reasons</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-bold">3</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Submit quickly</p>
                            <p class="text-sm text-gray-600">Try to complete forms within 2 hours to avoid this issue</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="window.location.reload()" 
                        class="w-full bg-blue-600 text-white px-6 py-4 rounded-lg hover:bg-blue-700 font-bold transition flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh Page & Continue
                </button>

                <button onclick="window.history.back()" 
                        class="w-full bg-gray-100 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-200 font-semibold transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Go Back
                </button>

                <a href="{{ url('/') }}" 
                   class="w-full block text-center bg-white text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-50 font-semibold transition border border-gray-300">
                    Return to Home
                </a>
            </div>

            <!-- Auto-refresh countdown -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Page will auto-refresh in <span id="countdown" class="font-bold text-blue-600">10</span> seconds
                </p>
            </div>

            <!-- Prevention Tips -->
            <div class="mt-8 bg-gray-100 rounded-lg p-4">
                <h3 class="font-bold text-gray-900 mb-2">💡 Tips to prevent this:</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• Complete forms within 2 hours of opening the page</li>
                    <li>• Don't leave form pages open overnight</li>
                    <li>• Save draft copies of long forms in a text editor</li>
                    <li>• If filling complex forms, do it in multiple short sessions</li>
                </ul>
            </div>

            <!-- Support -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Still having issues? 
                    <a href="mailto:support@farmvax.com" class="text-blue-600 hover:underline font-semibold">Contact Support</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Auto-refresh Script -->
    <script>
        let timeLeft = 10;
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(() => {
            timeLeft--;
            countdownElement.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.reload();
            }
        }, 1000);

        // Stop countdown if user clicks refresh button
        document.querySelector('button[onclick="window.location.reload()"]').addEventListener('click', () => {
            clearInterval(countdown);
        });
    </script>
</body>
</html>