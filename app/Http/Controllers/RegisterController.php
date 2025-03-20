<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\InfusionSession;
use App\Models\Device;

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

            session([
                'no_reg_pasien' => $patient->no_reg_pasien,
                'nama_pasien' => $patient->nama_pasien,
                'umur' => $patient->umur,
                'no_ruangan' => $patient->no_ruangan,
                'durasi' => intval($data['durasi']),
            ]);

            \Log::info('Session saat ini:', session()->all());

            return redirect()->route('devices.index');
        }

        return back()->withErrors(['error' => 'Pasien tidak ditemukan.']);
    }

}
