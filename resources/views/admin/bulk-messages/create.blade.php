@extends('layouts.admin')

@section('title', 'Create Bulk Message')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Create Bulk Message</h2>
            <p class="text-gray-600 mt-1">Send SMS or Email to multiple users at once</p>
        </div>
        <a href="{{ route('admin.bulk-messages.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition">
            Cancel
        </a>
    </div>
</div>

<!-- Error Messages -->
@if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <div class="flex">
            <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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

<!-- Form -->
<form action="{{ route('admin.bulk-messages.store') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Message Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Message Details</h3>
                
                <div class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Message Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Monthly Vaccination Reminder">
                    </div>

                    <!-- Message Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Message Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Select Type --</option>
                            <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>Email Only</option>
                            <option value="sms" {{ old('type') == 'sms' ? 'selected' : '' }}>SMS Only</option>
                            <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Both (Email & SMS)</option>
                        </select>
                    </div>

                    <!-- Message Content -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Message Content <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Type your message here...">{{ old('message') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            <span id="charCount">0</span> characters | For SMS: Keep under 160 characters per message
                        </p>
                    </div>
                </div>
            </div>

            <!-- Target Audience -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Target Audience</h3>
                
                <div class="space-y-4">
                    <!-- Target Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Who should receive this? <span class="text-red-500">*</span>
                        </label>
                        <select name="target_type" id="targetType" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Select Target --</option>
                            <option value="all" {{ old('target_type') == 'all' ? 'selected' : '' }}>All Users</option>
                            <option value="role" {{ old('target_type') == 'role' ? 'selected' : '' }}>By User Role</option>
                            <option value="location" {{ old('target_type') == 'location' ? 'selected' : '' }}>By Location</option>
                        </select>
                    </div>

                    <!-- By Role -->
                    <div id="roleSection" class="hidden">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select User Roles</label>
                        <div class="space-y-2">
                            @php
                                $roles = [
                                    'farmer' => 'Farmers',
                                    'animal_health_professional' => 'Animal Health Professionals',
                                    'volunteer' => 'Volunteers',
                                    'data_collector' => 'Data Collectors'
                                ];
                            @endphp
                            
                            @foreach($roles as $value => $label)
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="target_roles[]" value="{{ $value }}"
                                       {{ in_array($value, old('target_roles', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- By Location -->
                    <div id="locationSection" class="hidden">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">State</label>
                                <select name="state_id" id="stateSelect"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">-- Select State --</option>
                                    @foreach(\App\Models\State::orderBy('name')->get() as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">LGA (Optional)</label>
                                <select name="lga_id" id="lgaSelect"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">-- Select LGA --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Recipient Count -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Recipient Count</h3>
                
                <div class="text-center py-6">
                    <div class="text-5xl font-bold text-blue-600" id="recipientCount">0</div>
                    <p class="text-sm text-gray-500 mt-2">users will receive this message</p>
                </div>

                <button type="button" onclick="calculateRecipients()" 
                        class="w-full mt-4 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition">
                    <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Calculate Recipients
                </button>
            </div>

            <!-- Send Options -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Send Options</h3>
                
                <div class="space-y-3">
                    <!-- Send Now -->
                    <label class="flex items-center p-4 border-2 border-blue-200 bg-blue-50 rounded-lg cursor-pointer">
                        <input type="radio" name="send_option" value="now" checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-900">Send Immediately</span>
                            <p class="text-xs text-gray-600 mt-1">Message will be sent right away</p>
                        </div>
                    </label>

                    <!-- Save as Draft -->
                    <label class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="radio" name="send_option" value="draft"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <div class="ml-3">
                            <span class="text-sm font-semibold text-gray-900">Save as Draft</span>
                            <p class="text-xs text-gray-600 mt-1">Send later manually</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="space-y-3">
                <button type="submit" name="send_now" value="1" id="sendNowBtn"
                        class="w-full px-6 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold transition flex items-center justify-center shadow-lg">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Send Message Now
                </button>

                <button type="submit" id="saveDraftBtn" style="display: none;"
                        class="w-full px-6 py-4 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-bold transition flex items-center justify-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Save as Draft
                </button>
            </div>

        </div>

    </div>

</form>

<!-- Character Counter Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.querySelector('textarea[name="message"]');
    const charCount = document.getElementById('charCount');
    
    if (messageTextarea) {
        messageTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    // Target Type Toggle
    const targetType = document.getElementById('targetType');
    const roleSection = document.getElementById('roleSection');
    const locationSection = document.getElementById('locationSection');

    targetType.addEventListener('change', function() {
        roleSection.classList.add('hidden');
        locationSection.classList.add('hidden');

        if (this.value === 'role') {
            roleSection.classList.remove('hidden');
        } else if (this.value === 'location') {
            locationSection.classList.remove('hidden');
        }
    });

    // Send option toggle
    const sendOptions = document.querySelectorAll('input[name="send_option"]');
    const sendNowBtn = document.getElementById('sendNowBtn');
    const saveDraftBtn = document.getElementById('saveDraftBtn');

    sendOptions.forEach(option => {
        option.addEventListener('change', function() {
            if (this.value === 'now') {
                sendNowBtn.style.display = 'flex';
                saveDraftBtn.style.display = 'none';
            } else {
                sendNowBtn.style.display = 'none';
                saveDraftBtn.style.display = 'flex';
            }
        });
    });

    // LGA loading based on state
    const stateSelect = document.getElementById('stateSelect');
    const lgaSelect = document.getElementById('lgaSelect');

    if (stateSelect) {
        stateSelect.addEventListener('change', function() {
            const stateId = this.value;
            lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';

            if (stateId) {
                fetch(`/api/lgas-by-state/${stateId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(lga => {
                            const option = document.createElement('option');
                            option.value = lga.id;
                            option.textContent = lga.name;
                            lgaSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading LGAs:', error));
            }
        });
    }
});

function calculateRecipients() {
    // This would make an AJAX call to calculate recipients
    // For now, just show a placeholder
    alert('Recipient calculation will be implemented with backend endpoint');
}
</script>

@endsection