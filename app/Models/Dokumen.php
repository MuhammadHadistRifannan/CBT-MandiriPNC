<?php

namespace App\Models;

use App\Enums\DokumenStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    protected $table = 'dokumens';

    protected $fillable = [
        'user_id',
        'kartu_identitas',
        'foto',
        'suket',
        'ijazah',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_note',
    ];

    protected function casts(): array
    {
        return [
            'status' => DokumenStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function availableFiles(): array
    {
        return array_filter([
            'Pas Foto' => $this->foto,
            'Kartu Identitas' => $this->kartu_identitas,
            'Surat Keterangan' => $this->suket,
            'Ijazah' => $this->ijazah,
        ]);
    }
}
