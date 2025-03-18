<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosisInfusPasien extends Model
{
    protected $table = 'table_dosis_infus_pasien';
    protected $fillable = ['fk_no_reg_pasien', 'fk_no_pegawai', 'fk_id_perangkat_infusee', 'dosis_infus','laju_tetes_tpm','persentase_infus_menit','status_anomali_infus','durasi_infus_menit','timestamp_infus'];

    public function Pasien()
    {
        return $this->belongsTo(Pasien::class,'fk_no_reg_pasien',"no_reg_pasien");
    }
    public function PerangkatInfusee()
    {
        return $this->belongsTo(PerangkatInfusee::class,'fk_id_perangkat_infusee',"id_perangkat_infusee");
    }
    public function Perawat()
    {
        return $this->belongsTo(Perawat::class,"fk_no_pegawai","no_pegawai");
    }
} 

