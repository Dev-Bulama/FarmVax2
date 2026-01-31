<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\Admin\OutbreakAlertController;
use App\Http\Controllers\Admin\BulkMessageController;
use App\Http\Controllers\Individual\DashboardController as IndividualDashboardController;
use App\Http\Controllers\Professional\DashboardController as ProfessionalDashboardController;
use App\Http\Controllers\Volunteer\DashboardController as VolunteerDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Api\AiChatController;
use App\Http\Controllers\Admin\VolunteerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome Page & Authentication
Route::get('/', function() {
    try {
        // Get real-time statistics with error handling
        $stats = [];

        // Count farmers (check if is_active column exists)
        try {
            $stats['farmers'] = \App\Models\User::where('role', 'farmer')->count();
        } catch (\Exception $e) {
            $stats['farmers'] = 0;
        }

        // Count approved professionals
        try {
            $stats['professionals'] = \App\Models\AnimalHealthProfessional::where('approval_status', 'approved')->count();
        } catch (\Exception $e) {
            $stats['professionals'] = 0;
        }

        // Count livestock
        try {
            $stats['livestock'] = \App\Models\Livestock::count();
        } catch (\Exception $e) {
            $stats['livestock'] = 0;
        }

        // Count farm records
        try {
            $stats['farm_records'] = \App\Models\FarmRecord::count();
        } catch (\Exception $e) {
            $stats['farm_records'] = 0;
        }

        // Count vaccinations
        try {
            $stats['vaccinations'] = \App\Models\VaccinationHistory::count();
        } catch (\Exception $e) {
            $stats['vaccinations'] = 0;
        }

        return view('welcome', compact('stats'));
    } catch (\Exception $e) {
        // If all else fails, show welcome page with default stats
        \Log::error('Welcome page error: ' . $e->getMessage());
        $stats = [
            'farmers' => 0,
            'professionals' => 0,
            'livestock' => 0,
            'farm_records' => 0,
            'vaccinations' => 0,
        ];
        return view('welcome', compact('stats'));
    }
})->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes - Role Specific
// Route::get('/register/farmer', [RegisterController::class, 'showFarmerForm'])->name('register.farmer');
// Route::post('/register/farmer', [RegisterController::class, 'registerFarmer']);

// Route::get('/register/professional', [RegisterController::class, 'showProfessionalForm'])->name('register.professional');
// Route::post('/register/professional', [RegisterController::class, 'registerProfessional']);

// Route::get('/register/volunteer', [RegisterController::class, 'showVolunteerForm'])->name('register.volunteer');
// Route::post('/register/volunteer', [RegisterController::class, 'registerVolunteer']);
/*
|--------------------------------------------------------------------------
| Registration Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Show registration forms
Route::get('/register/farmer', [App\Http\Controllers\Auth\RegisterController::class, 'showFarmerForm'])->name('register.farmer.form');
Route::get('/register/professional', [App\Http\Controllers\Auth\RegisterController::class, 'showProfessionalForm'])->name('register.professional.form');
Route::get('/register/volunteer', [App\Http\Controllers\Auth\RegisterController::class, 'showVolunteerForm'])->name('register.volunteer.form');

// Handle registration submissions
Route::post('/register/farmer', [App\Http\Controllers\Auth\RegisterController::class, 'registerFarmer'])->name('register.farmer');
Route::post('/register/professional', [App\Http\Controllers\Auth\RegisterController::class, 'registerProfessional'])->name('register.professional');
Route::post('/register/volunteer', [App\Http\Controllers\Auth\RegisterController::class, 'registerVolunteer'])->name('register.volunteer');

// Generic registration (fallback)
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showFarmerForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
// Legacy route - redirect to farmer registration
Route::get('/register', function () {
    return redirect()->route('register.farmer');
})->name('register');
/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
*/

// Show forgot password form
Route::get('/password/forgot', [App\Http\Controllers\Auth\PasswordResetController::class, 'showForgotForm'])->name('password.request');

