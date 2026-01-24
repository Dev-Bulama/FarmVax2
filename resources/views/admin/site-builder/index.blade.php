@extends('layouts.admin')

@section('title', 'Site Builder')
@section('page-title', 'Site Content Builder')

@section('content')
<div class="mb-6">
    <p class="text-gray-600">Edit your website content and pages while preserving the design.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Page List -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Pages</h3>
            </div>
            <div class="divide-y">
                <a href="#" onclick="loadPage('home')" class="block p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">Home Page</p>
                            <p class="text-xs text-gray-500">Landing page</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                <a href="#" onclick="loadPage('about')" class="block p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">About Us</p>
                            <p class="text-xs text-gray-500">Company information</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                <a href="#" onclick="loadPage('services')" class="block p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">Services</p>
                            <p class="text-xs text-gray-500">Our services</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
                <a href="#" onclick="loadPage('contact')" class="block p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">Contact Us</p>
                            <p class="text-xs text-gray-500">Contact information</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Content Editor -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold">Edit Content</h3>
                <button onclick="saveContent()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i> Save Changes
                </button>
            </div>
            <div class="p-6">
                <div id="editor-content">
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-mouse-pointer text-4xl mb-3"></i>
                        <p>Select a page from the left to start editing</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Blocks -->
        <div class="mt-6 bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-lg font-semibold">Content Blocks</h3>
            </div>
            <div class="p-6 grid grid-cols-2 gap-4">
                <button onclick="addBlock('heading')" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                    <i class="fas fa-heading text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-semibold">Heading</p>
                </button>
                <button onclick="addBlock('text')" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                    <i class="fas fa-paragraph text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-semibold">Text Block</p>
                </button>
                <button onclick="addBlock('image')" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                    <i class="fas fa-image text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-semibold">Image</p>
                </button>
                <button onclick="addBlock('button')" class="p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                    <i class="fas fa-mouse-pointer text-2xl text-gray-400 mb-2"></i>
                    <p class="text-sm font-semibold">Button</p>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadPage(page) {
        alert('Loading ' + page + ' page editor...');
        document.getElementById('editor-content').innerHTML = `
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
                    <input type="text" value="${page.charAt(0).toUpperCase() + page.slice(1)}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Page Content</label>
                    <textarea rows="10" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Enter page content..."></textarea>
                </div>
            </div>
        `;
    }

    function addBlock(type) {
        alert('Adding ' + type + ' block...');
    }

    function saveContent() {
        alert('Saving content...');
    }
</script>
@endpush
@endsection
