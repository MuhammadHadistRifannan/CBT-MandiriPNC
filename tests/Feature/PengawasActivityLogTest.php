<?php

namespace Tests\Feature;

use App\Enums\UjianActivityType;
use App\Enums\UjianStatus;
use App\Models\Peserta;
use App\Models\Ujian;
use App\Models\UjianActivityLog;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengawasActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_participant_activity_is_recorded_and_displayed_to_pengawas(): void
    {
        $pengawas = User::factory()->create(['role' => UserRole::Pengawas->value]);
        [$participant, $ujian] = $this->createParticipantExam(UjianStatus::InExam);

        $this->actingAs($participant)
            ->postJson(route('participant.activity.store'), [
                'event_type' => UjianActivityType::TabHidden->value,
            ])
            ->assertOk();

        $this->assertDatabaseHas('ujian_activity_logs', [
            'ujian_id' => $ujian->id,
            'user_id' => $participant->id,
            'event_type' => UjianActivityType::TabHidden->value,
        ]);

        $this->actingAs($pengawas)
            ->get(route('pengawas.activities'))
            ->assertOk()
            ->assertSee('Aktivitas Peserta');

        $this->actingAs($pengawas)
            ->getJson(route('pengawas.activities.data', [
                'user_id' => $participant->id,
                'event_type' => UjianActivityType::TabHidden->value,
            ]))
            ->assertOk()
            ->assertJsonPath('stats.tab_switches', 1)
            ->assertJsonPath('logs.0.event_type', UjianActivityType::TabHidden->value);
    }

    public function test_activity_is_rejected_when_exam_is_not_running(): void
    {
        [$participant] = $this->createParticipantExam(UjianStatus::CheckedIn);

        $this->actingAs($participant)
            ->postJson(route('participant.activity.store'), [
                'event_type' => UjianActivityType::Refresh->value,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('event_type');

        $this->assertSame(0, UjianActivityLog::query()->count());
    }

    public function test_user_cannot_access_pengawas_activity_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);

        $this->actingAs($user)
            ->get(route('pengawas.activities'))
            ->assertRedirect(route('dashboard'));
    }

    private function createParticipantExam(UjianStatus $status): array
    {
        $participant = User::factory()->create(['role' => UserRole::User->value]);

        Peserta::create([
            'user_id' => $participant->id,
            'nomor_peserta' => 'CBT-'.$participant->id,
        ]);

        $ujian = Ujian::create([
            'user_id' => $participant->id,
            'kode_ujian' => 'UJN-'.$participant->id,
            'status' => $status,
        ]);

        return [$participant, $ujian];
    }
}
