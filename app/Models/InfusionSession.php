<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfusionSession extends Model
{
    use HasFactory;

    protected $table = 'table_dosis_infus_pasien'; // Nama tabel di database

    protected $fillable = [
        'no_reg_pasien',
        'no_pegawai',
        'id_perangkat_infusee',
        'dosis_infus',
        'laju_tetes_tpm',
        'persentase_infus_menit',
        'status_anomali_infus',
        'durasi_infus_menit',
        'timestamp_infus'	

    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'no_reg_pasien', 'no_reg_pasien');
    }

}
