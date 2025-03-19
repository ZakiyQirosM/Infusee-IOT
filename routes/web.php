<?php

use App\Http\Controllers\InfuseeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

// Route utama
Route::get('/', [RegisterController::class, 'index'])->name('register.index');

// Route untuk Infusee
Route::get('/infusee', [InfuseeController::class, 'index'])->name('infusee.index');

// Route untuk register
Route::prefix('register')->group(function () {
    Route::get('/', [RegisterController::class, 'index'])->name('register.index');
    Route::post('/', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/search', [RegisterController::class, 'search'])->name('register.search');
});

// Route untuk perangkat (devices)
Route::prefix('devices')->group(function () {
    Route::get('/', [DeviceController::class, 'index'])->name('devices.index');
    Route::post('/', [DeviceController::class, 'store'])->name('devices.store');
});
