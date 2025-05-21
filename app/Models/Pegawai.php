<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    protected $table = 'table_pegawai';
    protected $primaryKey = 'no_peg';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'nama_peg', 
        'no_peg', 
        'password', 
        'no_wa',
        'last_login_at',
        'last_activity_at',
    ];
    protected $hidden = ['password'];

    public function activities()
    {
        return $this->hasMany(HistoryActivity::class, 'no_peg', 'no_peg');
    }

    public function infusionSessions()
    {
        return $this->hasMany(InfusionSession::class, 'no_peg', 'no_peg');
    }
}


