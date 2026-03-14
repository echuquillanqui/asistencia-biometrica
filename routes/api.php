<?php

use App\Http\Controllers\Api\DeviceController;
use Illuminate\Support\Facades\Route;

Route::prefix('device')->middleware('device.key')->group(function () {
    Route::post('/sync-logs', [DeviceController::class, 'syncLogs']);
    Route::post('/register-log', [DeviceController::class, 'registerLog']);
    Route::get('/employees', [DeviceController::class, 'employees']);
    Route::post('/heartbeat', [DeviceController::class, 'heartbeat']);
});
