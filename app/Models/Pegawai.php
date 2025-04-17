<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

// app/Models/Pegawai.php
class Pegawai extends Authenticatable
{
    protected $table = 'table_pegawai';
    protected $fillable = ['nama_peg', 
    'no_peg', 
    'password', 
    'no_wa'];
    protected $hidden = ['password'];
}


