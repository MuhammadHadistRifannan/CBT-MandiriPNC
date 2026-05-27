<?php

namespace Database\Seeders;

use App\Models\SoalCbt;
use App\Models\User;
use App\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);

        $admin = User::query()
            ->where('role', UserRole::Admin->value)
            ->where('email', 'admin@pnc.ac.id')
            ->firstOrFail();

        if (SoalCbt::query()->doesntExist()) {
            SoalCbt::factory(10)->create([
                'created_by' => $admin->id,
            ]);
        }
    }
}
