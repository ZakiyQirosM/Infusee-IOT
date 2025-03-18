<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\InfusionSession;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patient = Patient::create([
            'name' => 'Sa Kitji Wo',
            'room_number' => '911',
            'device_id' => 'DIF001'
        ]);

        InfusionSession::create([
            'patient_id' => $patient->id,
            'current_volume' => 800,
            'total_volume' => 1000,
            'drop_rate' => 33,
            'remaining_percentage' => 80
        ]);
    }
}
