<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broadcast_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawas_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->timestamps();
        });

        Schema::create('broadcast_message_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_message_id')->constrained('broadcast_messages')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('dismissed_at')->nullable();
            $table->timestamps();

            $table->unique(['broadcast_message_id', 'user_id']);
            $table->index(['user_id', 'dismissed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broadcast_message_recipients');
        Schema::dropIfExists('broadcast_messages');
    }
};
