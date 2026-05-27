<?php

namespace App\Models;

use App\Enums\UjianActivityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianActivityLog extends Model
{
    protected $fillable = [
        'ujian_id',
        'user_id',
        'event_type',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'event_type' => UjianActivityType::class,
            'occurred_at' => 'datetime',
        ];
    }

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
