<?php

namespace Tests\Feature;

use App\Enums\BillingStatus;
use App\Models\Billings;
use App\Models\User;
use App\Services\PaymentNotificationService;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSnapTest extends TestCase
{
    use RefreshDatabase;

    public function test_snap_endpoint_returns_saved_token_as_json_without_regenerating_transaction(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);

        Billings::create([
            'user_id' => $user->id,
            'kode_bayar' => 'PAY-SAVED-TOKEN',
            'snap_token' => 'saved-snap-token',
            'payment_type' => 'bank_transfer',
            'gross_amount' => 250000,
            'transaction_status' => BillingStatus::Pending,
        ]);

        $this->actingAs($user)
            ->postJson(route('payment.snap'))
            ->assertOk()
            ->assertJson([
                'order_id' => 'PAY-SAVED-TOKEN',
                'snap_token' => 'saved-snap-token',
            ]);
    }

    public function test_snap_endpoint_returns_json_validation_error_when_billing_is_missing(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);

        $this->actingAs($user)
            ->postJson(route('payment.snap'))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('payment');
    }

    public function test_authenticated_user_can_request_provider_payment_status_sync(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);

        $this->mock(PaymentNotificationService::class, function ($mock) use ($user): void {
            $mock->shouldReceive('syncForUser')
                ->once()
                ->withArgs(fn (User $candidate): bool => $candidate->is($user))
                ->andReturn([
                    'transaction_status' => BillingStatus::Settlement->value,
                    'is_paid' => true,
                ]);
        });

        $this->actingAs($user)
            ->postJson(route('payment.sync'))
            ->assertOk()
            ->assertJson([
                'transaction_status' => BillingStatus::Settlement->value,
                'is_paid' => true,
            ]);
    }
}
