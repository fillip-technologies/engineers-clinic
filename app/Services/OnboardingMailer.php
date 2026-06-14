<?php

namespace App\Services;

use App\Jobs\SendWelcomeCredentialsEmail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class OnboardingMailer
{
    public function send(User $user, string $plainPassword, ?string $accountType = null): void
    {
        $user->loadMissing('role');

        $accountType = $accountType ?: ($user->role?->name ?? 'student');

        try {
            SendWelcomeCredentialsEmail::dispatchSync($user->id, $plainPassword, $accountType);
        } catch (Throwable $exception) {
            Log::warning('Unable to send onboarding email.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $exception->getMessage(),
            ]);
        }
    }

}
