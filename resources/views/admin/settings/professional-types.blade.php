@extends('layouts.admin')

@section('title', 'Professional Types')
@section('page-title', 'Professional Types & Specializations')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Professional Types -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Professional Types</h3>
            <button onclick="openModal('type')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                <i class="fas fa-plus mr-1"></i> Add
            </button>
        </div>
        <ul class="space-y-2">
            @forelse($professionalTypes ?? [] as $type)
                <li class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                    <span>{{ $type->name }}</span>
                    <div class="space-x-2">
                        <button onclick="editType({{ $type->id }})" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteType({{ $type->id }})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </li>
            @empty
                <li class="text-gray-500 text-center py-4">No types added</li>
            @endforelse
        </ul>
    </div>

    <!-- Specializations -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Specializations</h3>
            <button onclick="openModal('specialization')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                <i class="fas fa-plus mr-1"></i> Add
            </button>
        </div>
        <ul class="space-y-2">
            @forelse($specializations ?? [] as $spec)
                <li class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                    <span>{{ $spec->name }}</span>
                    <div class="space-x-2">
                        <button onclick="editSpec({{ $spec->id }})" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteSpec({{ $spec->id }})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </li>
            @empty
                <li class="text-gray-500 text-center py-4">No specializations added</li>
            @endforelse
        </ul>
    </div>

    <!-- Service Areas -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Service Areas</h3>
            <button onclick="openModal('area')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                <i class="fas fa-plus mr-1"></i> Add
            </button>
        </div>
        <ul class="space-y-2">
            @forelse($serviceAreas ?? [] as $area)
                <li class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                    <span>{{ $area->name }}</span>
                    <div class="space-x-2">
                        <button onclick="editArea({{ $area->id }})" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteArea({{ $area->id }})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </li>
            @empty
                <li class="text-gray-500 text-center py-4">No service areas added</li>
            @endforelse
        </ul>
    </div>
</div>

@push('scripts')
<script>
    function openModal(type) {
        alert('Open modal to add ' + type);
    }
    function editType(id) { alert('Edit type ' + id); }
    function editSpec(id) { alert('Edit specialization ' + id); }
    function editArea(id) { alert('Edit area ' + id); }
    function deleteType(id) { if(confirm('Delete this type?')) { alert('Delete type ' + id); }}
    function deleteSpec(id) { if(confirm('Delete this specialization?')) { alert('Delete spec ' + id); }}
    function deleteArea(id) { if(confirm('Delete this service area?')) { alert('Delete area ' + id); }}
</script>
@endpush
@endsection
