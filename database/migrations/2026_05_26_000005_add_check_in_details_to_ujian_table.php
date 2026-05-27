<?php

use App\Enums\UjianCheckInMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->enum('check_in_method', array_map(
                fn (UjianCheckInMethod $method) => $method->value,
                UjianCheckInMethod::cases()
            ))->nullable()->after('checked_in_at');
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('ujian', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
            $table->dropColumn('check_in_method');
        });
    }
};
