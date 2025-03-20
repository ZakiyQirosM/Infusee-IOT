<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\InfusionSession;
use App\Models\Device;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index');
    }

    public function search(Request $request)
{
    $pasien = Pasien::table('pasien')
                ->where('no_reg_pasien', $request->no_reg_pasien)
                ->first();

    if ($pasien) {
        return response()->json([
            'nama_pasien' => $pasien->nama_pasien,
            'umur' => $pasien->umur,
            'no_ruangan' => $pasien->no_ruangan
        ]);
    } else {
        return response()->json([]);
    }
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'no_reg_pasien' => 'required|string|exists:pasien,no_reg_pasien',
            'durasi' => 'required|integer|min:1',
        ]);

        $patient = Pasien::where('no_reg_pasien', $data['no_reg_pasien'])->first();

        if ($patient) {
            Log::info('Data yang diterima:', $data);

            session([
                'no_reg_pasien' => $patient->no_reg_pasien,
                'nama_pasien' => $patient->nama_pasien,
                'umur' => $patient->umur,
                'no_ruangan' => $patient->no_ruangan,
                'durasi' => (int) $data['durasi'],
            ]);

            Log::info('Session saat ini:', session()->all());

            return redirect()->route('devices.index');
        }

        return back()->withErrors(['error' => 'Pasien tidak ditemukan.']);
    }
}
