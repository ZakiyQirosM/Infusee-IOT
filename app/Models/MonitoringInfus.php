<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringInfus extends Model
{
    use HasFactory;

    protected $table = 'table_monitoring_infus'; // Sesuai nama tabel di migration

    public $timestamps = false; // Karena pakai kolom waktu sendiri (bukan created_at & updated_at) 

    protected $fillable = [
        'id_session',
        'berat_total',
        'berat_sekarang',
        'tpm_sensor',
        'tpm_prediksi',
        'waktu',
    ];
    

    public function infusionsession()
    {
        return $this->belongsTo(InfusionSession::class, 'id_session', 'id_session');
    }

}

