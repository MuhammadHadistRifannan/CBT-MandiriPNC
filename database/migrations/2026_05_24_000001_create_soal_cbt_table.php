<?php

use App\Enums\SoalCbtSource;
use App\Enums\SoalCbtStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal_cbt', function (Blueprint $table) {
            $table->id();
            $table->string('kode_soal')->unique();
            $table->enum('sub_soal', ['PM', 'PBI', 'PU', 'PPU']);
            $table->text('pertanyaan');
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->text('opsi_d');
            $table->text('opsi_e')->nullable();
            $table->enum('jawaban_benar', ['A', 'B', 'C', 'D', 'E']);
            $table->text('pembahasan')->nullable();
            $table->enum('status', array_map(fn (SoalCbtStatus $status) => $status->value, SoalCbtStatus::cases()))
                ->default(SoalCbtStatus::Draft->value);
            $table->enum('source_type', array_map(fn (SoalCbtSource $source) => $source->value, SoalCbtSource::cases()))
                ->default(SoalCbtSource::Manual->value);
            $table->string('source_file')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal_cbt');
    }
};
