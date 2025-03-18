<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\InfusionSession;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{

    public function run()
    {
        Patient::create([
            'no_reg_pasien' => 'REG123',
            'nama_pasien' => 'Budi Santoso',
            'umur' => 45,
            'no_ruangan' => 'Kamar 101'
        ]);

        Patient::create([
            'no_reg_pasien' => 'REG124',
            'nama_pasien' => 'Ani Lestari',
            'umur' => 38,
            'no_ruangan' => 'Kamar 202'
        ]);
    }

}
