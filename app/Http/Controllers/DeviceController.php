<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\InfusionSession;
use App\Models\DosisInfus;
use App\Models\Patient;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
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
                    'durasi_infus_menit' => $infusionSession['durasi_infus_menit'],
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

        $devices = Device::select('id_perangkat_infusee', 'alamat_ip_infusee', 'status')
            ->where('status', 'available')
            ->when($usedDeviceIds->count() > 0, function ($query) use ($usedDeviceIds) {
                return $query->whereNotIn('id_perangkat_infusee', $usedDeviceIds);
            })
            ->get();

        return view('devices.index', compact('devices', 'infusionSession', 'patientData'));
    }

    public function assign(Request $request)
    {
        // ✅ Validasi request
        $data = $request->validate([
            'device_id' => 'required|string|exists:table_perangkat_infusee,id_perangkat_infusee',
        ]);

        try {
            // ✅ Ambil infusion session yang aktif
            $infusion = InfusionSession::with('patient')
                ->whereNotNull('id_session')
                ->whereNull('id_perangkat_infusee')
                ->orderBy('timestamp_infus', 'desc')
                ->firstOrFail();

            // ✅ Cek apakah perangkat tersedia
            $device = Device::where('id_perangkat_infusee', $data['device_id'])
                ->where('status', 'available')
                ->first();

            if (!$device) {
                return response()->json([
                    'error' => 'Perangkat tidak tersedia atau sudah digunakan.'
                ], 400);
            }

            \DB::beginTransaction(); // ✅ Start Transaction

            // ✅ Update di table infusion_sessions
            $infusion->update([
                'id_perangkat_infusee' => $data['device_id'],
                'updated_at' => now(),
                'status_sesi_infus' => 'active',
            ]);

            // ✅ Buat data dosis infus dengan nilai dosis awal
            DosisInfus::create([
                'id_session' => $infusion->id_session,
                'id_perangkat_infusee' => $data['device_id'],
                'dosis_infus' => 500,
                'laju_tetes_tpm' => 50,
                'persentase_infus_menit' => 50,
                'status_anomali_infus' => 'normal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update status perangkat menjadi 'unavailable'
            $affectedRows = Device::where('id_perangkat_infusee', $data['device_id'])
                ->update(['status' => 'unavailable']);

            if ($affectedRows === 0) {
                throw new \Exception('Gagal memperbarui status perangkat');
            }

            \DB::commit();

            // Hapus session setelah perangkat berhasil dipilih
            session()->forget('infusion_session');

            return response()->json([
                'success' => true,
                'message' => 'Perangkat berhasil dipilih dan data disimpan!',
                'device_id' => $data['device_id'],
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
