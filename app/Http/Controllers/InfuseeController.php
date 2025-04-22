<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Patient;
use App\Models\MonitoringInfus;
use App\Models\InfusionSession;
use App\Models\Device;
use App\Models\HistoryActivity;
use Illuminate\Support\Facades\Auth;
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
            } elseif ($tpm > ($reference * 1.20)) {
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
                'no_reg_pasien' => $patient->no_reg_pasien ?? '-',
                'no_ruangan' => $patient->no_ruangan ?? '-',
                'id_perangkat_infusee' => $session->id_perangkat_infusee ?? '-',
                'berat_total' => $berat_sekarang,
                'tpm_sensor' => $dinfus->tpm_sensor ?? '-',
                'durasi_infus_jam' => $session->durasi_infus_jam ?? 0,
                'tpm_prediksi' => $dinfus->tpm_prediksi ?? 0,
                'persentase_infus' => $persentase,
                'status_sesi_infus' => $dinfus->status_sesi_infus ?? '-',
                'color' => $this->getColorBasedOnPercentage($persentase ?? 0),
                'bgColor' => $bgColor,
                'icon' => $icon,
                'status' => $status,
                'timestamp_infus' => $session->updated_at?->setTimezone('Asia/Jakarta')->format('c'),
            ];
        });

        return view('infusee.index', compact('infusees', 'layout'));
    }

    function maskNama($nama) 
    {
        $output = '';
        $length = strlen($nama);
        for ($i = 0; $i < $length; $i += 6) {
            $part = substr($nama, $i, 3);
            $mask = substr($nama, $i + 3, 3);
            $output .= $part;
            if ($mask) {
                $output .= str_repeat('*', strlen($mask));
            }
        }
        return $output;
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

    HistoryActivity::create([
        'id_session' => $session->id_session,
        'no_peg' => Auth::user()->no_peg,
        'aktivitas' => 'Mengakhiri sesi infus',
    ]);

    return redirect()->route('infusee.index')->with('success');
    }
}
