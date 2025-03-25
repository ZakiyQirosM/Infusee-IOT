<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosisInfus extends Model
{
    use HasFactory;

    protected $table = 'table_dosis_infus_pasien'; 

    protected $fillable = [
        'id_session',
        'dosis_infus',
        'laju_tetes_tpm',
        'persentase_infus_menit',
        'status_anomali_infus',
        'timestamp_infus',
        'id_perangkat_infusee',
    ];

    public function infusionsession()
    {
        return $this->belongsTo(InfusionSession::class, 'id_session', 'id_session');
    }

}

