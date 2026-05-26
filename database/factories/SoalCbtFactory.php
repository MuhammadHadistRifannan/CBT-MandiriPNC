<?php

namespace Database\Factories;

use App\Enums\SoalCbtSource;
use App\Enums\SoalCbtStatus;
use App\Models\SoalCbt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SoalCbt>
 */
class SoalCbtFactory extends Factory
{
    protected $model = SoalCbt::class;

    public function definition(): array
    {
        $subSoal = fake()->randomElement(SoalCbt::SUB_SOAL);

        return [
            'kode_soal' => SoalCbt::nextKodeSoal($subSoal) . '-' . fake()->unique()->numberBetween(100, 999),
            'sub_soal' => $subSoal,
            'pertanyaan' => fake()->sentence(12),
            'opsi_a' => fake()->sentence(4),
            'opsi_b' => fake()->sentence(4),
            'opsi_c' => fake()->sentence(4),
            'opsi_d' => fake()->sentence(4),
            'opsi_e' => null,
            'jawaban_benar' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'pembahasan' => fake()->sentence(10),
            'status' => SoalCbtStatus::Draft,
            'source_type' => SoalCbtSource::Manual,
            'source_file' => null,
            'created_by' => User::factory()->state(['role' => 'admin']),
        ];
    }
}
