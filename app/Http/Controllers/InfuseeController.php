<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Patient;
use App\Models\DosisInfus;
use App\Models\InfusionSession;
use App\Models\Device;
use Carbon\Carbon;

class InfuseeController extends Controller
{
    public function index()
    {
        $infusees = InfusionSession::all();
        $layout = auth('pegawai')->check() ? 'layouts.main' : 'layouts.guest';

        $dosisInfus = DosisInfus::whereHas('infusionsession', function ($query) {
            $query->where('status_sesi_infus', 'active');
        })->with(['infusionsession.patient'])->get();

        $infusees = $dosisInfus->map(function ($dosis) {
            $session = $dosis->infusionsession;
            $patient = $session?->patient;
        
            // Hitung status berdasarkan laju_tetes_tpm
            $tpm = $dosis->laju_tetes_tpm ?? 0;
            $reference = 33;
            if ($tpm < ($reference * 0.90)) {
                $status = 'slow';
                $bgColor = '#ff3333'; // Merah
                $icon = 'fa fa-arrow-down'; // Panah ke bawah
            } elseif ($tpm > ($reference * 1.10)) {
                $status = 'fast';
                $bgColor = '#ff3333'; // Merah
                $icon = 'fa fa-arrow-up'; // Panah ke atas
            } else {
                $status = 'normal';
                $bgColor = '#00cc44'; // Hijau
                $icon = 'fa fa-arrow-up'; // Panah ke atas
            }
        
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
                'bgColor' => $bgColor, // ✅ Tambahkan background color sendiri
                'icon' => $icon, // ✅ Tambahkan icon
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