// Send reset link
Route::post('/password/email', [App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetLink'])->name('password.email');

// Show reset password form
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');

// Update password
Route::post('/password/reset', [App\Http\Controllers\Auth\PasswordResetController::class, 'resetPassword'])->name('password.update');
/*
|--------------------------------------------------------------------------
| Ad Click Tracking (Authenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/ad/click/{id}', function($id) {
        $ad = \App\Models\Ad::findOrFail($id);
        
        // Track the click
        $adService = new \App\Services\AdService();
        $adService->trackAdClick($id, auth()->user());
        
        // Redirect to ad link
        if ($ad->link_url) {
            return redirect()->away($ad->link_url);
        }
        
        return redirect()->back();
    })->name('ad.click');
});
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| API Routes (for AJAX calls)
|--------------------------------------------------------------------------
*/

// API Routes for AJAX requests
Route::get('/api/lgas-by-state/{stateId}', function($stateId) {
    return \App\Models\Lga::where('state_id', $stateId)
                         ->orderBy('name')
                         ->get(['id', 'name']);
});
// Validate referral code
Route::get('/api/validate-referral/{code}', function ($code) {
    $volunteer = \App\Models\Volunteer::where('referral_code', strtoupper($code))->first();
    
    if ($volunteer) {
        $user = \App\Models\User::find($volunteer->user_id);
        return response()->json([
            'valid' => true,
            'volunteer_name' => $user->name ?? 'Volunteer',
            'volunteer_id' => $volunteer->id
        ]);
    }
    
    return response()->json(['valid' => false]);
});
/*
|--------------------------------------------------------------------------
| API Routes for Location (No Auth Required)
|--------------------------------------------------------------------------
*/
// Location API endpoints (ADD BEFORE auth middleware routes)
Route::get('/api/states/{country}', function ($country) {
    $states = \App\Models\State::where('country_id', $country)
        ->orderBy('name')
        ->get(['id', 'name']);
    return response()->json($states);
});

Route::get('/api/lgas/{state}', function ($state) {
    $lgas = \App\Models\Lga::where('state_id', $state)
        ->orderBy('name')
        ->get(['id', 'name']);
    return response()->json($lgas);
});
// Get countries
Route::get('/api/countries', [App\Http\Controllers\API\LocationController::class, 'getCountries']);

// Get states by country
Route::get('/api/states-by-country/{countryId}', [App\Http\Controllers\API\LocationController::class, 'getStatesByCountry']);

// Get LGAs by state (keep existing one or use this)
Route::get('/api/lgas-by-state/{stateId}', [App\Http\Controllers\API\LocationController::class, 'getLgasByState']);

// Reverse geocode coordinates to address
Route::post('/api/reverse-geocode', [App\Http\Controllers\API\LocationController::class, 'reverseGeocode']);

Route::prefix('api')->name('api.')->group(function () {
    // AI Chat API
    //Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');
    Route::post('/ai/chat', [App\Http\Controllers\Api\AiChatController::class, 'chat'])->name('ai.chat');

    // Location APIs
    Route::get('/countries', [LocationController::class, 'countries'])->name('countries');
    Route::get('/states/{countryId?}', [LocationController::class, 'states'])->name('states');
    Route::get('/lgas/{stateId?}', [LocationController::class, 'lgas'])->name('lgas');
    
    Route::post('/detect-location', [LocationController::class, 'detectLocation'])->name('detect-location');
    Route::get('/locations/search', [LocationController::class, 'search'])->name('locations.search');
    // Reverse Geocode - Match GPS coordinates to database locations
Route::post('/api/reverse-geocode', function (Request $request) {
    $latitude = $request->latitude;
    $longitude = $request->longitude;
    
    if (!$latitude || !$longitude) {
        return response()->json([
            'success' => false,
            'message' => 'Coordinates required'
        ]);
    }

    // Use a free geocoding service (Nominatim - OpenStreetMap)
    try {
        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=10&addressdetails=1";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'FarmVax/1.0');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200 || !$response) {
            throw new Exception('Geocoding service unavailable');
        }
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['address'])) {
            throw new Exception('Invalid geocoding response');
        }
        
        $address = $data['address'];
        $countryName = $address['country'] ?? null;
        $stateName = $address['state'] ?? $address['region'] ?? null;
        $lgaName = $address['county'] ?? $address['state_district'] ?? $address['municipality'] ?? null;
        
        // Try to match to database
        $matches = [
            'country_id' => null,
            'state_id' => null,
            'lga_id' => null,
        ];
        
        if ($countryName) {
            $country = \App\Models\Country::where('name', 'LIKE', "%{$countryName}%")->first();
            if ($country) {
                $matches['country_id'] = $country->id;
                
                // Try to match state
                if ($stateName) {
                    $state = \App\Models\State::where('country_id', $country->id)
                        ->where('name', 'LIKE', "%{$stateName}%")
                        ->first();
                    
                    if ($state) {
                        $matches['state_id'] = $state->id;
                        
                        // Try to match LGA
                        if ($lgaName) {
                            $lga = \App\Models\Lga::where('state_id', $state->id)
                                ->where('name', 'LIKE', "%{$lgaName}%")
                                ->first();
                            
                            if ($lga) {
                                $matches['lga_id'] = $lga->id;
                            }
                        }
                    }
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'matches' => $matches,
            'address' => [
                'formatted' => $data['display_name'] ?? '',
                'country' => $countryName,
                'state' => $stateName,
                'lga' => $lgaName,
                'city' => $address['city'] ?? $address['town'] ?? $address['village'] ?? '',
                'postcode' => $address['postcode'] ?? '',
            ],
            'raw' => $data
        ]);
        
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Could not determine address: ' . $e->getMessage(),
            'matches' => [
                'country_id' => null,
                'state_id' => null,
                'lga_id' => null,
            ]
        ]);
    }
});
/*
|--------------------------------------------------------------------------
| Farmer/Individual Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('farmer')->name('farmer.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Farmer\DashboardController::class, 'index'])->name('dashboard');

    // Help & Support
    Route::get('/help', function() {
        return view('farmer.help');
    })->name('help');

    // Farm Records - 3 Step Form
    Route::prefix('farm-records')->name('farm-records.')->group(function () {
        
        // List all farm records
        Route::get('/', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'index'])->name('index');
        
        // View single farm record
        Route::get('/{id}', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'show'])->name('show');
        
        // Step 1 - Basic Information
        Route::get('/create/step1', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step1'])->name('step1');
        Route::post('/create/step1', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep1'])->name('step1.post');
        
        // Step 2 - Livestock Information
        Route::get('/create/step2', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step2'])->name('step2');
        Route::post('/create/step2', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep2'])->name('step2.post');
        
        // Step 3 - Health & Vaccination
        Route::get('/create/step3', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step3'])->name('step3');
        Route::post('/create/step3', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep3'])->name('step3.post');
        
        // Navigate back
        Route::get('/back/{step}', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'previousStep'])->name('back');
        
    });
    
    // Livestock Management
    Route::resource('livestock', \App\Http\Controllers\Farmer\LivestockController::class);
    
    // Service Requests
    Route::resource('service-requests', \App\Http\Controllers\Farmer\ServiceRequestController::class);
    
    // Vaccinations
    Route::resource('vaccinations', \App\Http\Controllers\Farmer\VaccinationController::class);
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Farmer\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Farmer\ProfileController::class, 'update'])->name('profile.update');
    
});
    // Livestock
    Route::get('/livestock', [App\Http\Controllers\Farmer\LivestockController::class, 'index'])->name('livestock.index');
    Route::get('/livestock/create', [App\Http\Controllers\Farmer\LivestockController::class, 'create'])->name('livestock.create');
    Route::post('/livestock', [App\Http\Controllers\Farmer\LivestockController::class, 'store'])->name('livestock.store');
    
    // Service Requests
    // Route::get('/service-requests', [App\Http\Controllers\Farmer\ServiceRequestController::class, 'index'])->name('service-requests.index');
    // Route::get('/service-requests/create', [App\Http\Controllers\Farmer\ServiceRequestController::class, 'create'])->name('service-requests.create');
    // Route::post('/service-requests', [App\Http\Controllers\Farmer\ServiceRequestController::class, 'store'])->name('service-requests.store');
    Route::get('/service-requests', [App\Http\Controllers\Farmer\ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('/service-requests/create', [App\Http\Controllers\Farmer\ServiceRequestController::class, 'create'])->name('service-requests.create');
    Route::post('/service-requests', [App\Http\Controllers\Farmer\ServiceRequestController::class, 'store'])->name('service-requests.store');
    Route::get('/service-requests/{id}', [App\Http\Controllers\Farmer\ServiceRequestController::class, 'show'])->name('service-requests.show');
    Route::post('/service-requests/{id}/cancel', [App\Http\Controllers\Farmer\ServiceRequestController::class, 'cancel'])->name('service-requests.cancel');
    // Vaccinations
    Route::get('/vaccinations', [App\Http\Controllers\Farmer\VaccinationController::class, 'index'])->name('vaccinations.index');
    
   // Farm Records - 3-Step Simplified System
    Route::get('/farm-records/step1', [App\Http\Controllers\Farmer\FarmRecordController::class, 'step1'])->name('farm-records.step1');
    Route::post('/farm-records/step1', [App\Http\Controllers\Farmer\FarmRecordController::class, 'storeStep1'])->name('farm-records.step1.store');
    Route::get('/farm-records/step2', [App\Http\Controllers\Farmer\FarmRecordController::class, 'step2'])->name('farm-records.step2');
    Route::post('/farm-records/step2', [App\Http\Controllers\Farmer\FarmRecordController::class, 'storeStep2'])->name('farm-records.step2.store');
    Route::get('/farm-records/step3', [App\Http\Controllers\Farmer\FarmRecordController::class, 'step3'])->name('farm-records.step3');
    Route::post('/farm-records/step3', [App\Http\Controllers\Farmer\FarmRecordController::class, 'storeStep3'])->name('farm-records.step3.store');
    Route::get('/farm-records', [App\Http\Controllers\Farmer\FarmRecordController::class, 'index'])->name('farm-records.index');
    Route::get('/farm-records/{id}', [App\Http\Controllers\Farmer\FarmRecordController::class, 'show'])->name('farm-records.show');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Farmer\ProfileController::class, 'index'])->name('profile');



});  // ← Keep this closing bracket

/*
|--------------------------------------------------------------------------
| Individual Routes (LEGACY - Redirect to Farmer)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:farmer'])->prefix('individual')->name('individual.')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('farmer.dashboard'))->name('dashboard');
    Route::get('/livestock', fn() => redirect()->route('farmer.livestock.index'))->name('livestock.index');
    Route::get('/livestock/create', fn() => redirect()->route('farmer.livestock.create'))->name('livestock.create');
    Route::get('/service-requests', fn() => redirect()->route('farmer.service-requests.index'))->name('service-requests.index');
    Route::get('/service-requests/create', fn() => redirect()->route('farmer.service-requests.create'))->name('service-requests.create');
    Route::get('/vaccinations', fn() => redirect()->route('farmer.vaccinations.index'))->name('vaccinations.index');
    Route::get('/farm-records/step1', fn() => redirect()->route('farmer.farm-records.step1'))->name('farm-records.step1');
    // Route::get('/profile', fn() => redirect()->route('farmer.profile'))->name('profile');
});
    // Chat APIs (Authenticated)
    Route::middleware('auth')->group(function () {
        Route::get('/chat/conversations', [ChatController::class, 'index'])->name('chat.index');
        Route::post('/chat/conversations', [ChatController::class, 'store'])->name('chat.store');
        Route::get('/chat/conversations/{id}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/chat/conversations/{id}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::post('/chat/messages/{id}/reactions', [ChatController::class, 'addReaction'])->name('chat.reaction.add');
        Route::delete('/chat/messages/{id}/reactions', [ChatController::class, 'removeReaction'])->name('chat.reaction.remove');
        Route::post('/chat/conversations/{id}/participants', [ChatController::class, 'addParticipant'])->name('chat.participant.add');
        Route::delete('/chat/conversations/{id}/participants/{userId}', [ChatController::class, 'removeParticipant'])->name('chat.participant.remove');
        Route::post('/chat/conversations/{id}/leave', [ChatController::class, 'leave'])->name('chat.leave');
        Route::get('/chat/users/search', [ChatController::class, 'searchUsers'])->name('chat.users.search');
        Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread');
    });


/*
|--------------------------------------------------------------------------
| Chat Web Interface (All Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/chat', function() {
        return view('chat.index');
    })->name('chat.interface');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Settings
    
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/email', [SettingsController::class, 'email'])->name('settings.email');
    Route::put('/settings/email', [SettingsController::class, 'updateEmail'])->name('settings.email.update');
    Route::get('/settings/sms', [SettingsController::class, 'sms'])->name('settings.sms');
    Route::put('/settings/sms', [SettingsController::class, 'updateSms'])->name('settings.sms.update');
    Route::post('/settings/sms/test', [SettingsController::class, 'testSms'])->name('settings.sms.test');
    Route::post('/settings/email/test', [SettingsController::class, 'testEmail'])->name('settings.email.test');
    Route::get('/settings/ai', [SettingsController::class, 'ai'])->name('settings.ai');
    Route::put('/settings/ai', [SettingsController::class, 'updateAi'])->name('settings.ai.update');
    Route::get('/settings/professional-types', [SettingsController::class, 'professionalTypes'])->name('settings.professional-types');
    Route::post('/settings/professional-types', [SettingsController::class, 'storeProfessionalType'])->name('settings.professional-types.store');
    Route::post('/settings/specializations', [SettingsController::class, 'storeSpecialization'])->name('settings.specializations.store');
    Route::post('/settings/service-areas', [SettingsController::class, 'storeServiceArea'])->name('settings.service-areas.store');
    Route::delete('/settings/professional-types/{id}', [SettingsController::class, 'deleteProfessionalType'])->name('settings.professional-types.delete');
    Route::delete('/settings/specializations/{id}', [SettingsController::class, 'deleteSpecialization'])->name('settings.specializations.delete');
    Route::delete('/settings/service-areas/{id}', [SettingsController::class, 'deleteServiceArea'])->name('settings.service-areas.delete');
Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
Route::put('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');
   
// User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/bulk-action', [UserManagementController::class, 'bulkAction'])->name('users.bulk-action');
    Route::post('/users/{id}/activate', [UserManagementController::class, 'activate'])->name('users.activate');
    Route::post('/users/{id}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{id}/suspend', [UserManagementController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/ban', [UserManagementController::class, 'ban'])->name('users.ban');
    Route::post('/users/{id}/convert-role', [UserManagementController::class, 'convertRole'])->name('users.convert-role');
    Route::post('/users/bulk-convert-role', [UserManagementController::class, 'bulkConvertRole'])->name('users.bulk-convert-role');

    // Ads Management
    // Route::resource('ads', AdsController::class);
    // Route::get('/ads/{id}/analytics', [AdsController::class, 'analytics'])->name('ads.analytics');
// // Advertisements
// Route::get('/ads', [AdsController::class, 'index'])->name('ads.index');
// Route::get('/ads/create', [AdsController::class, 'create'])->name('ads.create');
// Route::post('/ads', [AdsController::class, 'store'])->name('ads.store');
// Route::get('/ads/{id}', [AdsController::class, 'show'])->name('ads.show');
// Route::get('/ads/{id}/edit', [AdsController::class, 'edit'])->name('ads.edit');
// Route::put('/ads/{id}', [AdsController::class, 'update'])->name('ads.update');
// Route::post('/ads/{id}/toggle-status', [AdsController::class, 'toggleStatus'])->name('ads.toggle-status');
// Route::delete('/ads/{id}', [AdsController::class, 'destroy'])->name('ads.destroy');
// Advertisements Routes
    Route::prefix('ads')->name('ads.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AdsController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AdsController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AdsController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\AdsController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\AdsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Admin\AdsController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\AdsController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [App\Http\Controllers\Admin\AdsController::class, 'toggleStatus'])->name('toggle');
        Route::post('/{id}/toggle-status', [App\Http\Controllers\Admin\AdsController::class, 'toggleStatus'])->name('toggle-status'); // ADD THIS LINE

    });
    
// Outbreak Alerts
// Route::get('/outbreak-alerts', [OutbreakAlertController::class, 'index'])->name('outbreak-alerts.index');
// Route::get('/outbreak-alerts/create', [OutbreakAlertController::class, 'create'])->name('outbreak-alerts.create');
// Route::post('/outbreak-alerts', [OutbreakAlertController::class, 'store'])->name('outbreak-alerts.store');
// Route::get('/outbreak-alerts/{id}', [OutbreakAlertController::class, 'show'])->name('outbreak-alerts.show');
// Route::put('/outbreak-alerts/{id}', [OutbreakAlertController::class, 'update'])->name('outbreak-alerts.update');
// Route::delete('/outbreak-alerts/{id}', [OutbreakAlertController::class, 'destroy'])->name('outbreak-alerts.destroy');
// Route::get('/outbreak-alerts/{id}/notifications', [OutbreakAlertController::class, 'notifications'])->name('outbreak-alerts.notifications');
Route::get('/outbreak-alerts', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'index'])->name('outbreak-alerts.index');
Route::get('/outbreak-alerts/create', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'create'])->name('outbreak-alerts.create');
Route::post('/outbreak-alerts', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'store'])->name('outbreak-alerts.store');
Route::get('/outbreak-alerts/{id}', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'show'])->name('outbreak-alerts.show');
Route::get('/outbreak-alerts/{id}/edit', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'edit'])->name('outbreak-alerts.edit');
Route::put('/outbreak-alerts/{id}', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'update'])->name('outbreak-alerts.update');
Route::delete('/outbreak-alerts/{id}', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'destroy'])->name('outbreak-alerts.destroy');
Route::post('/outbreak-alerts/{id}/toggle', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'toggleStatus'])->name('outbreak-alerts.toggle');
Route::post('/outbreak-alerts/{id}/resend', [App\Http\Controllers\Admin\OutbreakAlertController::class, 'resendNotifications'])->name('outbreak-alerts.resend');
// // Bulk Messages
//     Route::resource('bulk-messages', BulkMessageController::class);
//     Route::post('/bulk-messages/{id}/send', [BulkMessageController::class, 'send'])->name('bulk-messages.send');
//     Route::get('/bulk-messages/{id}/logs', [BulkMessageController::class, 'logs'])->name('bulk-messages.logs');
// Bulk Messages
  // Bulk Messages Routes
    Route::prefix('bulk-messages')->name('bulk-messages.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\BulkMessageController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\BulkMessageController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\BulkMessageController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\BulkMessageController::class, 'show'])->name('show');
        Route::post('/{id}/send', [App\Http\Controllers\Admin\BulkMessageController::class, 'send'])->name('send');
        Route::delete('/{id}', [App\Http\Controllers\Admin\BulkMessageController::class, 'destroy'])->name('destroy');
    });
// Route::get('/bulk-messages', [BulkMessageController::class, 'index'])->name('bulk-messages.index');
// Route::get('/bulk-messages/create', [BulkMessageController::class, 'create'])->name('bulk-messages.create');
// Route::post('/bulk-messages', [BulkMessageController::class, 'store'])->name('bulk-messages.store');
// Route::get('/bulk-messages/{id}', [BulkMessageController::class, 'show'])->name('bulk-messages.show');
// Route::post('/bulk-messages/{id}/send', [BulkMessageController::class, 'send'])->name('bulk-messages.send');
// Route::delete('/bulk-messages/{id}', [BulkMessageController::class, 'destroy'])->name('bulk-messages.destroy');
//     // Farmers
    Route::get('/farmers', [AdminDashboardController::class, 'farmers'])->name('farmers');
    
    // Animal Health Professionals
    Route::get('/professionals/pending', [AdminDashboardController::class, 'pendingProfessionals'])->name('professionals.pending');
    Route::get('/professionals/{id}/review', [AdminDashboardController::class, 'reviewProfessional'])->name('professionals.review');
    Route::post('/professionals/{id}/approve', [AdminDashboardController::class, 'approveProfessional'])->name('professionals.approve');
    Route::post('/professionals/{id}/reject', [AdminDashboardController::class, 'rejectProfessional'])->name('professionals.reject');
    Route::get('/professionals', [AdminDashboardController::class, 'professionals'])->name('professionals.index');
    
    // Farm Records
    Route::get('/farm-records', [AdminDashboardController::class, 'farmRecords'])->name('farm-records.index');
    Route::get('/farm-records/pending', [AdminDashboardController::class, 'pendingFarmRecords'])->name('farm-records.pending');
    Route::get('/farm-records/{id}', [AdminDashboardController::class, 'showFarmRecord'])->name('farm-records.show');
    Route::post('/farm-records/{id}/approve', [AdminDashboardController::class, 'approveFarmRecord'])->name('farm-records.approve');
    Route::post('/farm-records/{id}/reject', [AdminDashboardController::class, 'rejectFarmRecord'])->name('farm-records.reject');
    
    // Users
Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');    
    // Volunteers
    Route::get('/volunteers', [AdminDashboardController::class, 'volunteers'])->name('volunteers.index');
    Route::get('/volunteers/{id}', [AdminDashboardController::class, 'showVolunteer'])->name('volunteers.show');
    //Route::post('/volunteers/{id}/deactivate', [AdminDashboardController::class, 'deactivateVolunteer'])->name('volunteers.deactivate');
   Route::post('/volunteers/{id}/activate', [AdminDashboardController::class, 'activateVolunteer'])->name('volunteers.activate');
    Route::post('/volunteers/{id}/deactivate', [AdminDashboardController::class, 'deactivateVolunteer'])->name('volunteers.deactivate');
    Route::get('/volunteers/{id}/referrals', [VolunteerController::class, 'referrals'])->name('volunteers.referrals');
    // Service Requests
// Service Requests
    Route::get('/service-requests', [AdminDashboardController::class, 'serviceRequests'])->name('service-requests.index');
    Route::get('/service-requests/{id}', [AdminDashboardController::class, 'showServiceRequest'])->name('service-requests.show');
    Route::post('/service-requests/{id}/assign', [AdminDashboardController::class, 'assignServiceRequest'])->name('service-requests.assign');
    Route::post('/service-requests/{id}/update-status', [AdminDashboardController::class, 'updateServiceRequestStatus'])->name('service-requests.update-status');    
    // Analytics & Statistics
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/statistics', [AdminDashboardController::class, 'statistics'])->name('statistics');
//ai training
Route::get('/settings/ai-training', [SettingsController::class, 'aiTraining'])->name('settings.ai-training');
Route::post('/settings/ai-training', [SettingsController::class, 'storeAiTraining'])->name('settings.ai-training.store');
Route::post('/settings/ai-training/{id}/toggle', [SettingsController::class, 'toggleAiTraining'])->name('settings.ai-training.toggle');
Route::delete('/settings/ai-training/{id}', [SettingsController::class, 'destroyAiTraining'])->name('settings.ai-training.destroy');
    // Site Builder
    Route::get('/site-builder', function() {
        return view('admin.site-builder.index');
    })->name('site-builder.index');

    // System Updates & Version Management
    Route::prefix('system-updates')->name('system-updates.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\SystemUpdateController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\SystemUpdateController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\SystemUpdateController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\SystemUpdateController::class, 'show'])->name('show');
        Route::post('/{id}/apply', [App\Http\Controllers\Admin\SystemUpdateController::class, 'apply'])->name('apply');
        Route::delete('/{id}', [App\Http\Controllers\Admin\SystemUpdateController::class, 'destroy'])->name('destroy');
    });

    // System Health & Diagnostics
    Route::prefix('health-check')->name('health-check.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\HealthCheckController::class, 'index'])->name('index');
        Route::post('/diagnostic', [App\Http\Controllers\Admin\HealthCheckController::class, 'runDiagnostic'])->name('diagnostic');
    });

// Import/Export/Backup Routes
    Route::get('/import-export', [App\Http\Controllers\Admin\ImportExportController::class, 'index'])->name('import-export.index');
    
    // Export Routes
    Route::get('/export/users', [App\Http\Controllers\Admin\ImportExportController::class, 'exportUsers'])->name('export.users');
    Route::get('/export/farm-records', [App\Http\Controllers\Admin\ImportExportController::class, 'exportFarmRecords'])->name('export.farm-records');
    Route::get('/export/livestock', [App\Http\Controllers\Admin\ImportExportController::class, 'exportLivestock'])->name('export.livestock');
    Route::get('/export/service-requests', [App\Http\Controllers\Admin\ImportExportController::class, 'exportServiceRequests'])->name('export.service-requests');
    
    // Backup Routes
    Route::post('/backup/create', [App\Http\Controllers\Admin\ImportExportController::class, 'createBackup'])->name('backup.create');
    Route::get('/backup/download/{filename}', [App\Http\Controllers\Admin\ImportExportController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('/backup/delete/{filename}', [App\Http\Controllers\Admin\ImportExportController::class, 'deleteBackup'])->name('backup.delete');
    
    // Import/Restore Routes
    Route::post('/import/backup', [App\Http\Controllers\Admin\ImportExportController::class, 'importBackup'])->name('import.backup');
    Route::post('/backup/restore/{filename}', [App\Http\Controllers\Admin\ImportExportController::class, 'restoreBackup'])->name('backup.restore');
    // BULK USER IMPORT ROUTES (NEW SYSTEM)
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserImportController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\UserImportController::class, 'create'])->name('create');
        Route::post('/upload', [App\Http\Controllers\Admin\UserImportController::class, 'upload'])->name('upload');
        Route::post('/{id}/process', [App\Http\Controllers\Admin\UserImportController::class, 'process'])->name('process');
        Route::get('/{id}', [App\Http\Controllers\Admin\UserImportController::class, 'show'])->name('show');
        Route::post('/resend-email/{importedUserId}', [App\Http\Controllers\Admin\UserImportController::class, 'resendEmail'])->name('resend-email');
        Route::post('/{id}/resend-batch', [App\Http\Controllers\Admin\UserImportController::class, 'resendBatchEmails'])->name('resend-batch');
        Route::delete('/{id}', [App\Http\Controllers\Admin\UserImportController::class, 'destroy'])->name('destroy');
        Route::get('/template/{type}', [App\Http\Controllers\Admin\UserImportController::class, 'downloadTemplate'])->name('template');
    });
    
    // Professional Approval Routes
    Route::prefix('professionals')->name('professionals.')->group(function () {
        Route::get('/approvals', [App\Http\Controllers\Admin\ProfessionalApprovalController::class, 'index'])->name('approvals.index');
        Route::get('/approvals/{id}', [App\Http\Controllers\Admin\ProfessionalApprovalController::class, 'show'])->name('approvals.show');
        Route::post('/approvals/{id}/approve', [App\Http\Controllers\Admin\ProfessionalApprovalController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{id}/reject', [App\Http\Controllers\Admin\ProfessionalApprovalController::class, 'reject'])->name('approvals.reject');
        Route::post('/approvals/bulk-approve', [App\Http\Controllers\Admin\ProfessionalApprovalController::class, 'bulkApprove'])->name('approvals.bulk-approve');
    });
    
});




/*
|--------------------------------------------------------------------------
| Farmer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Farmer\DashboardController::class, 'index'])->name('dashboard');
    // Herd Groups - CRUD
    Route::get('/herd-groups', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'index'])->name('herd-groups.index');
    Route::get('/herd-groups/create', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'create'])->name('herd-groups.create');
    Route::post('/herd-groups', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'store'])->name('herd-groups.store');
    Route::get('/herd-groups/{id}', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'show'])->name('herd-groups.show');
    Route::get('/herd-groups/{id}/edit', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'edit'])->name('herd-groups.edit');
    Route::put('/herd-groups/{id}', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'update'])->name('herd-groups.update');
    Route::delete('/herd-groups/{id}', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'destroy'])->name('herd-groups.destroy');
    
    // Herd Groups - Additional Actions
    Route::post('/herd-groups/{id}/add-livestock', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'addLivestock'])->name('herd-groups.add-livestock');
    Route::delete('/herd-groups/{id}/remove-livestock', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'removeLivestock'])->name('herd-groups.remove-livestock');
    Route::put('/herd-groups/{id}/toggle-status', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'toggleStatus'])->name('herd-groups.toggle-status');
    Route::get('/herd-groups/{id}/statistics', [\App\Http\Controllers\Farmer\HerdGroupController::class, 'statistics'])->name('herd-groups.statistics');
    
    // Farm Records - 3 Step Form
    Route::get('/farm-records', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'index'])->name('farm-records.index');
    Route::get('/farm-records/create/step1', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step1'])->name('farm-records.step1');
    Route::post('/farm-records/create/step1', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep1'])->name('farm-records.step1.post');
    Route::get('/farm-records/create/step2', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step2'])->name('farm-records.step2');
    Route::post('/farm-records/create/step2', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep2'])->name('farm-records.step2.post');
    Route::get('/farm-records/create/step3', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step3'])->name('farm-records.step3');
    Route::post('/farm-records/create/step3', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep3'])->name('farm-records.step3.post');
    Route::get('/farm-records/{id}', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'show'])->name('farm-records.show');
    
    // Livestock
Route::resource('livestock', \App\Http\Controllers\Individual\LivestockController::class);    
    // Service Requests
    Route::resource('service-requests', \App\Http\Controllers\Farmer\ServiceRequestController::class);
    
    // Vaccinations
    Route::resource('vaccinations', \App\Http\Controllers\Farmer\VaccinationController::class);
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Farmer\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Farmer\ProfileController::class, 'update'])->name('profile.update');
});

// /*
// |--------------------------------------------------------------------------
// | Individual Routes (uses same controllers as Farmer)
// |--------------------------------------------------------------------------
// */
// Route::middleware(['auth', 'role:individual'])->prefix('individual')->name('individual.')->group(function () {
    
