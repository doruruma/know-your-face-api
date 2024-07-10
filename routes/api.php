<?php

use App\Http\Controllers\HolidayController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\RemoteScheduleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\WorkHourController;
use App\Http\Controllers\WorkstateController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to Know My Face API'
    ]);
});

// auth
Route::post('login', [UserController::class, 'login']);
Route::post('refresh-token', [UserController::class, 'refreshtoken']);

Route::middleware('auth:api')->group(function () {
    // holiday
    Route::get('holidays', [HolidayController::class, 'getAll']);
    Route::get('holidays/{year}', [HolidayController::class, 'getPerYear']);
    Route::get('holiday/{id}', [HolidayController::class, 'getById']);
    Route::post('holiday', [HolidayController::class, 'store']);
    Route::put('holiday/{id}', [HolidayController::class, 'update']);
    Route::delete('holiday/{id}', [HolidayController::class, 'delete']);
    // leaves
    Route::get('leaves', [LeaveController::class, 'getAll']);
    Route::get('leave/get-today-requested-count', [LeaveController::class, 'getTodayRequestedCount']);
    Route::get('leave/get-today-approved-sick-count', [LeaveController::class, 'getTodayApprovedSickCount']);    
    Route::get('leave/get-today-approved-leave-count', [LeaveController::class, 'getTodayApprovedLeaveCount']);
    Route::get('leave/{id}/requested', [LeaveController::class, 'getRequestedById']);
    Route::get('leave/{id}', [LeaveController::class, 'getById']);
    Route::post('leave', [LeaveController::class, 'store']);
    Route::put('leave/{id}', [LeaveController::class, 'update']);
    Route::put('leave/approve/{id}', [LeaveController::class, 'approve']);
    Route::put('leave/cancel/{id}', [LeaveController::class, 'cancel']);
    // leave-types
    Route::get('leave-types', [LeaveTypeController::class, 'getAll']);
    // presences
    Route::get('presences', [PresenceController::class, 'getAll']);
    Route::get('presence/get-today-count', [PresenceController::class, 'getTodayCount']);
    Route::get('presence/check-status', [PresenceController::class, 'checkStatus']);
    Route::get('presence/{id}', [PresenceController::class, 'getById']);    
    Route::post('presence/clock-in', [PresenceController::class, 'clockIn']);
    Route::post('presence/clock-out', [PresenceController::class, 'clockOut']);
    // positions
    Route::get('positions/get-staff', [PositionController::class, 'getStaff']);
    Route::get('positions/get-staff-no-paging', [PositionController::class, 'getStaffNoPaging']);
    // remote-schedules
    Route::get('remote-schedules', [RemoteScheduleController::class, 'getAll']);
    Route::get('remote-schedules/{year}', [RemoteScheduleController::class, 'getPerYear']);
    Route::get('remote-schedule/{id}', [RemoteScheduleController::class, 'getById']);
    Route::post('remote-schedule', [RemoteScheduleController::class, 'store']);
    Route::put('remote-schedule/{id}', [RemoteScheduleController::class, 'update']);
    Route::delete('remote-schedule/{id}', [RemoteScheduleController::class, 'delete']);
    // settings
    Route::get('setting', [SettingController::class, 'get']);
    Route::put('setting', [SettingController::class, 'update']);
    // users
    Route::get('users', [UserController::class, 'getAll']);
    Route::get('users/count', [UserController::class, 'getCount']);
    Route::get('user/current', [UserController::class, 'getCurrentUser']);
    Route::get('user/{id}', [UserController::class, 'getById']);
    Route::post('user', [UserController::class, 'store']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::put('user/update-profile/{id}', [UserController::class, 'updateProfile']);
    Route::put('user/update-password/{id}', [UserController::class, 'updatePassword']);
    Route::delete('user/{id}', [UserController::class, 'delete']);
    // work-hours
    // Route::get('work-hours', [WorkHourController::class, 'getAll']);
    // Route::post('work-hour', [WorkHourController::class, 'store']);
    // Route::put('work-hour/{id}', [WorkHourController::class, 'update']);
    // Route::delete('work-hour/{id}', [WorkHourController::class, 'delete']);
    // workstates
    Route::get('workstates', [WorkstateController::class, 'getAll']);
});
