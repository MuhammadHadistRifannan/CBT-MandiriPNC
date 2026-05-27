<?php

namespace App\Enums;

enum UjianActivityType: string
{
    case TabHidden = 'tab_hidden';
    case TabVisible = 'tab_visible';
    case WindowBlur = 'window_blur';
    case WindowFocus = 'window_focus';
    case Idle = 'idle';
    case Refresh = 'refresh';

    public function label(): string
    {
        return match ($this) {
            self::TabHidden => 'Pindah Tab',
            self::TabVisible => 'Kembali ke Tab',
            self::WindowBlur => 'Jendela Tidak Aktif',
            self::WindowFocus => 'Jendela Aktif',
            self::Idle => 'Tidak Aktif',
            self::Refresh => 'Refresh Halaman',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::TabHidden, self::WindowBlur => 'bg-red-100 text-red-700',
            self::Idle => 'bg-amber-100 text-amber-700',
            self::Refresh => 'bg-orange-100 text-orange-700',
            self::TabVisible, self::WindowFocus => 'bg-emerald-100 text-emerald-700',
        };
    }
}
