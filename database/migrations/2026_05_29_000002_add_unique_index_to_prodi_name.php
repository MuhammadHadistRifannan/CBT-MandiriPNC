<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $seenNames = [];

        DB::table('prodi')
            ->select(['id', 'nama_prodi'])
            ->orderBy('id')
            ->get()
            ->each(function (object $prodi) use (&$seenNames): void {
                $normalizedName = Str::lower(trim($prodi->nama_prodi));

                if (! isset($seenNames[$normalizedName])) {
                    $seenNames[$normalizedName] = true;

                    return;
                }

                DB::table('prodi')
                    ->where('id', $prodi->id)
                    ->update([
                        'nama_prodi' => trim($prodi->nama_prodi).' #'.$prodi->id,
                    ]);
            });

        Schema::table('prodi', function (Blueprint $table): void {
            $table->unique('nama_prodi', 'prodi_nama_prodi_unique');
        });
    }

    public function down(): void
    {
        Schema::table('prodi', function (Blueprint $table): void {
            $table->dropUnique('prodi_nama_prodi_unique');
        });
    }
};
