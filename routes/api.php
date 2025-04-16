<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::post('/device/ping', [DeviceController::class, 'ping']);  // Handle ping
Route::post('/device/off', [DeviceController::class, 'shutdown']); // Handle shutdown
