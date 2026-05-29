<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->foreignId('announcement_batch_id')
                ->nullable()
                ->after('id')
                ->constrained('announcement_batches')
                ->cascadeOnDelete();

            $table->dropUnique('announcements_nomor_peserta_unique');
            $table->unique(['announcement_batch_id', 'nomor_peserta'], 'announcements_batch_nomor_unique');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropUnique('announcements_batch_nomor_unique');
            $table->unique('nomor_peserta', 'announcements_nomor_peserta_unique');
            $table->dropConstrainedForeignId('announcement_batch_id');
        });
    }
};
