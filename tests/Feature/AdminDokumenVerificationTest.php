<?php

namespace Tests\Feature;

use App\Enums\DokumenStatus;
use App\Models\Dokumen;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDokumenVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_pending_document_queue_and_open_review(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $pendingUser = User::factory()->create([
            'name' => 'Peserta Menunggu',
            'role' => UserRole::User->value,
        ]);
        $verifiedUser = User::factory()->create([
            'name' => 'Peserta Selesai',
            'role' => UserRole::User->value,
        ]);

        $pending = $this->createDokumen($pendingUser, DokumenStatus::Pending);
        $this->createDokumen($verifiedUser, DokumenStatus::Verified);

        $this->actingAs($admin)
            ->get(route('admin.dokumen', ['status' => DokumenStatus::Pending->value]))
            ->assertOk()
            ->assertSee('Peserta Menunggu')
            ->assertDontSee('Peserta Selesai');

        $this->actingAs($admin)
            ->get(route('admin.dokumen.show', $pending))
            ->assertOk()
            ->assertSee('Review Dokumen Peserta')
            ->assertSee('Pas Foto')
            ->assertSee('Verifikasi dan Lanjut');
    }

    public function test_admin_can_verify_document_and_audit_reviewer(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $participant = User::factory()->create(['role' => UserRole::User->value]);
        $dokumen = $this->createDokumen($participant, DokumenStatus::Pending);

        $this->actingAs($admin)
            ->patch(route('admin.dokumen.review', $dokumen), [
                'status' => DokumenStatus::Verified->value,
            ])
            ->assertRedirect(route('admin.dokumen', ['status' => DokumenStatus::Pending->value]));

        $dokumen->refresh();

        $this->assertSame(DokumenStatus::Verified, $dokumen->status);
        $this->assertSame($admin->id, $dokumen->reviewed_by);
        $this->assertNotNull($dokumen->reviewed_at);
        $this->assertNull($dokumen->rejection_note);
    }

    public function test_rejected_document_requires_note_and_records_reason(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $participant = User::factory()->create(['role' => UserRole::User->value]);
        $dokumen = $this->createDokumen($participant, DokumenStatus::Pending);

        $this->actingAs($admin)
            ->from(route('admin.dokumen.show', $dokumen))
            ->patch(route('admin.dokumen.review', $dokumen), [
                'status' => DokumenStatus::Rejected->value,
            ])
            ->assertSessionHasErrors('rejection_note');

        $this->actingAs($admin)
            ->patch(route('admin.dokumen.review', $dokumen), [
                'status' => DokumenStatus::Rejected->value,
                'rejection_note' => 'Kartu identitas tidak terbaca.',
            ])
            ->assertRedirect(route('admin.dokumen', ['status' => DokumenStatus::Pending->value]));

        $this->assertDatabaseHas('dokumens', [
            'id' => $dokumen->id,
            'status' => DokumenStatus::Rejected->value,
            'rejection_note' => 'Kartu identitas tidak terbaca.',
            'reviewed_by' => $admin->id,
        ]);
    }

    public function test_new_upload_returns_reviewed_document_to_pending_queue(): void
    {
        Storage::fake('public');

        $reviewer = User::factory()->create(['role' => UserRole::Admin->value]);
        $participant = User::factory()->create(['role' => UserRole::User->value]);
        $dokumen = $this->createDokumen($participant, DokumenStatus::Verified);
        $dokumen->update([
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
        ]);

        $this->actingAs($participant)
            ->post(route('dokumen.simpan'), [
                'foto' => UploadedFile::fake()->image('foto-baru.jpg'),
            ])
            ->assertRedirect();

        $dokumen->refresh();

        $this->assertSame(DokumenStatus::Pending, $dokumen->status);
        $this->assertNull($dokumen->reviewed_by);
        $this->assertNull($dokumen->reviewed_at);
    }

    private function createDokumen(User $user, DokumenStatus $status): Dokumen
    {
        return Dokumen::create([
            'user_id' => $user->id,
            'foto' => 'dokumen/foto/pas-foto.jpg',
            'kartu_identitas' => 'dokumen/identitas/kartu-identitas.pdf',
            'suket' => 'dokumen/surat-keterangan/suket.pdf',
            'status' => $status,
        ]);
    }
}
