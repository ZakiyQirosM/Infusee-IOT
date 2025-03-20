<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfusionSession extends Model
{
    use HasFactory;

    // ✅ Nama tabel di database
    protected $table = 'infusion_sessions';

    protected $primaryKey = 'id_session';
    
    public $incrementing = true;

    // ✅ Kolom yang bisa diisi (fillable)
    protected $fillable = [
        'no_reg_pasien',
        'nama_pasien',
        'umur',
        'no_ruangan',
        'id_perangkat_infusee',
        'durasi_infus_menit',
        'timestamp_infus',
    ];

    // ✅ Kolom yang dianggap sebagai tanggal (otomatis format timestamp)
    protected $dates = ['timestamp_infus'];

    // ✅ Relasi ke tabel `patients`
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'no_reg_pasien', 'no_reg_pasien');
    }

    // ✅ Relasi ke tabel `devices`
    public function device()
    {
        return $this->belongsTo(Device::class, 'id_perangkat_infusee', 'id_perangkat_infusee');
    }
}
