<?php

use App\Http\Controllers\InfuseeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RegisterController;

Route::get('/', [InfuseeController::class, 'index'])->name('infusee.index');

// Route untuk menampilkan daftar perangkat aktif
Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');

// Route untuk menampilkan form registrasi
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Route untuk menyimpan data perangkat (POST)
Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
