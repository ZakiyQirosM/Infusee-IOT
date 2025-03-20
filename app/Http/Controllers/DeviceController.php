<?php

namespace App\Http\Controllers;

use App\Models\DosisInfusPasien;
use App\Models\PerangkatInfusee;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // Menampilkan daftar perangkat aktif
    public function index()
    {
        // Ambil data perangkat termasuk field status
        $devices = PerangkatInfusee::select('id_perangkat_infusee', 'alamat_ip_infusee')->get();

        $activeDevices = $devices ? $devices->filter(function ($device) {
            return $device->status == 0;
        }) : collect([]);

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

        // Simpan data ke database jika perlu
        // Contoh:
        // PerangkatInfusee::create($data);

        // Redirect ke halaman daftar perangkat aktif
        return redirect()->route('devices.index')->with('success', 'Alat berhasil dicari.');
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required|exists:table_perangkat_infusee,id_perangkat_infusee',
        ]);

    DosisInfusPasien::create([
            'no_reg_pasien' => session('no_reg_pasien'),
            'id_perangkat_infusee' => $data['device_id'],
            'no_pegawai' => session('no_pegawai'),
            'dosis_infus' => '100ml',
            'laju_tetes_tpm' => 20,
            'persentase_infus_menit' => 80,
            'status_anomali_infus' => 'Normal',
            'timestamp_infus' => now(),
        ]);

        session()->forget([
            'no_reg_pasien',
            'nama_pasien',
            'umur',
            'no_ruangan',
            'durasi',
        ]);

        return redirect()->route('register.index')->with('success', 'Data infus berhasil disimpan!');
    }

}

