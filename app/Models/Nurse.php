<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;

    protected $table = 'table_perawat';

    protected $fillable = [
        'no_pegawai',
        'nama_perawat',
    ];

    public function infusionSessions()
    {
        return $this->hasMany(InfusionSession::class, 'no_pegawai', 'no_pegawai');
    }
}