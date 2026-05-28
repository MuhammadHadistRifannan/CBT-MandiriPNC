<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianAnswer extends Model
{
    protected $fillable = [
        'ujian_id',
        'user_id',
        'soal_id',
        'jawaban',
        'answered_at',
    ];

    protected function casts(): array
    {
        return [
            'answered_at' => 'datetime',
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

    public function soal(): BelongsTo
    {
        return $this->belongsTo(SoalCbt::class, 'soal_id');
    }
}
