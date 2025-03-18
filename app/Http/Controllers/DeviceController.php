<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // Menampilkan daftar perangkat aktif
    public function index()
    {
        $devices = [
            [
                'id' => 'DIF001',
                'status' => true,
                'ip' => '192.168.1.101'
            ],
            [
                'id' => 'DIF002',
                'status' => false,
                'ip' => '192.168.1.102'
            ],
            [
                'id' => 'DIF003',
                'status' => true,
                'ip' => '192.168.1.103'
            ],
            [
                'id' => 'DIF004',
                'status' => false,
                'ip' => '192.168.1.104'
            ]
        ];

        // Filter hanya device yang aktif
        $activeDevices = array_filter($devices, function ($device) {
            return $device['status'] === true;
        });

        return view('devices.index', compact('activeDevices'));
    }

    // Proses pengiriman data dari form registrasi
    public function store(Request $request)
    {
        // Validasi data dari form
        $data = $request->validate([
            'no_register' => 'required|string',
            'nama_pasien' => 'required|string',
            'durasi' => 'required|integer|min:0',
        ]);

        // Lakukan sesuatu dengan data (misalnya simpan ke database atau proses lainnya)

        // Redirect ke halaman daftar perangkat aktif
        return redirect()->route('device.index')->with('success', 'Alat berhasil dicari.');
    }
}
