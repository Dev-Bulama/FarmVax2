@extends('layouts.farmer')

@section('title', $livestock->tag_number ?? $livestock->name ?? 'Livestock Details')

@section('content')
<div class="p-6 max-w-6xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('farmer.livestock.index') }}" class="text-gray-600 hover:text-gray-900 mb-2 inline-flex items-center">
            <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to My Livestock
        </a>
        <div class="flex items-center justify-between mt-2">
            <div>
                <h1 class="text-3xl font-bold" style="color: #11455b;">
                    {{ $livestock->tag_number ?? $livestock->name ?? 'Animal #' . $livestock->id }}
                </h1>
                <p class="text-gray-600 mt-1">{{ ucfirst($livestock->livestock_type ?? $livestock->type) }} &middot; {{ ucfirst($livestock->gender) }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('farmer.livestock.edit', $livestock->id) }}"
                   class="px-4 py-2 border-2 font-semibold rounded-lg hover:bg-gray-50"
                   style="border-color: #11455b; color: #11455b;">Edit</a>
                <form action="{{ route('farmer.livestock.destroy', $livestock->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Delete this livestock record?')"
                            class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">Delete</button>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
            <p class="text-green-800 font-semibold">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4" style="color: #11455b;">Basic Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div><p class="text-sm text-gray-600 font-semibold">Tag Number</p><p class="text-gray-900">{{ $livestock->tag_number ?? 'Not set' }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Name</p><p class="text-gray-900">{{ $livestock->name ?? 'Not set' }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Type</p><p class="text-gray-900">{{ ucfirst($livestock->livestock_type ?? $livestock->type) }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Breed</p><p class="text-gray-900">{{ $livestock->breed ?? 'Not specified' }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Gender</p><p class="text-gray-900">{{ ucfirst($livestock->gender) }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Age</p><p class="text-gray-900">{{ $livestock->age ?? 'Unknown' }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Quantity</p><p class="text-gray-900">{{ $livestock->quantity ?? 1 }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Color / Markings</p><p class="text-gray-900">{{ $livestock->color ?? 'Not specified' }}</p></div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4" style="color: #11455b;">Health Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Health Status</p>
                        @php $colors = ['healthy'=>'bg-green-100 text-green-800','sick'=>'bg-red-100 text-red-800','recovering'=>'bg-yellow-100 text-yellow-800','deceased'=>'bg-gray-100 text-gray-800']; @endphp
                        <span class="px-2 py-1 text-xs font-bold rounded-full {{ $colors[$livestock->health_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($livestock->health_status) }}
                        </span>
                    </div>
                    <div><p class="text-sm text-gray-600 font-semibold">Vaccination</p><p class="text-gray-900">{{ $livestock->is_vaccinated ? '✅ Vaccinated' : '❌ Not vaccinated' }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Last Vaccination</p><p class="text-gray-900">{{ $livestock->last_vaccination_date?->format('d M Y') ?? 'N/A' }}</p></div>
                    <div><p class="text-sm text-gray-600 font-semibold">Weight</p><p class="text-gray-900">{{ $livestock->weight ? $livestock->weight . ' ' . ($livestock->weight_unit ?? 'kg') : 'Not recorded' }}</p></div>
                </div>
                @if($livestock->notes)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600 font-semibold">Notes</p>
                        <p class="text-gray-900 mt-1">{{ $livestock->notes }}</p>
                    </div>
                @endif
            </div>

            @if($livestock->vaccinationHistory && $livestock->vaccinationHistory->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4" style="color: #11455b;">Vaccination History</h2>
                <div class="space-y-2">
                    @foreach($livestock->vaccinationHistory->take(5) as $vax)
                    <div class="flex justify-between items-center py-2 border-b last:border-0">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $vax->vaccine_name ?? 'Vaccine' }}</p>
                            <p class="text-sm text-gray-500">{{ $vax->administered_by ?? '' }}</p>
                        </div>
                        <p class="text-sm text-gray-600">{{ $vax->vaccination_date?->format('d M Y') ?? $vax->created_at->format('d M Y') }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4" style="color: #11455b;">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('farmer.vaccinations.create') }}" class="block w-full text-center py-2 px-4 border-2 font-semibold rounded-lg hover:bg-gray-50" style="border-color: #2fcb6e; color: #2fcb6e;">
                        + Record Vaccination
                    </a>
                    <a href="{{ route('farmer.service-requests.create') }}" class="block w-full text-center py-2 px-4 border-2 font-semibold rounded-lg hover:bg-gray-50" style="border-color: #11455b; color: #11455b;">
                        + Request Vet Service
                    </a>
                    <a href="{{ route('farmer.livestock.edit', $livestock->id) }}" class="block w-full text-center py-2 px-4 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200">
                        Edit Details
                    </a>
                </div>
            </div>

            @if($livestock->herdGroup)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-2" style="color: #11455b;">Herd Group</h2>
                <p class="font-semibold text-gray-800">{{ $livestock->herdGroup->name }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $livestock->herdGroup->description ?? '' }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
