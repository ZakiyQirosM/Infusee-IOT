<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\InfusionSession;
use App\Models\Device;

class RegisterController extends Controller
{
    // ✅ Menampilkan form
    public function index()
    {
        return view('register.index');
    }

    // ✅ Pencarian otomatis berdasarkan no_reg_pasien
    public function search(Request $request)
    {
        $noRegister = $request->input('no_reg_pasien');

        // ✅ Validasi jika input kosong
        if (!$noRegister) {
            return response()->json(['error' => 'Nomor registrasi harus diisi!'], 400);
        }

        // ✅ Gunakan `where()` karena MySQL case-insensitive untuk VARCHAR
        $patient = Patient::where('no_reg_pasien', $noRegister)->first();

        // ✅ Jika data ditemukan
        if ($patient) {
            return response()->json([
                'nama_pasien' => $patient->nama_pasien,
                'umur' => $patient->umur,
                'no_ruangan' => $patient->no_ruangan
            ]);
        }

        // ✅ Jika data tidak ditemukan
        return response()->json(['error' => 'Data pasien tidak ditemukan.'], 404);
    }

    // ✅ Simpan data ke database
    public function store(Request $request)
    {
        $data = $request->validate([
            'no_reg_pasien' => 'required|string|exists:table_pasien,no_reg_pasien',
            'durasi' => 'required|integer|min:1',
        ]);

        $patient = Patient::where('no_reg_pasien', $data['no_reg_pasien'])->first();

        if ($patient) {
            // ✅ Simpan data ke session
            session([
                'no_reg_pasien' => $patient->no_reg_pasien,
                'nama_pasien' => $patient->nama_pasien,
                'umur' => $patient->umur,
                'no_ruangan' => $patient->no_ruangan,
                'durasi' => $data['durasi']
            ]);

            // ✅ Redirect ke halaman device
            return redirect()->route('devices.index');
        }

        // ✅ Jika data pasien tidak ditemukan
        return back()->withErrors(['error' => 'Pasien tidak ditemukan.']);
    }
}
