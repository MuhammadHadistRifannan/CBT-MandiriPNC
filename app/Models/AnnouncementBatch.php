<?php

namespace App\Models;

use App\Enums\AnnouncementStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnnouncementBatch extends Model
{
    protected $fillable = [
        'title',
        'tahun',
        'announcement_date',
        'status',
        'ranking_locked',
        'generated_at',
        'created_by',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'announcement_date' => 'datetime',
            'status' => AnnouncementStatus::class,
            'ranking_locked' => 'boolean',
            'generated_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(AnnouncementResult::class);
    }

    public function isOpen(): bool
    {
        return $this->status === AnnouncementStatus::Published
            && $this->announcement_date
            && $this->announcement_date->timezone('Asia/Jakarta')->lessThanOrEqualTo(now('Asia/Jakarta'));
    }
}
