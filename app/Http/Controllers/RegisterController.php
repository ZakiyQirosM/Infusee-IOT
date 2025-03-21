<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\InfusionSession;
use App\Models\Device;
use Illuminate\Support\Facades\Log;


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
            // ✅ Debug untuk memeriksa nilai yang diterima
            Log::info('Data yang diterima:', $data);

            // ✅ Gunakan intval() untuk memastikan tipe integer
            session([
                'no_reg_pasien' => $patient->no_reg_pasien,
                'nama_pasien' => $patient->nama_pasien,
                'umur' => $patient->umur,
                'no_ruangan' => $patient->no_ruangan,
                'durasi_infus_menit' => intval($data['durasi']),
            ]);

            // ✅ Debug untuk memeriksa data yang disimpan di session
            Log::info('Session saat ini:', session()->all());

            // ✅ Redirect ke halaman pemilihan device
            return redirect()->route('devices.index');
        }

        return back()->withErrors(['error' => 'Pasien tidak ditemukan.']);
    }

}