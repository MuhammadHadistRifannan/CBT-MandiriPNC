<?php

namespace App\Models;

use App\Enums\AnnouncementResultStatus;
use App\Enums\AnnouncementStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'user_id',
        'announcement_batch_id',
        'nomor_peserta',
        'status_hasil',
        'prodi_diterima',
        'jalur_seleksi',
        'announcement_status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status_hasil' => AnnouncementResultStatus::class,
            'announcement_status' => AnnouncementStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(AnnouncementBatch::class, 'announcement_batch_id');
    }

    public function prodiDiterima(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_diterima');
    }
}
