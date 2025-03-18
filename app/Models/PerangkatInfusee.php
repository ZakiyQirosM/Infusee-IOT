<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatInfusee extends Model
{
    protected $table = "table_perangkat_infusee";
    protected $primaryKey = "id_perangkat_infuse";
    public $incrementing = false;
    protected $fillable = ['id_perangkat_infusee','alamat_api_infusee'];

    public function DosisInfusPasien()
    {
        return $this->hasMany(DosisInfusPasien::class,"fk_id_perangkat_infusee","id_perangkat_infusee");
    }
}
