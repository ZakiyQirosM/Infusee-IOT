<?php
namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\InfusionSession;
use App\Models\Patient;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index()
{
    // ✅ Cek apakah infusion session masih aktif
    $infusionSession = session()->get('infusion_session');

    if (!$infusionSession) {
        $infusionSession = InfusionSession::with('device', 'patient')
            ->whereNull('id_perangkat_infusee') // ✅ Hanya ambil jika belum diassign
            ->orderBy('timestamp_infus', 'desc')
            ->first();

        if ($infusionSession) {
            session()->put('infusion_session', $infusionSession);
        }
    }

    $patient = $infusionSession?->patient;

    // ✅ Ambil id perangkat yang sudah dipakai
    $usedDeviceIds = InfusionSession::whereNotNull('id_perangkat_infusee')
        ->pluck('id_perangkat_infusee')
        ->filter();

    // ✅ Ambil data device yang tersedia
    $devices = Device::select('id_perangkat_infusee', 'alamat_ip_infusee')
        ->when($usedDeviceIds->count() > 0, function ($query) use ($usedDeviceIds) {
            return $query->whereNotIn('id_perangkat_infusee', $usedDeviceIds);
        })
        ->get();

    // ✅ Kirim data ke view
    return view('devices.index', compact('devices', 'infusionSession', 'patient'));
}



    public function assign(Request $request)
{
    \Log::info('Data request:', $request->all());

    $data = $request->validate([
        'device_id' => 'required|string|exists:table_perangkat_infusee,id_perangkat_infusee',
    ]);

    // ✅ Ambil infusion session terakhir yang valid
    $infusion = InfusionSession::whereNotNull('id_session')
        ->orderBy('timestamp_infus', 'desc')
        ->first();

    if (!$infusion) {
        \Log::error('Data infusion session tidak ditemukan');
        return response()->json(['error' => 'Data infusion session tidak ditemukan.'], 400);
    }

    try {
        $infusion->update([
            'id_perangkat_infusee' => $data['device_id'],
            'updated_at' => now(),
        ]);

        // ✅ Update status device ke 'unavailable'
        $affectedRows = Device::where('id_perangkat_infusee', $data['device_id'])
            ->update(['status' => 'unavailable']);

        if ($affectedRows === 0) {
            throw new \Exception('Device status gagal diupdate');
        }

        // ✅ Hapus session infusion setelah update
        session()->forget('infusion_session');

        return response()->json([
            'success' => true,
            'message' => 'Device berhasil dipilih dan data disimpan!',
            'device_id' => $data['device_id'],
        ]);
    } catch (\Exception $e) {
        \Log::error('Gagal menyimpan infusion session: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal menyimpan data infusion session.'], 500);
    }
}


}
