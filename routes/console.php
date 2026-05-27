<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Enums\BillingStatus;
use App\Models\Billings;
use App\Models\Ujian;
use App\Services\UjianRegistrationService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ujian:backfill-sessions {--dry-run}', function (UjianRegistrationService $service) {
    $billings = Billings::query()
        ->where('transaction_status', BillingStatus::Settlement->value)
        ->whereDoesntHave('user.ujian')
        ->get();

    if ($this->option('dry-run')) {
        $this->info("{$billings->count()} sesi ujian akan dibuat.");

        return;
    }

    $billings->each(fn (Billings $billing) => $service->ensureForSettledBilling($billing));

    $this->info("{$billings->count()} sesi ujian berhasil dibuat.");
})->purpose('Membuat sesi ujian untuk peserta lunas yang belum memiliki sesi.');
