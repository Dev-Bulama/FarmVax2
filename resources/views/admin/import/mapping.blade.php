@extends('layouts.admin')

@section('title', 'Map Import Columns')
@section('page-title', 'Map Import Columns')

@section('content')

<!-- Progress Indicator -->
<div class="mb-6">
    <div class="flex items-center justify-between mb-2">
        <span class="text-sm font-medium text-gray-700">Import Progress</span>
        <span class="text-sm font-medium text-gray-700">Step 2 of 3</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2">
        <div class="bg-[#2FCB6E] h-2 rounded-full" style="width: 66%"></div>
    </div>
</div>

<!-- File Info -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-start">
        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <div>
            <h3 class="text-sm font-medium text-blue-900">{{ $import->original_filename }}</h3>
            <p class="text-sm text-blue-700 mt-1">
                <strong>{{ number_format($import->total_records) }}</strong> records found | 
                User Type: <strong>{{ $import->user_type_display }}</strong>
            </p>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Map Columns to Fields</h2>
    <p class="text-sm text-gray-600 mb-6">Match your Excel columns to the corresponding database fields. Required fields are marked with <span class="text-red-500">*</span></p>

    <form action="{{ route('admin.import.process', $import->id) }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Mapping Fields -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Field Mapping</h3>

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <select name="mapping[name]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                        <option value="">-- Select Column --</option>
                        @foreach($headers as $index => $header)
                            <option value="{{ $index }}" 
                                {{ stripos($header, 'name') !== false ? 'selected' : '' }}>
                                {{ $header }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <select name="mapping[email]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                        <option value="">-- Select Column --</option>
                        @foreach($headers as $index => $header)
                            <option value="{{ $index }}"
                                {{ stripos($header, 'email') !== false ? 'selected' : '' }}>
                                {{ $header }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <select name="mapping[phone]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                        <option value="">-- Select Column --</option>
                        @foreach($headers as $index => $header)
                            <option value="{{ $index }}"
                                {{ stripos($header, 'phone') !== false || stripos($header, 'whatsapp') !== false ? 'selected' : '' }}>
                                {{ $header }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Address <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <select name="mapping[address]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                        <option value="">-- Select Column --</option>
                        @foreach($headers as $index => $header)
                            <option value="{{ $index }}"
                                {{ stripos($header, 'address') !== false ? 'selected' : '' }}>
                                {{ $header }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Farm Name (for farmers) -->
                @if($import->user_type === 'farmer')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Farm Name <span class="text-gray-400 text-xs">(Optional)</span>
                        </label>
                        <select name="mapping[farm_name]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2FCB6E] focus:border-transparent">
                            <option value="">-- Select Column --</option>
                            @foreach($headers as $index => $header)
                                <option value="{{ $index }}"
                                    {{ stripos($header, 'farm') !== false && stripos($header, 'name') !== false ? 'selected' : '' }}>
                                    {{ $header }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <!-- Preview Data -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Data Preview</h3>
                <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-300">
                                @foreach($headers as $header)
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 whitespace-nowrap">
                                        {{ $header }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sampleData as $row)
                                <tr class="border-b border-gray-200">
                                    @foreach($row as $cell)
                                        <td class="px-3 py-2 text-gray-600 whitespace-nowrap">
                                            {{ Str::limit($cell, 30) }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="text-xs text-gray-500 mt-3">Showing first 3 rows as sample</p>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Important Notes:</h3>
                    <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside space-y-1">
                        <li>Random passwords will be generated for all imported users</li>
                        <li>Welcome emails with login credentials will be sent automatically</li>
                        <li>Duplicate email addresses will be skipped</li>
                        <li>All users will be set to "Active" status by default</li>
                        <li>Default country will be set to Nigeria</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.import.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-[#2FCB6E] text-white rounded-lg hover:bg-[#25a356] transition flex items-center"
                    onclick="return confirm('This will import {{ number_format($import->total_records) }} users. Continue?')">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Process Import ({{ number_format($import->total_records) }} Users)
            </button>
        </div>
    </form>
</div>

@endsection