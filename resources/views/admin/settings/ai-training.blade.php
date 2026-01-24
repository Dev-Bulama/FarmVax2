@extends('layouts.admin')

@section('title', 'AI Training Data')
@section('page-title', 'AI Chatbot Training Data')

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Add Training Data Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Add Training Data</h3>
            
            <form action="{{ route('admin.settings.ai-training.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" id="training-type" required onchange="toggleTrainingFields()"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="text">Manual Text</option>
                            <option value="url">Website URL</option>
                            <option value="document">Upload Document</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" name="title" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="e.g., Cattle Vaccination Guide">
                    </div>

                    <!-- Text Input -->
                    <div id="text-field">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                        <textarea name="content" rows="6"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                  placeholder="Enter training content..."></textarea>
                    </div>

                    <!-- URL Input -->
                    <div id="url-field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
                        <input type="url" name="url"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="https://example.com/article">
                        <p class="text-xs text-gray-500 mt-1">We'll automatically extract content from this URL</p>
                    </div>

                    <!-- Document Upload -->
                    <div id="document-field" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Document</label>
                        <input type="file" name="document" accept=".pdf,.doc,.docx,.txt"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, or TXT (Max 5MB)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="general">General</option>
                            <option value="vaccination">Vaccination</option>
                            <option value="diseases">Diseases</option>
                            <option value="livestock">Livestock Care</option>
                            <option value="farm_management">Farm Management</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm text-gray-900">Active</label>
                    </div>
                </div>

                <button type="submit" class="w-full mt-6 px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold">
                    Add Training Data
                </button>
            </form>
        </div>
    </div>

    <!-- Training Data List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">Training Data</h3>
                <span class="text-sm text-gray-500">{{ $trainingData->total() }} entries</span>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($trainingData as $data)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h4 class="text-base font-semibold text-gray-900">{{ $data->title }}</h4>
                                    @if($data->type == 'url')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">URL</span>
                                    @elseif($data->type == 'document')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Document</span>
                                    @else
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">Text</span>
                                    @endif
                                    @if($data->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded">Inactive</span>
                                    @endif
                                </div>
                                
                                @if($data->type == 'url')
                                    <p class="text-sm text-gray-600 mb-2">
                                        <a href="{{ $data->source_url }}" target="_blank" class="text-blue-600 hover:underline">{{ Str::limit($data->source_url, 60) }}</a>
                                    </p>
                                @elseif($data->type == 'document')
                                    <p class="text-sm text-gray-600 mb-2">Document: {{ $data->source_url }}</p>
                                @endif
                                
                                <p class="text-sm text-gray-600">{{ Str::limit($data->content, 150) }}</p>
                                
                                <div class="flex items-center space-x-4 mt-3 text-xs text-gray-500">
                                    <span>Category: {{ ucfirst(str_replace('_', ' ', $data->category)) }}</span>
                                    <span>•</span>
                                    <span>Added {{ $data->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                <form action="{{ route('admin.settings.ai-training.toggle', $data->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-900 transition" title="{{ $data->is_active ? 'Deactivate' : 'Activate' }}">
                                        @if($data->is_active)
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.settings.ai-training.destroy', $data->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Delete this training data?')" class="text-red-600 hover:text-red-900 transition" title="Delete">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-gray-500">No training data yet</p>
                        <p class="text-sm text-gray-400 mt-2">Add URLs, documents, or manual text to train your AI chatbot</p>
                    </div>
                @endforelse
            </div>
            
            @if($trainingData->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $trainingData->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

<script>
    function toggleTrainingFields() {
        const type = document.getElementById('training-type').value;
        const textField = document.getElementById('text-field');
        const urlField = document.getElementById('url-field');
        const documentField = document.getElementById('document-field');
        
        textField.classList.add('hidden');
        urlField.classList.add('hidden');
        documentField.classList.add('hidden');
        
        if (type === 'text') {
            textField.classList.remove('hidden');
        } else if (type === 'url') {
            urlField.classList.remove('hidden');
        } else if (type === 'document') {
            documentField.classList.remove('hidden');
        }
    }
</script>

@endsection