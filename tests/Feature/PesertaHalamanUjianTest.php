<?php

namespace Tests\Feature;

use App\Enums\SoalCbtStatus;
use App\Enums\UjianStatus;
use App\Models\PilihanProdi;
use App\Models\Prodi;
use App\Models\SoalCbt;
use App\Models\Ujian;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesertaHalamanUjianTest extends TestCase
{
    use RefreshDatabase;

    public function test_participant_can_open_running_exam_page_and_save_answer(): void
    {
        [$user, $ujian] = $this->createRunningExam();
        $question = SoalCbt::factory()->create(['status' => SoalCbtStatus::Released]);

        $this->actingAs($user)
            ->get(route('ujian.show'))
            ->assertOk()
            ->assertSee('Halaman Ujian')
            ->assertSee($question->kode_soal);

        $this->actingAs($user)
            ->postJson(route('ujian.answers.store'), [
                'soal_id' => $question->id,
                'jawaban' => 'B',
            ])
            ->assertOk()
            ->assertJsonPath('answered_count', 1)
            ->assertJsonPath('progress_percentage', 100);

        $this->assertDatabaseHas('ujian_answers', [
            'ujian_id' => $ujian->id,
            'user_id' => $user->id,
            'soal_id' => $question->id,
            'jawaban' => 'B',
        ]);

        $this->actingAs($user)
            ->getJson(route('ujian.status'))
            ->assertOk()
            ->assertJsonPath('total_soal', 1)
            ->assertJsonPath('answered_count', 1)
            ->assertJsonPath('status', UjianStatus::InExam->value)
            ->assertJsonPath('submitted', false);
    }

    public function test_status_endpoint_shows_submitted_exam_progress(): void
    {
        [$user] = $this->createRunningExam(UjianStatus::Submitted);
        SoalCbt::factory()->count(2)->create(['status' => SoalCbtStatus::Released]);

        $this->actingAs($user)
            ->getJson(route('ujian.status'))
            ->assertOk()
            ->assertJsonPath('total_soal', 2)
            ->assertJsonPath('answered_count', 0)
            ->assertJsonPath('remaining_time', 0)
            ->assertJsonPath('status', UjianStatus::Submitted->value)
            ->assertJsonPath('submitted', true);
    }

    public function test_participant_can_submit_exam_and_answers_are_locked(): void
    {
        [$user, $ujian] = $this->createRunningExam();
        $correct = SoalCbt::factory()->create([
            'status' => SoalCbtStatus::Released,
            'jawaban_benar' => 'B',
        ]);
        $wrong = SoalCbt::factory()->create([
            'status' => SoalCbtStatus::Released,
            'jawaban_benar' => 'D',
        ]);

        $this->actingAs($user)->postJson(route('ujian.answers.store'), [
            'soal_id' => $correct->id,
            'jawaban' => 'B',
        ])->assertOk();

        $this->actingAs($user)->postJson(route('ujian.answers.store'), [
            'soal_id' => $wrong->id,
            'jawaban' => 'A',
        ])->assertOk();

        $this->actingAs($user)
            ->postJson(route('ujian.submit'), ['submit_type' => 'manual'])
            ->assertOk()
            ->assertJsonPath('status', UjianStatus::Submitted->value)
            ->assertJsonPath('submitted', true)
            ->assertJsonPath('nilai', '50.00');

        $ujian->refresh();
        $this->assertSame(UjianStatus::Submitted, $ujian->status);
        $this->assertNotNull($ujian->submitted_at);
        $this->assertSame('50.00', $ujian->nilai);

        $this->actingAs($user)
            ->postJson(route('ujian.answers.store'), [
                'soal_id' => $correct->id,
                'jawaban' => 'C',
            ])
            ->assertUnprocessable();
    }

    public function test_time_expired_status_auto_submits_exam(): void
    {
        [$user, $ujian] = $this->createRunningExam(UjianStatus::InExam, [
            'started_at' => now()->subMinutes(121),
            'duration_minutes' => 120,
        ]);
        SoalCbt::factory()->create(['status' => SoalCbtStatus::Released]);

        $this->actingAs($user)
            ->getJson(route('ujian.status'))
            ->assertOk()
            ->assertJsonPath('status', UjianStatus::Submitted->value)
            ->assertJsonPath('submitted', true)
            ->assertJsonPath('submit_type', 'auto');

        $this->assertSame(UjianStatus::Submitted, $ujian->refresh()->status);
    }

    public function test_exam_page_is_locked_before_exam_started(): void
    {
        [$user] = $this->createRunningExam(UjianStatus::CheckedIn);

        $this->actingAs($user)
            ->get(route('ujian.show'))
            ->assertRedirect(route('portal.ujian'));
    }

    public function test_unreleased_question_cannot_be_answered(): void
    {
        [$user] = $this->createRunningExam();
        $draft = SoalCbt::factory()->create(['status' => SoalCbtStatus::Draft]);

        $this->actingAs($user)
            ->postJson(route('ujian.answers.store'), [
                'soal_id' => $draft->id,
                'jawaban' => 'A',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('soal_id');
    }

    private function createRunningExam(UjianStatus $status = UjianStatus::InExam, array $ujianOverrides = []): array
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);
        $first = $this->createProdi('Teknik Informatika '.$user->id);
        $second = $this->createProdi('Teknologi Rekayasa '.$user->id);

        PilihanProdi::create([
            'user_id' => $user->id,
            'pilihan_1' => $first->id,
            'pilihan_2' => $second->id,
        ]);

        $ujian = Ujian::create(array_merge([
            'user_id' => $user->id,
            'kode_ujian' => 'EXAM-'.$user->id,
            'status' => $status,
            'started_at' => $status === UjianStatus::InExam ? now() : null,
            'duration_minutes' => 120,
        ], $ujianOverrides));

        return [$user, $ujian];
    }

    private function createProdi(string $name): Prodi
    {
        return Prodi::create([
            'nama_prodi' => $name,
            'tingkat' => 'd4',
            'jurusan' => 'Komputer dan Bisnis',
            'peminat' => 0,
            'daya_tampung' => 100,
            'keketatan' => 0.42,
        ]);
    }
}
