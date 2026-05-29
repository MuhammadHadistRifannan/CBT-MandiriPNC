<?php

namespace App\Enums;

enum UjianFlagStatus: string
{
    case Normal = 'normal';
    case Suspicious = 'suspicious';
    case Cheating = 'cheating';

    public function label(): string
    {
        return match ($this) {
            self::Normal => 'Normal',
            self::Suspicious => 'Mencurigakan',
            self::Cheating => 'Terindikasi Curang',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Normal => 'bg-emerald-100 text-emerald-700',
            self::Suspicious => 'bg-amber-100 text-amber-700',
            self::Cheating => 'bg-red-100 text-red-700',
        };
    }
}
