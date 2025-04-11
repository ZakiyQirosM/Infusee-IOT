<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\InfusionSession;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index');
    }

    public function search(Request $request)
    {
        $noRegister = $request->input('no_reg_pasien');

        if (!$noRegister) {
            return response()->json(['error' => 'Nomor registrasi harus diisi!']);
        }

        $patient = Patient::where('no_reg_pasien', $noRegister)->first();

        if ($patient) {
            return response()->json([
                'nama_pasien' => $patient->nama_pasien,
                'umur' => $patient->umur,
                'no_ruangan' => $patient->no_ruangan
            ]);
        }

        return response()->json(['error' => 'Data pasien tidak ditemukan.'], 404);
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'no_reg_pasien' => 'required|string|exists:table_pasien,no_reg_pasien',
        'durasi' => 'required|integer|min:1',
    ]);
    
    $patient = Patient::where('no_reg_pasien', $data['no_reg_pasien'])->first();
    
    if ($patient) {
        // 🔎 Cek apakah ada infusion session yang belum selesai dengan status aktif
        $infusAktif = InfusionSession::where('no_reg_pasien', $patient->no_reg_pasien)
                                    ->where('status_sesi_infus', 'active')
                                    ->first();
        
        if ($infusAktif) {
            return redirect()->back()->withErrors(['error' => 'Pasien ini sudah memiliki sesi infus yang aktif. Silakan tunggu tau selesaikan sesi infus yang sedang berlangsung sebelum membuat sesi baru.']);
        }

        // 🔎 Cek apakah ada infusion session yang belum punya id_device
        $infusBelumSelesai = InfusionSession::whereNull('id_perangkat_infusee')->first();
    
        if ($infusBelumSelesai) {
            return redirect()->back()
                ->withErrors(['Terdapat registrasi yang belum selesai (belum dipilihkan device). Silakan selesaikan atau hapus terlebih dahulu di Device Aktif.']);
        }

        $infusion = InfusionSession::create([
            'no_reg_pasien' => $patient->no_reg_pasien,
            'durasi_infus_jam' => intval($data['durasi']),
            'timestamp_infus' => Carbon::now()->toDateTimeString(),
            'status_sesi_infus' => 'active',
        ]);
    
        return redirect()->route('devices.index')->with('success', 'Data infus berhasil disimpan.');
    }
    
    return back()->withErrors(['error' => 'Pasien tidak ditemukan.']);
}


    
}

