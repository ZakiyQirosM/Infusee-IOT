<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\DosisInfus;
use App\Models\InfusionSession;
use Carbon\Carbon;

class InfuseeController extends Controller
{
    public function index()
    {
        $dosisInfus = DosisInfus::with(['infusionsession.patient'])->get();

        $infusees = $dosisInfus->map(function ($dosis) {
            $session = $dosis->infusionsession;
            $patient = $session?->patient;

            // ✅ Atur timezone ke Asia/Jakarta
            $remainingTime = $session->created_at 
                ? $session->created_at->setTimezone('Asia/Jakarta')->format('c')
                : null;

            return [
                'id_session' => $dosis->id_session ?? '-',
                'nama_pasien' => $patient->nama_pasien ?? '-',
                'no_ruangan' => $patient->no_ruangan ?? '-',
                'id_perangkat_infusee' => $session->id_perangkat_infusee ?? '-',
                'dosis_infus' => $dosis->dosis_infus ?? '-',
                'laju_tetes_tpm' => $dosis->laju_tetes_tpm ?? '-',
                'persentase_infus_menit' => $dosis->persentase_infus_menit ?? 0,
                'status_anomali_infus' => $dosis->status_anomali_infus ?? '-',
                'color' => $this->getColorBasedOnPercentage($dosis->persentase_infus_menit ?? 0),
                'timestamp_infus' => $session->created_at?->setTimezone('Asia/Jakarta')->format('c'), // ✅ Format ISO8601 untuk JS
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
