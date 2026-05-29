<?php

use App\Enums\AnnouncementStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_batches', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedSmallInteger('tahun');
            $table->timestamp('announcement_date');
            $table->enum('status', array_map(fn (AnnouncementStatus $status) => $status->value, AnnouncementStatus::cases()))
                ->default(AnnouncementStatus::Draft->value);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'announcement_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_batches');
    }
};
