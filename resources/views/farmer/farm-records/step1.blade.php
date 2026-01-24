@extends('layouts.farmer')

@section('title', 'New Farm Record - Step 1')

@section('content')

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        {{-- Progress Bar --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold" style="color: #11455b;">Step 1 of 3</span>
                <span class="text-sm text-gray-600">Basic Information</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-500" style="width: 33.33%; background-color: #2fcb6e;"></div>
            </div>
        </div>

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

        {{-- Form Card --}}
        <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold" style="color: #11455b;">Farm Record Registration</h2>
                <p class="text-gray-600 mt-1">Please provide your basic farm and personal information</p>
            </div>

            <form action="{{ route('farmer.farm-records.step1.post') }}" method="POST">
                @csrf

                {{-- Farmer Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4" style="color: #11455b;">👨‍🌾 Farmer Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Farmer Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="farmer_name" value="{{ old('farmer_name', auth()->user()->name) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   style="focus:ring-color: #2fcb6e;"
                                   placeholder="Enter your full name">
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="farmer_phone" value="{{ old('farmer_phone', auth()->user()->phone) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="+234 000 000 0000">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" name="farmer_email" value="{{ old('farmer_email', auth()->user()->email) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="email@example.com">
                        </div>

                        {{-- Address --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Street Address
                            </label>
                            <textarea name="farmer_address" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                      placeholder="Enter your street address">{{ old('farmer_address', auth()->user()->address) }}</textarea>
                        </div>

                        {{-- City --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                City/Town <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="farmer_city" value="{{ old('farmer_city') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter city or town">
                        </div>

                        {{-- State --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                State <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="farmer_state" value="{{ old('farmer_state') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter state">
                        </div>

                        {{-- LGA --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Local Government Area (LGA)
                            </label>
                            <input type="text" name="farmer_lga" value="{{ old('farmer_lga') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter LGA">
                        </div>

                    </div>
                </div>

                {{-- Farm Information --}}
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4" style="color: #11455b;">🏡 Farm Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        {{-- Farm Name --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Farm Name
                            </label>
                            <input type="text" name="farm_name" value="{{ old('farm_name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter farm name (optional)">
                        </div>

                        {{-- Farm Size --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Farm Size
                            </label>
                            <input type="number" step="0.01" name="farm_size" value="{{ old('farm_size') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                   placeholder="Enter size">
                        </div>

                        {{-- Farm Size Unit --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Unit <span class="text-red-500">*</span>
                            </label>
                            <select name="farm_size_unit" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent">
                                <option value="hectares" {{ old('farm_size_unit') == 'hectares' ? 'selected' : '' }}>Hectares</option>
                                <option value="acres" {{ old('farm_size_unit') == 'acres' ? 'selected' : '' }}>Acres</option>
                                <option value="square_meters" {{ old('farm_size_unit') == 'square_meters' ? 'selected' : '' }}>Square Meters</option>
                            </select>
                        </div>

                        {{-- Farm Type --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Farm Type <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:border-[#2fcb6e]" style="border-color: {{ old('farm_type') == 'subsistence' ? '#2fcb6e' : '#d1d5db' }}">
                                    <input type="radio" name="farm_type" value="subsistence" {{ old('farm_type', 'subsistence') == 'subsistence' ? 'checked' : '' }} class="mr-2">
                                    <span class="text-sm font-semibold">Subsistence</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:border-[#2fcb6e]" style="border-color: {{ old('farm_type') == 'commercial' ? '#2fcb6e' : '#d1d5db' }}">
                                    <input type="radio" name="farm_type" value="commercial" {{ old('farm_type') == 'commercial' ? 'checked' : '' }} class="mr-2">
                                    <span class="text-sm font-semibold">Commercial</span>
                                </label>
                                <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition hover:border-[#2fcb6e]" style="border-color: {{ old('farm_type') == 'mixed' ? '#2fcb6e' : '#d1d5db' }}">
                                    <input type="radio" name="farm_type" value="mixed" {{ old('farm_type') == 'mixed' ? 'checked' : '' }} class="mr-2">
                                    <span class="text-sm font-semibold">Mixed</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- GPS Location (Optional) --}}
                <div class="mb-8 bg-blue-50 p-4 rounded-lg border-l-4 border-blue-400">
                    <h3 class="text-sm font-bold text-blue-900 mb-3">📍 GPS Location (Optional)</h3>
                    <p class="text-sm text-blue-800 mb-3">You can add your farm's GPS coordinates for better service delivery</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                   placeholder="e.g., 6.5244">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                   placeholder="e.g., 3.3792">
                        </div>
                    </div>
                    <button type="button" onclick="getLocation()" 
                            class="mt-3 text-sm font-semibold text-blue-600 hover:text-blue-700">
                        📍 Use My Current Location
                    </button>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('farmer.farm-records.index') }}" 
                       class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 text-white font-bold rounded-lg shadow-lg transition hover:shadow-xl"
                            style="background-color: #2fcb6e;">
                        Next Step →
                    </button>
                </div>

            </form>

        </div>

        {{-- Help Text --}}
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Need help? <a href="mailto:support@farmvax.com" class="font-semibold" style="color: #2fcb6e;">Contact Support</a>
            </p>
        </div>

    </div>
</div>

<script>
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.querySelector('input[name="latitude"]').value = position.coords.latitude.toFixed(6);
            document.querySelector('input[name="longitude"]').value = position.coords.longitude.toFixed(6);
            alert('✅ Location captured successfully!');
        }, function(error) {
            alert('❌ Unable to get location. Please enter manually.');
        });
    } else {
        alert('❌ Geolocation is not supported by your browser.');
    }
}
</script>

@endsection