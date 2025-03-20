<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\InfusionSession;

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
            \Log::info('Data yang diterima:', $data);

            // ✅ Simpan langsung ke tabel `infusion_sessions`
            $infusion = InfusionSession::create([
                'no_reg_pasien' => $patient->no_reg_pasien,
                'nama_pasien' => $patient->nama_pasien,
                'umur' => $patient->umur,
                'no_ruangan' => $patient->no_ruangan,
                'durasi_infus_menit' => intval($data['durasi']),
            ]);

            \Log::info('Data InfusionSession:', $infusion->toArray());

            // ✅ Redirect ke halaman pilih device
            return redirect()->route('devices.index')->with('success', 'Data infus berhasil disimpan.');
        }

        return back()->withErrors(['error' => 'Pasien tidak ditemukan.']);
    }
}

