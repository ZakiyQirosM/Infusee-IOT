<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\InfusionSession;

class RegisterController extends Controller
{
    // Menampilkan form registrasi
    public function index()
    {
        return view('register.index');
    }

    // Menangani pencarian pasien berdasarkan no_reg_pasien
    public function search(Request $request)
{
    $noRegister = $request->input('no_reg_pasien');

    if (!$noRegister) {
        return response()->json(['error' => 'Nomor registrasi harus diisi!'], 400);
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


    // Menangani pengiriman data dari form registrasi
    public function store(Request $request)
    {
        // Validasi data dari form
        $data = $request->validate([
            'no_reg_pasien' => 'required|string|exists:patients,no_reg_pasien',
            'durasi' => 'required|integer|min:0',
        ]);

        $patient = Patient::where('no_reg_pasien', $data['no_reg_pasien'])->first();

        if ($patient) {
            // Simpan ke tabel infusion_sessions
            InfusionSession::create([
                'no_reg_pasien' => $patient->no_reg_pasien,
                'nama_pasien' => $patient->nama_pasien,
                'umur' => $patient->umur,
                'no_ruangan' => $patient->no_ruangan,
                'durasi_infus_menit' => $data['durasi'],
                'timestamp_infus' => now(),
            ]);

            return redirect()->route('devices.index')->with('success', 'Data infus berhasil disimpan.');
        }

        return back()->withErrors(['error' => 'Pasien tidak ditemukan.']);
    }
}
