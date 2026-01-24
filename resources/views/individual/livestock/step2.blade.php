<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Step 2: Physical Characteristics - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('individual.dashboard') }}" class="text-green-600 hover:text-green-700 text-sm font-medium mb-4 inline-block">← Back to Dashboard</a>
                <h1 class="text-3xl font-bold text-gray-900">Register New Livestock</h1>
                <p class="mt-2 text-sm text-gray-600">Complete all 6 steps to register your animal</p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-green-600">Step 2 of 6</span>
                    <span class="text-sm font-medium text-gray-500">33% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 33%"></div>
                </div>
                <div class="mt-2 flex justify-between text-xs text-gray-500">
                    <span class="text-green-600">✓ Basic Info</span>
                    <span class="font-medium text-green-600">Physical</span>
                    <span>Health</span>
                    <span>Vaccination</span>
                    <span>Production</span>
                    <span>Origin</span>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-green-50">
                    <h2 class="text-xl font-semibold text-gray-900">Step 2: Physical Characteristics</h2>
                    <p class="mt-1 text-sm text-gray-600">Weight, height, color, and distinctive features</p>
                </div>

                @if(session('success'))
                <div class="px-6 py-4 bg-green-50 border-b border-green-200">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('individual.livestock.save-step', ['step' => 2]) }}" class="px-6 py-8">
                    @csrf

                    <!-- Weight Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Weight</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Weight</label>
                                <input type="number" name="weight" id="weight" step="0.01" min="0"
                                    value="{{ old('weight', $livestockData['step2']['weight'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., 250">
                            </div>
                            <div>
                                <label for="weight_unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                <select name="weight_unit" id="weight_unit" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                    <option value="kg" {{ old('weight_unit', $livestockData['step2']['weight_unit'] ?? 'kg') == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                    <option value="lbs" {{ old('weight_unit', $livestockData['step2']['weight_unit'] ?? '') == 'lbs' ? 'selected' : '' }}>Pounds (lbs)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Height Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Height</h3>
                        <div class="max-w-md">
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Height (in centimeters)</label>
                            <input type="number" name="height" id="height" step="0.01" min="0"
                                value="{{ old('height', $livestockData['step2']['height'] ?? '') }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                placeholder="e.g., 120">
                            <p class="mt-1 text-xs text-gray-500">Height at shoulder/withers</p>
                        </div>
                    </div>

                    <!-- Appearance Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 pb-2 border-b border-gray-200">Appearance</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                                <input type="text" name="color" id="color"
                                    value="{{ old('color', $livestockData['step2']['color'] ?? '') }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="e.g., Black and White, Brown, Speckled">
                            </div>
                            <div>
                                <label for="markings" class="block text-sm font-medium text-gray-700 mb-1">Distinctive Markings or Features</label>
                                <textarea name="markings" id="markings" rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                    placeholder="Describe any unique spots, scars, or identifying features">{{ old('markings', $livestockData['step2']['markings'] ?? '') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Helps identify the animal if lost or stolen</p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('individual.livestock.step', ['step' => 1]) }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </a>
                            <button type="button" onclick="saveDraft()"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Save as Draft
                            </button>
                        </div>
                        <button type="submit" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Next: Health Status
                            <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function saveDraft() {
            if (confirm('Save your progress as a draft?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("individual.livestock.draft") }}';
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>