<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    public function run()
    {
        Pegawai::create([
            'nama_peg' => 'Buna',
            'no_peg' => '252525',
            'password' => Hash::make('worker321'),
            'no_wa' => '085156186177',
        ]);

        Pegawai::create([
            'nama_peg' => 'Prabski',
            'no_peg' => '242424',
            'password' => Hash::make('worker123'),
            'no_wa' => '085190083495',
        ]);
    }
}
