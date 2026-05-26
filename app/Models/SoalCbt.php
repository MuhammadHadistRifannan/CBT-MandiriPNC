<?php

namespace App\Models;

use App\Enums\SoalCbtSource;
use App\Enums\SoalCbtStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable([
    'kode_soal',
    'sub_soal',
    'pertanyaan',
    'opsi_a',
    'opsi_b',
    'opsi_c',
    'opsi_d',
    'opsi_e',
    'jawaban_benar',
    'pembahasan',
    'status',
    'source_type',
    'source_file',
    'created_by',
    'reviewed_at',
    'released_at',
])]
class SoalCbt extends Model
{
    use HasFactory;

    protected $table = 'soal_cbt';

    public const SUB_SOAL = ['PM', 'PBI', 'PU', 'PPU'];

    public const JAWABAN = ['A', 'B', 'C', 'D', 'E'];

    protected function casts(): array
    {
        return [
            'status' => SoalCbtStatus::class,
            'source_type' => SoalCbtSource::class,
            'reviewed_at' => 'datetime',
            'released_at' => 'datetime',
        ];
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function nextKodeSoal(string $subSoal): string
    {
        $date = Carbon::now()->format('Ymd');
        $count = self::where('sub_soal', $subSoal)
            ->whereDate('created_at', Carbon::today())
            ->count() + 1;

        return sprintf('%s-%s-%04d', $subSoal, $date, $count);
    }

    public function isReadyToRelease(): bool
    {
        $required = [
            $this->pertanyaan,
            $this->opsi_a,
            $this->opsi_b,
            $this->opsi_c,
            $this->opsi_d,
            $this->jawaban_benar,
        ];

        return collect($required)->every(fn ($value) => filled($value))
            && in_array($this->jawaban_benar, self::JAWABAN, true)
            && ($this->jawaban_benar !== 'E' || filled($this->opsi_e));
    }
}
