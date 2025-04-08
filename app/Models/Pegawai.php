<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pegawai extends Authenticatable
{
    protected $table = 'table_pegawai';
    protected $fillable = ['nama_peg', 'no_peg', 'password'];
    protected $hidden = ['password'];
}

