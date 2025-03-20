<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\InfusionSession;
use Carbon\Carbon;

class InfuseeController extends Controller
{
    public function index()
    {
        // ✅ Ambil data pasien dan sesi infus terkait
        $patients = Patient::with('infusionSessions')->get();

        // ✅ Format data untuk view
        $infusees = $patients->map(function ($patient) {
            $session = $patient->infusionSessions->first(); // Ambil sesi pertama (jika ada)
    
            // ✅ Hitung selisih waktu dari timestamp ke waktu sekarang
            $remainingTime = '-';
            if ($session && $session->timestamp_infus) {
                $startTime = Carbon::parse($session->timestamp_infus);
                $now = Carbon::now();

                if ($startTime->lessThanOrEqualTo($now)) {
                    $diff = $startTime->diff($now);
                    $remainingTime = $diff->format('%H:%I:%S');
                }
            }

            return [
                'id_session' => $session->id ?? '-', // ✅ Tambahkan id session
                'nama_pasien' => $patient->nama_pasien,
                'no_ruangan' => $patient->no_ruangan,
                'id_perangkat_infusee' => $session->id_perangkat_infusee ?? '-', // ✅ Default ke '-'
                'laju_tetes_tpm' => $session->laju_tetes_tpm ?? '-', // ✅ Default ke '-'
                'persentase_infus_menit' => $session->persentase_infus_menit ?? 0, // ✅ Default ke 0
                'color' => $this->getColorBasedOnPercentage($session->persentase_infus_menit ?? 0),
                'timestamp_infus' => $remainingTime, // ✅ Pastikan tidak error jika kosong
            ];
        });

        return view('infusee.index', compact('infusees'));
    }
 
    // ✅ Perbaiki fungsi warna untuk persentase
    private function getColorBasedOnPercentage($value)
    {
        if ($value >= 80) return '#00cc44'; // Hijau
        if ($value >= 60) return '#ffcc00'; // Kuning
        if ($value >= 40) return '#ff9900'; // Oranye
        if ($value >= 11) return '#ff3333'; // Merah
        return '#000000'; // Hitam
    }
}
