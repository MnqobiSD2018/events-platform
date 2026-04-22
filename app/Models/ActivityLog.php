<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity_date',
        'workout_type',
        'steps',
        'runs',
        'distance_km',
        'duration_minutes',
        'source',
        'provider',
        'notes',
        'raw_payload',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'distance_km' => 'decimal:2',
            'raw_payload' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
