<?php

use App\Enums\UjianFlagStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->enum('flag_status', array_map(fn (UjianFlagStatus $status) => $status->value, UjianFlagStatus::cases()))
                ->default(UjianFlagStatus::Normal->value)
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropColumn('flag_status');
        });
    }
};
