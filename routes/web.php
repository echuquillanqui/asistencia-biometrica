<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\DeviceLogController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::resource('branches', BranchController::class)->middleware('permission:manage_branches');
    Route::resource('devices', DeviceController::class)->middleware('permission:manage_devices');
    Route::resource('employees', EmployeeController::class)->middleware('permission:manage_employees');
    Route::resource('attendances', AttendanceController::class)->middleware('permission:view_attendance');
    Route::resource('device-logs', DeviceLogController::class)->middleware('permission:view_reports');
    Route::resource('roles', RoleController::class)->middleware('permission:manage_users');
    Route::resource('permissions', PermissionController::class)->middleware('permission:manage_users');
});
