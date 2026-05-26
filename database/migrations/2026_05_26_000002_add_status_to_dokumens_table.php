<?php

use App\Enums\DokumenStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->string('status')->default(DokumenStatus::Pending->value)->after('ijazah');
        });
    }

    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
