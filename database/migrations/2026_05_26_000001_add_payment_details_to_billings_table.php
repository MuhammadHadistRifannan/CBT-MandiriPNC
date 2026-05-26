<?php

use App\Enums\BillingStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('kode_bayar');
            $table->string('payment_type')->nullable()->after('qr_string');
            $table->string('transaction_status')->default(BillingStatus::Pending->value)->after('payment_type');
            $table->decimal('gross_amount', 12, 2)->default(0)->after('transaction_status');
        });

        DB::table('billings')
            ->where('isPay', true)
            ->update([
                'transaction_status' => BillingStatus::Settlement->value,
                'gross_amount' => (float) config('services.midtrans.gross_amount', 0),
            ]);
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_type', 'transaction_status', 'gross_amount']);
        });
    }
};
