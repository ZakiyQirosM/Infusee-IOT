<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\DosisInfus;
use App\Models\InfusionSession;
use App\Models\Device;
use Carbon\Carbon;

class InfuseeController extends Controller
{
    public function index()
{
    $dosisInfus = DosisInfus::whereHas('infusionsession', function ($query) {
        $query->where('status_sesi_infus', 'active');
    })->with(['infusionsession.patient'])->get();

    $infusees = $dosisInfus->map(function ($dosis) {
        $session = $dosis->infusionsession;
        $patient = $session?->patient;

        return [
            'id_session' => $dosis->id_session ?? '-',
            'nama_pasien' => $patient->nama_pasien ?? '-',
            'no_ruangan' => $patient->no_ruangan ?? '-',
            'id_perangkat_infusee' => $session->id_perangkat_infusee ?? '-',
            'dosis_infus' => $dosis->dosis_infus ?? '-',
            'laju_tetes_tpm' => $dosis->laju_tetes_tpm ?? '-',
            'durasi_infus_menit' => $session->durasi_infus_menit ?? 0,
            'persentase_infus_menit' => $dosis->persentase_infus_menit ?? 0,
            'status_anomali_infus' => $dosis->status_anomali_infus ?? '-',
            'status_sesi_infus' => $dosis->status_sesi_infus ?? '-',
            'color' => $this->getColorBasedOnPercentage($dosis->persentase_infus_menit ?? 0),
            'timestamp_infus' => $session->updated_at?->setTimezone('Asia/Jakarta')->format('c'),
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

    public function endSession($id_session)
    {
        $session = InfusionSession::findOrFail($id_session);
        
        $session->update([
            'status_sesi_infus' => 'inactive'
        ]);

        $device = Device::where('id_perangkat_infusee', $session->id_perangkat_infusee)->first();
        if ($device) {
            $device->update(['status' => 'available']);
        }

        return redirect()->route('infusee.index')->with('success');
    }
}
