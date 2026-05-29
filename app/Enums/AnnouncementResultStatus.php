<?php

namespace App\Enums;

enum AnnouncementResultStatus: string
{
    case Lulus = 'lulus';
    case TidakLulus = 'tidak_lulus';
    case Cadangan = 'cadangan';

    public function label(): string
    {
        return match ($this) {
            self::Lulus => 'LULUS',
            self::TidakLulus => 'TIDAK LULUS',
            self::Cadangan => 'CADANGAN',
        };
    }

    public function cardClass(): string
    {
        return match ($this) {
            self::Lulus => 'border-emerald-200 bg-emerald-50 text-emerald-800',
            self::TidakLulus => 'border-slate-200 bg-slate-50 text-slate-700',
            self::Cadangan => 'border-amber-200 bg-amber-50 text-amber-800',
        };
    }
}
