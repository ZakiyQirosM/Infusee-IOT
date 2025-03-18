<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\InfusionSession;
use Carbon\Carbon;

class InfuseeController extends Controller
{
    public function index()
    {
        $patients = Patient::with('infusionSessions')->get();

        $infusees = $patients->map(function ($patient) {
            $session = $patient->infusionSessions->first();

            // Hitung selisih waktu dari timestamp ke waktu sekarang
            $remainingTime = null;
            if ($session && $session->timestamp_infus) {
                $startTime = Carbon::parse($session->timestamp_infus);
                $now = Carbon::now();
                $diff = $startTime->diff($now);
                $remainingTime = $diff->format('%H:%I:%S');
            }

            return [
                'nama_pasien' => $patient->nama_pasien,
                'no_ruangan' => $patient->no_ruangan,
                'id_perangkat_infusee' => $session->id_perangkat_infusee ?? '-',
                'laju_tetes_tpm' => $session->laju_tetes_tpm ?? '-',
                'persentase_infus_menit' => $session->persentase_infus_menit ?? 0,
                'color' => $this->getColorBasedOnPercentage($session->persentase_infus_menit ?? 0),
                'timestamp_infus' => $remainingTime ?? '-'
            ];
        });

        return view('infusee.index', compact('infusees'));
    }

    private function getColorBasedOnPercentage($value)
    {
        if ($value >= 80) return '#00cc44'; // Hijau
        if ($value >= 60) return '#ffcc00'; // Kuning
        if ($value >= 40) return '#ff9900'; // Oranye
        if ($value >= 11) return '#ff3333'; // Merah
        return '#000000'; // Hitam
    }
}
