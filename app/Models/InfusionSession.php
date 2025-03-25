<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfusionSession extends Model
{
    use HasFactory;

    protected $table = 'infusion_sessions';

    protected $primaryKey = 'id_session';

    protected $fillable = [
        'no_reg_pasien',
        'id_perangkat_infusee',
        'durasi_infus_menit',
        'timestamp_infus',
    ];

    // ✅ Kolom yang dianggap sebagai tanggal (otomatis format timestamp)
    protected $dates = ['timestamp_infus'];

    // ✅ Relasi ke tabel `patients`
    public function patient()
    {
        return $this->hasOne(Patient::class, 'no_reg_pasien', 'no_reg_pasien');
    }

    // ✅ Relasi ke tabel `devices`
    public function device()
    {
        return $this->hasMany(Device::class, 'id_perangkat_infusee', 'id_perangkat_infusee');
    }

    public function dosisInfus()
    {
        return $this->hasOne(DosisInfus::class, 'id_session', 'id_session');
    }

}
