<?php

namespace App\Services;

use App\Enums\UjianStatus;
use App\Models\User;

class UjianServices
{
    public function accessFor(User $user): array
    {
        $user->loadMissing(['pilihan', 'ujian']);

        if (! $user->pilihan) {
            return ['status' => 'locked', 'ujian' => null, 'activityTrackingEnabled' => false];
        }

        if (! $user->ujian || $user->ujian->status === UjianStatus::NotCheckedIn) {
            return ['status' => 'verification', 'ujian' => $user->ujian, 'activityTrackingEnabled' => false];
        }

        return [
            'status' => match ($user->ujian->status) {
                UjianStatus::Blocked => 'blocked',
                UjianStatus::Submitted => 'submitted',
                default => 'ready',
            },
            'ujian' => $user->ujian,
            'activityTrackingEnabled' => in_array($user->ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true),
        ];
    }
}
