<?php

namespace App\Enums;

enum DokumenStatus: string
{
    case Pending = 'pending';
    case Verified = 'verified';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Verified => 'Verified',
            self::Rejected => 'Rejected',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-100 text-amber-700',
            self::Verified => 'bg-blue-100 text-blue-700',
            self::Rejected => 'bg-red-100 text-red-700',
        };
    }
}
