<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackerConnection extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'status',
        'external_user_id',
        'last_synced_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'last_synced_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
