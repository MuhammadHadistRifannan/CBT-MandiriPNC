<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained('soal_cbt')->cascadeOnDelete();
            $table->string('jawaban', 5)->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->unique(['ujian_id', 'soal_id']);
            $table->index(['user_id', 'answered_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_answers');
    }
};
