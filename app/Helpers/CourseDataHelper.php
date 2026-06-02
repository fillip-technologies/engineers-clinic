<?php

namespace App\Helpers;

use App\Models\Course;
use Illuminate\Support\Str;

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

        foreach (Course::query()->orderBy('title')->get() as $courseModel) {
            $courses[] = self::attachCourseMeta(self::mapCourseModel($courseModel));
        }

        $existingSlugs = array_column($courses, 'slug');

        foreach ($courseFiles as $courseFile) {
            $course = json_decode(file_get_contents($courseFile), true);

            if (! is_array($course) || ! isset($course['slug'])) {
                continue;
            }

            if (in_array($course['slug'], $existingSlugs, true)) {
                continue;
            }

            $courses[] = self::attachCourseMeta($course);
            $existingSlugs[] = $course['slug'];
        }

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
        $dbCourse = Course::where('slug', $slug)->first();

        if ($dbCourse) {
            return self::attachCourseMeta(self::mapCourseModel($dbCourse));
        }

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
        $slug = Str::slug($topic);
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
            'curriculum' => self::buildMappedCurriculum($topic, $category),
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

    protected static function mapCourseModel(Course $course): array
    {
        $payload = [
            'id' => $course->id,
            'slug' => $course->slug,
            'title' => $course->title,
            'category' => $course->category ?? 'Internship',
            'level' => $course->level ?? 'Beginner',
            'duration' => self::formatDuration($course->duration_months),
            'duration_months' => $course->duration_months,
            'fee' => $course->fee,
            'image' => $course->image,
            'description' => $course->description,
            'hero_badge' => $course->hero_badge,
            'career_path' => $course->career_path,
            'program_overview' => $course->program_overview ?? [],
            'why_choose' => $course->why_choose ?? [],
            'testimonials' => $course->testimonials ?? [],
            'faq' => $course->faq ?? [],
            'curriculum' => $course->curriculum ?? [],
            'modules' => $course->modules ?? [],
            'phases' => $course->phases ?? [],
            'outcome' => $course->outcome ?? [],
        ];

        if (! self::hasMappedCurriculum($payload['curriculum'])) {
            $payload['curriculum'] = self::buildMappedCurriculum(
                $payload['title'],
                $payload['category'],
                $payload['modules']
            );
        }

        return $payload;
    }

    protected static function hasMappedCurriculum(mixed $curriculum): bool
    {
        if (! is_array($curriculum) || count($curriculum) !== 50) {
            return false;
        }

        $firstProject = $curriculum[0] ?? null;
        $firstTask = is_array($firstProject) ? ($firstProject['tasks'][0] ?? null) : null;

        return is_array($firstProject)
            && is_array($firstTask)
            && array_key_exists('project_no', $firstProject)
            && array_key_exists('difficulty', $firstProject)
            && array_key_exists('estimated_hours', $firstProject)
            && array_key_exists('task_no', $firstTask);
    }

    protected static function buildMappedCurriculum(string $courseTitle, string $category, array $modules = []): array
    {
        $projectTitles = self::projectTitlesFor($courseTitle, $modules);
        $projectCategory = self::curriculumCategory($courseTitle, $category);

        return collect(range(1, 50))->map(function (int $projectNo) use ($courseTitle, $projectCategory, $projectTitles) {
            $title = $projectTitles[$projectNo - 1] ?? "Portfolio Project {$projectNo}";
            $difficulty = match (true) {
                $projectNo <= 12 => 'Beginner',
                $projectNo <= 28 => 'Intermediate',
                $projectNo <= 42 => 'Advanced',
                default => 'Advanced Capstone',
            };
            $estimatedHours = match (true) {
                $projectNo <= 12 => 4 + ($projectNo % 2),
                $projectNo <= 28 => 6 + ($projectNo % 3),
                $projectNo <= 42 => 9 + ($projectNo % 4),
                default => 12 + ($projectNo % 5),
            };

            return [
                'project_no' => $projectNo,
                'title' => "Project {$projectNo}: {$title}",
                'category' => $projectCategory,
                'difficulty' => $difficulty,
                'estimated_hours' => $estimatedHours,
                'description' => "Build a practical {$courseTitle} portfolio project focused on planning, execution, quality review, and presentation.",
                'tasks' => self::projectTasks($projectNo, $title, $courseTitle),
            ];
        })->all();
    }

    protected static function projectTasks(int $projectNo, string $projectTitle, string $courseTitle): array
    {
        $templates = [
            ['Define Project Brief', 'Write the objective, audience, scope, constraints, and success criteria.', 'Brief document with scope and acceptance criteria.', 'Checks clarity, scope, realism, and alignment with the course topic.'],
            ['Plan Workflow', 'Break the project into milestones, tools, references, and expected deliverables.', 'Milestone checklist with tools and references.', 'Checks sequence, tool fit, and execution readiness.'],
            ['Build Core Output', 'Create the main working artifact for the project with practical, reviewable detail.', 'Project file, repository, design link, notebook, model, or report.', 'Checks completeness, practical correctness, and structure.'],
            ['Document Decisions', 'Explain key decisions, tradeoffs, rejected options, and supporting evidence.', 'README, decision log, screenshots, diagrams, or notes.', 'Checks reasoning quality and usefulness for portfolio review.'],
            ['Review Quality', 'Test the output against acceptance criteria and list issues, risks, or gaps.', 'Quality checklist with fixes and remaining limitations.', 'Checks review depth, issue priority, and evidence.'],
            ['Polish Final Version', 'Improve clarity, usability, accuracy, performance, or presentation based on review.', 'Updated final artifact with a short improvement note.', 'Checks whether revisions are meaningful and connected to feedback.'],
            ['Prepare Case Study', 'Summarize problem, process, final output, learning, and next steps.', 'Portfolio case study page, PDF, slide deck, or README section.', 'Checks storytelling, professional tone, and interview readiness.'],
            ['Present Outcome', 'Create a short demo or presentation explaining the final result and value.', 'Presentation notes, demo outline, recording plan, or final links.', 'Checks clarity, confidence, and outcome focus.'],
        ];

        $taskCount = 4 + ($projectNo % 5);

        return collect(array_slice($templates, 0, $taskCount))->values()->map(fn (array $task, int $index) => [
            'task_no' => $index + 1,
            'title' => $task[0],
            'assignment' => "{$task[1]} Apply this to {$projectTitle} for {$courseTitle}.",
            'submission' => $task[2],
            'ai_review' => $task[3],
        ])->all();
    }

    protected static function projectTitlesFor(string $courseTitle, array $modules = []): array
    {
        $title = strtolower($courseTitle . ' ' . implode(' ', array_map('strval', $modules)));

        return match (true) {
            str_contains($title, 'frontend') || str_contains($title, 'web') || str_contains($title, 'react') => [
                'Responsive Landing Page', 'Portfolio Homepage', 'Cafe Menu Interface', 'Pricing Table Experience', 'FAQ Accordion System', 'Image Gallery Layout', 'Agency Landing Page', 'Timeline Interface', 'Dashboard Shell', 'Newsletter Signup Flow',
                'Product Detail Page', 'Blog Article Template', 'Login and Registration UI', 'Responsive Navbar System', 'Card Grid Marketplace', 'Profile Settings Page', 'Checkout Summary UI', 'Admin Table Interface', 'Search Results Page', 'Weather App UI',
                'React State Panel', 'Reusable Button Library', 'Component Props Showcase', 'Tabbed Content UI', 'Form Validation Experience', 'API Data Listing Page', 'Filtered Product Catalog', 'Todo Workflow App', 'Kanban Board Interface', 'Analytics Dashboard',
                'Theme Toggle System', 'Accessible Modal Flow', 'Multi-Step Form Wizard', 'Notification Center UI', 'Calendar Event Layout', 'Responsive Blog Platform', 'E-commerce Cart Experience', 'Portfolio Case Study Page', 'Design System Starter', 'Performance Optimized Landing',
                'Progressive Web App Shell', 'Realtime Chat Interface', 'Role-Based Dashboard UI', 'Headless CMS Frontend', 'Frontend Testing Suite', 'Micro-Interaction Library', 'Data Visualization Screen', 'Full SaaS Marketing Site', 'Capstone Product Interface', 'Frontend Architecture Portfolio',
            ],
            str_contains($title, 'ui') || str_contains($title, 'ux') || str_contains($title, 'design') => [
                'Persona and Problem Framing Board', 'Mobile App Wireframe Set', 'Landing Page Wireframe', 'Moodboard and Visual Direction', 'Design Tokens Starter', 'Button Component States', 'Navigation Pattern Study', 'Signup Flow Redesign', 'Dashboard Information Architecture', 'E-commerce Product Page',
                'Checkout Flow UX', 'Accessibility Contrast Audit', 'Microcopy Improvement Sprint', 'Empty State Design Pack', 'Mobile Banking Screen', 'Food Delivery App Flow', 'Travel Booking Search Flow', 'SaaS Settings Experience', 'Onboarding Journey Map', 'User Interview Synthesis',
                'Competitive UX Audit', 'Clickable Prototype Sprint', 'Usability Test Plan', 'Design System Foundations', 'Responsive Web Mockup', 'Developer Handoff Sheet', 'Figma Auto Layout Library', 'Interactive Component Prototype', 'Admin Dashboard Redesign', 'Healthcare Appointment Flow',
                'EdTech Learning Dashboard', 'FinTech Wallet Flow', 'CRM Contact Management UI', 'B2B Product Trial Flow', 'Mobile App Design System', 'Research to Prototype Case Study', 'Service Blueprint Design', 'Conversion-Focused Landing Page', 'UX Metrics Review', 'Product Requirements to Screens',
                'Advanced Prototype with States', 'Cross-Platform UI Kit', 'Design QA Checklist', 'Stakeholder Presentation Deck', 'Portfolio Case Study Rewrite', 'A/B Variant Design Set', 'Analytics-Informed Redesign', 'End-to-End Product Prototype', 'Capstone Product Design Case Study', 'Portfolio Design System Showcase',
            ],
            str_contains($title, 'data') || str_contains($title, 'machine learning') || str_contains($title, 'analytics') => [
                'Dataset Cleaning Notebook', 'Sales Summary Dashboard', 'Customer Segmentation Report', 'Survey Insights Analysis', 'Exploratory Data Analysis Pack', 'CSV Quality Audit', 'Excel to Python Workflow', 'KPI Scorecard Dashboard', 'Marketing Funnel Analysis', 'Inventory Trend Report',
                'HR Attrition Insights', 'Financial Expense Tracker', 'Web Traffic Analytics', 'A/B Test Interpretation', 'Correlation Study Notebook', 'Time Series Forecast Starter', 'Classification Baseline Model', 'Regression Prediction Model', 'Model Evaluation Report', 'Feature Engineering Notebook',
                'Power BI Executive Dashboard', 'SQL Reporting Dataset', 'ETL Pipeline Prototype', 'Data Dictionary and Governance Sheet', 'Customer Churn Analysis', 'Sentiment Analysis Mini Project', 'Recommendation Logic Prototype', 'Anomaly Detection Report', 'Big Data Pipeline Blueprint', 'Spark Transformation Exercise',
                'Cloud Data Warehouse Plan', 'Dashboard Storytelling Case Study', 'Model Bias Review', 'Experiment Tracking Sheet', 'API Data Collection Project', 'Automated Data Refresh Workflow', 'Forecasting Dashboard', 'Business Metrics Deep Dive', 'Interactive Analytics App', 'Data Quality Monitoring Plan',
                'ML Deployment Readiness Checklist', 'Advanced Feature Pipeline', 'Stakeholder Insight Memo', 'End-to-End Analytics Case Study', 'Predictive Dashboard Prototype', 'Portfolio Data Story', 'Capstone Decision Intelligence Project', 'Production Analytics Blueprint', 'Advanced ML Portfolio Project', 'Data Science Portfolio Showcase',
            ],
            default => [
                'Foundation Concept Map', 'Tool Setup and Workflow Board', 'Mini Case Study', 'Process Documentation Pack', 'Starter Portfolio Artifact', 'Industry Terminology Glossary', 'Practical Checklist Build', 'Simple Scenario Analysis', 'Reference Board and Notes', 'Client Brief Interpretation',
                'Workflow Diagram', 'Quality Review Sheet', 'Problem Statement Report', 'Applied Calculation Exercise', 'Tool-Based Practice File', 'Resource Planning Sheet', 'Risk and Assumption Register', 'Draft Output Review', 'Submission-Ready Mini Project', 'Peer Review Improvement',
                'Intermediate Case Study', 'Real-World Dataset or Site Review', 'Standards Alignment Checklist', 'Documentation Sprint', 'Comparative Analysis Report', 'Prototype or Model Build', 'Operational Workflow Plan', 'Stakeholder Summary Memo', 'Metrics and Evaluation Sheet', 'Automation Opportunity Map',
                'Advanced Tool Practice', 'Integrated Project Draft', 'Quality Assurance Review', 'Performance Improvement Plan', 'Compliance and Safety Review', 'Portfolio Case Study', 'Presentation Deck Build', 'Client-Ready Report', 'Multi-Step Workflow Project', 'Decision Matrix Exercise',
                'Advanced Scenario Simulation', 'End-to-End Implementation Plan', 'Review and Remediation Pack', 'Professional Documentation Set', 'Capstone Planning Sprint', 'Capstone Build Sprint', 'Capstone Validation Sprint', 'Capstone Presentation', 'Advanced Portfolio Case Study', 'Professional Portfolio Showcase',
            ],
        };
    }

    protected static function curriculumCategory(string $courseTitle, string $category): string
    {
        $title = strtolower($courseTitle);

        return match (true) {
            str_contains($title, 'frontend') || str_contains($title, 'web') => 'Frontend Development',
            str_contains($title, 'ui') || str_contains($title, 'ux') || str_contains($title, 'design') => 'UI/UX and Product Design',
            str_contains($title, 'data') || str_contains($title, 'machine learning') => 'Data Science and Analytics',
            str_contains($title, 'cloud') || str_contains($title, 'aws') => 'Cloud and Backend Systems',
            str_contains($title, 'security') || str_contains($title, 'hacking') => 'Cyber Security',
            default => str_replace(' (BBA/MBA)', '', $category),
        };
    }

    protected static function formatDuration(?int $months): ?string
    {
        if (! $months) {
            return null;
        }

        return $months . ' ' . Str::plural('Month', $months);
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
        $curriculum = $course['curriculum'] ?? [];

        if (is_array($curriculum) && ! empty($curriculum)) {
            $mappedProjectCount = collect($curriculum)
                ->filter(fn ($section) => is_array($section) && array_key_exists('project_no', $section))
                ->count();

            if ($mappedProjectCount > 0) {
                return $mappedProjectCount;
            }

            $taskCount = collect($curriculum)
                ->sum(fn ($section) => is_array($section) ? count($section['tasks'] ?? []) : 0);

            if ($taskCount > 0) {
                return $taskCount;
            }
        }

        $phaseTaskCount = 0;

        foreach (($course['phases'] ?? []) as $phase) {
            foreach (($phase['modules'] ?? []) as $module) {
                $phaseTaskCount += count($module['tasks'] ?? []);
            }
        }

        return $phaseTaskCount > 0 ? $phaseTaskCount : null;
    }
}
