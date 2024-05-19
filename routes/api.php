<?php

use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkHourController;
use App\Http\Controllers\WorkstateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Know My Face API'
    ]);
});

Route::middleware('auth:api')->group(function () {
    // leave-types
    Route::get('leave-types', [LeaveTypeController::class, 'getAll']);
    // presences
    Route::get('presences', [PresenceController::class, 'getAll']);
    // positions
    Route::get('positions/get-staff', [PositionController::class, 'getStaff']);
    // users
    Route::get('users', [UserController::class, 'getAll']);
    Route::get('user/{id}', [UserController::class, 'getById']);
    Route::post('user', [UserController::class, 'store']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::put('user/update-profile/{id}', [UserController::class, 'updateProfile']);
    Route::put('user/update-password/{id}', [UserController::class, 'updatePassword']);
    Route::delete('user/{id}', [UserController::class, 'delete']);
    // work-hours
    Route::get('work-hours', [WorkHourController::class, 'getAll']);
    Route::post('work-hour', [WorkHourController::class, 'store']);
    Route::put('work-hour/{id}', [WorkHourController::class, 'update']);
    Route::delete('work-hour/{id}', [WorkHourController::class, 'delete']);
    // workstates
    Route::get('workstates', [WorkstateController::class, 'getAll']);
});
