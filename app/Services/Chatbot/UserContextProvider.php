<?php

namespace App\Services\Chatbot;

use App\Models\Certificate;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\QuizResult;
use App\Models\Student;
use App\Models\User;

/**
 * Builds a concise, safe summary of a logged-in user's account so the chatbot
 * can answer personal questions ("what's my order status?", "my certificates?").
 * Mirrors the same queries used by the student dashboard.
 */
class UserContextProvider
{
    /**
     * @return array{name:string, has_any:bool, courses:?string, orders:?string, quizzes:?string, certificates:?string, text:string}
     */
    public function for(User $user): array
    {
        $firstName = explode(' ', trim((string) $user->name))[0] ?: $user->name;
        $student = Student::where('user_id', $user->id)->first();

        if (! $student) {
            return [
                'name'         => $firstName,
                'has_any'      => false,
                'courses'      => null,
                'orders'       => null,
                'quizzes'      => null,
                'certificates' => null,
                'text'         => "Logged-in user: {$user->name}. No student profile or enrollments yet.",
            ];
        }

        $coursesText = $this->coursesText($student->id);
        $ordersText = $this->ordersText($student->id);
        $quizzesText = $this->quizzesText($student->id);
        $certsText = $this->certificatesText($student->id);

        $parts = array_filter([
            $coursesText ? "Enrolled courses:\n{$coursesText}" : null,
            $ordersText ? "Recent orders:\n{$ordersText}" : null,
            $quizzesText ? "Quizzes: {$quizzesText}" : null,
            $certsText ? "Certificates:\n{$certsText}" : null,
        ]);

        return [
            'name'         => $firstName,
            'has_any'      => ! empty($parts),
            'courses'      => $coursesText,
            'orders'       => $ordersText,
            'quizzes'      => $quizzesText,
            'certificates' => $certsText,
            'text'         => "Logged-in user: {$user->name}.\n" . implode("\n\n", $parts),
        ];
    }

    private function coursesText(int $studentId): ?string
    {
        $enrollments = Enrollment::with('course')
            ->where('student_id', $studentId)
            ->orderByDesc('enrollment_date')
            ->get();

        if ($enrollments->isEmpty()) {
            return null;
        }

        return $enrollments->map(function (Enrollment $e) {
            $title = $e->course->title ?? 'Course';
            $progress = (int) ($e->progress ?? 0);

            return "- {$title} (progress: {$progress}%)";
        })->implode("\n");
    }

    private function ordersText(int $studentId): ?string
    {
        $payments = Payment::with('course')
            ->where('student_id', $studentId)
            ->orderByDesc('payment_date')
            ->limit(5)
            ->get();

        if ($payments->isEmpty()) {
            return null;
        }

        return $payments->map(function (Payment $p) {
            $orderId = 'ORD-' . str_pad((string) $p->id, 4, '0', STR_PAD_LEFT);
            $title = $p->course->title ?? 'Course';
            $amount = $p->amount ? 'Rs. ' . number_format($p->amount, 0) : 'N/A';
            $status = ucfirst($p->status ?? 'pending');

            return "- {$orderId}: {$title} — {$amount} — {$status}";
        })->implode("\n");
    }

    private function quizzesText(int $studentId): ?string
    {
        $results = QuizResult::where('student_id', $studentId)->get();

        if ($results->isEmpty()) {
            return null;
        }

        $passed = $results->where('passed', true)->count();

        return "{$results->count()} attempt(s), {$passed} passed";
    }

    private function certificatesText(int $studentId): ?string
    {
        $certs = Certificate::with('course')
            ->where('student_id', $studentId)
            ->get();

        if ($certs->isEmpty()) {
            return null;
        }

        return $certs->map(function (Certificate $c) {
            $title = $c->course->title ?? 'Course';
            $issued = optional($c->issued_date)->format('d M Y');

            return $issued ? "- {$title} (issued {$issued})" : "- {$title}";
        })->implode("\n");
    }
}
