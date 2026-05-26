<?php

namespace App\Enums;

enum SoalCbtSource: string
{
    case Manual = 'manual';
    case Pdf = 'pdf';

    public function label(): string
    {
        return match ($this) {
            self::Manual => 'Manual',
            self::Pdf => 'PDF',
        };
    }
}
