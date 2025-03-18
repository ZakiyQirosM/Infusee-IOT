<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'table_pasien'; // Nama tabel di database

    protected $fillable = [
        'no_reg_pasien', // Kolom di database
        'nama_pasien',
        'umur',
        'no_ruangan',
    ];

    public function infusionSessions()
    {
        return $this->hasMany(InfusionSession::class, 'no_reg_pasien', 'no_reg_pasien');
    }

}
