<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\IdCardController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;


// Home endpoint
Route::get('/', function () {
    return response()->json([
        'message' => 'API is working!',
        'endpoints' => [
            '/tasks' => url('/api/tasks'),
            '/data' => url('/api/data'),
        ]
    ]);
});

// Data operations
Route::get('/data', [DataController::class, 'index']);

// ID card upload
Route::post('/idcard', [IdCardController::class, 'store']);

// Login
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Get authenticated user info
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Get user profile
    Route::get('/profile', function (Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    });

    Route::post('/id-card', [UserController::class, 'storeIdCard']);

    Route::get('/user/documents', [UserController::class, 'getUserDocuments']);
    Route::post('/user/upload-pdfs', [UserController::class, 'uploadPdfs']);

    Route::post('/tasks', [UserController::class, 'createTask']);
    Route::get('/tasks/my', [UserController::class, 'myTasks']);
    Route::post('/tasks/{id}/complete', [UserController::class, 'completeTask']);
    Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'allUsers']);

        Route::post('/work/start', [UserController::class, 'startWork']);
        Route::post('/work/end', [UserController::class, 'endWork']);
        Route::get('/work/times', [UserController::class, 'getWorkTimes']);





    // Get current user details
    Route::get('/me', [UserController::class, 'me']);



});




