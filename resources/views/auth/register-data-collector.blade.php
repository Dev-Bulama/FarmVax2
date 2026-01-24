<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Data Collector - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="max-w-3xl mx-auto">
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center">
                    <svg class="h-12 w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </a>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Register as Data Collector
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500">
                        Sign in
                    </a>
                </p>
                <div class="mt-4 bg-blue-50 border border-blue-200 rounded-md p-4">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> Your application will be reviewed by an admin before you can access the system.
                    </p>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="mt-8 bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                
                <!-- Error Messages -->
                @if($errors->any())
                <div class="mb-6 rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There were errors with your submission
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('register.data-collector.submit') }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf

                    <!-- SECTION 1: Personal Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        
                        <!-- Profile Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Profile Image <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img id="profile-preview" class="h-24 w-24 rounded-full object-cover border-2 border-gray-300" src="https://via.placeholder.com/150" alt="Preview">
                                </div>
                                <div class="flex-1">
                                    <input 
                                        type="file" 
                                        name="profile_image" 
                                        id="profile_image" 
                                        accept="image/jpeg,image/jpg,image/png"
                                        required
                                        onchange="previewProfileImage(event)"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                                    >
                                    <p class="mt-1 text-xs text-gray-500">JPG, JPEG or PNG. Max 2MB.</p>
                                </div>
                            </div>
                            @error('profile_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Full Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name') }}"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                            >
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email and Phone -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    value="{{ old('email') }}"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    id="phone" 
                                    value="{{ old('phone') }}"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <label for="bio" class="block text-sm font-medium text-gray-700">
                                Short Bio
                            </label>
                            <textarea 
                                name="bio" 
                                id="bio" 
                                rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                placeholder="Tell us about yourself..."
                            >{{ old('bio') }}</textarea>
                            @error('bio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- SECTION 2: Location Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Location Information</h3>
                        
                        <!-- Address -->
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="address" 
                                id="address" 
                                rows="2"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                            >{{ old('address') }}</textarea>
                            @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- City, State, Country -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="city" 
                                    id="city" 
                                    value="{{ old('city') }}"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="state" 
                                    id="state" 
                                    value="{{ old('state') }}"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">
                                    Country
                                </label>
                                <input 
                                    type="text" 
                                    name="country" 
                                    id="country" 
                                    value="{{ old('country', 'Nigeria') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Professional Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Information</h3>
                        
                        <!-- Organization, Position, Employee ID -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="organization" class="block text-sm font-medium text-gray-700">
                                    Organization
                                </label>
                                <input 
                                    type="text" 
                                    name="organization" 
                                    id="organization" 
                                    value="{{ old('organization') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('organization')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700">
                                    Position/Title
                                </label>
                                <input 
                                    type="text" 
                                    name="position" 
                                    id="position" 
                                    value="{{ old('position') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700">
                                    Employee ID
                                </label>
                                <input 
                                    type="text" 
                                    name="employee_id" 
                                    id="employee_id" 
                                    value="{{ old('employee_id') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('employee_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Education Level -->
                        <div class="mb-4">
                            <label for="education_level" class="block text-sm font-medium text-gray-700">
                                Education Level
                            </label>
                            <input 
                                type="text" 
                                name="education_level" 
                                id="education_level" 
                                value="{{ old('education_level') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                placeholder="e.g., Bachelor's Degree in Agriculture"
                            >
                            @error('education_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Experience -->
                        <div class="mb-4">
                            <label for="experience" class="block text-sm font-medium text-gray-700">
                                Work Experience
                            </label>
                            <textarea 
                                name="experience" 
                                id="experience" 
                                rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                placeholder="Describe your relevant work experience..."
                            >{{ old('experience') }}</textarea>
                            @error('experience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reason for Applying -->
                        <div class="mb-4">
                            <label for="reason_for_applying" class="block text-sm font-medium text-gray-700">
                                Why do you want to be a Data Collector? <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="reason_for_applying" 
                                id="reason_for_applying" 
                                rows="4"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                placeholder="Please explain your motivation and how you plan to contribute (minimum 50 characters)..."
                            >{{ old('reason_for_applying') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Minimum 50 characters required</p>
                            @error('reason_for_applying')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Territory Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="assigned_territory" class="block text-sm font-medium text-gray-700">
                                    Assigned Territory
                                </label>
                                <input 
                                    type="text" 
                                    name="assigned_territory" 
                                    id="assigned_territory" 
                                    value="{{ old('assigned_territory') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                    placeholder="e.g., Lagos Region"
                                >
                                @error('assigned_territory')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="coverage_area" class="block text-sm font-medium text-gray-700">
                                    Coverage Area (LGA/District)
                                </label>
                                <input 
                                    type="text" 
                                    name="coverage_area" 
                                    id="coverage_area" 
                                    value="{{ old('coverage_area') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                    placeholder="e.g., Ikeja LGA"
                                >
                                @error('coverage_area')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: Identification & Verification Documents -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Identification & Verification Documents</h3>
                        
                        <!-- ID Card Type and Number -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="id_card_type" class="block text-sm font-medium text-gray-700">
                                    ID Card Type <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="id_card_type" 
                                    id="id_card_type" 
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                    <option value="">Select ID Type</option>
                                    <option value="National ID" {{ old('id_card_type') == 'National ID' ? 'selected' : '' }}>National ID</option>
                                    <option value="Passport" {{ old('id_card_type') == 'Passport' ? 'selected' : '' }}>International Passport</option>
                                    <option value="Driver's License" {{ old('id_card_type') == "Driver's License" ? 'selected' : '' }}>Driver's License</option>
                                    <option value="Voter's Card" {{ old('id_card_type') == "Voter's Card" ? 'selected' : '' }}>Voter's Card</option>
                                </select>
                                @error('id_card_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="id_card_number" class="block text-sm font-medium text-gray-700">
                                    ID Card Number <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="id_card_number" 
                                    id="id_card_number" 
                                    value="{{ old('id_card_number') }}"
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('id_card_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- ID Card Document Upload -->
                        <div class="mb-4">
                            <label for="id_card_document" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload ID Card Document <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="file" 
                                name="id_card_document" 
                                id="id_card_document" 
                                accept="image/jpeg,image/jpg,image/png,application/pdf"
                                required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            >
                            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG or PDF. Max 5MB.</p>
                            @error('id_card_document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Verification/Authorization Document -->
                        <div class="mb-4">
                            <label for="verification_document" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Government/Organization Verification Document <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="file" 
                                name="verification_document" 
                                id="verification_document" 
                                accept="image/jpeg,image/jpg,image/png,application/pdf"
                                required
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            >
                            <p class="mt-1 text-xs text-gray-500">Letter of assignment, authorization, or proof of employment. Max 5MB.</p>
                            @error('verification_document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Professional Certificates (Multiple) -->
                        <div class="mb-4">
                            <label for="certificates" class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Professional Certificates (Optional)
                            </label>
                            <input 
                                type="file" 
                                name="certificates[]" 
                                id="certificates" 
                                accept="image/jpeg,image/jpg,image/png,application/pdf"
                                multiple
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100"
                            >
                            <p class="mt-1 text-xs text-gray-500">You can select multiple files. Max 5MB each.</p>
                            @error('certificates.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- SECTION 5: Reference Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Reference Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="reference_name" class="block text-sm font-medium text-gray-700">
                                    Reference Name
                                </label>
                                <input 
                                    type="text" 
                                    name="reference_name" 
                                    id="reference_name" 
                                    value="{{ old('reference_name') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('reference_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reference_phone" class="block text-sm font-medium text-gray-700">
                                    Reference Phone
                                </label>
                                <input 
                                    type="tel" 
                                    name="reference_phone" 
                                    id="reference_phone" 
                                    value="{{ old('reference_phone') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('reference_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reference_email" class="block text-sm font-medium text-gray-700">
                                    Reference Email
                                </label>
                                <input 
                                    type="email" 
                                    name="reference_email" 
                                    id="reference_email" 
                                    value="{{ old('reference_email') }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                @error('reference_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 6: Security -->
                    <div class="pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Security</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                                @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition"
                        >
                            Submit Application for Review
                        </button>
                    </div>

                    <!-- Alternative Registration -->
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">
                                    Or
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a 
                                href="{{ route('register.individual') }}" 
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition"
                            >
                                Register as Individual instead
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Back to Login -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium text-purple-600 hover:text-purple-500">
                    ← Back to Login
                </a>
            </div>
        </div>

    </div>

    <!-- JavaScript for Image Preview -->
    <script>
        function previewProfileImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>
</html>