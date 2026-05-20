<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InternshipTopicCourseSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->courses() as $course) {
            Course::updateOrCreate(
                ['slug' => $course['slug']],
                $course
            );
        }
    }

    protected function courses(): array
    {
        $levels = config('internship_topics', []);
        $courses = [];

        foreach ($levels as $level => $levelData) {
            foreach (($levelData['categories'] ?? []) as $category => $topics) {
                foreach ($topics as $topic) {
                    $courses[] = $this->buildCourse($topic, $level, $category, $levelData);
                }
            }
        }

        return $courses;
    }

    protected function buildCourse(string $topic, string $level, string $category, array $levelData): array
    {
        $duration = $levelData['duration'] ?? match ($level) {
            'Intermediate' => '75 Days',
            'Advanced' => '90 Days',
            default => '45 Days',
        };

        $projects = $levelData['projects'] ?? 'Portfolio Projects Required';
        $focus = $levelData['focus'] ?? 'Practical internship workflow with guided submissions.';
        $domain = str_replace(' (BBA/MBA)', '', $category);

        return [
            'title' => $topic,
            'slug' => Str::slug($topic),
            'description' => "{$topic} is a {$level} internship track focused on {$focus}",
            'level' => $level,
            'category' => $category,
            'image' => $this->topicImage($category),
            'hero_badge' => strtoupper("{$level} {$domain} internship"),
            'career_path' => "{$domain} skill-building and portfolio project track",
            'duration_months' => $this->durationMonths($duration),
            'fee' => $this->feeForLevel($level),
            'program_overview' => [
                'features' => [
                    [
                        'title' => "{$topic} workflow",
                        'description' => "Learn the practical steps, tools, and decision points used in {$topic}.",
                    ],
                    [
                        'title' => 'Portfolio-ready execution',
                        'description' => 'Complete a structured assignment that can be explained in internships, interviews, and project discussions.',
                    ],
                ],
                'stats' => [
                    [
                        'value' => $duration,
                        'label' => "{$level} tier timeline",
                    ],
                    [
                        'value' => $projects,
                        'label' => 'Project requirement for this tier',
                    ],
                    [
                        'value' => $domain,
                        'label' => 'Career domain',
                    ],
                ],
                'cta' => [
                    'button_text' => "Explore {$topic} Program",
                    'batch_info' => "Enrollment is open for the next {$topic} cohort.",
                ],
            ],
            'why_choose' => [
                [
                    'title' => 'Guided Practical Workflow',
                    'description' => "The track breaks {$topic} into clear tasks, checkpoints, and submission expectations.",
                ],
                [
                    'title' => 'Career-Relevant Output',
                    'description' => 'You finish with a concrete project artifact that helps show your learning progress.',
                ],
                [
                    'title' => 'Structured Internship Path',
                    'description' => 'The program is organized by tier, duration, and portfolio requirements so learners know exactly what to complete.',
                ],
            ],
            'testimonials' => [
                [
                    'name' => 'Aarav Mehta',
                    'role' => "{$domain} Learner",
                    'text' => "The structure helped me understand {$topic} through practical tasks and clear checkpoints.",
                ],
                [
                    'name' => 'Nisha Kapoor',
                    'role' => 'College Student',
                    'text' => 'I liked that every module ended with something concrete to submit, review, and improve.',
                ],
            ],
            'faq' => [
                [
                    'question' => 'Do I need prior experience before joining?',
                    'answer' => $level === 'Beginner'
                        ? 'No. The beginner tier starts with foundations and then moves into applied tasks.'
                        : 'Some basic familiarity helps, but the track still gives you a guided workflow and clear assignments.',
                ],
                [
                    'question' => 'Will I build practical projects inside this course?',
                    'answer' => 'Yes. The curriculum is organized around structured assignments, submissions, and reviewable portfolio outputs.',
                ],
                [
                    'question' => 'What kind of certificate path does this follow?',
                    'answer' => "This is a {$duration} {$level} tier track with {$projects}.",
                ],
            ],
            'curriculum' => [
                [
                    'title' => "Task 1: {$topic} Portfolio Sprint",
                    'tasks' => [
                        [
                            'title' => 'Applied internship assignment',
                            'assignment' => "Complete a guided {$topic} task and turn it into a reviewable portfolio output.",
                            'submission' => 'Project file, document, design link, repository, or report based on the track.',
                            'ai_review' => 'The review checks clarity, structure, completeness, and practical alignment with the selected internship topic.',
                        ],
                    ],
                ],
            ],
            'modules' => [
                "{$domain} foundations",
                'Guided workflow practice',
                'Portfolio project submission',
            ],
            'phases' => [
                [
                    'title' => "Phase 1: {$topic} Foundations",
                    'modules' => [
                        [
                            'title' => 'Module 1: Practical Skill Build',
                            'tasks' => [
                                'Concept mapping and tool setup',
                                'Guided mini assignment',
                                'Portfolio project draft',
                                'Final submission and review',
                            ],
                        ],
                    ],
                ],
            ],
            'outcome' => [
                'certificate' => "{$duration} {$level} internship certificate",
                'portfolio' => $projects,
                'summary' => "A reviewable {$topic} portfolio artifact aligned with {$domain} workflows.",
            ],
        ];
    }

    protected function durationMonths(string $duration): int
    {
        preg_match('/\d+/', $duration, $matches);

        $days = (int) ($matches[0] ?? 30);

        return max(1, (int) ceil($days / 30));
    }

    protected function feeForLevel(string $level): int
    {
        return match ($level) {
            'Intermediate' => 4999,
            'Advanced' => 7999,
            default => 2999,
        };
    }

    protected function topicImage(string $category): string
    {
        return match ($category) {
            'Technology & Data' => '/images/courses/web-ecosystems-frontend.svg',
            'Business & Management (BBA/MBA)' => '/images/courses/b2b-digital-marketing-automation-mba-bba.svg',
            'Engineering (Mechanical & Civil)' => '/images/courses/bim-infrastructure.svg',
            'Law & Legal Studies' => '/images/courses/llb-corporate-law-legal-tech-tech-law.svg',
            'Mass Communication & Media' => '/images/courses/mass-communication-journalism-digital-media-pr-tech.svg',
            default => '/images/courses/default.svg',
        };
    }
}
