<?php
namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\InfusionSession;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    // ✅ Ambil data infusion session dan simpan ke session
    public function index()
    {
        // ✅ Ambil data infusion session terbaru
        $infusionSession = InfusionSession::with('device')
            ->orderBy('timestamp_infus', 'desc')
            ->first();

        // ✅ Simpan data ke session supaya tidak perlu query ulang
        if ($infusionSession) {
            session()->put('infusion_session', $infusionSession);
        }

        // ✅ Ambil daftar perangkat
        $devices = Device::select('id_perangkat_infusee', 'alamat_ip_infusee')->get();

        return view('devices.index', compact('devices', 'infusionSession'));
    }

    // ✅ Assign device ke infusion session yang ada di session
    public function assign(Request $request)
{
    \Log::info('Data request:', $request->all());

    $data = $request->validate([
        'device_id' => 'required|string|exists:table_perangkat_infusee,id_perangkat_infusee',
    ]);

    // ✅ Ambil data infusion session terakhir yang memiliki id valid
    $infusion = InfusionSession::whereNotNull('id_session')
        ->orderBy('timestamp_infus', 'desc')
        ->first();

    if (!$infusion) {
        \Log::error('Data infusion session tidak ditemukan');
        return response()->json(['error' => 'Data infusion session tidak ditemukan.'], 400);
    }

    try {
        // ✅ Lakukan update dengan `id` yang valid
        $infusion->update([
            'id_perangkat_infusee' => $data['device_id'],
            'updated_at' => now(),
        ]);

        \Log::info('Device berhasil dipilih:', $infusion->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Device berhasil dipilih dan data disimpan!',
        ]);
    } catch (\Exception $e) {
        \Log::error('Gagal menyimpan infusion session: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal menyimpan data infusion session.'], 500);
    }
}

}
