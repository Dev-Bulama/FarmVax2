@props(['ads', 'type' => 'banner'])

@if($ads && $ads->count() > 0)
    <div class="ads-container {{ $type }}-ads">
        @foreach($ads as $ad)
            <div class="ad-item mb-4 relative {{ $type === 'banner' ? 'w-full' : '' }}" data-ad-id="{{ $ad->id }}">
                <!-- Ad Label -->
                <span class="absolute top-2 right-2 bg-gray-800 bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                    Sponsored
                </span>

                @if($type === 'banner')
                    <!-- Banner Ad (Full Width) -->
                    <a href="{{ $ad->link_url ? route('ad.click', $ad->id) : '#' }}" 
                       target="{{ $ad->link_url ? '_blank' : '_self' }}"
                       class="block rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition">
                        @if($ad->image_url)
                            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full h-32 md:h-48 object-cover">
                        @else
                            <div class="w-full h-32 md:h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <div class="text-center text-white p-4">
                                    <h3 class="text-xl font-bold mb-2">{{ $ad->title }}</h3>
                                    <p class="text-sm">{{ Str::limit($ad->description, 100) }}</p>
                                </div>
                            </div>
                        @endif
                    </a>

                @elseif($type === 'sidebar')
                    <!-- Sidebar Ad (Square/Vertical) -->
                    <a href="{{ $ad->link_url ? route('ad.click', $ad->id) : '#' }}" 
                       target="{{ $ad->link_url ? '_blank' : '_self' }}"
                       class="block bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition">
                        @if($ad->image_url)
                            <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full h-40 object-cover">
                        @endif
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 mb-2">{{ $ad->title }}</h4>
                            <p class="text-sm text-gray-600">{{ Str::limit($ad->description, 80) }}</p>
                            @if($ad->link_url)
                                <button class="mt-3 text-blue-600 text-sm font-semibold hover:underline">
                                    Learn More →
                                </button>
                            @endif
                        </div>
                    </a>

                @elseif($type === 'inline')
                    <!-- Inline Ad (Within Content) -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 rounded-lg p-4">
                        <div class="flex items-start">
                            @if($ad->image_url)
                                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-20 h-20 rounded object-cover mr-4">
                            @endif
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-1">{{ $ad->title }}</h4>
                                <p class="text-sm text-gray-700 mb-2">{{ Str::limit($ad->description, 100) }}</p>
                                @if($ad->link_url)
                                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                                       class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                        Learn More
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                @elseif($type === 'popup')
                    <!-- Popup Ad (Modal style - implement with JavaScript) -->
                    <div id="popup-ad-{{ $ad->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white rounded-lg max-w-lg w-full mx-4 relative">
                            <button onclick="closePopup('popup-ad-{{ $ad->id }}')" 
                                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            @if($ad->image_url)
                                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="w-full h-64 object-cover rounded-t-lg">
                            @endif
                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $ad->title }}</h3>
                                <p class="text-gray-700 mb-4">{{ $ad->description }}</p>
                                @if($ad->link_url)
                                    <a href="{{ route('ad.click', $ad->id) }}" target="_blank"
                                       class="block w-full text-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        Learn More
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif