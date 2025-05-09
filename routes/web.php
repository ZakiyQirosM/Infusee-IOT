<?php

use App\Http\Controllers\InfuseeController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\AuthPegawai;

Route::middleware(['auth:pegawai'])->group(function () {
    // Route untuk menampilkan form registrasi
    Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
    Route::get('/register/search', [RegisterController::class, 'search'])->name('register.search');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    
    // Route untuk halaman pemilihan device
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::post('/devices/select', [DeviceController::class, 'select'])->name('devices.select');
    Route::post('/devices/assign', [DeviceController::class, 'assign'])->name('devices.assign');
    Route::get('/devices/status/{deviceId}', [DeviceController::class, 'status']);

    Route::post('/infusee/end-session/{id_session}', [InfuseeController::class, 'endSession'])->name('infusee.endSession');
    Route::get('/infusee/{infusee}', [InfuseeController::class, 'show']);
});

Route::get('/infusee', [InfuseeController::class, 'index'])->name('infusee.index');

Route::get('/logout', function () {
    Session::flush(); // Hapus semua data session
    return redirect('/')->with('success', 'Berhasil logout');
})->name('logout');

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/', function () {
    return view('landingpage.index');
})->name('landing');






