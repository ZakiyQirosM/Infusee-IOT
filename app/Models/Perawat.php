<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perawat extends Model
{
    protected $table = "table_perawat";
    protected $fillable = [
        "no_pegawai",
        "nama_perawat"
    ];
    protected $primaryKey = "no_pegawai";
    public $incrementing = false;
    public function DosisInfusPasien()
    {
        return $this->hasMany(DosisInfusPasien::class,"fk_no_pegawai","no_pegawai");
    }
}
