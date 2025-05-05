<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MonitoringController;

Route::post('/monitoring', [MonitoringController::class, 'store']);
Route::post('/device/ping', [DeviceController::class, 'ping']);  // Handle ping
Route::post('/device/off', [DeviceController::class, 'shutdown']); // Handle shutdown
