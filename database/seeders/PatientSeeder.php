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
            'no_reg_pasien' => '123456789001',
            'nama_pasien' => 'Saaa Kitji Whoo',
            'umur' => 45,
            'no_ruangan' => '22.22.01'
        ]);

        Patient::create([
            'no_reg_pasien' => '123456789002',
            'nama_pasien' => 'jhon doee',
            'umur' => 38,
            'no_ruangan' => '22.22.02'
        ]);
    }

}
