<?php

namespace App\Http\Controllers;

use App\Models\MonitoringInfus;
use App\Models\InfusionSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonitoringController extends Controller
{
    public function storeInternal($id_session, $berat_total = null, $tpm_sensor = null)
    {
        // Validasi session
        $session = InfusionSession::where('id_session', $id_session)->first();
        if (!$session) {
            Log::error('MonitoringController: ID session tidak ditemukan.', ['id_session' => $id_session]);
            return ['error' => 'ID session tidak valid'];
        }

        // Nilai default jika tidak dikirim
        $berat_total = $berat_total ?? rand(100, 1000);
        $tpm_sensor  = $tpm_sensor ?? rand(10, 60);

        try {
            $existing = MonitoringInfus::where('id_session', $id_session)->first();

            if (!$existing) {
                // Pertama kali alat aktif
                MonitoringInfus::create([
                    'id_session' => $id_session,
                    'berat_total' => $berat_total,
                    'berat_sekarang' => $berat_total,
                    'tpm_sensor' => $tpm_sensor,
                    'tpm_prediksi' => 0,
                    'waktu' => now(),
                ]);
            } else {
                // Update berat sekarang
                $existing->update([
                    'berat_sekarang' => $berat_total,
                    'tpm_sensor' => $tpm_sensor,
                    'waktu' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan MonitoringInfus: ' . $e->getMessage());
            return ['error' => 'Gagal menyimpan data monitoring.'];
        }

        // Panggil FastAPI untuk prediksi
        try {
            $response = Http::asJson()->post('http://127.0.0.1:8001/prediksi-dari-db', [
                'id_session' => $id_session,
            ]);

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error('FastAPI tidak merespons dengan benar.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return ['error' => 'FastAPI error: ' . $response->status()];
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghubungi FastAPI: ' . $e->getMessage());
            return ['error' => 'Gagal menghubungi FastAPI'];
        }
    }
}
