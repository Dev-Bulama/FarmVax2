<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service Request - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen py-8 px-4">
        <div class="max-w-3xl mx-auto">
            
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('individual.service-requests.show', $serviceRequest->id) }}" class="text-green-600 hover:text-green-700 flex items-center mb-4">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Request Details
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Edit Service Request</h1>
                <p class="text-sm text-gray-600 mt-1">Update your service request details</p>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg p-6">
                <form method="POST" action="{{ route('individual.service-requests.update', $serviceRequest->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Service Type -->
                    <div class="mb-6">
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Service Type <span class="text-red-500">*</span>
                        </label>
                        <select name="service_type" id="service_type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Select service type...</option>
                            <option value="vaccination" {{ old('service_type', $serviceRequest->service_type) == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                            <option value="checkup" {{ old('service_type', $serviceRequest->service_type) == 'checkup' ? 'selected' : '' }}>General Checkup</option>
                            <option value="treatment" {{ old('service_type', $serviceRequest->service_type) == 'treatment' ? 'selected' : '' }}>Treatment</option>
                            <option value="emergency" {{ old('service_type', $serviceRequest->service_type) == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            <option value="deworming" {{ old('service_type', $serviceRequest->service_type) == 'deworming' ? 'selected' : '' }}>Deworming</option>
                            <option value="artificial_insemination" {{ old('service_type', $serviceRequest->service_type) == 'artificial_insemination' ? 'selected' : '' }}>Artificial Insemination</option>
                            <option value="castration" {{ old('service_type', $serviceRequest->service_type) == 'castration' ? 'selected' : '' }}>Castration</option>
                            <option value="consultation" {{ old('service_type', $serviceRequest->service_type) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                            <option value="other" {{ old('service_type', $serviceRequest->service_type) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('service_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Livestock -->
                    <div class="mb-6">
                        <label for="livestock_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select Livestock (Optional)
                        </label>
                        <select name="livestock_id" id="livestock_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">General request (not for specific animal)</option>
                            @foreach($livestock as $animal)
                            <option value="{{ $animal->id }}" {{ old('livestock_id', $serviceRequest->livestock_id) == $animal->id ? 'selected' : '' }}>
                                {{ $animal->name ?? $animal->tag_number }} - {{ ucfirst($animal->livestock_type) }}
                            </option>
                            @endforeach
                        </select>
                        @error('livestock_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                            placeholder="Please describe the issue or service needed in detail..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description', $serviceRequest->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div class="mb-6">
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="low" {{ old('priority', $serviceRequest->priority) == 'low' ? 'selected' : '' }}>Low - Not urgent</option>
                            <option value="medium" {{ old('priority', $serviceRequest->priority) == 'medium' ? 'selected' : '' }}>Medium - Schedule within a week</option>
                            <option value="high" {{ old('priority', $serviceRequest->priority) == 'high' ? 'selected' : '' }}>High - Needs attention soon</option>
                            <option value="emergency" {{ old('priority', $serviceRequest->priority) == 'emergency' ? 'selected' : '' }}>Emergency - Immediate attention required</option>
                        </select>
                        @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Date -->
                    <div class="mb-6">
                        <label for="preferred_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Date (Optional)
                        </label>
                        <input type="date" name="preferred_date" id="preferred_date"
                            value="{{ old('preferred_date', $serviceRequest->preferred_date) }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('preferred_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Phone -->
                    <div class="mb-6">
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" 
                            value="{{ old('contact_phone', $serviceRequest->contact_phone) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('contact_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="mb-6">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Location/Address
                        </label>
                        <textarea name="location" id="location" rows="2"
                            placeholder="Where should the vet visit?"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('location', $serviceRequest->location) }}</textarea>
                        @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('individual.service-requests.show', $serviceRequest->id) }}" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" 
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Update Request
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

</body>
</html>