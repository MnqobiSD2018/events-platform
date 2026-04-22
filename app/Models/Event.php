<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['name', 'description', 'type', 'event_date', 'user_id'];

    protected $casts = [
        'event_date' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
}
