<?php

namespace App\Services;

use App\Models\BroadcastMessage;
use App\Models\BroadcastMessageRecipient;
use App\Models\User;

class ParticipantBroadcastService
{
    public function feedFor(User $user): array
    {
        return BroadcastMessageRecipient::query()
            ->with('broadcastMessage.pengawas')
            ->where('user_id', $user->id)
            ->whereNull('dismissed_at')
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (BroadcastMessageRecipient $recipient): array => [
                'id' => $recipient->broadcast_message_id,
                'message' => $recipient->broadcastMessage->message,
                'sender' => $recipient->broadcastMessage->pengawas?->name ?? 'Pengawas',
                'sent_at' => $recipient->broadcastMessage->created_at->format('H:i'),
                'dismiss_url' => route('participant.broadcast.dismiss', $recipient->broadcast_message_id),
            ])
            ->all();
    }

    public function dismiss(BroadcastMessage $broadcastMessage, User $user): void
    {
        $recipient = BroadcastMessageRecipient::query()
            ->where('broadcast_message_id', $broadcastMessage->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (! $recipient->dismissed_at) {
            $recipient->update(['dismissed_at' => now()]);
        }
    }
}
