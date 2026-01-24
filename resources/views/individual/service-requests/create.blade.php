@extends('layouts.farmer')

@section('title', 'New Service Request')
@section('page-title', 'Create Service Request')
@section('page-subtitle', 'Request veterinary services for your livestock')

@section('content')
<div class="p-6">

    <div class="max-w-3xl mx-auto">
        
        <!-- Info Alert -->
        <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <div class="flex">
                <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-blue-700">Submit a service request and a verified veterinary professional will contact you soon.</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Service Request Details</h2>
            </div>

            <form method="POST" action="{{ route('individual.service-requests.store') }}" class="p-6">
                @csrf

                <div class="space-y-6">
                    
                    <!-- Service Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Service Type <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('service_type') == 'vaccination' ? 'border-green-500 bg-green-50' : '' }}">
                                <input type="radio" name="service_type" value="vaccination" required 
                                       {{ old('service_type') == 'vaccination' ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900">Vaccination</p>
                                    <p class="text-xs text-gray-600">Preventive vaccine administration</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('service_type') == 'treatment' ? 'border-green-500 bg-green-50' : '' }}">
                                <input type="radio" name="service_type" value="treatment" required
                                       {{ old('service_type') == 'treatment' ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900">Treatment</p>
                                    <p class="text-xs text-gray-600">Medical treatment for sick animals</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition {{ old('service_type') == 'consultation' ? 'border-green-500 bg-green-50' : '' }}">
                                <input type="radio" name="service_type" value="consultation" required
                                       {{ old('service_type') == 'consultation' ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-gray-900">Consultation</p>
                                    <p class="text-xs text-gray-600">Expert advice and guidance</p>
                                </div>
                            </label>

                            <label class="flex items-center p-4 border-2 border-red-300 rounded-lg cursor-pointer hover:bg-red-50 transition {{ old('service_type') == 'emergency' ? 'border-red-500 bg-red-50' : '' }}">
                                <input type="radio" name="service_type" value="emergency" required
                                       {{ old('service_type') == 'emergency' ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 focus:ring-red-500">
                                <div class="ml-3">
                                    <p class="text-sm font-semibold text-red-900">Emergency</p>
                                    <p class="text-xs text-red-600">Urgent medical attention needed</p>
                                </div>
                            </label>
                        </div>
                        @error('service_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urgency Level -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Urgency Level <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="flex items-center justify-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('urgency') == 'low' ? 'border-blue-500 bg-blue-50' : '' }}">
                                <input type="radio" name="urgency" value="low" required
                                       {{ old('urgency') == 'low' ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-semibold text-gray-900">Low</span>
                            </label>

                            <label class="flex items-center justify-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('urgency') == 'medium' ? 'border-yellow-500 bg-yellow-50' : '' }}">
                                <input type="radio" name="urgency" value="medium" required
                                       {{ old('urgency') == 'medium' ? 'checked' : '' }}
                                       class="h-4 w-4 text-yellow-600 focus:ring-yellow-500">
                                <span class="ml-2 text-sm font-semibold text-gray-900">Medium</span>
                            </label>

                            <label class="flex items-center justify-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 {{ old('urgency') == 'high' ? 'border-orange-500 bg-orange-50' : '' }}">
                                <input type="radio" name="urgency" value="high" required
                                       {{ old('urgency') == 'high' ? 'checked' : '' }}
                                       class="h-4 w-4 text-orange-600 focus:ring-orange-500">
                                <span class="ml-2 text-sm font-semibold text-gray-900">High</span>
                            </label>

                            <label class="flex items-center justify-center p-3 border-2 border-red-300 rounded-lg cursor-pointer hover:bg-red-50 {{ old('urgency') == 'critical' ? 'border-red-500 bg-red-50' : '' }}">
                                <input type="radio" name="urgency" value="critical" required
                                       {{ old('urgency') == 'critical' ? 'checked' : '' }}
                                       class="h-4 w-4 text-red-600 focus:ring-red-500">
                                <span class="ml-2 text-sm font-semibold text-red-900">Critical</span>
                            </label>
                        </div>
                        @error('urgency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="5" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Please describe the issue, symptoms, number of animals affected, etc.">{{ old('description') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Be as detailed as possible to help the veterinarian prepare.</p>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Preferred Visit Date (Optional)
                        </label>
                        <input type="date" name="preferred_date" value="{{ old('preferred_date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Select your preferred date for the visit (subject to availability).</p>
                        @error('preferred_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Information -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Your Phone</label>
                                <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="+234...">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alternative Phone (Optional)</label>
                                <input type="tel" name="alternative_phone" value="{{ old('alternative_phone') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="+234...">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('individual.service-requests.index') }}" class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection