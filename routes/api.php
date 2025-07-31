<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;


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

    // Get current user details
    Route::get('/me', [UserController::class, 'me']);

});




