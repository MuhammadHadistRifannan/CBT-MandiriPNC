<?php

namespace Tests\Feature;

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
