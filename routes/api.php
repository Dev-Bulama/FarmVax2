<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Nnjeim\World\World;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Location API Endpoints using nnjeim/world package
// Route::get('/countries', function (Request $request) {
//     $world = new World();
//     $action = $world->countries();

//     if ($action->success) {
//         return response()->json($action->data);
//     }

//     return response()->json(['error' => 'Unable to fetch countries'], 500);
// });

// Route::get('/states/{country}', function (Request $request, $country) {
//     $world = new World();

//     // Try to get states by country ID or code
//     $action = $world->states([
//         'filters' => [
//             'country_id' => is_numeric($country) ? $country : null,
//             'country_code' => !is_numeric($country) ? $country : null,
//         ]
//     ]);

//     if ($action->success) {
//         return response()->json($action->data);
//     }

//     return response()->json([]);
// });

// Route::get('/cities/{state}', function (Request $request, $state) {
//     $world = new World();

//     // Get cities by state ID or code
//     $action = $world->cities([
//         'filters' => [
//             'state_id' => is_numeric($state) ? $state : null,
//             'state_code' => !is_numeric($state) ? $state : null,
//         ]
//     ]);

//     if ($action->success) {
//         return response()->json($action->data);
//     }

//     return response()->json([]);
// });

// // Alias for LGAs (Local Government Areas) - same as cities
// Route::get('/lgas/{state}', function (Request $request, $state) {
//     $world = new World();

//     // Get cities/LGAs by state ID or code
//     $action = $world->cities([
//         'filters' => [
//             'state_id' => is_numeric($state) ? $state : null,
//             'state_code' => !is_numeric($state) ? $state : null,
//         ]
//     ]);

//     if ($action->success) {
//         return response()->json($action->data);
//     }

//     return response()->json([]);
// });

// // AI Chat endpoint
// Route::post('/ai/chat', [\App\Http\Controllers\Api\AiChatController::class, 'chat']);
