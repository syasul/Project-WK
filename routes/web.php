<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\auth\AuthAdminController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\EmployeeController;
use App\Http\Controllers\admin\ProjectController;
use App\Http\Controllers\admin\ShiftController;
use App\Http\Controllers\admin\HolidayController;
use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\LeaveController;
use App\Http\Controllers\admin\LocationsController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

use App\Models\User;

// Redirect Halaman Utama ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes (Hanya untuk yang BELUM Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthAdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthAdminController::class, 'login']);
});


//  Authenticated Routes (Hanya untuk Admin yang SUDAH Login)
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthAdminController::class, 'logout'])->name('logout');

    // Group Admin (Prefix URL: /admin/...)
    

});


Route::prefix('admin')->name('admin.')->group(function () {
       Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // employee
        Route::delete('employees/bulk-delete', [EmployeeController::class, 'bulkDestroy'])->name('employees.bulkDestroy');
        Route::resource('employees', EmployeeController::class);
        Route::patch('/employees/{user_id}/verify', [EmployeeController::class, 'verify'])->name('employees.verify');
        Route::patch('/employees/{user_id}/shift', [EmployeeController::class, 'updateShift'])->name('employees.updateShift');
        Route::patch('/employees/{user_id}/promote', [EmployeeController::class, 'promoteToLeader'])->name('employees.promote');

        // project
        Route::delete('projects/bulk-delete', [ProjectController::class, 'bulkDestroy'])->name('projects.bulkDestroy');
        Route::resource('projects', ProjectController::class)->except(['show']);

        // shift
        Route::delete('shifts/bulk-delete', [ShiftController::class, 'bulkDestroy'])->name('shifts.bulkDestroy');
        Route::resource('shifts', ShiftController::class)->except(['show']);

        // holidays
        Route::delete('holidays/bulk-delete', [HolidayController::class, 'bulkDestroy'])->name('holidays.bulkDestroy');
        Route::resource('holidays', HolidayController::class)->except(['show']);

        // location
        Route::delete('locations/bulk-delete', [LocationsController::class, 'bulkDestroy'])->name('locations.bulkDestroy');
        Route::resource('locations', LocationsController::class)->except(['show']);

        // attendance
        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('/attendances/map', [AttendanceController::class, 'map'])->name('attendances.map');

        // leave
        Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
        Route::post('/leaves', [LeaveController::class, 'store'])->name('leaves.store');
        Route::patch('/leaves/{id}/approve', [LeaveController::class, 'approveLeave'])->name('leaves.approve');
        Route::patch('/leaves/{id}/reject', [LeaveController::class, 'rejectLeave'])->name('leaves.reject');

    });
