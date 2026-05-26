<?php

namespace Tests\Feature;

use App\Enums\SoalCbtSource;
use App\Enums\SoalCbtStatus;
use App\Models\SoalCbt;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSoalCbtTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_cannot_access_admin_bank_soal(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.soal'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_create_manual_question_as_draft(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.soal.store'), $this->validPayload())
            ->assertRedirect(route('admin.soal'));

        $this->assertDatabaseHas('soal_cbt', [
            'sub_soal' => 'PM',
            'jawaban_benar' => 'C',
            'status' => SoalCbtStatus::Draft->value,
            'source_type' => SoalCbtSource::Manual->value,
            'created_by' => $admin->id,
        ]);
    }

    public function test_admin_can_update_question_and_mark_it_as_preview(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $soal = SoalCbt::factory()->create(['created_by' => $admin->id]);

        $this->actingAs($admin)
            ->put(route('admin.soal.update', $soal), [
                ...$this->validPayload(),
                'pertanyaan' => 'Pertanyaan sudah direview admin?',
                'jawaban_benar' => 'A',
            ])
            ->assertRedirect(route('admin.soal.preview', $soal));

        $this->assertDatabaseHas('soal_cbt', [
            'id' => $soal->id,
            'pertanyaan' => 'Pertanyaan sudah direview admin?',
            'jawaban_benar' => 'A',
            'status' => SoalCbtStatus::Preview->value,
        ]);
    }

    public function test_admin_can_release_complete_question(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $soal = SoalCbt::factory()->create([
            'created_by' => $admin->id,
            'status' => SoalCbtStatus::Preview,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.soal.release', $soal))
            ->assertRedirect(route('admin.soal'));

        $this->assertDatabaseHas('soal_cbt', [
            'id' => $soal->id,
            'status' => SoalCbtStatus::Released->value,
        ]);
        $this->assertNotNull($soal->fresh()->released_at);
    }

    public function test_admin_can_delete_questions_and_unused_pdf_source_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('soal-cbt/template-soal.pdf', 'pdf content');

        $admin = User::factory()->create(['role' => 'admin']);
        $firstQuestion = SoalCbt::factory()->create([
            'created_by' => $admin->id,
            'source_type' => SoalCbtSource::Pdf,
            'source_file' => 'soal-cbt/template-soal.pdf',
        ]);
        $lastQuestion = SoalCbt::factory()->create([
            'created_by' => $admin->id,
            'source_type' => SoalCbtSource::Pdf,
            'source_file' => 'soal-cbt/template-soal.pdf',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.soal.destroy', $firstQuestion))
            ->assertRedirect(route('admin.soal'));

        $this->assertDatabaseMissing('soal_cbt', ['id' => $firstQuestion->id]);
        Storage::disk('public')->assertExists('soal-cbt/template-soal.pdf');

        $this->actingAs($admin)
            ->delete(route('admin.soal.destroy', $lastQuestion))
            ->assertRedirect(route('admin.soal'));

        $this->assertDatabaseMissing('soal_cbt', ['id' => $lastQuestion->id]);
        Storage::disk('public')->assertMissing('soal-cbt/template-soal.pdf');
    }

    public function test_admin_can_filter_questions_by_status_and_sub_soal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pm = SoalCbt::factory()->create([
            'created_by' => $admin->id,
            'sub_soal' => 'PM',
            'status' => SoalCbtStatus::Draft,
            'pertanyaan' => 'Soal PM tampil',
        ]);
        SoalCbt::factory()->create([
            'created_by' => $admin->id,
            'sub_soal' => 'PBI',
            'status' => SoalCbtStatus::Released,
            'pertanyaan' => 'Soal PBI tidak tampil',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.soal', [
                'sub_soal' => 'PM',
                'status' => SoalCbtStatus::Draft->value,
            ]))
            ->assertOk()
            ->assertSee($pm->kode_soal)
            ->assertDontSee('Soal PBI tidak tampil');
    }

    public function test_pdf_import_uses_gemini_and_saves_draft_questions(): void
    {
        Storage::fake('public');
        Config::set('services.gemini.api_key', 'testing-key');
        Config::set('services.gemini.model', 'gemini-3.5-flash');
        Config::set('services.gemini.base_url', 'https://generativelanguage.googleapis.com');

        Http::fake([
            'https://generativelanguage.googleapis.com/upload/v1beta/files' => Http::response([], 200, [
                'X-Goog-Upload-URL' => 'https://upload.example.test/gemini',
            ]),
            'https://upload.example.test/gemini' => Http::response([
                'file' => ['uri' => 'files/testing-pdf'],
            ]),
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'questions' => [
                                            [
                                                'sub_soal' => 'PBI',
                                                'pertanyaan' => 'Makna kata akademik adalah?',
                                                'opsi_a' => 'Pilihan A',
                                                'opsi_b' => 'Pilihan B',
                                                'opsi_c' => 'Pilihan C',
                                                'opsi_d' => 'Pilihan D',
                                                'opsi_e' => null,
                                                'jawaban_benar' => 'B',
                                                'pembahasan' => 'Kunci dari PDF.',
                                            ],
                                        ],
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $pdf = UploadedFile::fake()->create('template-soal.pdf', 128, 'application/pdf');

        $this->actingAs($admin)
            ->post(route('admin.soal.import-pdf'), ['pdf' => $pdf])
            ->assertRedirect(route('admin.soal'));

        $this->assertDatabaseHas('soal_cbt', [
            'sub_soal' => 'PBI',
            'pertanyaan' => 'Makna kata akademik adalah?',
            'jawaban_benar' => 'B',
            'status' => SoalCbtStatus::Draft->value,
            'source_type' => SoalCbtSource::Pdf->value,
            'created_by' => $admin->id,
        ]);

        Http::assertSentCount(3);
    }

    private function validPayload(): array
    {
        return [
            'sub_soal' => 'PM',
            'pertanyaan' => 'Jika 2x + 4 = 10, maka nilai x adalah?',
            'opsi_a' => '2',
            'opsi_b' => '4',
            'opsi_c' => '3',
            'opsi_d' => '5',
            'opsi_e' => null,
            'jawaban_benar' => 'C',
            'pembahasan' => '2x = 6 sehingga x = 3.',
        ];
    }
}
