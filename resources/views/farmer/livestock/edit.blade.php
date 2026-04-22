@extends('layouts.farmer')

@section('title', 'Edit Livestock')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('farmer.livestock.show', $livestock->id) }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Details
        </a>
        <h1 class="text-3xl font-bold mt-2" style="color: #11455b;">Edit Livestock</h1>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <p class="text-sm font-medium text-red-800">Please fix the following errors:</p>
            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('farmer.livestock.update', $livestock->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tag Number <span class="text-gray-400">(optional)</span></label>
                    <input type="text" name="tag_number" value="{{ old('tag_number', $livestock->tag_number) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Name (Optional)</label>
                    <input type="text" name="name" value="{{ old('name', $livestock->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Livestock Type <span class="text-red-500">*</span></label>
                    <select name="livestock_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                        @foreach(['cattle'=>'🐄 Cattle','goat'=>'🐐 Goat','sheep'=>'🐑 Sheep','pig'=>'🐷 Pig','chicken'=>'🐔 Poultry/Chicken','duck'=>'🦆 Duck','rabbit'=>'🐇 Rabbit','horse'=>'🐴 Horse','donkey'=>'🫏 Donkey','other'=>'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ old('livestock_type', $livestock->livestock_type ?? $livestock->type) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Breed</label>
                    <input type="text" name="breed" value="{{ old('breed', $livestock->breed) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                        <option value="male"    {{ old('gender', $livestock->gender) == 'male'    ? 'selected' : '' }}>Male</option>
                        <option value="female"  {{ old('gender', $livestock->gender) == 'female'  ? 'selected' : '' }}>Female</option>
                        <option value="unknown" {{ old('gender', $livestock->gender) == 'unknown' ? 'selected' : '' }}>Unknown</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $livestock->quantity ?? 1) }}" min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Age (Years)</label>
                    <input type="number" name="age_years" value="{{ old('age_years', $livestock->age_years) }}" min="0"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Age (Months)</label>
                    <input type="number" name="age_months" value="{{ old('age_months', $livestock->age_months) }}" min="0" max="11"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">Health Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Health Status <span class="text-red-500">*</span></label>
                    <select name="health_status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                        @foreach(['healthy'=>'Healthy','sick'=>'Sick','recovering'=>'Recovering','deceased'=>'Deceased'] as $val => $label)
                            <option value="{{ $val }}" {{ old('health_status', $livestock->health_status) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Weight (kg)</label>
                    <input type="number" name="weight_kg" value="{{ old('weight_kg', $livestock->weight) }}" min="0" step="0.1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Color / Markings</label>
                    <input type="text" name="color_markings" value="{{ old('color_markings', $livestock->color) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_vaccinated" value="1" {{ old('is_vaccinated', $livestock->is_vaccinated) ? 'checked' : '' }} class="h-5 w-5 rounded">
                        <span class="ml-2 text-sm font-semibold text-gray-700">This animal has been vaccinated</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4" style="color: #11455b;">Additional Information</h2>
            @if(isset($herdGroups) && $herdGroups->count() > 0)
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Herd Group</label>
                    <select name="herd_group_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                        <option value="">No Herd Group</option>
                        @foreach($herdGroups as $herd)
                            <option value="{{ $herd->id }}" {{ old('herd_group_id', $livestock->herd_group_id) == $herd->id ? 'selected' : '' }}>{{ $herd->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                <textarea name="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">{{ old('notes', $livestock->notes) }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-between bg-white rounded-lg shadow p-6">
            <a href="{{ route('farmer.livestock.show', $livestock->id) }}" class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-8 py-3 text-white font-bold rounded-lg shadow-lg" style="background-color: #2fcb6e;">Save Changes</button>
        </div>
    </form>
</div>
@endsection
