<?php

namespace App\Helpers;

class CourseDataHelper
{
    protected static array $courseFamilies = [
        'ui-ux-product-design-professional' => [
            'menu_group' => 'Design & Product',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Design product experiences end to end',
            'career_path' => 'UI, UX, and product systems track',
        ],
        'data-science-analytics-expert' => [
            'menu_group' => 'Data & Analytics',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Turn data into practical decisions',
            'career_path' => 'Analytics, dashboards, and insight track',
        ],
        'b2b-digital-marketing-automation-mba-bba' => [
            'menu_group' => 'Business & Marketing',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Build growth systems for B2B brands',
            'career_path' => 'Digital funnels and automation track',
        ],
        'aws-cloud-solutions-architect' => [
            'menu_group' => 'Cloud & Infrastructure',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Architect and scale cloud systems',
            'career_path' => 'AWS infrastructure and deployment track',
        ],
        'btech-civil-engineering-smart-city-bim-infrastructure' => [
            'menu_group' => 'Civil Engineering',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Plan smarter infrastructure systems',
            'career_path' => 'BIM and smart city engineering track',
        ],
        'btech-mechanical-engineering-digital-twin-automation' => [
            'menu_group' => 'Mechanical Engineering',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Connect machines with digital intelligence',
            'career_path' => 'Automation and digital twin track',
        ],
        'btech-electrical-electronics-iot-power-grids' => [
            'menu_group' => 'Electrical & Electronics',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Work with IoT-enabled power systems',
            'career_path' => 'Connected devices and smart grids track',
        ],
        'llb-corporate-law-legal-tech-tech-law' => [
            'menu_group' => 'Law & Legal Tech',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Navigate legal work in digital contexts',
            'career_path' => 'Corporate law and legal tech track',
        ],
        'mass-communication-journalism-digital-media-pr-tech' => [
            'menu_group' => 'Media & Communication',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Build digital-first media communication skills',
            'career_path' => 'Journalism, media, and PR tech track',
        ],
    ];

    public static function loadAllCourses(): array
    {
        $courseFiles = glob(resource_path('data/courses/*.json')) ?: [];

        $courses = [];

        foreach ($courseFiles as $courseFile) {
            $course = json_decode(file_get_contents($courseFile), true);

            if (! is_array($course) || ! isset($course['slug'])) {
                continue;
            }

            $courses[] = self::attachCourseMeta($course);
        }

        $existingSlugs = array_column($courses, 'slug');

        foreach (self::topicCourses() as $course) {
            if (in_array($course['slug'], $existingSlugs, true)) {
                continue;
            }

            $courses[] = self::attachCourseMeta($course);
        }

        usort($courses, fn (array $first, array $second) => strcmp($first['title'], $second['title']));

        return $courses;
    }

    public static function loadCourseBySlug(string $slug): ?array
    {
        $courseFile = resource_path("data/courses/{$slug}.json");

        if (! is_file($courseFile)) {
            $topicCourse = self::topicCourseBySlug($slug);

            return $topicCourse ? self::attachCourseMeta($topicCourse) : null;
        }

        $course = json_decode(file_get_contents($courseFile), true);

        if (! is_array($course)) {
            return null;
        }

        return self::attachCourseMeta($course);
    }

    /**
     * Get course phases/modules structure.
     */
    public static function getCoursePhases($course): array
    {
        if (! $course) {
            return [];
        }

        $tasks = $course->tasks()->orderBy('created_at')->get();

        if ($tasks->isEmpty()) {
            return [
                [
                    'title' => 'Month 1: Getting Started',
                    'modules' => [
                        ['id' => 'module-1', 'title' => 'Introduction', 'state' => 'active'],
                        ['id' => 'module-2', 'title' => 'Basics', 'state' => 'locked'],
                    ],
                ],
            ];
        }

        $phases = [];
        $phaseIndex = 1;
        $modules = [];

        foreach ($tasks as $index => $task) {
            $modules[] = [
                'id' => 'module-' . ($index + 1),
                'title' => $task->title,
                'state' => $index === 0 ? 'active' : 'locked',
            ];

            if (($index + 1) % 3 === 0 || $index === $tasks->count() - 1) {
                $phases[] = [
                    'title' => 'Month ' . $phaseIndex . ': Learning',
                    'modules' => $modules,
                ];
                $modules = [];
                $phaseIndex++;
            }
        }

        return $phases;
    }

    /**
     * Get course module content.
     */
    public static function getCourseModules($course): array
    {
        if (! $course) {
            return [];
        }

        $tasks = $course->tasks()->orderBy('created_at')->get();

        $modules = [];

        foreach ($tasks as $index => $task) {
            $modules['module-' . ($index + 1)] = [
                'title' => $task->title,
                'description' => $task->description ?? 'Complete this module to progress.',
            ];
        }

        return $modules;
    }

    protected static function attachCourseMeta(array $course): array
    {
        $slug = $course['slug'] ?? '';

        $meta = self::$courseFamilies[$slug] ?? [
            'menu_group' => 'AI Remote Internships',
            'menu_group_label' => 'Our Programs',
            'hero_badge' => 'Structured practical learning',
            'career_path' => 'Career-focused guided track',
        ];

        $course['menu_group'] = $meta['menu_group'];
        $course['menu_group_label'] = $meta['menu_group_label'];
        $course['hero_badge'] = $course['hero_badge'] ?? $meta['hero_badge'];
        $course['career_path'] = $course['career_path'] ?? $meta['career_path'];

        $course['hero'] = self::buildCourseHero($course);

        return $course;
    }

