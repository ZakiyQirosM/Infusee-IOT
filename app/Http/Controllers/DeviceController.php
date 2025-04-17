<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\InfusionSession;
use App\Models\MonitoringInfus;
use App\Models\Patient;
use App\Http\Controllers\MonitoringController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class DeviceController extends Controller
{
    public function ping(Request $request)
    {
        $request->validate([
            'id_perangkat_infusee' => 'required|string',
            'alamat_ip_infusee' => 'required|ip',
        ]);

        // Cek apakah perangkat sudah ada
        $device = Device::where('id_perangkat_infusee', $request->id_perangkat_infusee)->first();

        if ($device) {
            // Kalau perangkat sudah ada, hanya update status_device dan last_ping
            $device->update([
                'alamat_ip_infusee' => $request->alamat_ip_infusee,
                'last_ping' => now(),
                'status_device' => 'online',
            ]);
        } else {
            // Kalau belum ada, buat baru dengan status = available
            $device = Device::create([
                'id_perangkat_infusee' => $request->id_perangkat_infusee,
                'alamat_ip_infusee' => $request->alamat_ip_infusee,
                'status' => 'available',
                'last_ping' => now(),
                'status_device' => 'online',
            ]);
        }

        return response()->json([
            'message' => 'Ping diterima.',
            'device' => $device
        ]);
    }

    public function shutdown(Request $request)
    {
        try {
            // Ambil id_perangkat_infusee dari request
            $deviceId = $request->input('id_perangkat_infusee');
            
            // Periksa apakah id_perangkat_infusee ada di request
            if (!$deviceId) {
                return response()->json(['message' => 'id_perangkat_infusee required'], 400);
            }

            // Cari perangkat berdasarkan id_perangkat_infusee
            $device = device::where('id_perangkat_infusee', $deviceId)->first();
            
            // Periksa apakah perangkat ditemukan
            if (!$device) {
                return response()->json(['message' => 'Device not found'], 404);
            }

            // Update status perangkat menjadi 'offline'
            $device->status_device = 'offline';
            $device->save();

            return response()->json(['message' => 'Device status updated to offline'], 200);
            
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    public function getPatientData()
    {
        $infusionSession = session()->get('infusion_session');

        if ($infusionSession) {
            $patient = Patient::where('no_reg_pasien', $infusionSession['no_reg_pasien'])->first();
            if ($patient) {
                return [
                    'no_reg_pasien' => $patient->no_reg_pasien,
                    'nama_pasien' => $patient->nama_pasien,
                    'umur' => $patient->umur,
                    'no_ruangan' => $patient->no_ruangan,
                    'durasi_infus_jam' => $infusionSession['durasi_infus_jam'],
                ];
            }
        }

        return null;
    }

    public function index()
    {
        session()->forget('skip_autoload');

        $infusionSession = session()->get('infusion_session');

        if (!$infusionSession) {
            $infusionSession = InfusionSession::with('device', 'patient')
                ->whereNull('id_perangkat_infusee')
                ->orderBy('timestamp_infus', 'desc')
                ->first();

            if ($infusionSession) {
                $infusionSession->load('patient');
                session()->put('infusion_session', $infusionSession);
            }
        }

        // ✅ Ambil data pasien
        $patientData = $this->getPatientData();

        $usedDeviceIds = InfusionSession::whereNotNull('id_perangkat_infusee')
            ->whereHas('device', function ($query) {
                $query->where('status', 'unavailable');
            })
            ->pluck('id_perangkat_infusee')
            ->filter();

        $devices = Device::select('id_perangkat_infusee', 'alamat_ip_infusee', 'status', 'status_device')
            ->where('status', 'available')
            ->where('status_device', 'online')
            ->get();

        return view('devices.index', compact('devices', 'infusionSession', 'patientData'));
    }

    public function assign(Request $request)
    {
        // ✅ Validasi request
        $data = $request->validate([
            'id_perangkat_infusee' => 'required|string|exists:table_perangkat_infusee,id_perangkat_infusee',
        ]);

        try {
            // ✅ Ambil infusion session yang aktif
            $infusion = InfusionSession::with('patient')
                ->whereNotNull('id_session')
                ->whereNull('id_perangkat_infusee')
                ->orderBy('timestamp_infus', 'desc')
                ->firstOrFail();

                $device = Device::where('id_perangkat_infusee', $data['id_perangkat_infusee'])
                ->where('status', 'available')
                ->where('status_device', 'online')
                ->first();
            

            if (!$device) {
                return response()->json([
                    'error' => 'Perangkat tidak tersedia atau sudah digunakan.'
                ], 400);
            }

            \DB::beginTransaction(); // ✅ Start Transaction

            // ✅ Update di table infusion_sessions
            $infusion->update([
                'id_perangkat_infusee' => $data['id_perangkat_infusee'],
                'updated_at' => now(),
                'status_sesi_infus' => 'active',
            ]);

            // Update status perangkat menjadi 'unavailable'
            $affectedRows = Device::where('id_perangkat_infusee', $data['id_perangkat_infusee'])
                ->update(['status' => 'unavailable']);

            if ($affectedRows === 0) {
                throw new \Exception('Gagal memperbarui status perangkat');
            }

            \DB::commit();

            // ✅ Panggil MonitoringController
            $monitoringController = new MonitoringController();
            $monitoringResult = $monitoringController->storeInternal($infusion->id_session);

            // Hapus session setelah perangkat berhasil dipilih
            session()->forget('infusion_session');

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil dipilih dan data disimpan!',
                'id_perangkat_infusee' => $data['id_perangkat_infusee'],
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \DB::rollBack(); // ✅ Rollback jika gagal
            \Log::error('Infusion session tidak ditemukan: ' . $e->getMessage());

            return response()->json([
                'error' => 'Data infusion session tidak ditemukan atau sudah memiliki perangkat.'
            ], 400);
        } catch (\Exception $e) {
            \DB::rollBack(); // ✅ Rollback jika gagal
            \Log::error('Gagal menyimpan infusion session: ' . $e->getMessage());

            return response()->json([
                'error' => 'Gagal menyimpan data infusion session.',
                'details' => $e->getMessage(),
            ], 500);
        }
        
    }

    public function clear($id_session)
    {
        $session = InfusionSession::where('id_session', $id_session)->first();

        if (!$session) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $session->delete();

        // ✅ Hapus session dengan key yang benar
        if (session()->get('infusion_session.id_session') == $id_session) {
            session()->forget('infusion_session');
        }

        return redirect()->route('devices.index')->with('success', 'Data pasien berhasil dihapus.');
    }


}
