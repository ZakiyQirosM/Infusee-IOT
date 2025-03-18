<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\DosisInfusPasien;

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
        $request->validate([
            'no_reg_pasien' => 'required|string',
        ]);

        $noRegister = $request->input('no_reg_pasien');

        // Cari data pasien
        $patient = Pasien::where('no_reg_pasien', $noRegister)->first();

        if ($patient) {
            return response()->json([
                'no_reg_pasien' => $patient->no_reg_pasien,
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
        try {
            // Validasi input dari form
            $data = $request->validate([
                'no_reg_pasien' => 'required|string|exists:pasiens,no_reg_pasien',
                'durasi' => 'required|integer|min:1',
            ]);

            // Cari data pasien berdasarkan `no_reg_pasien`
            $patient = Pasien::where('no_reg_pasien', $data['no_reg_pasien'])->firstOrFail();

            // Simpan ke tabel `DosisInfusPasien`
            DosisInfusPasien::create([
                'fk_no_reg_pasien' => $patient->no_reg_pasien,
                'durasi_infus_menit' => $data['durasi'],
            ]);

            return redirect()->route('register.index')->with('success', 'Data infus berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
