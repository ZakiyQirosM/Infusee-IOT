<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfuseeController extends Controller
{
    public function index()
{
    $infusees = [
        [
            'name' => 'Sa Kitij Wo',
            'percentage' => 80,
            'device' => 'DIF001',
            'tpm_rate' => '33/34',
            'timestamp' => '08.01.00'
        ],
        [
            'name' => 'Kim Bhab',
            'percentage' => 60,
            'device' => 'DIF002',
            'tpm_rate' => '20/20',
            'timestamp' => '01.01.00'
        ],
        [
            'name' => 'Tsung Tsank',
            'percentage' => 40,
            'device' => 'DIF003',
            'tpm_rate' => '25/27',
            'timestamp' => '01.00.00'
        ],
        [
            'name' => 'La Ra Moto',
            'percentage' => 20,
            'device' => 'DIF005',
            'tpm_rate' => '33/34',
            'timestamp' => '00.01.00'
        ]
    ];
    

    foreach ($infusees as &$infusee) {
        $infusee['color'] = $this->getColorBasedOnPercentage($infusee['percentage']);
    }

    return view('infusee.index', compact('infusees'));
}

private function getColorBasedOnPercentage($value)
{
    if ($value >= 80) return '#00cc44'; // Hijau
    if ($value >= 60) return '#ffcc00'; // Kuning
    if ($value >= 40) return '#ff9900'; // Oranye
    if ($value >= 11) return '#ff3333'; // Merah
    return '#000000'; // Hitam
}

}
