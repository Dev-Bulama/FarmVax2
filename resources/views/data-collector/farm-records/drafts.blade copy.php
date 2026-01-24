<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draft Records - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8 px-4">
        <div class="max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Draft Farm Records</h1>
            
            <div class="bg-white shadow rounded-lg p-6">
                @if($drafts->count() > 0)
                    <p class="text-gray-600">You have {{ $drafts->count() }} draft(s)</p>
                @else
                    <p class="text-gray-600">No draft records found.</p>
                @endif
                <a href="{{ route('data-collector.dashboard') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-700">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>