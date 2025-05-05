<?php

namespace App\Http\Controllers;

use App\Models\MonitoringInfus;
use App\Models\InfusionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MonitoringController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_session' => 'required|string',
            'berat' => 'required|numeric',
            'tpm_sensor' => 'required|numeric',
        ]);

        $id_session = $validated['id_session'];
        $berat = $validated['berat'];
        $tpm_sensor = $validated['tpm_sensor'];

        try {
            $existing = MonitoringInfus::where('id_session', $id_session)->first();

            $monitoringData = MonitoringInfus::updateOrCreate(
                ['id_session' => $id_session],
                [
                    'berat_total' => ($existing && $existing->berat_total > 0) ? $existing->berat_total : $berat,
                    'berat_sekarang' => $berat,
                    'tpm_sensor' => $tpm_sensor,
                    'tpm_prediksi' => 0,
                    'waktu' => now()
                ]
            );

            // Get prediction after storing data
            $prediction = $this->getPrediction($id_session);

            // Returning both the monitoring data and the prediction
            return response()->json([
                'success' => true,
                'message' => $existing ? 'Data monitoring diperbarui' : 'Data monitoring awal disimpan',
                'data' => $monitoringData,
                'prediction' => $prediction
            ]);
            
        } catch (\Exception $e) {
            Log::error('Monitoring store error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'Gagal menyimpan data monitoring'
            ], 500);
        }
    }

    public function storeInternal($id_session, $berat_total = null, $tpm_sensor = null)
    {
        try {
            $session = InfusionSession::find($id_session);
            if (!$session) {
                Log::error('MonitoringController: Session tidak ditemukan', ['id_session' => $id_session]);
                return ['success' => false, 'error' => 'Session tidak valid'];
            }

            // Mengatur nilai default untuk $berat_total jika tidak ada
            $berat_total = $berat_total ?? $session->berat_awal ?? 0;
            $tpm_sensor = $tpm_sensor ?? 0;

            $existing = MonitoringInfus::where('id_session', $id_session)->first();

            $monitoringData = MonitoringInfus::updateOrCreate(
                ['id_session' => $id_session],
                [
                    'berat_total' => ($existing && $existing->berat_total > 0) ? $existing->berat_total : $berat_total,
                    'berat_sekarang' => $berat_total,
                    'tpm_sensor' => (int) $tpm_sensor,
                    'tpm_prediksi' => 0,
                    'waktu' => now()
                ]
            );

            Log::info('Monitoring awal berhasil disimpan', [
                'id_session' => $id_session,
                'berat_total' => $berat_total,
                'tpm_sensor' => $tpm_sensor
            ]);

            $prediction = $this->getPrediction($id_session);

            return [
                'success' => true,
                'message' => $existing ? 'Monitoring diperbarui' : 'Monitoring awal dibuat',
                'data' => $monitoringData,
                'prediction' => $prediction
            ];

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data monitoring', [
                'error' => $e->getMessage(),
                'session' => $id_session,
                'trace' => $e->getTraceAsString()
            ]);
            return ['success' => false, 'error' => 'Gagal menyimpan data monitoring: ' . $e->getMessage()];
        }
    }

    protected function getPrediction($id_session)
    {
        try {
            $response = Http::timeout(5)
                ->post('http://127.0.0.1:8001/prediksi-dari-db', [
                    'id_session' => (string) $id_session
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Prediksi gagal', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return ['error' => 'Service prediksi sementara tidak tersedia'];

        } catch (\Exception $e) {
            Log::error('Koneksi ke service prediksi gagal', [
                'error' => $e->getMessage(),
                'session' => $id_session
            ]);
            return ['error' => 'Koneksi ke service prediksi gagal'];
        }
    }
}
