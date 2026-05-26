<?php

namespace App\Models;

use App\Enums\BillingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

class Billings extends Model
{
    protected $table = 'billings';

    protected $fillable = [
        'user_id',
        'kode_bayar',
        'snap_token',
        'virtual_account',
        'payment_type',
        'transaction_status',
        'gross_amount',
        'isPay',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'isPay' => 'boolean',
            'transaction_status' => BillingStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function checkBillings($user_id)
    {
        $bill = Billings::where('user_id', $user_id)->first();

        return $bill?->transaction_status === BillingStatus::Settlement || $bill?->isPay;
    }

    public static function createKodeBayar()
    {
        return 'PAY-'.Carbon::now()->format('Ymd').'-'.strtoupper(Str::random(6));
    }
}
