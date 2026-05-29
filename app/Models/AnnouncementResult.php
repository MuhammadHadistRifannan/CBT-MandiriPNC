<?php

namespace App\Models;

use App\Enums\AnnouncementResultStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementResult extends Model
{
    protected $fillable = [
        'announcement_batch_id',
        'user_id',
        'nomor_peserta',
        'skor_akhir',
        'pilihan_1_id',
        'pilihan_2_id',
        'prodi_diterima_id',
        'status_hasil',
        'ranking_position',
    ];

    protected function casts(): array
    {
        return [
            'skor_akhir' => 'decimal:2',
            'status_hasil' => AnnouncementResultStatus::class,
            'ranking_position' => 'integer',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(AnnouncementBatch::class, 'announcement_batch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pilihanPertama(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'pilihan_1_id');
    }

    public function pilihanKedua(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'pilihan_2_id');
    }

    public function prodiDiterima(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_diterima_id');
    }
}
