<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prodi', function (Blueprint $table) {
            $table->decimal('keketatan', 3, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('prodi', function (Blueprint $table) {
            $table->float('keketatan', 2)->change();
        });
    }
};
