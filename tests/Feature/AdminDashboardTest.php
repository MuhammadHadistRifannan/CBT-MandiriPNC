<?php

namespace Tests\Feature;

use App\Enums\BillingStatus;
use App\Enums\DokumenStatus;
use App\Models\Billings;
use App\Models\Dokumen;
use App\Models\Peserta;
use App\Models\PilihanProdi;
use App\Models\Prodi;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_displays_live_statistics_and_recent_participants(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $favorite = $this->createProdi('Teknologi Rekayasa Multimedia');
        $backup = $this->createProdi('Teknik Informatika');
        $newestUser = User::factory()->create([
            'name' => 'Peserta Terbaru',
            'role' => UserRole::User->value,
        ]);
        $otherUser = User::factory()->create(['role' => UserRole::User->value]);

        PilihanProdi::create([
            'user_id' => $newestUser->id,
            'pilihan_1' => $favorite->id,
            'pilihan_2' => $backup->id,
        ]);
        PilihanProdi::create([
            'user_id' => $otherUser->id,
            'pilihan_1' => $favorite->id,
            'pilihan_2' => $backup->id,
        ]);

        Billings::create([
            'user_id' => $newestUser->id,
            'kode_bayar' => 'PAY-SETTLED',
            'gross_amount' => 150000,
            'transaction_status' => BillingStatus::Settlement,
            'isPay' => true,
        ]);
        Billings::create([
            'user_id' => $otherUser->id,
            'kode_bayar' => 'PAY-PENDING',
            'gross_amount' => 900000,
            'transaction_status' => BillingStatus::Pending,
        ]);

        Dokumen::create([
            'user_id' => $newestUser->id,
            'foto' => 'foto.jpg',
            'kartu_identitas' => 'identitas.pdf',
            'status' => DokumenStatus::Verified,
        ]);
        Dokumen::create([
            'user_id' => $otherUser->id,
            'foto' => 'foto-lain.jpg',
            'kartu_identitas' => 'identitas-lain.pdf',
            'status' => DokumenStatus::Pending,
        ]);

        Peserta::create(['user_id' => $otherUser->id, 'nomor_peserta' => 'CBT-OLD']);
        Peserta::create(['user_id' => $newestUser->id, 'nomor_peserta' => 'CBT-NEW']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Rp150.000')
            ->assertSee('Teknologi Rekayasa Multimedia')
            ->assertSee('2 pilihan utama')
            ->assertSee('Peserta Terbaru')
            ->assertSee('CBT-NEW')
            ->assertSee('Verified');
    }

    public function test_signed_midtrans_settlement_creates_participant_and_updates_billing(): void
    {
        Config::set('services.midtrans.server_key', 'testing-server-key');

        $user = User::factory()->create(['role' => UserRole::User->value]);
        Billings::create([
            'user_id' => $user->id,
            'kode_bayar' => 'PAY-CALLBACK',
            'transaction_status' => BillingStatus::Pending,
        ]);

        $payload = [
            'order_id' => 'PAY-CALLBACK',
            'status_code' => '200',
            'gross_amount' => '250000.00',
            'transaction_status' => BillingStatus::Settlement->value,
            'payment_type' => 'bank_transfer',
            'va_numbers' => [['va_number' => '988001122']],
        ];
        $payload['signature_key'] = hash(
            'sha512',
            $payload['order_id'].$payload['status_code'].$payload['gross_amount'].'testing-server-key'
        );

        $this->postJson(route('payment.notification'), $payload)
            ->assertOk();

        $this->assertDatabaseHas('billings', [
            'kode_bayar' => 'PAY-CALLBACK',
            'transaction_status' => BillingStatus::Settlement->value,
            'gross_amount' => 250000,
            'virtual_account' => '988001122',
            'isPay' => true,
        ]);
        $this->assertDatabaseHas('peserta', ['user_id' => $user->id]);
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
