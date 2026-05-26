<?php

namespace App\Enums;

enum SoalCbtStatus: string
{
    case Draft = 'draft';
    case Preview = 'preview';
    case Released = 'released';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Preview => 'Preview',
            self::Released => 'Released',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Draft => 'bg-amber-100 text-amber-700',
            self::Preview => 'bg-blue-100 text-blue-700',
            self::Released => 'bg-emerald-100 text-emerald-700',
        };
    }
}
