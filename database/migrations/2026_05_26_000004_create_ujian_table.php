<?php

use App\Enums\UjianStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('kode_ujian')->unique();
            $table->enum('status', array_map(fn (UjianStatus $status) => $status->value, UjianStatus::cases()))
                ->default(UjianStatus::NotCheckedIn->value);
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->unsignedSmallInteger('duration_minutes')->default(120);
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->foreignId('pengawas_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('flagged_reason')->nullable();
            $table->timestamps();

            $table->index(['status', 'updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};
