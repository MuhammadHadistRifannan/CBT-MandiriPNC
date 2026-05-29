<?php

namespace Tests\Feature;

use App\Enums\AnnouncementResultStatus;
use App\Enums\AnnouncementStatus;
use App\Enums\UjianStatus;
use App\Models\AnnouncementBatch;
use App\Models\AnnouncementResult;
use App\Models\Peserta;
use App\Models\PilihanProdi;
use App\Models\Prodi;
use App\Models\Ujian;
use App\Models\User;
use App\Services\SelectionRankingService;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_announcement_page_can_check_published_result(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value, 'name' => 'Peserta Lulus']);
        Peserta::create(['user_id' => $user->id, 'nomor_peserta' => 'CBT-PUBLISH']);
        $prodi = $this->createProdi();
        $batch = AnnouncementBatch::create([
            'title' => 'Pengumuman PMB 2026',
            'tahun' => 2026,
            'announcement_date' => now('Asia/Jakarta')->subMinute(),
            'status' => AnnouncementStatus::Published,
            'published_at' => now('Asia/Jakarta')->subMinute(),
        ]);

        AnnouncementResult::create([
            'announcement_batch_id' => $batch->id,
            'user_id' => $user->id,
            'nomor_peserta' => 'CBT-PUBLISH',
            'skor_akhir' => 88,
            'pilihan_1_id' => $prodi->id,
            'pilihan_2_id' => $prodi->id,
            'status_hasil' => AnnouncementResultStatus::Lulus,
            'prodi_diterima_id' => $prodi->id,
            'ranking_position' => 1,
        ]);

        $this->get(route('pengumuman.index'))
            ->assertOk()
            ->assertSee('Pengumuman Hasil Seleksi PMB');

        $this->post(route('pengumuman.check'), [
            'nomor_peserta' => 'CBT-PUBLISH',
        ])
            ->assertOk()
            ->assertSee('SELAMAT!')
            ->assertSee('Peserta Lulus')
            ->assertSee('Teknik Informatika');
    }

    public function test_draft_announcement_is_not_visible_publicly(): void
    {
        AnnouncementBatch::create([
            'title' => 'Pengumuman Draft',
            'tahun' => 2026,
            'announcement_date' => now('Asia/Jakarta')->subMinute(),
            'status' => AnnouncementStatus::Draft,
        ]);

        $this->post(route('pengumuman.check'), [
            'nomor_peserta' => 'CBT-DRAFT',
        ])
            ->assertOk()
            ->assertSee('Pengumuman belum tersedia');
    }

    public function test_future_published_batch_is_not_visible_until_schedule(): void
    {
        AnnouncementBatch::create([
            'title' => 'Pengumuman Terjadwal',
            'tahun' => 2026,
            'announcement_date' => now('Asia/Jakarta')->addDay(),
            'status' => AnnouncementStatus::Published,
            'published_at' => now('Asia/Jakarta'),
        ]);

        $this->post(route('pengumuman.check'), [
            'nomor_peserta' => 'CBT-FUTURE',
        ])
            ->assertOk()
            ->assertSee('Silakan kembali sesuai jadwal yang telah ditentukan');
    }

    public function test_admin_can_generate_ranking_result(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin->value]);
        $user = User::factory()->create(['role' => UserRole::User->value, 'name' => 'Peserta Ranking']);
        $prodi = $this->createProdi(['kuota' => 1]);
        Peserta::create(['user_id' => $user->id, 'nomor_peserta' => 'CBT-ADMIN']);
        PilihanProdi::create(['user_id' => $user->id, 'pilihan_1' => $prodi->id, 'pilihan_2' => $prodi->id]);
        Ujian::create([
            'user_id' => $user->id,
            'kode_ujian' => 'UJI-ADMIN',
            'status' => UjianStatus::Submitted,
            'nilai' => 95,
            'submitted_at' => now('Asia/Jakarta'),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.pengumuman.batches.store'), [
                'title' => 'Pengumuman Admin',
                'tahun' => 2026,
                'announcement_date' => now('Asia/Jakarta')->subMinute()->format('Y-m-d H:i:s'),
                'status' => AnnouncementStatus::Published->value,
            ])
            ->assertRedirect(route('admin.pengumuman'));

        $batch = AnnouncementBatch::query()->where('title', 'Pengumuman Admin')->first();

        $this->actingAs($admin)
            ->post(route('admin.pengumuman.batches.generate', $batch))
            ->assertRedirect(route('admin.pengumuman', ['batch_id' => $batch->id]));

        $this->assertDatabaseHas('announcement_results', [
            'nomor_peserta' => 'CBT-ADMIN',
            'announcement_batch_id' => $batch->id,
            'status_hasil' => AnnouncementResultStatus::Lulus->value,
            'prodi_diterima_id' => $prodi->id,
        ]);

        $this->assertTrue($batch->fresh()->ranking_locked);
    }

    public function test_ranking_allocates_second_choice_when_first_choice_is_full(): void
    {
        $firstChoice = $this->createProdi(['nama_prodi' => 'Akuntansi', 'kuota' => 1]);
        $secondChoice = $this->createProdi(['nama_prodi' => 'Teknik Informatika', 'kuota' => 1]);
        $batch = AnnouncementBatch::create([
            'title' => 'Pengumuman Ranking',
            'tahun' => 2026,
            'announcement_date' => now('Asia/Jakarta')->subMinute(),
            'status' => AnnouncementStatus::Draft,
        ]);
        $topUser = $this->createSubmittedParticipant('CBT-TOP', 99, $firstChoice, $secondChoice);
        $secondUser = $this->createSubmittedParticipant('CBT-SECOND', 90, $firstChoice, $secondChoice);

        app(SelectionRankingService::class)->generate($batch);

        $this->assertDatabaseHas('announcement_results', [
            'user_id' => $topUser->id,
            'prodi_diterima_id' => $firstChoice->id,
            'ranking_position' => 1,
        ]);
        $this->assertDatabaseHas('announcement_results', [
            'user_id' => $secondUser->id,
            'prodi_diterima_id' => $secondChoice->id,
            'ranking_position' => 2,
        ]);
    }

    private function createProdi(array $overrides = []): Prodi
    {
        return Prodi::create(array_merge([
            'nama_prodi' => 'Teknik Informatika',
            'tingkat' => 'd4',
            'jurusan' => 'Komputer dan Bisnis',
            'peminat' => 0,
            'daya_tampung' => 100,
            'kuota' => 100,
            'keketatan' => 0.42,
        ], $overrides));
    }

    private function createSubmittedParticipant(string $number, int $score, Prodi $firstChoice, Prodi $secondChoice): User
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);
        Peserta::create(['user_id' => $user->id, 'nomor_peserta' => $number]);
        PilihanProdi::create(['user_id' => $user->id, 'pilihan_1' => $firstChoice->id, 'pilihan_2' => $secondChoice->id]);
        Ujian::create([
            'user_id' => $user->id,
            'kode_ujian' => 'UJI-'.$number,
            'status' => UjianStatus::Submitted,
            'nilai' => $score,
            'submitted_at' => now('Asia/Jakarta')->addMinutes(100 - $score),
        ]);

        return $user;
    }
}
