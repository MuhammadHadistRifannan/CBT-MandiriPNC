<?php

namespace Tests\Feature;

use App\Enums\UjianFlagStatus;
use App\Enums\UjianStatus;
use App\Models\Peserta;
use App\Models\Ujian;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengawasDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengawas_dashboard_displays_exam_summary_and_quick_monitoring(): void
    {
        $pengawas = User::factory()->create(['role' => UserRole::Pengawas->value]);
        $active = $this->createParticipant('Peserta Aktif', 'CBT-AKTIF');
        $idle = $this->createParticipant('Peserta Idle', 'CBT-IDLE');
        $completed = $this->createParticipant('Peserta Selesai', 'CBT-SELESAI');

        Ujian::create([
            'user_id' => $active->id,
            'kode_ujian' => 'UJIAN-AKTIF',
            'status' => UjianStatus::InExam,
            'started_at' => now()->subMinutes(15),
            'duration_minutes' => 120,
            'progress_percentage' => 30,
        ]);
        Ujian::create([
            'user_id' => $idle->id,
            'kode_ujian' => 'UJIAN-IDLE',
            'status' => UjianStatus::Idle,
            'started_at' => now()->subMinutes(10),
            'progress_percentage' => 12,
        ]);
        Ujian::create([
            'user_id' => $completed->id,
            'kode_ujian' => 'UJIAN-SELESAI',
            'status' => UjianStatus::Submitted,
            'progress_percentage' => 100,
        ]);

        $this->actingAs($pengawas)
            ->get(route('pengawas.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Ujian')
            ->assertSee('Quick Monitoring')
            ->assertSee('Peserta Aktif')
            ->assertSee('Perlu Penanganan');

        $this->actingAs($pengawas)
            ->getJson(route('pengawas.dashboard.data'))
            ->assertOk()
            ->assertJsonPath('stats.total', 3)
            ->assertJsonPath('stats.active', 1)
            ->assertJsonPath('stats.completed', 1)
            ->assertJsonPath('stats.issues', 1);
    }

    public function test_user_cannot_access_pengawas_dashboard(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);

        $this->actingAs($user)
            ->get(route('pengawas.dashboard'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_pengawas_can_flag_participant_from_dashboard(): void
    {
        $pengawas = User::factory()->create(['role' => UserRole::Pengawas->value]);
        $participant = $this->createParticipant('Peserta Flag', 'CBT-FLAG');
        $ujian = Ujian::create([
            'user_id' => $participant->id,
            'kode_ujian' => 'UJIAN-FLAG',
            'status' => UjianStatus::InExam,
            'started_at' => now(),
            'duration_minutes' => 120,
        ]);

        $this->actingAs($pengawas)
            ->patchJson(route('pengawas.dashboard.flag', $ujian), [
                'flag_status' => UjianFlagStatus::Suspicious->value,
                'flagged_reason' => 'Sering berpindah tab.',
            ])
            ->assertOk()
            ->assertJsonPath('participant.flag_status', UjianFlagStatus::Suspicious->value)
            ->assertJsonPath('participant.flagged_reason', 'Sering berpindah tab.');

        $this->assertDatabaseHas('ujian', [
            'id' => $ujian->id,
            'flag_status' => UjianFlagStatus::Suspicious->value,
            'flagged_reason' => 'Sering berpindah tab.',
        ]);

        $this->actingAs($pengawas)
            ->getJson(route('pengawas.dashboard.data'))
            ->assertOk()
            ->assertJsonPath('stats.flagged', 1)
            ->assertJsonPath('monitoring.0.flag_status', UjianFlagStatus::Suspicious->value);
    }

    public function test_pengawas_can_control_participant_exam_timer(): void
    {
        $pengawas = User::factory()->create(['role' => UserRole::Pengawas->value]);
        $participant = $this->createParticipant('Peserta Timer', 'CBT-TIMER');
        $ujian = Ujian::create([
            'user_id' => $participant->id,
            'kode_ujian' => 'UJIAN-TIMER',
            'status' => UjianStatus::CheckedIn,
            'duration_minutes' => 120,
        ]);

        $this->actingAs($pengawas)
            ->patchJson(route('pengawas.dashboard.timer', $ujian), [
                'action' => 'start',
            ])
            ->assertOk()
            ->assertJsonPath('participant.status', UjianStatus::InExam->value);

        $ujian->refresh();
        $this->assertSame(UjianStatus::InExam, $ujian->status);
        $this->assertNotNull($ujian->started_at);

        $this->actingAs($pengawas)
            ->patchJson(route('pengawas.dashboard.timer', $ujian), [
                'action' => 'extend',
                'minutes' => 15,
            ])
            ->assertOk()
            ->assertJsonPath('participant.duration_minutes', 135);

        $this->actingAs($pengawas)
            ->patchJson(route('pengawas.dashboard.timer', $ujian), [
                'action' => 'stop',
            ])
            ->assertOk()
            ->assertJsonPath('participant.remaining', '00:00');

        $this->assertLessThanOrEqual(1, $ujian->refresh()->duration_minutes);
    }

    public function test_pengawas_login_is_redirected_to_pengawas_dashboard(): void
    {
        $pengawas = User::factory()->create([
            'role' => UserRole::Pengawas->value,
            'password' => bcrypt('password'),
        ]);

        $this->post(route('login'), [
            'email' => $pengawas->email,
            'password' => 'password',
        ])->assertRedirect(route('pengawas.dashboard'));
    }

    private function createParticipant(string $name, string $number): User
    {
        $user = User::factory()->create([
            'name' => $name,
            'role' => UserRole::User->value,
        ]);

        Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => $number,
        ]);

        return $user;
    }
}