//     // Dashboard
//     Route::get('/dashboard', [\App\Http\Controllers\Individual\DashboardController::class, 'index'])->name('dashboard');
//     Route::resource('livestock', \App\Http\Controllers\Farmer\LivestockController::class);

//     // Farm Records - Same as Farmer
//     Route::get('/farm-records', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'index'])->name('farm-records.index');
//     Route::get('/farm-records/create/step1', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step1'])->name('farm-records.step1');
//     Route::post('/farm-records/create/step1', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep1'])->name('farm-records.step1.post');
//     Route::get('/farm-records/create/step2', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step2'])->name('farm-records.step2');
//     Route::post('/farm-records/create/step2', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep2'])->name('farm-records.step2.post');
//     Route::get('/farm-records/create/step3', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step3'])->name('farm-records.step3');
//     Route::post('/farm-records/create/step3', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep3'])->name('farm-records.step3.post');
//     Route::get('/farm-records/{id}', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'show'])->name('farm-records.show');
    
//     // Service Requests
//     Route::resource('service-requests', \App\Http\Controllers\Individual\ServiceRequestController::class);
    
//     // Profile
//     Route::get('/profile', [\App\Http\Controllers\Individual\ProfileController::class, 'index'])->name('profile');
//     Route::put('/profile', [\App\Http\Controllers\Individual\ProfileController::class, 'update'])->name('profile.update');
// });
/*
/*
|--------------------------------------------------------------------------
| Individual Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:individual'])->prefix('individual')->name('individual.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Individual\DashboardController::class, 'index'])->name('dashboard');
    
    // Livestock - MUST use Individual Controller
    Route::get('/livestock', [\App\Http\Controllers\Individual\LivestockController::class, 'index'])->name('livestock.index');
    Route::get('/livestock/create', [\App\Http\Controllers\Individual\LivestockController::class, 'create'])->name('livestock.create');
    Route::post('/livestock', [\App\Http\Controllers\Individual\LivestockController::class, 'store'])->name('livestock.store');
    Route::get('/livestock/{id}', [\App\Http\Controllers\Individual\LivestockController::class, 'show'])->name('livestock.show');
    Route::get('/livestock/{id}/edit', [\App\Http\Controllers\Individual\LivestockController::class, 'edit'])->name('livestock.edit');
    Route::put('/livestock/{id}', [\App\Http\Controllers\Individual\LivestockController::class, 'update'])->name('livestock.update');
    Route::delete('/livestock/{id}', [\App\Http\Controllers\Individual\LivestockController::class, 'destroy'])->name('livestock.destroy');
    
    // Vaccinations
    Route::get('/vaccinations', [\App\Http\Controllers\Individual\DashboardController::class, 'vaccinations'])->name('vaccinations.index');
    
    // Farm Records
    Route::get('/farm-records', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'index'])->name('farm-records.index');
    Route::get('/farm-records/create/step1', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step1'])->name('farm-records.step1');
    Route::post('/farm-records/create/step1', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep1'])->name('farm-records.step1.post');
    Route::get('/farm-records/create/step2', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step2'])->name('farm-records.step2');
    Route::post('/farm-records/create/step2', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep2'])->name('farm-records.step2.post');
    Route::get('/farm-records/create/step3', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'step3'])->name('farm-records.step3');
    Route::post('/farm-records/create/step3', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'postStep3'])->name('farm-records.step3.post');
    Route::get('/farm-records/{id}', [\App\Http\Controllers\Farmer\FarmRecordController::class, 'show'])->name('farm-records.show');
    
    // Service Requests
    Route::get('/service-requests', [\App\Http\Controllers\Individual\ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::get('/service-requests/create', [\App\Http\Controllers\Individual\ServiceRequestController::class, 'create'])->name('service-requests.create');
    Route::post('/service-requests', [\App\Http\Controllers\Individual\ServiceRequestController::class, 'store'])->name('service-requests.store');
    Route::get('/service-requests/{id}', [\App\Http\Controllers\Individual\ServiceRequestController::class, 'show'])->name('service-requests.show');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Individual\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Individual\ProfileController::class, 'update'])->name('profile.update');
});
/*
|--------------------------------------------------------------------------
| Animal Health Professional Routes
|--------------------------------------------------------------------------
*/

