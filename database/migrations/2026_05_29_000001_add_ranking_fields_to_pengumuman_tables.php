<?php

use App\Enums\AnnouncementResultStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prodi', function (Blueprint $table): void {
            if (! Schema::hasColumn('prodi', 'kuota')) {
                $table->unsignedInteger('kuota')->default(0)->after('daya_tampung');
            }
        });

        Schema::table('announcement_batches', function (Blueprint $table): void {
            if (! Schema::hasColumn('announcement_batches', 'ranking_locked')) {
                $table->boolean('ranking_locked')->default(false)->after('status');
            }

            if (! Schema::hasColumn('announcement_batches', 'generated_at')) {
                $table->timestamp('generated_at')->nullable()->after('ranking_locked');
            }
        });

        Schema::create('announcement_results', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('announcement_batch_id')->constrained('announcement_batches')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nomor_peserta');
            $table->decimal('skor_akhir', 5, 2)->default(0);
            $table->foreignId('pilihan_1_id')->constrained('prodi')->cascadeOnDelete();
            $table->foreignId('pilihan_2_id')->nullable()->constrained('prodi')->nullOnDelete();
            $table->foreignId('prodi_diterima_id')->nullable()->constrained('prodi')->nullOnDelete();
            $table->enum('status_hasil', [
                AnnouncementResultStatus::Lulus->value,
                AnnouncementResultStatus::TidakLulus->value,
            ]);
            $table->unsignedInteger('ranking_position');
            $table->timestamps();

            $table->unique(['announcement_batch_id', 'user_id']);
            $table->unique(['announcement_batch_id', 'nomor_peserta']);
            $table->index(['announcement_batch_id', 'status_hasil']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_results');

        Schema::table('announcement_batches', function (Blueprint $table): void {
            if (Schema::hasColumn('announcement_batches', 'generated_at')) {
                $table->dropColumn('generated_at');
            }

            if (Schema::hasColumn('announcement_batches', 'ranking_locked')) {
                $table->dropColumn('ranking_locked');
            }
        });

        Schema::table('prodi', function (Blueprint $table): void {
            if (Schema::hasColumn('prodi', 'kuota')) {
                $table->dropColumn('kuota');
            }
        });
    }
};
