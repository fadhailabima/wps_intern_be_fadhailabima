<?php

use App\Http\Controllers\DailyLogController;
use App\Http\Controllers\DirekturController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// login api
Route::post('/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // logout API
    Route::post('/logout', [UserController::class, 'logout']);

    // Get current user API
    Route::get('/user', [UserController::class, 'getCurrentUser']);

    // Manager API
    Route::middleware(['role:Manager'])->group(function () {
        Route::get('/manager/dailylog', [ManagerController::class, 'getDailyLogStaff']);
        Route::put('/manager/dailylog/{id}', [ManagerController::class, 'updateStatusDailyLog']);
    });

    // Direktur API
    Route::middleware(['role:Direktur'])->group(function () {
        Route::get('/direktur/dailylog', [DirekturController::class, 'getDailyLogsByManager']);
    });

    // Add daily log API for Manager and Staff
    Route::middleware('role:Manager,Staff')->group(function () {
        Route::post('/dailylog', [DailyLogController::class, 'addDailyLog']);
        Route::get('/dailylog', [DailyLogController::class, 'getDailyLogByUser']);
    });

    // Update daily log status API for Manager and Direktur
    Route::middleware('role:Manager,Direktur')->group(function () {
        Route::put('/dailylog/{id}', [DailyLogController::class, 'updateStatusDailyLog']);
    });
});

// Get image from storage
Route::get('/storage/app/public/{directory}/{filename}', function ($directory, $filename) {
    $path = storage_path('app/public/' . $directory . '/' . $filename);

    if (!File::exists($path)) {
        return response()->json(['message' => 'File not found.'], 404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});