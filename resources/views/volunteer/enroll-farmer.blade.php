@extends('layouts.volunteer')

@section('title', 'Enroll Farmer')

@section('content')

<div class="p-6 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('volunteer.dashboard') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>
        <h1 class="text-3xl font-bold mt-2" style="color: #11455b;">Enroll New Farmer</h1>
        <p class="text-gray-600 mt-1">Help farmers join the FarmVax platform and earn 10 points!</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Info Banner --}}
    <div class="mb-6 rounded-lg p-4 border-l-4" style="background-color: rgba(47, 203, 110, 0.1); border-color: #2fcb6e;">
        <div class="flex">
            <svg class="h-5 w-5 mr-2" style="color: #2fcb6e;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-semibold" style="color: #11455b;">What happens after enrollment?</p>
                <p class="text-sm text-gray-700 mt-1">The farmer will receive login credentials via email and can immediately access the platform to manage their livestock and farm records.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('volunteer.enroll.farmer') }}" method="POST">
        @csrf

        {{-- Personal Information --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">👤 Personal Information</h2>
            
            <div class="space-y-4">
                
                {{-- Full Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           style="focus:ring-color: #2fcb6e;"
                           placeholder="e.g., John Doe">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="e.g., farmer@example.com">
                    <p class="text-xs text-gray-500 mt-1">This will be used for login credentials</p>
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="e.g., +234 801 234 5678">
                </div>

                {{-- Address --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Farm Address <span class="text-red-500">*</span>
                    </label>
                    <textarea name="address" rows="3" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                              placeholder="Enter complete farm address...">{{ old('address') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Login Credentials --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">🔐 Login Credentials</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="Minimum 8 characters">
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                           placeholder="Re-enter password">
                </div>

            </div>

            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    💡 <strong>Tip:</strong> Create a strong password with at least 8 characters. The farmer will use this to login.
                </p>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('volunteer.dashboard') }}" 
                   class="w-full sm:w-auto px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition text-center">
                    Cancel
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl flex items-center justify-center"
                        style="background-color: #2fcb6e;">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Enroll Farmer & Earn 10 Points
                </button>
            </div>
        </div>

    </form>

    {{-- Success Tips --}}
    <div class="mt-6 bg-gradient-to-br from-green-50 to-blue-50 rounded-lg shadow p-6 border-l-4" style="border-color: #2fcb6e;">
        <h3 class="text-sm font-bold mb-2" style="color: #11455b;">💡 After Enrollment</h3>
        <ul class="text-sm text-gray-700 space-y-1">
            <li class="flex items-start">
                <span class="mr-2" style="color: #2fcb6e;">✓</span>
                The farmer will appear in your "My Farmers" list
            </li>
            <li class="flex items-start">
                <span class="mr-2" style="color: #2fcb6e;">✓</span>
                Your enrollment count and points will increase
            </li>
            <li class="flex items-start">
                <span class="mr-2" style="color: #2fcb6e;">✓</span>
                The farmer can immediately login and use the platform
            </li>
            <li class="flex items-start">
                <span class="mr-2" style="color: #2fcb6e;">✓</span>
                You'll be notified of their progress and activities
            </li>
        </ul>
    </div>

</div>

@endsection