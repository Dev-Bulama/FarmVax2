@php
    $role = auth()->user()->role ?? 'farmer';
    $layout = match($role) {
        'admin'                      => 'layouts.admin',
        'volunteer'                  => 'layouts.volunteer',
        'animal_health_professional' => 'layouts.professional',
        'individual'                 => 'layouts.individual',
        default                      => 'layouts.farmer',
    };
@endphp
@extends($layout)
@section('title', 'AI Animal Disease Scan')
@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('disease-detection.index') }}" class="text-gray-600 hover:text-gray-900 inline-flex items-center mb-2">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Scan History
        </a>
        <h1 class="text-3xl font-bold" style="color:#11455b;">🔬 AI Animal Disease Scan</h1>
        <p class="text-gray-500 mt-1">Upload a clear photo of your animal. Our AI will analyse it for signs of disease or illness with &ge;90% accuracy.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
            <ul class="text-sm text-red-700 list-disc list-inside">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('disease-detection.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow p-6 space-y-4">
            <h2 class="text-lg font-bold" style="color:#11455b;">Upload Animal Photo</h2>

            {{-- Image upload --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Animal Photo <span class="text-red-500">*</span>
                </label>
                <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-green-400 transition">
                    <input type="file" id="image-input" name="image" accept="image/jpeg,image/jpg,image/png,image/webp"
                           class="hidden" required onchange="previewImage(this)">
                    <div id="upload-placeholder">
                        <div class="text-5xl mb-3">📸</div>
                        <p class="font-semibold text-gray-700">Click to upload or drag & drop</p>
                        <p class="text-sm text-gray-400 mt-1">JPEG, JPG, PNG, WebP — max 10 MB</p>
                        <button type="button" onclick="document.getElementById('image-input').click()"
                                class="mt-3 px-4 py-2 text-white font-semibold rounded-lg text-sm" style="background:#2fcb6e;">
                            Choose Photo
                        </button>
                    </div>
                    <img id="preview-img" src="" alt="Preview" class="hidden max-h-64 mx-auto rounded-xl object-contain">
                </div>
                <p class="text-xs text-gray-400 mt-2">
                    Tips: Good lighting, clear focus, include the full body or the affected area in the frame.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Animal Type <span class="text-red-500">*</span></label>
                    <select name="animal_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400">
                        <option value="">-- Select Type --</option>
                        @foreach(['cattle'=>'🐄 Cattle','goat'=>'🐐 Goat','sheep'=>'🐑 Sheep','pig'=>'🐷 Pig','chicken'=>'🐔 Poultry / Chicken','duck'=>'🦆 Duck','rabbit'=>'🐇 Rabbit','horse'=>'🐴 Horse','donkey'=>'🫏 Donkey','other'=>'Other'] as $val => $label)
                            <option value="{{ $val }}" {{ old('animal_type') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Link to Registered Animal (Optional)</label>
                    <select name="livestock_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2">
                        <option value="">-- No specific animal --</option>
                        @foreach($livestock as $animal)
                            <option value="{{ $animal->id }}" {{ old('livestock_id') == $animal->id ? 'selected' : '' }}>
                                {{ $animal->tag_number ?? $animal->name ?? '#'.$animal->id }} — {{ ucfirst($animal->livestock_type ?? $animal->type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Describe what you've observed (Optional)</label>
                    <textarea name="symptoms_reported" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2"
                              placeholder="e.g. Not eating, limping, unusual discharge, swollen joints, weight loss…">{{ old('symptoms_reported') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-800">
            <strong>⚠️ Important:</strong> This AI scan is a screening tool, not a replacement for professional veterinary care.
            Always consult a licensed veterinarian for diagnosis and treatment, especially for serious or urgent conditions.
        </div>

        <div class="flex items-center justify-between bg-white rounded-xl shadow p-5">
            <a href="{{ route('disease-detection.index') }}" class="px-5 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg">Cancel</a>
            <button type="submit" id="submit-btn"
                    class="px-8 py-3 text-white font-bold rounded-xl shadow flex items-center gap-2" style="background:#2fcb6e;">
                <span id="btn-text">🔬 Analyse Now</span>
                <svg id="btn-spinner" class="hidden h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('preview-img').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
// Drag and drop
const zone = document.getElementById('drop-zone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-green-400'); });
zone.addEventListener('dragleave',  () => zone.classList.remove('border-green-400'));
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('border-green-400');
    const file = e.dataTransfer.files[0];
    if (file) {
        document.getElementById('image-input').files = e.dataTransfer.files;
        previewImage(document.getElementById('image-input'));
    }
});
// Loading state on submit
document.querySelector('form').addEventListener('submit', function() {
    document.getElementById('btn-text').textContent = 'Analysing…';
    document.getElementById('btn-spinner').classList.remove('hidden');
    document.getElementById('submit-btn').disabled = true;
});
</script>
@endsection