// Route::middleware(['auth', 'role:animal_health_professional'])->prefix('professional')->name('professional.')->group(function () {
//     Route::get('/dashboard', [ProfessionalDashboardController::class, 'index'])->name('dashboard');
//     Route::get('/profile', [ProfessionalDashboardController::class, 'profile'])->name('profile');
    
//     // Pending approval message
//     Route::get('/pending-approval', function () {
//         return view('professional.pending-approval');
//     })->name('pending-approval');
    
//     // Farm Records (for approved professionals)
//     Route::middleware(['approved.professional'])->group(function () {
//         Route::get('/farm-records', [ProfessionalDashboardController::class, 'farmRecords'])->name('farm-records.index');
//         Route::get('/farm-records/create', [ProfessionalDashboardController::class, 'createFarmRecord'])->name('farm-records.create');
//         Route::post('/farm-records', [ProfessionalDashboardController::class, 'storeFarmRecord'])->name('farm-records.store');
        
//         // Service Requests
//         Route::get('/service-requests', [ProfessionalDashboardController::class, 'serviceRequests'])->name('service-requests.index');
//         Route::get('/service-requests/{id}', [ProfessionalDashboardController::class, 'showServiceRequest'])->name('service-requests.show');
//     });
// });
/*
/*
/*
/*
|--------------------------------------------------------------------------
| Professional Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:animal_health_professional'])->prefix('professional')->name('professional.')->group(function () {
    
    // Dashboard - accessible even when pending
    Route::get('/dashboard', [\App\Http\Controllers\Professional\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pending-approval', function () {
        return view('professional.pending-approval');
    })->name('pending-approval');
    // Profile - accessible even when pending
   // In professional routes, update these lines:
Route::get('/profile', [\App\Http\Controllers\Professional\ProfileController::class, 'index'])->name('profile');
Route::put('/profile', [\App\Http\Controllers\Professional\ProfileController::class, 'update'])->name('profile.update');
    // Farm Records (placeholder)
    Route::get('/farm-records', function() {
        return view('professional.farm-records.index', [
            'farmRecords' => collect()
        ]);
    })->name('farm-records.index');
    
    // Service Requests - ONLY for approved professionals
    Route::get('/service-requests', function() {
        $profile = \App\Models\AnimalHealthProfessional::where('user_id', auth()->id())->first();
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved before accessing service requests.');
        }
        return app(\App\Http\Controllers\Professional\ServiceRequestController::class)->index();
    })->name('service-requests.index');
    
    Route::get('/service-requests/{id}', function($id) {
        $profile = \App\Models\AnimalHealthProfessional::where('user_id', auth()->id())->first();
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved before accessing service requests.');
        }
        return app(\App\Http\Controllers\Professional\ServiceRequestController::class)->show($id);
    })->name('service-requests.show');
    
    Route::post('/service-requests/{id}/accept', function($id) {
        $profile = \App\Models\AnimalHealthProfessional::where('user_id', auth()->id())->first();
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved before accepting requests.');
        }
        return app(\App\Http\Controllers\Professional\ServiceRequestController::class)->accept($id);
    })->name('service-requests.accept');
    
    // ✅ FIX: Complete route - properly pass Request object
    Route::post('/service-requests/{id}/complete', function(\Illuminate\Http\Request $request, $id) {
        $profile = \App\Models\AnimalHealthProfessional::where('user_id', auth()->id())->first();
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved before completing requests.');
        }
        return app(\App\Http\Controllers\Professional\ServiceRequestController::class)->complete($request, $id);
    })->name('service-requests.complete');
    
    // ✅ ADD: Cancel route
    Route::post('/service-requests/{id}/cancel', function(\Illuminate\Http\Request $request, $id) {
        $profile = \App\Models\AnimalHealthProfessional::where('user_id', auth()->id())->first();
        if (!$profile || $profile->approval_status !== 'approved') {
            return redirect()->route('professional.dashboard')
                ->with('error', 'Your account must be approved.');
        }
        return app(\App\Http\Controllers\Professional\ServiceRequestController::class)->cancel($request, $id);
    })->name('service-requests.cancel');
    Route::get('/farm-records/{id}', function($id) {
    $record = \App\Models\FarmRecord::with('user')->findOrFail($id);
    return view('professional.farm-records.show', compact('record'));
})->name('farm-records.show');
});
/*
|--------------------------------------------------------------------------
| Volunteer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:volunteer'])->prefix('volunteer')->name('volunteer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Volunteer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/enroll-farmer', [\App\Http\Controllers\Volunteer\DashboardController::class, 'showEnrollForm'])->name('enroll.farmer');
    Route::post('/enroll-farmer', [\App\Http\Controllers\Volunteer\DashboardController::class, 'enrollFarmer']);
    Route::get('/my-farmers', [\App\Http\Controllers\Volunteer\DashboardController::class, 'myFarmers'])->name('my-farmers');
    Route::get('/activity', [\App\Http\Controllers\Volunteer\DashboardController::class, 'activity'])->name('activity');
    Route::get('/profile', [\App\Http\Controllers\Volunteer\DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Volunteer\ProfileController::class, 'update'])->name('profile.update');
});
// Route::middleware(['auth', 'role:volunteer'])->prefix('volunteer')->name('volunteer.')->group(function () {
//     Route::get('/dashboard', [VolunteerDashboardController::class, 'index'])->name('dashboard');
//     Route::get('/profile', [VolunteerDashboardController::class, 'profile'])->name('profile');
    
//     // Enroll Farmers
//     Route::get('/enroll-farmer', [VolunteerDashboardController::class, 'showEnrollForm'])->name('enroll.farmer');
//     Route::post('/enroll-farmer', [VolunteerDashboardController::class, 'enrollFarmer'])->name('enroll.farmer.submit');
    
//     // View enrolled farmers
//     Route::get('/my-farmers', [VolunteerDashboardController::class, 'myFarmers'])->name('my-farmers');
//     Route::get('/my-farmers/{id}', [VolunteerDashboardController::class, 'showFarmer'])->name('my-farmers.show');
    
//     // Activity
//     Route::get('/activity', [VolunteerDashboardController::class, 'activity'])->name('activity');
// });

/*
|--------------------------------------------------------------------------
| LEGACY ROUTE REDIRECTS
|--------------------------------------------------------------------------
*/

