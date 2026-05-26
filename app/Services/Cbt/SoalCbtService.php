<?php

namespace App\Services\Cbt;

use App\Enums\SoalCbtSource;
use App\Enums\SoalCbtStatus;
use App\Models\SoalCbt;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SoalCbtService
{
    public function __construct(private readonly GeminiOcrService $geminiOcrService)
    {
    }

    public function createManual(array $data, int $adminId): SoalCbt
    {
        return SoalCbt::create($this->payload($data, $adminId, SoalCbtSource::Manual));
    }

    public function update(SoalCbt $soal, array $data): SoalCbt
    {
        $soal->update([
            ...$data,
            'status' => SoalCbtStatus::Preview,
            'reviewed_at' => now(),
            'released_at' => null,
        ]);

        return $soal->fresh();
    }

    public function importPdf(UploadedFile $file, int $adminId): int
    {
        $fileHash = hash_file('sha256', $file->getRealPath());
        $lock = Cache::lock("soal-cbt:ocr:{$adminId}:{$fileHash}", 300);

        if (!$lock->get()) {
            throw ValidationException::withMessages([
                'pdf' => 'PDF ini sedang diproses OCR. Tunggu sampai proses sebelumnya selesai.',
            ]);
        }

        $path = null;

        try {
            $path = $file->store('soal-cbt', 'public');
            $questions = $this->geminiOcrService->extractQuestions($file);

            return DB::transaction(function () use ($questions, $adminId, $path) {
                $count = 0;

                foreach ($questions as $question) {
                    $normalized = $this->normalizeImportedQuestion($question);

                    SoalCbt::create($this->payload(
                        $normalized,
                        $adminId,
                        SoalCbtSource::Pdf,
                        $path
                    ));

                    $count++;
                }

                return $count;
            });
        } catch (\Throwable $exception) {
            if ($path !== null) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        } finally {
            $lock->release();
        }
    }

    public function release(SoalCbt $soal): SoalCbt
    {
        if (!$soal->isReadyToRelease()) {
            throw ValidationException::withMessages([
                'soal' => 'Soal belum lengkap dan belum bisa dirilis.',
            ]);
        }

        $soal->update([
            'status' => SoalCbtStatus::Released,
            'reviewed_at' => $soal->reviewed_at ?? now(),
            'released_at' => now(),
        ]);

        return $soal->fresh();
    }

    public function delete(SoalCbt $soal): void
    {
        $sourceFile = $soal->source_file;

        $soal->delete();

        $this->deleteSourceFileIfUnused($sourceFile);
    }

    public function deleteSourceFileIfUnused(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        if (!SoalCbt::where('source_file', $path)->exists()) {
            Storage::disk('public')->delete($path);
        }
    }

    private function payload(array $data, int $adminId, SoalCbtSource $source, ?string $sourceFile = null): array
    {
        $subSoal = strtoupper($data['sub_soal'] ?? 'PM');

        return [
            'kode_soal' => SoalCbt::nextKodeSoal($subSoal),
            'sub_soal' => $subSoal,
            'pertanyaan' => trim($data['pertanyaan']),
            'opsi_a' => trim($data['opsi_a']),
            'opsi_b' => trim($data['opsi_b']),
            'opsi_c' => trim($data['opsi_c']),
            'opsi_d' => trim($data['opsi_d']),
            'opsi_e' => filled($data['opsi_e'] ?? null) ? trim($data['opsi_e']) : null,
            'jawaban_benar' => strtoupper($data['jawaban_benar']),
            'pembahasan' => filled($data['pembahasan'] ?? null) ? trim($data['pembahasan']) : null,
            'status' => SoalCbtStatus::Draft,
            'source_type' => $source,
            'source_file' => $sourceFile,
            'created_by' => $adminId,
        ];
    }

    private function normalizeImportedQuestion(array $question): array
    {
        $subSoal = strtoupper($question['sub_soal'] ?? 'PM');
        $jawaban = strtoupper($question['jawaban_benar'] ?? 'A');

        return [
            'sub_soal' => in_array($subSoal, SoalCbt::SUB_SOAL, true) ? $subSoal : 'PM',
            'pertanyaan' => $question['pertanyaan'] ?? '',
            'opsi_a' => $question['opsi_a'] ?? '',
            'opsi_b' => $question['opsi_b'] ?? '',
            'opsi_c' => $question['opsi_c'] ?? '',
            'opsi_d' => $question['opsi_d'] ?? '',
            'opsi_e' => $question['opsi_e'] ?? null,
            'jawaban_benar' => in_array($jawaban, SoalCbt::JAWABAN, true) ? $jawaban : 'A',
            'pembahasan' => $question['pembahasan'] ?? null,
        ];
    }
}
