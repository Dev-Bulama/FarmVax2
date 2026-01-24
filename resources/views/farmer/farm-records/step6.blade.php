<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farm Record - Step 6 - FarmVax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">
    @include('farmer.partials.sidebar')

    <div class="flex-1 overflow-auto">
        <header class="bg-white shadow">
            <div class="px-4 sm:px-6 lg:px-8 py-4">
                <h1 class="text-2xl font-bold text-green-600">Farm Record Submission</h1>
                <p class="text-sm text-gray-600">Step 6 of 6: Feedback & Consent</p>
            </div>
            
            <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-green-600 text-white hover:bg-green-700">
                <svg id="menu-open-icon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="menu-close-icon" class="h-6 w-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </header>

        <main class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Progress Bar -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-green-600">Step 6 of 6</span>
                    <span class="text-xs text-gray-500">100% Complete</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 100%"></div>
                </div>
            </div>

            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow p-6 md:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Feedback & Consent</h2>
                    <p class="text-sm text-gray-600 mb-6">Almost done! Please review consent options and provide any feedback.</p>

                    <form method="POST" action="{{ route('individual.farm-records.step6.store') }}">
                        @csrf

                        <!-- Consent Options -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Consent & Permissions <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-4">
                                <label class="flex items-start p-4 border-2 rounded-md cursor-pointer hover:bg-green-50 {{ old('data_sharing_consent', session('farm_record_step6.data_sharing_consent')) == '1' ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    <input type="checkbox" name="data_sharing_consent" value="1" {{ old('data_sharing_consent', session('farm_record_step6.data_sharing_consent')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded mt-1" required>
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Data Sharing Consent</span>
                                        <p class="text-xs text-gray-500 mt-1">I consent to sharing my livestock data with veterinary authorities for disease monitoring and outbreak prevention.</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-4 border rounded-md cursor-pointer hover:bg-green-50 {{ old('research_participation_consent', session('farm_record_step6.research_participation_consent')) == '1' ? 'bg-green-50' : '' }}">
                                    <input type="checkbox" name="research_participation_consent" value="1" {{ old('research_participation_consent', session('farm_record_step6.research_participation_consent')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded mt-1">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Research Participation (Optional)</span>
                                        <p class="text-xs text-gray-500 mt-1">I agree to participate in research studies that may improve livestock health and farming practices.</p>
                                    </div>
                                </label>

                                <label class="flex items-start p-4 border rounded-md cursor-pointer hover:bg-green-50 {{ old('marketing_consent', session('farm_record_step6.marketing_consent')) == '1' ? 'bg-green-50' : '' }}">
                                    <input type="checkbox" name="marketing_consent" value="1" {{ old('marketing_consent', session('farm_record_step6.marketing_consent')) == '1' ? 'checked' : '' }} class="h-4 w-4 text-green-600 rounded mt-1">
                                    <div class="ml-3">
                                        <span class="text-sm font-medium">Marketing Communications (Optional)</span>
                                        <p class="text-xs text-gray-500 mt-1">I agree to receive promotional materials about livestock products and services.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Preferred Language -->
                        <div class="mb-6">
                            <label for="preferred_language" class="block text-sm font-medium text-gray-700 mb-2">
                                Preferred Language <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="preferred_language" 
                                name="preferred_language" 
                                required
                                class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                            >
                                @php
                                    $lang = old('preferred_language', session('farm_record_step6.preferred_language', 'english'));
                                @endphp
                                <option value="english" {{ $lang == 'english' ? 'selected' : '' }}>English</option>
                                <option value="hausa" {{ $lang == 'hausa' ? 'selected' : '' }}>Hausa</option>
                                <option value="yoruba" {{ $lang == 'yoruba' ? 'selected' : '' }}>Yoruba</option>
                                <option value="igbo" {{ $lang == 'igbo' ? 'selected' : '' }}>Igbo</option>
                                <option value="fulfulde" {{ $lang == 'fulfulde' ? 'selected' : '' }}>Fulfulde</option>
                                <option value="pidgin" {{ $lang == 'pidgin' ? 'selected' : '' }}>Pidgin</option>
                                <option value="french" {{ $lang == 'french' ? 'selected' : '' }}>French</option>
                                <option value="swahili" {{ $lang == 'swahili' ? 'selected' : '' }}>Swahili</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Language for communications and alerts</p>
                        </div>

                        <!-- Feedback Rating -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                How would you rate this form?
                            </label>
                            <div class="flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="form_rating" value="{{ $i }}" {{ old('form_rating', session('farm_record_step6.form_rating')) == $i ? 'checked' : '' }} class="sr-only peer">
                                    <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Feedback Comments -->
                        <div class="mb-6">
                            <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">
                                Feedback on Services
                            </label>
                            <textarea 
                                id="feedback" 
                                name="feedback" 
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Tell us about your experience with FarmVax services or any suggestions..."
                            >{{ old('feedback', session('farm_record_step6.feedback')) }}</textarea>
                        </div>

                        <!-- Additional Comments -->
                        <div class="mb-6">
                            <label for="additional_comments" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Comments
                            </label>
                            <textarea 
                                id="additional_comments" 
                                name="additional_comments" 
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                placeholder="Any other information you'd like to share..."
                            >{{ old('additional_comments', session('farm_record_step6.additional_comments')) }}</textarea>
                        </div>

                        <!-- Privacy Notice -->
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Privacy & Data Security</h3>
                                    <p class="mt-1 text-xs text-blue-700">Your data is encrypted and stored securely. We only share information with authorized veterinary professionals and government health authorities for disease prevention purposes. You can request data deletion at any time by contacting support.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation -->
                        <div class="flex justify-between items-center pt-6 border-t mt-8">
                            <a href="{{ route('individual.farm-records.step5') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition flex items-center">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Previous
                            </a>
                            <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition flex items-center font-semibold">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Submit Farm Record
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Success Preview -->
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">What happens next?</h3>
                            <p class="mt-1 text-xs text-green-700">After submission, your farm record will be reviewed by our team. You'll receive a confirmation SMS and email within 24 hours. Our veterinary professionals may contact you to schedule services based on your needs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');
    const menuButton = document.getElementById('mobile-menu-button');
    const menuOpenIcon = document.getElementById('menu-open-icon');
    const menuCloseIcon = document.getElementById('menu-close-icon');

    if (menuButton) {
        menuButton.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
            menuOpenIcon.classList.toggle('hidden');
            menuCloseIcon.classList.toggle('hidden');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            menuOpenIcon.classList.remove('hidden');
            menuCloseIcon.classList.add('hidden');
        });
    }
});
</script>

</body>
</html>