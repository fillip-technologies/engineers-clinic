<?php

namespace App\Services;

use App\Mail\OnboardingWelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OnboardingMailer
{
    public function send(User $user, string $plainPassword, ?string $accountType = null): void
    {
        $user->loadMissing('role');

        $accountType = $accountType ?: ($user->role?->name ?? 'student');

        try {
            Mail::to($user->email)->send(new OnboardingWelcomeMail(
                user: $user,
                plainPassword: $plainPassword,
                accountType: $accountType,
                loginUrl: route('login'),
                dashboardUrl: $this->dashboardUrl($accountType)
            ));
        } catch (Throwable $exception) {
            Log::warning('Unable to send onboarding email.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function dashboardUrl(string $accountType): string
    {
        return match ($accountType) {
            'admin' => route('admin.dashboard'),
            'college' => route('college.dashboard'),
            default => route('dashboard'),
        };
    }
}
