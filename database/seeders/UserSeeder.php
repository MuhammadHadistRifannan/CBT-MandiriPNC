<?php

namespace Database\Seeders;

use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Administrator CBT',
                'email' => 'admin@pnc.ac.id',
                'role' => UserRole::Admin,
            ],
            [
                'name' => 'Pengawas CBT',
                'email' => 'pengawas@pnc.ac.id',
                'role' => UserRole::Pengawas,
            ],
            [
                'name' => 'Peserta Demo',
                'email' => 'peserta@pnc.ac.id',
                'role' => UserRole::User,
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'role' => $account['role']->value,
                    'password' => Hash::make('password'),
                ]
            );

            $user->forceFill(['email_verified_at' => now()])->save();
        }
    }
}
