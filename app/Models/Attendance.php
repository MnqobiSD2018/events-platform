<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['participant_id', 'event_id', 'checked_in_at'];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];
    
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
