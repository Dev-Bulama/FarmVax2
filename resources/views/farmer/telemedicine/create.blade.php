@extends('layouts.farmer')
@section('title', 'Request Video Consultation')
@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('farmer.telemedicine.index') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-2">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>Back
        </a>
        <h1 class="text-3xl font-bold" style="color:#11455b;">Request Video Consultation</h1>
        <p class="text-gray-500 mt-1">Submit your concern — the system automatically connects you to an available vet.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <ul class="text-sm text-red-700 list-disc list-inside">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('farmer.telemedicine.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow p-6 space-y-4">
            <h2 class="text-lg font-bold" style="color:#11455b;">Consultation Details</h2>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Reason for Consultation <span class="text-red-500">*</span>
                </label>
                <textarea name="reason" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400"
                          placeholder="Describe your concern — symptoms, behavior changes, or questions about your animal...">{{ old('reason') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes (Optional)</label>
                <textarea name="notes" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400"
                          placeholder="Any extra information that may help the vet...">{{ old('notes') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Related Animal (Optional)</label>
                    <select name="livestock_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                        <option value="">-- Select Animal --</option>
                        @foreach($livestock as $animal)
                            <option value="{{ $animal->id }}" {{ old('livestock_id') == $animal->id ? 'selected' : '' }}>
                                {{ $animal->tag_number ?? $animal->name ?? '#'.$animal->id }} — {{ ucfirst($animal->livestock_type ?? $animal->type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Priority <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                        <option value="normal"  {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="urgent"  {{ old('priority') == 'urgent' ? 'selected' : '' }}>🚨 Urgent</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
            <strong>How it works:</strong> After submitting, an available vet is automatically assigned.
            You'll land directly on a page with a <strong>Join Call</strong> button — click it to start your private video session instantly (no app download needed).
        </div>

        <div class="flex items-center justify-between bg-white rounded-xl shadow p-5">
            <a href="{{ route('farmer.telemedicine.index') }}" class="px-5 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg">Cancel</a>
            <button type="submit" class="px-8 py-3 text-white font-bold rounded-xl shadow" style="background:#2fcb6e;">
                Submit Request
            </button>
        </div>
    </form>
</div>
@endsection
