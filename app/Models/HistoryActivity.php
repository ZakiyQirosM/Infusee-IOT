<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryActivity extends Model
{
    protected $table = 'history_activity';
    protected $primaryKey = 'id_hist_act';
    public $timestamps = false;

    protected $fillable = [
        'id_session', 'no_peg', 'aktivitas', 'created_at'
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'no_peg', 'no_peg');
    }

    public function session()
    {
        return $this->belongsTo(InfusionSession::class, 'id_session', 'id_session');
    }
}
