<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Patient;
use App\Models\MonitoringInfus;
use App\Models\InfusionSession;
use App\Models\Device;
use Carbon\Carbon;

class InfuseeController extends Controller
{
    public function getLatestInfus()
{
    $latest = MonitoringInfus::latest()->first();
    return response()->json($latest);
}

    public function index()
{
    $layout = auth('pegawai')->check() ? 'layouts.main' : 'layouts.guest';

    // Ambil data monitoring + relasi ke session dan patient
    $monitoringData = MonitoringInfus::whereHas('infusionsession', function ($query) {
        $query->where('status_sesi_infus', 'active');
    })->with(['infusionsession.patient'])->get();

    // Loop untuk map data
    $infusees = $monitoringData->map(function ($dinfus) {
        $session = $dinfus->infusionsession;
        $patient = $session?->patient;

        $berat_total = $dinfus->berat_total ?? 1;
        $berat_sekarang = $dinfus->berat_sekarang ?? 0;
        $persentase = round(($berat_sekarang / $berat_total) * 100, 2);

        $tpm = $dinfus->tpm_sensor ?? 0;
        $reference = $dinfus->tpm_prediksi ?? 0;
        if ($tpm < ($reference * 0.50)) {
            $status = 'slow';
            $bgColor = '#ff3333';
            $icon = 'fa fa-arrow-down';
        } elseif ($tpm > ($reference * 1.30)) {
            $status = 'fast';
            $bgColor = '#ff3333';
            $icon = 'fa fa-arrow-up';
        } else {
            $status = 'normal';
            $bgColor = '#00cc44';
            $icon = 'fas fa-circle-check';
        }

        return [
            'id_session' => $dinfus->id_session ?? '-',
            'nama_pasien' => $patient->nama_pasien ?? '-',
            'no_ruangan' => $patient->no_ruangan ?? '-',
            'id_perangkat_infusee' => $session->id_perangkat_infusee ?? '-',
            'berat_total' => $berat_sekarang,
            'tpm_sensor' => $dinfus->tpm_sensor ?? '-',
            'durasi_infus_menit' => $session->durasi_infus_menit ?? 0,
            'tpm_prediksi' => $dinfus->tpm_prediksi ?? 0,
            'persentase_infus' => $persentase,
            'status_sesi_infus' => $dinfus->status_sesi_infus ?? '-',
            'color' => $this->getColorBasedOnPercentage($dinfus->tpm_prediksi ?? 0),
            'bgColor' => $bgColor,
            'icon' => $icon,
            'status' => $status,
            'timestamp_infus' => $session->updated_at?->setTimezone('Asia/Jakarta')->format('c'),
        ];
    });

    return view('infusee.index', compact('infusees', 'layout'));
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
            'status_sesi_infus' => 'ended'
        ]);

        $device = Device::where('id_perangkat_infusee', $session->id_perangkat_infusee)->first();
        if ($device) {
            $device->update(['status' => 'available']);
        }

        return redirect()->route('infusee.index')->with('success');
    }
}
