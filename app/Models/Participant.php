<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    protected $fillable = ['name', 'email', 'department', 'qr_code', 'event_id'];
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }
}
