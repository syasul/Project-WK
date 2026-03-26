<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthEmployeeController;
use App\Http\Controllers\api\AttendanceEmployeeController;

// 1. Route Public (Login)
Route::post('/login', [AuthEmployeeController::class, 'login']); 

// 2. Route Private (Harus bawa Token / Sudah Login di HP)
Route::middleware('auth:sanctum')->group(function () {

    // Cek User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/attendance/clock-in', [AttendanceEmployeeController::class, 'clockIn']);
    Route::post('/attendance/clock-out', [AttendanceEmployeeController::class, 'clockOut']);
    Route::get('/attendance/history', [AttendanceEmployeeController::class, 'history']);
    Route::post('/leave', [App\Http\Controllers\Api\AttendanceEmployeeController::class, 'applyLeave']);
    Route::get('/leave/history', [App\Http\Controllers\Api\AttendanceEmployeeController::class, 'leaveHistory']);
    Route::get('/leave/stats', [App\Http\Controllers\Api\AttendanceEmployeeController::class, 'leaveStats']);
    Route::post('/logout', [AuthEmployeeController::class, 'logout']);
});