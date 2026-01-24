<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">

            <!-- Icon -->
            <div class="mx-auto mb-6 flex items-center justify-center w-16 h-16 rounded-full bg-yellow-100">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Message -->
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Session Expired</h1>
            <p class="text-gray-600 mb-6">
                Your session has expired due to inactivity.  
                For security reasons, please log in again to continue.
            </p>

            <!-- Action -->
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Go to Login
            </a>

            <!-- Footer note -->
            <p class="text-sm text-gray-500 mt-6">
                Your account and data remain safe.
            </p>

        </div>
    </div>
</body>
</html>
