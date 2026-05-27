<?php

namespace App;

enum UserRole : string
{
    case Admin = 'admin';
    case User = 'user';
    case Pengawas = 'pengawas';

    public function dashboardRouteName(): string
    {
        return match ($this) {
            self::Admin => 'admin.dashboard',
            self::Pengawas => 'pengawas.dashboard',
            self::User => 'dashboard',
        };
    }
}
