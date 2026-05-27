<?php

namespace Tests\Feature;

use App\Enums\UjianStatus;
use App\Models\BroadcastMessage;
use App\Models\BroadcastMessageRecipient;
use App\Models\Ujian;
use App\Models\User;
use App\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengawasBroadcastTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengawas_can_send_message_only_to_active_participants(): void
    {
        $pengawas = User::factory()->create(['role' => UserRole::Pengawas->value]);
        $active = $this->createExamParticipant(UjianStatus::InExam);
        $idle = $this->createExamParticipant(UjianStatus::Idle);
        $waiting = $this->createExamParticipant(UjianStatus::CheckedIn);

        $this->actingAs($pengawas)
            ->get(route('pengawas.broadcast'))
            ->assertOk()
            ->assertSee('Broadcast Pesan');

        $this->actingAs($pengawas)
            ->post(route('pengawas.broadcast.store'), [
                'message' => 'Waktu ujian diperpanjang lima menit.',
            ])
            ->assertRedirect(route('pengawas.broadcast'));

        $broadcast = BroadcastMessage::query()->sole();

        $this->assertDatabaseHas('broadcast_message_recipients', [
            'broadcast_message_id' => $broadcast->id,
            'user_id' => $active->id,
        ]);
        $this->assertDatabaseHas('broadcast_message_recipients', [
            'broadcast_message_id' => $broadcast->id,
            'user_id' => $idle->id,
        ]);
        $this->assertDatabaseMissing('broadcast_message_recipients', [
            'broadcast_message_id' => $broadcast->id,
            'user_id' => $waiting->id,
        ]);
    }

    public function test_participant_feed_is_polled_and_message_can_be_dismissed(): void
    {
        $pengawas = User::factory()->create(['role' => UserRole::Pengawas->value]);
        $participant = $this->createExamParticipant(UjianStatus::InExam);
        $broadcast = BroadcastMessage::create([
            'pengawas_id' => $pengawas->id,
            'message' => 'Tetap tenang dan lanjutkan pengerjaan.',
        ]);
        BroadcastMessageRecipient::create([
            'broadcast_message_id' => $broadcast->id,
            'user_id' => $participant->id,
        ]);

        $this->actingAs($participant)
            ->getJson(route('participant.broadcast.index'))
            ->assertOk()
            ->assertJsonPath('messages.0.message', 'Tetap tenang dan lanjutkan pengerjaan.');

        $this->actingAs($participant)
            ->postJson(route('participant.broadcast.dismiss', $broadcast))
            ->assertOk();

        $this->assertDatabaseMissing('broadcast_message_recipients', [
            'broadcast_message_id' => $broadcast->id,
            'user_id' => $participant->id,
            'dismissed_at' => null,
        ]);

        $this->actingAs($participant)
            ->getJson(route('participant.broadcast.index'))
            ->assertOk()
            ->assertJsonCount(0, 'messages');
    }

    public function test_user_cannot_access_pengawas_broadcast_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);

        $this->actingAs($user)
            ->get(route('pengawas.broadcast'))
            ->assertRedirect(route('dashboard'));
    }

    private function createExamParticipant(UjianStatus $status): User
    {
        $user = User::factory()->create(['role' => UserRole::User->value]);

        Ujian::create([
            'user_id' => $user->id,
            'kode_ujian' => 'UJN-'.$user->id,
            'status' => $status,
        ]);

        return $user;
    }
}
