<?php

namespace Tests\Feature;

use App\Enums\UjianStatus;
use App\Models\PilihanProdi;
use App\Models\Prodi;
use App\Models\Ujian;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesertaMulaiUjianTest extends TestCase
{
    use RefreshDatabase;

    public function test_checked_in_participant_can_start_exam(): void
    {
        [$user, $ujian] = $this->createParticipantExam(UjianStatus::CheckedIn);

        $this->actingAs($user)
            ->get(route('portal.ujian'))
            ->assertOk()
            ->assertSee('Sistem Siap')
            ->assertSee('Mulai Ujian');

        $this->actingAs($user)
            ->post(route('ujian.start'), ['agree' => '1'])
            ->assertRedirect(route('portal.ujian'));

        $ujian->refresh();

        $this->assertSame(UjianStatus::InExam, $ujian->status);
        $this->assertNotNull($ujian->started_at);
        $this->assertNotNull($ujian->last_activity_at);

        $this->actingAs($user)
            ->get(route('portal.ujian'))
            ->assertOk()
            ->assertSee('Ujian Sedang Berjalan')
            ->assertSee('Timer server aktif');
    }

    public function test_participant_must_agree_before_starting_exam(): void
    {
        [$user, $ujian] = $this->createParticipantExam(UjianStatus::CheckedIn);

        $this->actingAs($user)
            ->postJson(route('ujian.start'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('agree');

        $this->assertSame(UjianStatus::CheckedIn, $ujian->refresh()->status);
        $this->assertNull($ujian->started_at);
    }

    public function test_not_checked_in_participant_cannot_start_exam(): void
    {
        [$user, $ujian] = $this->createParticipantExam(UjianStatus::NotCheckedIn);

        $this->actingAs($user)
            ->postJson(route('ujian.start'), ['agree' => '1'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('ujian');

        $this->assertSame(UjianStatus::NotCheckedIn, $ujian->refresh()->status);
        $this->assertNull($ujian->started_at);
    }

    public function test_started_exam_is_idempotent_and_cannot_reset_timer(): void
    {
        [$user, $ujian] = $this->createParticipantExam(UjianStatus::InExam, [
            'started_at' => now()->subMinutes(10),
        ]);
        $startedAt = $ujian->started_at->copy();

        $this->actingAs($user)
            ->post(route('ujian.start'), ['agree' => '1'])
            ->assertRedirect(route('portal.ujian'));

        $this->assertTrue($startedAt->equalTo($ujian->refresh()->started_at));
    }

    public function test_blocked_or_submitted_exam_cannot_be_started(): void
    {
        [$blockedUser] = $this->createParticipantExam(UjianStatus::Blocked);
        [$submittedUser] = $this->createParticipantExam(UjianStatus::Submitted);

        $this->actingAs($blockedUser)
            ->postJson(route('ujian.start'), ['agree' => '1'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('ujian');

        $this->actingAs($submittedUser)
            ->postJson(route('ujian.start'), ['agree' => '1'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('ujian');
    }

    private function createParticipantExam(UjianStatus $status, array $ujianOverrides = []): array
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
            'kode_ujian' => 'START-'.$user->id,
            'status' => $status,
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
