<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'table_perangkat_infusee';

    protected $fillable = [
        'id_perangkat_infusee',
        'alamat_ip_infusee',
    ];

    public function infusionSessions()
    {
        return $this->hasMany(InfusionSession::class, 'id_perangkat_infusee', 'id_perangkat_infusee');
    }
}