<?php

namespace Tests\Feature;

use App\Enums\BillingStatus;
use App\Enums\DokumenStatus;
use App\Enums\UjianStatus;
use App\Models\Billings;
use App\Models\Dokumen;
use App\Models\Peserta;
use App\Models\PilihanProdi;
use App\Models\Prodi;
use App\Models\User;
use App\Models\Ujian;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CetakIdentitasAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_cetak_identitas_is_locked_until_payment_is_settled(): void
    {
        $user = $this->createUserWithRegistration(BillingStatus::Pending, DokumenStatus::Verified);

        $this->actingAs($user)
            ->get(route('cetak.identitas'))
            ->assertOk()
            ->assertSee('Akses Terkunci')
            ->assertSee('Pembayaran sudah berhasil')
            ->assertDontSee('Preview Kartu Ujian');
    }

    public function test_cetak_identitas_is_locked_when_documents_are_not_complete_and_verified(): void
    {
        $user = $this->createUserWithRegistration(BillingStatus::Settlement, DokumenStatus::Pending, false);

        $this->actingAs($user)
            ->get(route('cetak.identitas'))
            ->assertOk()
            ->assertSee('Dokumen sudah lengkap')
            ->assertSee('Dokumen sudah divalidasi admin')
            ->assertDontSee('Preview Kartu Ujian');
    }

    public function test_cetak_identitas_is_available_after_payment_and_document_verification(): void
    {
        $user = $this->createUserWithRegistration(BillingStatus::Settlement, DokumenStatus::Verified);

        $this->actingAs($user)
            ->get(route('cetak.identitas'))
            ->assertOk()
            ->assertSee('Preview Kartu Ujian')
            ->assertSee('CBT-PRINT-001')
            ->assertDontSee('Akses Terkunci');
    }

    public function test_locked_user_cannot_upload_photo_from_cetak_page_directly(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => UserRole::User->value]);

        $this->actingAs($user)
            ->post(route('cetak.upload-foto'), [
                'foto' => UploadedFile::fake()->image('foto-kartu.jpg'),
            ])
            ->assertRedirect(route('cetak.identitas'));

        $this->assertNull($user->fresh()->foto);
    }

    private function createUserWithRegistration(
        BillingStatus $billingStatus,
        DokumenStatus $documentStatus,
        bool $completeDocument = true
    ): User {
        $user = User::factory()->create(['role' => UserRole::User->value]);
        $utama = $this->createProdi('Teknik Informatika');
        $cadangan = $this->createProdi('Teknologi Rekayasa Multimedia');

        PilihanProdi::create([
            'user_id' => $user->id,
            'pilihan_1' => $utama->id,
            'pilihan_2' => $cadangan->id,
        ]);

        Billings::create([
            'user_id' => $user->id,
            'kode_bayar' => 'PAY-'.$user->id,
            'transaction_status' => $billingStatus,
            'isPay' => $billingStatus === BillingStatus::Settlement,
        ]);

        Dokumen::create([
            'user_id' => $user->id,
            'foto' => 'dokumen/foto/pas-foto.jpg',
            'kartu_identitas' => 'dokumen/identitas/kartu-identitas.pdf',
            'suket' => $completeDocument ? 'dokumen/surat-keterangan/suket.pdf' : null,
            'status' => $documentStatus,
        ]);

        Peserta::create([
            'user_id' => $user->id,
            'nomor_peserta' => 'CBT-PRINT-001',
        ]);
        Ujian::create([
            'user_id' => $user->id,
            'kode_ujian' => 'UJIAN-PRINT-'.$user->id,
            'status' => UjianStatus::NotCheckedIn,
        ]);

        return $user;
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
