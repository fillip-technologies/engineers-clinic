<?php

namespace App\Jobs;

use App\Mail\OnboardingWelcomeMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendWelcomeCredentialsEmail implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $userId,
        public string $plainPassword,
        public string $accountType = 'student'
    ) {
    }

    public function handle(): void
    {
        $user = User::with('role')->find($this->userId);

        if (! $user) {
            return;
        }

        Mail::to($user->email)->queue(new OnboardingWelcomeMail(
            user: $user,
            plainPassword: $this->plainPassword,
            accountType: $this->accountType,
            loginUrl: route('login'),
            dashboardUrl: $this->dashboardUrl()
        ));
    }

    public function failed(Throwable $exception): void
    {
        Log::warning('Unable to queue onboarding credentials email.', [
            'user_id' => $this->userId,
            'message' => $exception->getMessage(),
        ]);
    }

    private function dashboardUrl(): string
    {
        return match ($this->accountType) {
            'admin' => route('admin.dashboard'),
            'college' => route('college.dashboard'),
            default => route('dashboard'),
        };
    }
}
