@extends('layouts.farmer')

@section('title', 'Farm Record - Step 3')
@section('page-title', 'Farm Record Submission')
@section('page-subtitle', 'Step 3 of 6: Health & Vaccination History')

@section('content')
<div class="p-6">
    
    <!-- Progress Bar -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-green-600">Step 3 of 6</span>
            <span class="text-sm text-gray-500">50% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: 50%"></div>
        </div>
        
        <!-- Step Indicators -->
        <div class="mt-4 grid grid-cols-6 gap-2">
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Basic Info</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">✓</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Livestock</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">3</div>
                <p class="text-xs mt-1 text-green-600 font-semibold">Health</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">4</div>
                <p class="text-xs mt-1 text-gray-500">Infrastructure</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">5</div>
                <p class="text-xs mt-1 text-gray-500">Alerts</p>
            </div>
            <div class="text-center">
                <div class="w-8 h-8 mx-auto bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-bold">6</div>
                <p class="text-xs mt-1 text-gray-500">Review</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Health & Vaccination History</h2>
            <p class="text-sm text-gray-600 mt-1">Please provide health and vaccination information</p>
        </div>

<form method="POST" action="{{ route('farmer.farm-records.step2.store') }}" class="p-6">
                @csrf

            <div class="space-y-6">
                
                <!-- Vaccination Program -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <div class="flex">
                        <svg class="h-5 w-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-blue-700">Regular vaccination helps prevent disease outbreaks and keeps your livestock healthy.</p>
                    </div>
                </div>

                <!-- Do you vaccinate? -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        Do you have a vaccination program? <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-4">
                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                            <input type="radio" name="has_vaccination_program" value="yes" required
                                   {{ old('has_vaccination_program', session('farm_record.has_vaccination_program')) == 'yes' ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                            <span class="ml-3 text-sm font-medium text-gray-700">Yes</span>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                            <input type="radio" name="has_vaccination_program" value="no" required
                                   {{ old('has_vaccination_program', session('farm_record.has_vaccination_program')) == 'no' ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                            <span class="ml-3 text-sm font-medium text-gray-700">No</span>
                        </label>
                    </div>
                </div>

                <!-- Common Vaccines Used -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Common Vaccines Used (Select all that apply)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @php
                            $vaccines = [
                                'FMD' => 'Foot and Mouth Disease (FMD)',
                                'PPR' => 'Peste des Petits Ruminants (PPR)',
                                'CBPP' => 'Contagious Bovine Pleuropneumonia (CBPP)',
                                'Anthrax' => 'Anthrax',
                                'Blackleg' => 'Blackleg',
                                'Newcastle' => 'Newcastle Disease',
                                'Gumboro' => 'Gumboro (IBD)',
                                'Rabies' => 'Rabies',
                                'Brucellosis' => 'Brucellosis',
                                'Other' => 'Other'
                            ];
                        @endphp
                        @foreach($vaccines as $key => $label)
                            <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="vaccines_used[]" value="{{ $key }}"
                                       {{ in_array($key, old('vaccines_used', session('farm_record.vaccines_used', []))) ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <span class="ml-3 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Last Vaccination Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Last Vaccination Date</label>
                        <input type="date" name="last_vaccination_date" value="{{ old('last_vaccination_date', session('farm_record.last_vaccination_date')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Vaccination Frequency</label>
                        <select name="vaccination_frequency"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select frequency</option>
                            <option value="monthly" {{ old('vaccination_frequency', session('farm_record.vaccination_frequency')) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ old('vaccination_frequency', session('farm_record.vaccination_frequency')) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="biannually" {{ old('vaccination_frequency', session('farm_record.vaccination_frequency')) == 'biannually' ? 'selected' : '' }}>Bi-annually</option>
                            <option value="annually" {{ old('vaccination_frequency', session('farm_record.vaccination_frequency')) == 'annually' ? 'selected' : '' }}>Annually</option>
                            <option value="as_needed" {{ old('vaccination_frequency', session('farm_record.vaccination_frequency')) == 'as_needed' ? 'selected' : '' }}>As Needed</option>
                        </select>
                    </div>
                </div>

                <!-- Disease History -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Disease History</h3>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Have you experienced any disease outbreaks in the past 12 months? <span class="text-red-500">*</span>
                        </label>
                        <div class="flex space-x-4">
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" name="had_disease_outbreak" value="yes" required
                                       {{ old('had_disease_outbreak', session('farm_record.had_disease_outbreak')) == 'yes' ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-700">Yes</span>
                            </label>
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" name="had_disease_outbreak" value="no" required
                                       {{ old('had_disease_outbreak', session('farm_record.had_disease_outbreak')) == 'no' ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-700">No</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">If yes, please describe the diseases and impact</label>
                        <textarea name="disease_details" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="e.g., FMD outbreak in January 2024, affected 15 cattle, 2 deaths...">{{ old('disease_details', session('farm_record.disease_details')) }}</textarea>
                    </div>
                </div>

                <!-- Veterinary Services -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Veterinary Services</h3>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Do you have access to veterinary services? <span class="text-red-500">*</span>
                        </label>
                        <div class="flex space-x-4">
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" name="has_vet_access" value="yes" required
                                       {{ old('has_vet_access', session('farm_record.has_vet_access')) == 'yes' ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-700">Yes</span>
                            </label>
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" name="has_vet_access" value="no" required
                                       {{ old('has_vet_access', session('farm_record.has_vet_access')) == 'no' ? 'checked' : '' }}
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300">
                                <span class="ml-3 text-sm font-medium text-gray-700">No</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Veterinarian Name (if applicable)</label>
                            <input type="text" name="vet_name" value="{{ old('vet_name', session('farm_record.vet_name')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Dr. John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Veterinarian Phone</label>
                            <input type="tel" name="vet_phone" value="{{ old('vet_phone', session('farm_record.vet_phone')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="+234...">
                        </div>
                    </div>
                </div>

                <!-- Health Monitoring -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Health Monitoring</h3>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">How often do you conduct health checks?</label>
                        <select name="health_check_frequency"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select frequency</option>
                            <option value="daily" {{ old('health_check_frequency', session('farm_record.health_check_frequency')) == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('health_check_frequency', session('farm_record.health_check_frequency')) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('health_check_frequency', session('farm_record.health_check_frequency')) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="as_needed" {{ old('health_check_frequency', session('farm_record.health_check_frequency')) == 'as_needed' ? 'selected' : '' }}>As Needed</option>
                        </select>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Health Notes</label>
                        <textarea name="health_notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Any other health-related information...">{{ old('health_notes', session('farm_record.health_notes')) }}</textarea>
                    </div>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('individual.farm-records.step2') }}" class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition flex items-center">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Previous
                </a>
                <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition flex items-center">
                    Next Step
                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection