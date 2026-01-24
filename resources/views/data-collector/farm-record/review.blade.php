<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review & Submit - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Component -->
        @include('components.data-collector-sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center">
                        <button onclick="toggleSidebar()" class="mr-4 text-gray-500 hover:text-gray-700 md:hidden">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Review & Submit</h1>
                            <p class="text-sm text-gray-600">Review your farm record before submitting</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">Data Collector</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4 md:p-6">
                <div class="max-w-5xl mx-auto">
                    
                    <!-- Progress Complete Banner -->
                    <div class="mb-6 rounded-lg bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-300 p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4 flex-1">
                                <h2 class="text-xl font-bold text-gray-900">All Steps Completed! 🎉</h2>
                                <p class="mt-1 text-sm text-gray-700">Review all information below and submit when ready.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Stakeholder Information -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">1</div>
                                <h3 class="text-lg font-semibold text-gray-900">Stakeholder Information</h3>
                            </div>
                            <a href="{{ route('data-collector.farm-record.step', ['step' => 1]) }}" 
                               class="inline-flex items-center px-3 py-1 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(!empty($farmRecordData['step1']['farmer_name']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Farmer Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step1']['farmer_name'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step1']['farmer_phone']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step1']['farmer_phone'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step1']['farmer_email']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step1']['farmer_email'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step1']['city']) || !empty($farmRecordData['step1']['state']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $farmRecordData['step1']['city'] ?? '' }}{{ !empty($farmRecordData['step1']['city']) && !empty($farmRecordData['step1']['state']) ? ', ' : '' }}{{ $farmRecordData['step1']['state'] ?? '' }}
                                    </dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step1']['farm_name']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Farm Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step1']['farm_name'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step1']['farm_size']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Farm Size</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step1']['farm_size'] }} {{ ucfirst(str_replace('_', ' ', $farmRecordData['step1']['farm_size_unit'] ?? '')) }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step1']['farm_type']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Farm Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($farmRecordData['step1']['farm_type']) }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step1']['average_household_size']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Household Size</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step1']['average_household_size'] }} people</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Step 2: Livestock Profile -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">2</div>
                                <h3 class="text-lg font-semibold text-gray-900">Livestock Profile</h3>
                            </div>
                            <a href="{{ route('data-collector.farm-record.step', ['step' => 2]) }}" 
                               class="inline-flex items-center px-3 py-1 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(!empty($farmRecordData['step2']['total_livestock_count']))
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Total Livestock Count</dt>
                                    <dd class="mt-1 text-2xl font-bold text-blue-600">{{ $farmRecordData['step2']['total_livestock_count'] }} animals</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step2']['livestock_types']))
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Livestock Types</dt>
                                    <dd class="mt-2 flex flex-wrap gap-2">
                                        @foreach($farmRecordData['step2']['livestock_types'] as $type)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                        </span>
                                        @endforeach
                                    </dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step2']['young_count']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Young Animals (0-1 years)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step2']['young_count'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step2']['adult_count']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Adult Animals (1-7 years)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step2']['adult_count'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step2']['old_count']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Senior Animals (7+ years)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step2']['old_count'] }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Step 3: Health & Vaccination -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">3</div>
                                <h3 class="text-lg font-semibold text-gray-900">Health & Vaccination</h3>
                            </div>
                            <a href="{{ route('data-collector.farm-record.step', ['step' => 3]) }}" 
                               class="inline-flex items-center px-3 py-1 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(!empty($farmRecordData['step3']['last_vaccination_date']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Vaccination Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ date('M d, Y', strtotime($farmRecordData['step3']['last_vaccination_date'])) }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step3']['has_health_issues']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Health Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Has Health Issues
                                        </span>
                                    </dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step3']['current_health_issues']))
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Current Health Issues</dt>
                                    <dd class="mt-2 flex flex-wrap gap-2">
                                        @foreach($farmRecordData['step3']['current_health_issues'] as $issue)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            {{ ucfirst(str_replace('_', ' ', $issue)) }}
                                        </span>
                                        @endforeach
                                    </dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step3']['veterinarian_name']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Veterinarian</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step3']['veterinarian_name'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step3']['veterinarian_phone']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vet Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step3']['veterinarian_phone'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step3']['past_diseases']))
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Past Diseases</dt>
                                    <dd class="mt-2 flex flex-wrap gap-2">
                                        @foreach($farmRecordData['step3']['past_diseases'] as $disease)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                            {{ ucfirst(str_replace('_', ' ', $disease)) }}
                                        </span>
                                        @endforeach
                                    </dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Step 4: Service Needs -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">4</div>
                                <h3 class="text-lg font-semibold text-gray-900">Service Needs</h3>
                            </div>
                            <a href="{{ route('data-collector.farm-record.step', ['step' => 4]) }}" 
                               class="inline-flex items-center px-3 py-1 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(!empty($farmRecordData['step4']['service_needs']))
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Services Needed</dt>
                                    <dd class="mt-2 flex flex-wrap gap-2">
                                        @foreach($farmRecordData['step4']['service_needs'] as $service)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            {{ ucfirst(str_replace('_', ' ', $service)) }}
                                        </span>
                                        @endforeach
                                    </dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step4']['urgency_level']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Urgency Level</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $farmRecordData['step4']['urgency_level'] === 'emergency' ? 'bg-red-100 text-red-800' : ($farmRecordData['step4']['urgency_level'] === 'high' ? 'bg-orange-100 text-orange-800' : ($farmRecordData['step4']['urgency_level'] === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800')) }}">
                                            {{ ucfirst($farmRecordData['step4']['urgency_level']) }}
                                        </span>
                                    </dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step4']['preferred_service_date']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preferred Service Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ date('M d, Y', strtotime($farmRecordData['step4']['preferred_service_date'])) }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step4']['service_description']))
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Service Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step4']['service_description'] }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Step 5: Alert Preferences -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">5</div>
                                <h3 class="text-lg font-semibold text-gray-900">Alert Preferences</h3>
                            </div>
                            <a href="{{ route('data-collector.farm-record.step', ['step' => 5]) }}" 
                               class="inline-flex items-center px-3 py-1 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Alert Channels</dt>
                                    <dd class="flex flex-wrap gap-2">
                                        @if(!empty($farmRecordData['step5']['sms_alerts']))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                            </svg>
                                            SMS
                                        </span>
                                        @endif
                                        @if(!empty($farmRecordData['step5']['email_alerts']))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Email
                                        </span>
                                        @endif
                                        @if(!empty($farmRecordData['step5']['phone_alerts']))
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            Phone
                                        </span>
                                        @endif
                                    </dd>
                                </div>
                                @if(!empty($farmRecordData['step5']['alert_types']))
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Alert Types</dt>
                                    <dd class="mt-2 flex flex-wrap gap-2">
                                        @foreach($farmRecordData['step5']['alert_types'] as $alertType)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                            {{ ucfirst(str_replace('_', ' ', $alertType)) }}
                                        </span>
                                        @endforeach
                                    </dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step5']['preferred_contact_method']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preferred Contact Method</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($farmRecordData['step5']['preferred_contact_method']) }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Step 6: Consent & Feedback -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 bg-blue-50 border-b border-blue-200 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-3">6</div>
                                <h3 class="text-lg font-semibold text-gray-900">Consent & Feedback</h3>
                            </div>
                            <a href="{{ route('data-collector.farm-record.step', ['step' => 6]) }}" 
                               class="inline-flex items-center px-3 py-1 border border-blue-600 text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Consents Provided</dt>
                                    <dd class="space-y-2">
                                        @if(!empty($farmRecordData['step6']['data_sharing_consent']))
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Data Sharing for Veterinary Services
                                        </div>
                                        @endif
                                        @if(!empty($farmRecordData['step6']['research_participation_consent']))
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Research Participation
                                        </div>
                                        @endif
                                        @if(!empty($farmRecordData['step6']['marketing_consent']))
                                        <div class="flex items-center text-sm text-gray-900">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Promotional Communications
                                        </div>
                                        @endif
                                    </dd>
                                </div>
                                @if(!empty($farmRecordData['step6']['feedback']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Feedback</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step6']['feedback'] }}</dd>
                                </div>
                                @endif
                                @if(!empty($farmRecordData['step6']['additional_comments']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Additional Comments</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $farmRecordData['step6']['additional_comments'] }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-8 text-center">
                            <svg class="mx-auto h-16 w-16 text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Ready to Submit?</h3>
                            <p class="text-sm text-gray-600 mb-6 max-w-2xl mx-auto">
                                By submitting this farm record, you confirm that all information provided is accurate to the best of your knowledge. 
                                The record will be sent for admin review.
                            </p>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                                <a href="{{ route('data-collector.farm-record.step', ['step' => 6]) }}" 
                                   class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Back to Step 6
                                </a>

                                <form method="POST" action="{{ route('data-collector.farm-record.submit') }}" class="inline">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Submit this farm record for review? You won\'t be able to edit it after submission.')"
                                        class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-bold rounded-md text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 shadow-lg hover:shadow-xl transition-all duration-200">
                                        <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Submit Farm Record
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

</body>
</html>