<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\InfusionSession;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // ✅ Menampilkan list device
    public function index()
    {
        $devices = Device::select('id_perangkat_infusee', 'alamat_id_infusee')->get();

        return view('devices.index', compact('devices'));
    }

    // ✅ Assign device ke infusion_sessions
    public function assign(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required|string|exists:devices,id_perangkat_infusee',
        ]);

        // ✅ Ambil data pasien dari session
        $no_reg_pasien = session('no_reg_pasien');
        $nama_pasien = session('nama_pasien');
        $umur = session('umur');
        $no_ruangan = session('no_ruangan');
        $durasi = session('durasi');

        if (!$no_reg_pasien || !$durasi) {
            return response()->json(['error' => 'Data pasien atau durasi tidak tersedia.'], 400);
        }

        // ✅ Simpan data ke database infusion_sessions
        $infusion = InfusionSession::create([
            'no_reg_pasien' => $no_reg_pasien,
            'id_perangkat_infusee' => $data['device_id'],
            'durasi_infus_menit' => $durasi,
            'timestamp_infus' => now(),
        ]);

        // ✅ Bersihkan session setelah sukses
        session()->forget([
            'no_reg_pasien',
            'nama_pasien',
            'umur',
            'no_ruangan',
            'durasi_infus_menit',
        ]);

        // ✅ Redirect ke infusion_sessions setelah sukses
        return redirect()->route('infusee.index')->with('success', 'Device berhasil dipilih dan data disimpan!');
    }
}
