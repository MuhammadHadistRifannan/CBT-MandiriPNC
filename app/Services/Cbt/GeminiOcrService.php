<?php

namespace App\Services\Cbt;

use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class GeminiOcrService
{
    public function extractQuestions(UploadedFile $file): array
    {
        $apiKey = env('GEMINI_API_KEY');

        if (blank($apiKey)) {
            throw new RuntimeException('GEMINI_API_KEY belum dikonfigurasi.');
        }

        $fileUri = $this->uploadFile($file, $apiKey);
        $response = $this->generateContent($fileUri, $apiKey);
        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

        if (blank($text)) {
            throw new RuntimeException('Gemini tidak mengembalikan hasil ekstraksi soal.');
        }

        return $this->parseQuestions($text);
    }

    private function uploadFile(UploadedFile $file, string $apiKey): string
    {
        $baseUrl = env('GEMINI_BASE_URL');
        $content = file_get_contents($file->getRealPath());

        $start = Http::withHeaders([
            'x-goog-api-key' => $apiKey,
            'X-Goog-Upload-Protocol' => 'resumable',
            'X-Goog-Upload-Command' => 'start',
            'X-Goog-Upload-Header-Content-Length' => strlen($content),
            'X-Goog-Upload-Header-Content-Type' => 'application/pdf',
            'Content-Type' => 'application/json',
        ])->post($baseUrl . '/upload/v1beta/files', [
            'file' => [
                'display_name' => $file->getClientOriginalName(),
            ],
        ]);

        $this->throwIfFailed($start, 'Gagal memulai upload PDF ke Gemini.');

        $uploadUrl = $start->header('X-Goog-Upload-URL');

        if (blank($uploadUrl)) {
            throw new RuntimeException('Gemini tidak mengembalikan upload URL.');
        }

        $upload = Http::withBody($content, 'application/pdf')
            ->withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Length' => strlen($content),
                'X-Goog-Upload-Offset' => '0',
                'X-Goog-Upload-Command' => 'upload, finalize',
            ])->post($uploadUrl);

        $this->throwIfFailed($upload, 'Gagal mengupload PDF ke Gemini.');

        $fileUri = data_get($upload->json(), 'file.uri');

        if (blank($fileUri)) {
            throw new RuntimeException('Gemini tidak mengembalikan file URI.');
        }

        return $fileUri;
    }

    private function generateContent(string $fileUri, string $apiKey): Response
    {
        $baseUrl = env('GEMINI_BASE_URL');
        $model = config('services.gemini.model', 'gemini-3.5-flash');
        $prompt = $this->prompt();

        $response = Http::withHeaders([
            'x-goog-api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($baseUrl . "/v1beta/models/{$model}:generateContent", [
            'contents' => [
                [
                    'parts' => [
                        [
                            'file_data' => [
                                'mime_type' => 'application/pdf',
                                'file_uri' => $fileUri,
                            ],
                        ],
                        ['text' => $prompt],
                    ],
                ],
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
            ],
        ]);

        $this->throwIfFailed($response, 'Gagal mengekstrak soal dari Gemini.');

        return $response;
    }

    private function parseQuestions(string $text): array
    {
        $json = trim($text);
        $json = Str::of($json)
            ->replaceMatches('/^```json\s*/', '')
            ->replaceMatches('/^```\s*/', '')
            ->replaceMatches('/\s*```$/', '')
            ->toString();

        $payload = json_decode($json, true);

        if (!is_array($payload)) {
            throw new RuntimeException('Format JSON dari Gemini tidak valid.');
        }

        $questions = $payload['questions'] ?? $payload;

        if (!is_array($questions) || $questions === []) {
            throw new RuntimeException('Gemini tidak menemukan soal pada PDF.');
        }

        return $questions;
    }

    private function prompt(): string
    {
        return <<<'PROMPT'
JIKA ADA GAMBAR DI SOAL YANG BERISI DATA , SAJIKAN DALAM BENTUK TABEL.
Ekstrak soal pilihan ganda dari PDF template rapi Bank Soal CBT.

Kembalikan JSON valid saja tanpa markdown, dengan bentuk:
{
  "questions": [
    {
      "sub_soal": "PM|PBI|PU|PPU",
      "pertanyaan": "teks soal",
      "opsi_a": "teks opsi A",
      "opsi_b": "teks opsi B",
      "opsi_c": "teks opsi C",
      "opsi_d": "teks opsi D",
      "opsi_e": null,
      "jawaban_benar": "A|B|C|D|E",
      "pembahasan": null
    }
  ]
}

Aturan:
- Hanya gunakan sub_soal PM, PBI, PU, atau PPU.
- Jika opsi E tidak ada, isi null.
- Jangan mengarang jawaban. Gunakan kunci jawaban yang ada di PDF.
- Jika kunci jawaban tidak terbaca, pilih jawaban paling mungkin dan jelaskan keraguan di pembahasan.
- Jika tidak terdeteksi bukan dokumen soal , maka jangan respons apapun.
PROMPT;
    }

    private function throwIfFailed(Response $response, string $message): void
    {
        if ($response->failed()) {
            throw new RuntimeException($message . ' ' . $response->body());
        }
    }
}
