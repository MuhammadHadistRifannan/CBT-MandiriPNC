<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pilihanprodi', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
            ->constrained('users');
            
            $table->foreignId('pilihan_1')
            ->constrained('prodi');
            
            $table->foreignId('pilihan_2')
            ->constrained('prodi');

            $table->boolean('is_verified')->default(false); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilihanprodi');
    }
};