    protected static function topicCourseBySlug(string $slug): ?array
    {
        foreach (self::topicCourses() as $course) {
            if (($course['slug'] ?? null) === $slug) {
                return $course;
            }
        }

        return null;
    }

    protected static function topicCourses(): array
    {
        $levels = self::topicConfig();
        $courses = [];

        foreach ($levels as $level => $levelData) {
            foreach (($levelData['categories'] ?? []) as $category => $topics) {
                foreach ($topics as $topic) {
                    $courses[] = self::buildTopicCourse($topic, $level, $category, $levelData);
                }
            }
        }

        return $courses;
    }

    protected static function buildTopicCourse(string $topic, string $level, string $category, array $levelData): array
    {
        $slug = \Illuminate\Support\Str::slug($topic);
        $duration = $levelData['duration'] ?? match ($level) {
            'Intermediate' => '75 Days',
            'Advanced' => '90 Days',
            default => '45 Days',
        };

        $projects = $levelData['projects'] ?? 'Portfolio Projects Required';
        $focus = $levelData['focus'] ?? 'Practical internship workflow with guided submissions.';
        $domain = str_replace(' (BBA/MBA)', '', $category);

        return [
            'slug' => $slug,
            'title' => $topic,
            'category' => $category,
            'level' => $level,
            'duration' => $duration,
            'image' => self::topicImage($category),
            'description' => "{$topic} is a {$level} internship track focused on {$focus}",
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
            'hero_badge' => strtoupper("{$level} {$domain} internship"),
            'career_path' => "{$domain} skill-building and portfolio project track",
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
        ];
    }

    protected static function topicConfig(): array
    {
        $path = config_path('internship_topics.php');

        if (! is_file($path)) {
            return [];
        }

        $topics = require $path;

        return is_array($topics) ? $topics : [];
    }

    protected static function topicImage(string $category): string
    {
        return match (true) {
            str_contains($category, 'Business') => '/images/courses/digital-marketing.svg',
            str_contains($category, 'Engineering') => '/images/courses/business-analytics.svg',
            str_contains($category, 'Law') => '/images/courses/seo-search-engine-optimization.svg',
            str_contains($category, 'Mass Communication') => '/images/courses/graphic-design.svg',
            default => '/images/courses/backend-development.svg',
        };
    }

    protected static function buildCourseHero(array $course): array
    {
        $overview = $course['program_overview'] ?? [];
        $overviewStats = is_array($overview) ? ($overview['stats'] ?? []) : [];
        $heroConfig = $course['hero'] ?? [];
        $primaryCta = is_array($heroConfig['primary_cta'] ?? null) ? $heroConfig['primary_cta'] : [];
        $secondaryCta = is_array($heroConfig['secondary_cta'] ?? null) ? $heroConfig['secondary_cta'] : [];

        $features = $heroConfig['features']
            ?? $course['feature_points']
            ?? $course['features']
            ?? [];

        if (empty($features)) {
            $features = array_values(array_filter([
                'Choose any 3 projects from your selected level',
                ! empty($course['level']) ? "{$course['level']} internship certificate & mentor-guided learning" : 'Internship certificate & mentor-guided learning',
                ! empty($course['modules'][0]) ? "Real-world assignments in {$course['modules'][0]}" : 'Real-world assignments designed for career growth',
            ]));
        }

        $features = collect($features)
            ->map(function ($feature) {
                if (is_array($feature)) {
                    return $feature['title'] ?? $feature['label'] ?? $feature['text'] ?? null;
                }

                return is_string($feature) ? $feature : null;
            })
            ->filter()
            ->take(4)
            ->values()
            ->all();

        $logos = $heroConfig['logos']
            ?? $course['company_logos']
            ?? [];

        $stats = [
            'learners' => $heroConfig['learner_count'] ?? $course['learner_count'] ?? null,
            'mentors' => $heroConfig['mentor_count'] ?? $course['mentor_count'] ?? null,
            'projects' => $heroConfig['projects_count'] ?? $course['projects_count'] ?? self::countCourseProjects($course),
        ];

        return [
            'title' => $heroConfig['title'] ?? $course['title'] ?? null,
            'subtitle' => $heroConfig['subtitle'] ?? $course['subtitle'] ?? $course['description'] ?? null,
            'trusted_badge' => $heroConfig['trusted_badge'] ?? $course['hero_badge'] ?? $course['menu_group_label'] ?? $course['category'] ?? null,
            'level' => $heroConfig['level'] ?? $course['level'] ?? null,
            'duration' => $heroConfig['duration'] ?? $course['duration'] ?? null,
            'features' => $features,
            'primary_cta' => [
                'label' => $primaryCta['label'] ?? 'Reserve Your Seat',
                'href' => $primaryCta['href'] ?? '#enroll-now',
            ],
            'secondary_cta' => [
                'label' => $secondaryCta['label'] ?? 'View Curriculum',
                'href' => $secondaryCta['href'] ?? (! empty($course['curriculum']) ? '#curriculum' : null),
            ],
            'trust_label' => $heroConfig['trust_label'] ?? (! empty($logos) ? 'Trusted by learners from' : null),
            'logos' => $logos,
            'stats' => array_filter($stats),
            'overview_stats' => is_array($overviewStats) ? $overviewStats : [],
        ];
    }

    protected static function countCourseProjects(array $course): ?int
    {
        $count = 0;

        foreach (($course['phases'] ?? []) as $phase) {
            foreach (($phase['modules'] ?? []) as $module) {
                $count += count($module['tasks'] ?? []);
            }
        }

        if ($count > 0) {
            return $count;
        }

        foreach (($course['curriculum'] ?? []) as $section) {
            $count += count($section['tasks'] ?? []);
        }

        return $count > 0 ? $count : null;
    }
}
