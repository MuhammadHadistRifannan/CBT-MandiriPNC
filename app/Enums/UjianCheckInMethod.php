<?php

namespace App\Enums;

enum UjianCheckInMethod: string
{
    case Qr = 'qr';
    case Manual = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::Qr => 'Scan QR',
            self::Manual => 'Input Manual',
        };
    }
}
