<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">My Profile</h1>
            
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">{{ $user->name }}</h2>
                <p class="text-gray-600">Email: {{ $user->email }}</p>
                <p class="text-gray-600">Phone: {{ $user->phone }}</p>
                
                @if($profile)
                    <p class="text-gray-600 mt-4">Organization: {{ $profile->organization }}</p>
                    <p class="text-gray-600">Territory: {{ $profile->assigned_territory }}</p>
                @endif
                
                <a href="{{ route('data-collector.dashboard') }}" class="mt-6 inline-block text-blue-600 hover:text-blue-700">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>