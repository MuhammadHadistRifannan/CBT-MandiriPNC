<?php

namespace Tests\Feature;

use App\Enums\BillingStatus;
use App\Enums\UjianCheckInMethod;
use App\Enums\UjianStatus;
use App\Models\Billings;
use App\Models\Peserta;
use App\Models\PilihanProdi;
use App\Models\Prodi;
use App\Models\Ujian;
use App\Models\User;
use App\Services\UjianQrService;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengawasCheckInTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_pengawas_cannot_access_pengawas_check_in(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);

        $this->actingAs($user)
            ->get(route('pengawas.check-in'))
            ->assertRedirect(route('dashboard'));

        $this->actingAs($admin)
            ->get(route('pengawas.check-in'))
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_pengawas_can_lookup_signed_qr_and_confirm_check_in(): void
    {
        $pengawas = User::factory()->create(['role' => UserRole::Pengawas->value]);
        $ujian = $this->createEligibleExam();
        $payload = app(UjianQrService::class)->payload($ujian);

        $this->actingAs($pengawas)
            ->get(route('pengawas.check-in'))
            ->assertOk()
            ->assertSee('Scan Kartu Ujian');

        $this->actingAs($pengawas)
            ->postJson(route('pengawas.check-in.lookup'), [
                'method' => UjianCheckInMethod::Qr->value,
                'qr_payload' => $payload,
            ])
            ->assertOk()
            ->assertJsonPath('participant.exam_code', $ujian->kode_ujian);

        $this->actingAs($pengawas)
            ->postJson(route('pengawas.check-in.confirm', $ujian), [
                'method' => UjianCheckInMethod::Qr->value,
                'qr_payload' => $payload,
            ])
            ->assertOk()
            ->assertJsonPath('participant.status', UjianStatus::CheckedIn->value);

        $this->assertDatabaseHas('ujian', [
            'id' => $ujian->id,
            'status' => UjianStatus::CheckedIn->value,
            'check_in_method' => UjianCheckInMethod::Qr->value,
            'pengawas_id' => $pengawas->id,
        ]);

        $this->actingAs($pengawas)
            ->postJson(route('pengawas.check-in.confirm', $ujian), [
                'method' => UjianCheckInMethod::Qr->value,
                'qr_payload' => $payload,
            ])
            ->assertUnprocessable();
    }

    public function test_invalid_signed_qr_and_unpaid_manual_check_in_are_rejected(): void
    {
        $pengawas = User::factory()->create(['role' => UserRole::Pengawas->value]);
        $eligible = $this->createEligibleExam();
        $unpaid = $this->createEligibleExam(BillingStatus::Pending);

        $this->actingAs($pengawas)
            ->postJson(route('pengawas.check-in.lookup'), [
                'method' => UjianCheckInMethod::Qr->value,
                'qr_payload' => $eligible->kode_ujian.'.invalid-signature',
            ])
            ->assertUnprocessable();

        $this->actingAs($pengawas)
            ->postJson(route('pengawas.check-in.lookup'), [
                'method' => UjianCheckInMethod::Manual->value,
                'kode_ujian' => $unpaid->kode_ujian,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('kode_ujian');
    }

    public function test_portal_ujian_is_ready_only_after_check_in(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);
        $ujian = $this->createEligibleExam(BillingStatus::Settlement, $user);

        $this->actingAs($user)
            ->get(route('portal.ujian'))
            ->assertOk()
            ->assertSee('Menunggu Verifikasi')
            ->assertDontSee('Sistem Siap');

        $ujian->update(['status' => UjianStatus::CheckedIn]);

        $this->actingAs($user)
            ->get(route('portal.ujian'))
            ->assertOk()
            ->assertSee('Sistem Siap');
    }

    public function test_backfill_command_creates_missing_exam_sessions_idempotently(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);

        Billings::create([
            'user_id' => $user->id,
            'kode_bayar' => 'PAY-BACKFILL',
            'transaction_status' => BillingStatus::Settlement,
            'isPay' => true,
        ]);

        $this->artisan('ujian:backfill-sessions --dry-run')
            ->expectsOutput('1 sesi ujian akan dibuat.')
            ->assertSuccessful();

        $this->artisan('ujian:backfill-sessions')->assertSuccessful();
        $this->artisan('ujian:backfill-sessions')->assertSuccessful();

        $this->assertSame(1, Ujian::query()->where('user_id', $user->id)->count());
    }

    private function createEligibleExam(
        BillingStatus $billingStatus = BillingStatus::Settlement,
        ?User $user = null
    ): Ujian {
        $user ??= User::factory()->create(['role' => UserRole::User->value]);
        $first = $this->createProdi('Teknik Informatika '.$user->id);
        $second = $this->createProdi('Teknologi Rekayasa '.$user->id);

        PilihanProdi::create([
            'user_id' => $user->id,
            'pilihan_1' => $first->id,
            'pilihan_2' => $second->id,
        ]);
        Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => 'CBT-'.$user->id,
        ]);
        Billings::create([
            'user_id' => $user->id,
            'kode_bayar' => 'PAY-'.$user->id,
            'transaction_status' => $billingStatus,
            'isPay' => $billingStatus === BillingStatus::Settlement,
        ]);

        return Ujian::create([
            'user_id' => $user->id,
            'kode_ujian' => 'UJIAN-'.$user->id,
            'status' => UjianStatus::NotCheckedIn,
        ]);
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
