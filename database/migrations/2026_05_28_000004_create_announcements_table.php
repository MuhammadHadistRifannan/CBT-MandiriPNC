<?php

use App\Enums\AnnouncementResultStatus;
use App\Enums\AnnouncementStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nomor_peserta')->unique();
            $table->enum('status_hasil', array_map(fn (AnnouncementResultStatus $status) => $status->value, AnnouncementResultStatus::cases()));
            $table->foreignId('prodi_diterima')->nullable()->constrained('prodi')->nullOnDelete();
            $table->string('jalur_seleksi')->default('Seleksi Mandiri');
            $table->enum('announcement_status', array_map(fn (AnnouncementStatus $status) => $status->value, AnnouncementStatus::cases()))
                ->default(AnnouncementStatus::Draft->value);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['announcement_status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
