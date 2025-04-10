<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;

class DeviceSeeder extends Seeder
{
    public function run()
    {
        Device::create([
            'id_perangkat_infusee' => 111,
            'alamat_ip_infusee' => '192.168.1.10',
            'status' => 'available',
        ]);

        Device::create([
            'id_perangkat_infusee' => 112,
            'alamat_ip_infusee' => '192.168.1.11',
            'status' => 'available',
        ]);

        Device::create([
            'id_perangkat_infusee' => 113,
            'alamat_ip_infusee' => '192.168.1.12',
            'status' => 'available',
        ]);
    }
}
