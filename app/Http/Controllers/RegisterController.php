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
            \Log::info('Data yang diterima:', $data);

            $durasi = $data['durasi'];

            //if (strpos($durasi, ':') !== false) {
                // ✅ Jika format HH:MM
            //    list($jam, $menit) = explode(':', $durasi);
            //    $totalMenit = ($jam * 60) + $menit;
            //} else {
                // ✅ Jika format pecahan (misalnya 0.5 berarti 30 menit)
            //    $totalMenit = floatval($durasi) * 60;
            //}

            $infusion = InfusionSession::create([
                'no_reg_pasien' => $patient->no_reg_pasien,
                'durasi_infus_menit' => intval($durasi),
                'timestamp_infus' => Carbon::now()->toDateTimeString(),
            ]);

            \Log::info('Data InfusionSession:', $infusion->toArray());

            return redirect()->route('devices.index')->with('success', 'Data infus berhasil disimpan.');
        }

        return back()->withErrors(['error' => 'Pasien tidak ditemukan.']);
    }
}

