<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['name', 'room_number', 'device_id'];

    public function infusionSessions()
    {
        return $this->hasMany(InfusionSession::class);
    }
}
