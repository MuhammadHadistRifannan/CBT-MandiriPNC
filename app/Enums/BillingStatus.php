<?php

namespace App\Enums;

enum BillingStatus: string
{
    case Pending = 'pending';
    case Settlement = 'settlement';
    case Expire = 'expire';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Settlement => 'Lunas',
            self::Expire => 'Kedaluwarsa',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-100 text-amber-700',
            self::Settlement => 'bg-emerald-100 text-emerald-700',
            self::Expire => 'bg-red-100 text-red-700',
        };
    }
}