// Registration redirects
Route::get('/register/individual', fn() => redirect()->route('register.farmer'))->name('register.individual');
Route::get('/register/data-collector', fn() => redirect()->route('register.professional'))->name('register.data-collector');

// Vaccination routes (redirect to farmer vaccinations)
Route::redirect('/individual/vaccinations', '/farmer/vaccinations')->name('individual.vaccinations.index');
Route::redirect('/individual/vaccinations/create', '/farmer/vaccinations')->name('individual.vaccinations.create');
Route::redirect('/individual/vaccinations/{id}', '/farmer/vaccinations')->name('individual.vaccinations.show');
Route::redirect('/individual/vaccinations/{id}/edit', '/farmer/vaccinations')->name('individual.vaccinations.edit');
// Farm Records routes
Route::redirect('/individual/farm-records', '/farmer/farm-records/step1')->name('individual.farm-records.index');
Route::redirect('/individual/farm-records/create', '/farmer/farm-records/step1')->name('individual.farm-records.create');
Route::redirect('/individual/farm-records/{id}', '/farmer/farm-records/{id}')->name('individual.farm-records.show');
Route::redirect('/individual/farm-records/{id}/edit', '/farmer/farm-records/{id}/edit')->name('individual.farm-records.edit');

// Service Request routes
Route::redirect('/individual/service-requests', '/farmer/service-requests')->name('individual.service-requests.index');
Route::redirect('/individual/service-requests/create', '/farmer/service-requests/create')->name('individual.service-requests.create');
Route::redirect('/individual/service-requests/{id}', '/farmer/service-requests/{id}')->name('individual.service-requests.show');

// Livestock routes
Route::redirect('/individual/livestock', '/farmer/livestock')->name('individual.livestock.index');
Route::redirect('/individual/livestock/create', '/farmer/livestock/create')->name('individual.livestock.create');
Route::redirect('/individual/livestock/{id}', '/farmer/livestock/{id}')->name('individual.livestock.show');
Route::redirect('/individual/livestock/{id}/edit', '/farmer/livestock/{id}/edit')->name('individual.livestock.edit');

// Dashboard route
Route::redirect('/individual/dashboard', '/farmer/dashboard')->name('individual.dashboard');

// Data Collector (Professional) routes
Route::redirect('/data-collector/dashboard', '/professional/dashboard')->name('data-collector.dashboard');
Route::redirect('/data-collector/farm-records', '/professional/farm-records')->name('data-collector.farm-records.index');
Route::redirect('/data-collector/service-requests', '/professional/service-requests')->name('data-collector.service-requests.index');