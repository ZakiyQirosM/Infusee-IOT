<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\InfusionSession;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::select('id_perangkat_infusee', 'alamat_ip_infusee')->get();

        return view('devices.index', compact(
            'devices',
        ));
    }


    // ✅ Menyimpan data ke infusion_sessions
    public function assign(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required|exists:table_perangkat_infusee,id_perangkat_infusee',
        ]);

        InfusionSession::create([
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
