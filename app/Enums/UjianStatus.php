<?php

namespace App\Enums;

enum UjianStatus: string
{
    case NotCheckedIn = 'not_checked_in';
    case CheckedIn = 'checked_in';
    case InExam = 'in_exam';
    case Idle = 'idle';
    case Submitted = 'submitted';
    case Blocked = 'blocked';

    public function label(): string
    {
        return match ($this) {
            self::NotCheckedIn => 'Belum Mulai',
            self::CheckedIn => 'Check-in',
            self::InExam => 'Sedang Ujian',
            self::Idle => 'Idle',
            self::Submitted => 'Selesai',
            self::Blocked => 'Blocked',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::NotCheckedIn => 'bg-slate-100 text-slate-600',
            self::CheckedIn => 'bg-blue-100 text-blue-700',
            self::InExam => 'bg-emerald-100 text-emerald-700',
            self::Idle => 'bg-amber-100 text-amber-700',
            self::Submitted => 'bg-sky-100 text-sky-700',
            self::Blocked => 'bg-red-100 text-red-700',
        };
    }
}
