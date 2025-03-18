<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfusionSession extends Model
{
    protected $fillable = ['patient_id', 'current_volume', 'total_volume', 'drop_rate', 'remaining_percentage'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
