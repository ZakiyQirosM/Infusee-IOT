<?php

use App\Http\Controllers\InfuseeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RegisterController;

Route::get('/', [RegisterController::class, 'index'])->name('register.index');

Route::get('/infusee', [InfuseeController::class, 'index'])->name('infusee.index');
Route::post('/infusee/end-session/{id_session}', [InfuseeController::class, 'endSession'])->name('infusee.endSession');

// Route untuk menampilkan form registrasi
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::get('/register/search', [RegisterController::class, 'search'])->name('register.search');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Route untuk halaman pemilihan device
Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
Route::post('/devices/select', [DeviceController::class, 'select'])->name('devices.select');
Route::post('/devices/assign', [DeviceController::class, 'assign'])->name('devices.assign');
Route::get('/devices/status/{deviceId}', [DeviceController::class, 'status']);

Route::get('/infusee/{infusee}', [InfuseeController::class, 'show']);



