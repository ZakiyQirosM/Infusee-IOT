<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function index()
    {
        return view('register.index');
    }

    // Menangani pengiriman data dari form registrasi
    public function store(Request $request)
    {
        // Validasi data dari form
        $data = $request->validate([
            'no_register' => 'required|string',
            'nama_pasien' => 'required|string',
            'durasi' => 'required|integer|min:0',
        ]);

        // Lakukan sesuatu dengan data (misalnya simpan ke database atau proses lainnya)

        // Redirect ke halaman daftar perangkat aktif dengan pesan sukses
        return redirect()->route('devices.index')->with('success', 'Alat berhasil dicari.');
    }
}
