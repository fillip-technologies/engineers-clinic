<?php

namespace App\Services\Chatbot;

use App\Helpers\CourseDataHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChatbotService
{
    public function __construct(private readonly GeminiClient $gemini)
    {
    }

    /**
     * Resolve a quick-reply button tap.
     *
     * @return array{reply:string, action:?string}
     */
    public function quickReply(string $key): array
    {
        $replies = collect(config('chatbot.quick_replies', []));
        $reply = $replies->firstWhere('key', $key);

        if (! $reply) {
            return ['reply' => config('chatbot.fallback'), 'action' => null];
        }

        return match ($reply['type']) {
            'handoff' => [
                'reply'  => "Sure — I'll connect you with our team. Please share your details below and we'll reach out shortly. 🙌",
                'action' => 'open_handoff',
            ],
            'dynamic' => ['reply' => $this->resolveDynamic($key), 'action' => null],
            default   => ['reply' => $reply['answer'] ?? config('chatbot.fallback'), 'action' => null],
        };
    }

    /**
     * Answer a free-typed message: AI when configured, rule-based otherwise.
     *
     * @param  array<int, array{role:string, text:string}>  $history
     * @param  array|null  $userContext  Logged-in user's account summary (see UserContextProvider).
     * @return array{reply:string, action:?string}
     */
    public function answer(string $message, array $history = [], ?array $userContext = null): array
    {
        $message = trim($message);

        if ($message === '') {
            return ['reply' => config('chatbot.fallback'), 'action' => null];
        }

        $matchedFaqs = $this->matchFaqs($message);

        // AI path (grounded on live courses + matched FAQs + this user's account).
        if ($this->gemini->isConfigured()) {
            $systemPrompt = $this->buildSystemPrompt($matchedFaqs, $userContext);
            $reply = $this->gemini->generate($systemPrompt, $history, $message);

            if ($reply !== null) {
                return ['reply' => $reply, 'action' => null];
            }
            // fall through to rule-based if the API call failed.
        }

        // Rule-based: personal account questions first (for logged-in users).
        if ($userContext && ($userContext['has_any'] ?? false)) {
            $personal = $this->personalAnswer($message, $userContext);
            if ($personal !== null) {
                return ['reply' => $personal, 'action' => null];
            }
        }

        // Then best FAQ match, then friendly fallback.
        if (! empty($matchedFaqs)) {
            return ['reply' => $matchedFaqs[0]['a'], 'action' => null];
        }

        if ($this->looksLikeCourseQuery($message)) {
            return ['reply' => $this->resolveDynamic('courses'), 'action' => null];
        }

        return ['reply' => config('chatbot.fallback'), 'action' => null];
    }

    /**
     * Rule-based answers for "my account" style questions.
     */
    private function personalAnswer(string $message, array $ctx): ?string
    {
        $m = Str::lower($message);

        if (Str::contains($m, ['order', 'payment', 'purchase', 'invoice', 'transaction', 'paid', 'billing'])) {
            return $ctx['orders']
                ? "Here are your recent orders:\n{$ctx['orders']}"
                : "I couldn't find any orders on your account yet.";
        }

        if (Str::contains($m, ['certificate', 'certification'])) {
            return $ctx['certificates']
                ? "Your certificates:\n{$ctx['certificates']}"
                : "You don't have any certificates issued yet. Complete your course workspace, projects and the final quiz to earn one.";
        }

        if (Str::contains($m, ['quiz', 'score', 'result', 'test', 'exam'])) {
            return $ctx['quizzes']
                ? "Your quiz summary: {$ctx['quizzes']}."
                : "You haven't attempted any quizzes yet.";
        }

        if (Str::contains($m, ['my course', 'enrolled', 'my learning', 'progress', 'continue learning'])) {
            return $ctx['courses']
                ? "Your enrolled courses:\n{$ctx['courses']}"
                : "You're not enrolled in any course yet. Browse our courses and tap \"Reserve Your Seat\" to begin.";
        }

        return null;
    }

    /**
     * Build the grounded system prompt: personality + course catalog + matched FAQs + account.
     *
     * @param  array<int, array{q:string, a:string}>  $matchedFaqs
     * @param  array|null  $userContext
     */
    private function buildSystemPrompt(array $matchedFaqs, ?array $userContext = null): string
    {
        $knowledge = "KNOWLEDGE:\n\n=== Course catalog ===\n" . $this->courseSummary();

        if (! empty($matchedFaqs)) {
            $knowledge .= "\n\n=== Relevant FAQs ===\n";
            foreach ($matchedFaqs as $faq) {
                $knowledge .= "Q: {$faq['q']}\nA: {$faq['a']}\n\n";
            }
        }

        if ($userContext && ($userContext['has_any'] ?? false)) {
            $knowledge .= "\n=== This user's account (use ONLY to answer their personal questions; never share with anyone else) ===\n"
                . $userContext['text'] . "\n";
        }

        $knowledge .= "\n=== Useful links ===\n"
            . "Register: /register | Login: /login | All courses: / | College tie-up: /college-tieup | About: /about\n";

        return config('chatbot.system_prompt') . "\n\n" . $knowledge;
    }

    /**
     * A compact, cached summary of the live course catalog.
     */
    public function courseSummary(): string
    {
        return Cache::remember('chatbot.course_summary', now()->addMinutes(15), function (): string {
            $courses = CourseDataHelper::loadAllCourses();

            if (empty($courses)) {
                return "No courses are listed right now. Suggest the user check back soon or contact the team.";
            }

            $lines = [];
            foreach ($courses as $course) {
                $title = $course['title'] ?? 'Untitled course';
                $level = $course['level'] ?? null;
                $duration = $course['duration'] ?? ($course['duration_months'] ?? null);
                $slug = $course['slug'] ?? null;

                $bits = ["- {$title}"];
                if ($level) {
                    $bits[] = "level: {$level}";
                }
                if ($duration) {
                    $bits[] = is_numeric($duration) ? "duration: {$duration} months" : "duration: {$duration}";
                }
                if ($slug) {
                    $bits[] = "page: /course/{$slug}";
                }

                $lines[] = implode(' | ', $bits);
            }

            return implode("\n", $lines);
        });
    }

    /**
     * Human-friendly course list for the "Courses & fees" quick reply.
     */
    private function resolveDynamic(string $key): string
    {
        if ($key !== 'courses') {
            return config('chatbot.fallback');
        }

        $courses = CourseDataHelper::loadAllCourses();

        if (empty($courses)) {
            return "We're updating our course list right now. Please check back shortly, or tap \"Talk to a human\" and we'll guide you.";
        }

        $intro = "Here are our courses 👇\n";
        $lines = [];
        foreach (array_slice($courses, 0, 8) as $course) {
            $title = $course['title'] ?? 'Course';
            $slug = $course['slug'] ?? null;
            $level = $course['level'] ?? null;

            $line = "• {$title}";
            if ($level) {
                $line .= " ({$level})";
            }
            if ($slug) {
                $line .= " — /course/{$slug}";
            }
            $lines[] = $line;
        }

        $outro = "\nOpen any course page and tap \"Reserve Your Seat\" to enroll. Want help choosing? Tap \"Talk to a human\".";

        return $intro . implode("\n", $lines) . "\n" . $outro;
    }

    /**
     * Score FAQ entries against the message using keyword overlap.
     *
     * @return array<int, array{q:string, a:string}>
     */
    private function matchFaqs(string $message, int $limit = 3): array
    {
        $haystack = Str::lower($message);
        $scored = [];

        foreach (config('chatbot_faq', []) as $faq) {
            $score = 0;
            foreach (($faq['keywords'] ?? []) as $keyword) {
                if (Str::contains($haystack, Str::lower($keyword))) {
                    $score += strlen($keyword); // longer/more specific keywords weigh more
                }
            }

            if ($score > 0) {
                $scored[] = ['score' => $score, 'q' => $faq['q'], 'a' => $faq['a']];
            }
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        return array_map(
            fn ($item) => ['q' => $item['q'], 'a' => $item['a']],
            array_slice($scored, 0, $limit)
        );
    }

    private function looksLikeCourseQuery(string $message): bool
    {
        return Str::contains(Str::lower($message), ['course', 'courses', 'learn', 'training', 'program', 'class']);
    }
}
