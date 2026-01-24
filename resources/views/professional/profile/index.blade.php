@extends('layouts.professional')

@section('title', 'My Profile')

@section('content')

@php
    $user = auth()->user();
    $adService = new \App\Services\AdService();
    $sidebarAds = $adService->getSidebarAds($user);
@endphp

<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600 mt-1">Manage your professional information and credentials</p>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
            <p class="text-green-800">{{ session('success') }}</p>
        </div>
    @endif

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="lg:col-span-2">

            <form action="{{ route('professional.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Personal Information --}}
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Personal Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Full Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Address --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="2"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address', $user->address) }}</textarea>
                        </div>

                        {{-- Profile Picture --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Profile Picture</label>
                            @if($user->profile_picture)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="h-24 w-24 rounded-full object-cover">
                                </div>
                            @endif
                            <input type="file" name="profile_picture" accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Max 2MB, JPG or PNG</p>
                        </div>

                    </div>
                </div>

                {{-- Professional Information --}}
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Professional Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Professional Type --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Professional Type <span class="text-red-500">*</span>
                            </label>
                            <select name="professional_type" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="veterinarian" {{ old('professional_type', $profile->professional_type ?? '') == 'veterinarian' ? 'selected' : '' }}>Veterinarian</option>
                                <option value="paraveterinarian" {{ old('professional_type', $profile->professional_type ?? '') == 'paraveterinarian' ? 'selected' : '' }}>Paraveterinarian</option>
                                <option value="community_animal_health_worker" {{ old('professional_type', $profile->professional_type ?? '') == 'community_animal_health_worker' ? 'selected' : '' }}>Community Animal Health Worker</option>
                                <option value="others" {{ old('professional_type', $profile->professional_type ?? '') == 'others' ? 'selected' : '' }}>Others</option>
                            </select>
                        </div>

                        {{-- License Number --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">License Number</label>
                            <input type="text" name="license_number" value="{{ old('license_number', $profile->license_number ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Experience Years --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Years of Experience</label>
                            <input type="number" name="experience_years" min="0" max="50" value="{{ old('experience_years', $profile->experience_years ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Organization --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Organization/Employer</label>
                            <input type="text" name="organization" value="{{ old('organization', $profile->organization ?? '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Specialization --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Specialization</label>
                            <input type="text" name="specialization" value="{{ old('specialization', $profile->specialization ?? '') }}"
                                   placeholder="e.g., Cattle, Poultry, Small Animals"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Bio --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Professional Bio</label>
                            <textarea name="bio" rows="4"
                                      placeholder="Brief description of your experience and expertise..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('bio', $profile->bio ?? '') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Max 1000 characters</p>
                        </div>

                    </div>
                </div>

                {{-- Change Password --}}
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Change Password</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Current Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Leave blank to keep current password">
                        </div>

                        <div></div>

                        {{-- New Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                            <input type="password" name="new_password" minlength="8"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Minimum 8 characters">
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" minlength="8"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Confirm new password">
                        </div>

                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        💾 Save Changes
                    </button>
                </div>

            </form>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Account Status --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Account Status</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Approval Status</span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $profile->approval_status === 'approved' ? 'bg-green-100 text-green-800' : ($profile->approval_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($profile->approval_status) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Member Since</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                    
                    @if($profile->experience_years)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Experience</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $profile->experience_years }} years</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Ads --}}
            @if($sidebarAds && $sidebarAds->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">📢 Sponsored</h3>
                    @foreach($sidebarAds as $ad)
                        <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition mb-4">
                            <div class="relative">
                                <span class="absolute top-2 right-2 bg-gray-900 bg-opacity-75 text-white text-xs px-2 py-1 rounded-full z-10">
                                    Sponsored
                                </span>
                                @if($ad->image_url)
                                    <img src="{{ asset('storage/' . $ad->image_url) }}" 
                                         alt="{{ $ad->title }}" 
                                         class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center">
                                        <span class="text-white text-4xl">📢</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-gray-900 mb-2">{{ $ad->title }}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($ad->description, 80) }}</p>
                                @if($ad->link_url)
                                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                                       class="text-blue-600 text-sm font-semibold hover:text-blue-700">
                                        Learn More →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Help --}}
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-bold text-blue-900 mb-3">💡 Need Help?</h3>
                <p class="text-sm text-blue-800 mb-4">
                    If you need to update your credentials or have questions about your account status, contact support.
                </p>
                <a href="mailto:support@farmvax.com" 
                   class="text-blue-600 text-sm font-semibold hover:text-blue-700">
                    Contact Support →
                </a>
            </div>

        </div>

    </div>

</div>

@endsection