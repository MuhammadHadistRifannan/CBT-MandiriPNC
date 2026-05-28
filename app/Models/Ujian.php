<?php

namespace App\Models;

use App\Enums\UjianCheckInMethod;
use App\Enums\UjianStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ujian extends Model
{
    protected $table = 'ujian';

    protected $fillable = [
        'user_id',
        'kode_ujian',
        'status',
        'progress_percentage',
        'nilai',
        'duration_minutes',
        'checked_in_at',
        'check_in_method',
        'started_at',
        'submitted_at',
        'last_activity_at',
        'pengawas_id',
        'flagged_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => UjianStatus::class,
            'progress_percentage' => 'integer',
            'nilai' => 'decimal:2',
            'duration_minutes' => 'integer',
            'checked_in_at' => 'datetime',
            'check_in_method' => UjianCheckInMethod::class,
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pengawas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(UjianActivityLog::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UjianAnswer::class);
    }
}
