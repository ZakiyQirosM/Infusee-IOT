<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $primaryKey = 'no_reg_pasien';
    public $incrementing = false;
    protected $table = "table_pasien";
    protected $keytype = 'varchar';
    protected $fillable = ['no_reg_pasien', 'nama_pasien', 'umur','no_ruangan'];

    public function DosisInfusPasien()
    {
        return $this->hasMany(DosisInfusPasien::class,"fk_no_reg_pasien","no_reg_pasien");
    }
}
