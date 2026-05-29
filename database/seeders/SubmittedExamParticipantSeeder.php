<?php

namespace Database\Seeders;

use App\Enums\UjianFlagStatus;
use App\Enums\UjianStatus;
use App\Models\Peserta;
use App\Models\PilihanProdi;
use App\Models\Prodi;
use App\Models\Ujian;
use App\Models\User;
use App\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SubmittedExamParticipantSeeder extends Seeder
{
    private const PARTICIPANT_COUNT = 100;

    public function run(): void
    {
        $prodis = $this->ensureProdis();
        $baseSubmittedAt = Carbon::create(2026, 5, 29, 8, 0, 0, 'Asia/Jakarta');

        for ($number = 1; $number <= self::PARTICIPANT_COUNT; $number++) {
            $user = User::updateOrCreate(
                ['email' => sprintf('ranking%03d@pnc.test', $number)],
                [
                    'name' => sprintf('Peserta Ranking %03d', $number),
                    'role' => UserRole::User->value,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now('Asia/Jakarta'),
                ]
            );

            Peserta::updateOrCreate(
                ['user_id' => $user->id],
                ['nomor_peserta' => sprintf('CBT-RANK-%04d', $number)]
            );

            $firstChoice = $prodis[($number - 1) % $prodis->count()];
            $secondChoice = $prodis[$number % $prodis->count()];

            PilihanProdi::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'pilihan_1' => $firstChoice->id,
                    'pilihan_2' => $secondChoice->id,
                    'is_verified' => true,
                ]
            );

            $score = $this->scoreFor($number);
            $submittedAt = $baseSubmittedAt->copy()->addMinutes($number);

            Ujian::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'kode_ujian' => sprintf('UJI-RANK-%04d', $number),
                    'status' => UjianStatus::Submitted->value,
                    'flag_status' => UjianFlagStatus::Normal->value,
                    'progress_percentage' => 100,
                    'nilai' => $score,
                    'duration_minutes' => 120,
                    'started_at' => $submittedAt->copy()->subMinutes(90),
                    'submitted_at' => $submittedAt,
                    'last_activity_at' => $submittedAt,
                ]
            );
        }
    }

    private function ensureProdis()
    {
        $seedProdis = collect([
            ['nama_prodi' => 'Teknik Informatika', 'tingkat' => 'd4', 'jurusan' => 'Komputer dan Bisnis', 'kuota' => 12],
            ['nama_prodi' => 'Rekayasa Keamanan Siber', 'tingkat' => 'd4', 'jurusan' => 'Komputer dan Bisnis', 'kuota' => 10],
            ['nama_prodi' => 'Teknik Mesin', 'tingkat' => 'd3', 'jurusan' => 'Teknik Mesin', 'kuota' => 10],
            ['nama_prodi' => 'Teknik Listrik', 'tingkat' => 'd3', 'jurusan' => 'Teknik Elektro', 'kuota' => 8],
            ['nama_prodi' => 'Akuntansi', 'tingkat' => 'd3', 'jurusan' => 'Akuntansi', 'kuota' => 8],
        ]);

        $seedProdis->each(function (array $data): void {
            Prodi::updateOrCreate(
                ['nama_prodi' => $data['nama_prodi']],
                [
                    'tingkat' => $data['tingkat'],
                    'jurusan' => $data['jurusan'],
                    'peminat' => 100,
                    'daya_tampung' => max($data['kuota'], 20),
                    'kuota' => $data['kuota'],
                    'keketatan' => 0.50,
                ]
            );
        });

        return Prodi::query()
            ->whereIn('nama_prodi', $seedProdis->pluck('nama_prodi'))
            ->orderBy('id')
            ->get();
    }

    private function scoreFor(int $number): float
    {
        $score = 98 - (($number - 1) % 54);

        if ($number % 10 === 0) {
            return 85.00;
        }

        if ($number % 15 === 0) {
            return 78.00;
        }

        return (float) max(45, $score);
    }
}
