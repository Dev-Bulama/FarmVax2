@extends('layouts.admin')

@section('title', 'Advertisement Details')

@section('content')

<!-- Page Header -->
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">{{ $ad->title }}</h2>
        <p class="text-gray-600 mt-1">Advertisement Details</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.ads.edit', $ad->id) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
            Edit Ad
        </a>
        <a href="{{ route('admin.ads.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition">
            Back to List
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Ad Preview -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Advertisement Preview</h3>
            
            @if($ad->image_url)
            <div class="mb-4">
                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full rounded-lg shadow-md">
            </div>
            @endif

            <div class="space-y-3">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Title</p>
                    <p class="text-lg font-bold text-gray-900">{{ $ad->title }}</p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-600">Description</p>
                    <p class="text-gray-700">{{ $ad->description }}</p>
                </div>

                @if($ad->link_url)
                <div>
                    <p class="text-sm font-semibold text-gray-600">Link</p>
                    <a href="{{ $ad->link_url }}" target="_blank" class="text-blue-600 hover:underline">
                        {{ $ad->link_url }}
                    </a>
                </div>
                @endif

                <div>
                    <p class="text-sm font-semibold text-gray-600">Type</p>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full 
                        @if($ad->type == 'banner') bg-blue-100 text-blue-800
                        @elseif($ad->type == 'sidebar') bg-green-100 text-green-800
                        @elseif($ad->type == 'popup') bg-purple-100 text-purple-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($ad->type) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Recent Views -->
        @if(isset($recentViews) && $recentViews->count() > 0)
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Recent Views</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    @foreach($recentViews->take(10) as $view)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                <span class="text-xs font-semibold">{{ substr($view->user->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $view->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-500">{{ $view->viewed_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @if($view->clicked)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Clicked</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        
        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Performance</h3>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Total Views</p>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($ad->views_count) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Total Clicks</p>
                    <p class="text-3xl font-bold text-orange-600">{{ number_format($ad->clicks_count) }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600 mb-2">Click-Through Rate</p>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min($ad->click_through_rate, 100) }}%"></div>
                    </div>
                    <p class="text-sm font-semibold text-gray-900 mt-1">{{ $ad->click_through_rate }}%</p>
                </div>
            </div>
        </div>

        <!-- Status & Schedule -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Status & Schedule</h3>
            
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600">Status</p>
                    <p class="font-semibold mt-1">
                        @if($ad->is_active)
                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </p>
                </div>

                @if($ad->start_date)
                <div>
                    <p class="text-gray-600">Start Date</p>
                    <p class="font-semibold text-gray-900">{{ $ad->start_date->format('M d, Y') }}</p>
                </div>
                @endif

                @if($ad->end_date)
                <div>
                    <p class="text-gray-600">End Date</p>
                    <p class="font-semibold text-gray-900">{{ $ad->end_date->format('M d, Y') }}</p>
                </div>
                @endif

                @if($ad->budget)
                <div>
                    <p class="text-gray-600">Budget</p>
                    <p class="font-semibold text-gray-900">${{ number_format($ad->budget, 2) }}</p>
                </div>
                @endif

                @if($ad->cost_per_click)
                <div>
                    <p class="text-gray-600">Cost Per Click</p>
                    <p class="font-semibold text-gray-900">${{ number_format($ad->cost_per_click, 2) }}</p>
                </div>
                @endif

                <div>
                    <p class="text-gray-600">Created</p>
                    <p class="font-semibold text-gray-900">{{ $ad->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Actions</h3>
            
            <div class="space-y-3">
                <form action="{{ route('admin.ads.toggle', $ad->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-3 {{ $ad->is_active ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg font-semibold transition">
                        {{ $ad->is_active ? 'Deactivate Ad' : 'Activate Ad' }}
                    </button>
                </form>

                <form action="{{ route('admin.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Delete this advertisement permanently?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition">
                        Delete Advertisement
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>

@endsection