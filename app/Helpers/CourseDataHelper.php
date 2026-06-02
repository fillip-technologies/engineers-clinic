<?php

namespace App\Helpers;

use App\Models\Course;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CourseDataHelper
{
    public static function loadAllCourses(): array
    {
        $courses = [];
        $seenSlugs = [];

        foreach (Course::query()->orderBy('title')->get() as $course) {
            $payload = self::prepareCoursePayload(self::mapCourseModel($course));
            $courses[] = $payload;
            $seenSlugs[] = $payload['slug'];
        }

        foreach (self::legacyJsonCourses() as $course) {
            if (in_array($course['slug'] ?? null, $seenSlugs, true)) {
                continue;
            }

            $courses[] = self::prepareCoursePayload($course);
            $seenSlugs[] = $course['slug'];
        }

        usort($courses, fn (array $first, array $second) => strcmp($first['title'] ?? '', $second['title'] ?? ''));

        return $courses;
    }

    public static function loadCourseBySlug(string $slug): ?array
    {
        $course = Course::where('slug', $slug)->first();

        if ($course) {
            return self::prepareCoursePayload(self::mapCourseModel($course));
        }

        $course = self::legacyJsonCourseBySlug($slug);

        return $course ? self::prepareCoursePayload($course) : null;
    }

    public static function getCoursePhases(?Course $course): array
    {
        if (! $course) {
            return [];
        }

        $storedPhases = self::arrayPayloadValue($course->phases);

        if (! empty($storedPhases)) {
            return $storedPhases;
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

        return $tasks
            ->values()
            ->chunk(3)
            ->values()
            ->map(function (Collection $chunk, int $phaseIndex) {
                return [
                    'title' => 'Month ' . ($phaseIndex + 1) . ': Learning',
                    'modules' => $chunk->values()->map(fn ($task, int $index) => [
                        'id' => 'module-' . (($phaseIndex * 3) + $index + 1),
                        'title' => $task->title,
                        'state' => ($phaseIndex === 0 && $index === 0) ? 'active' : 'locked',
                    ])->all(),
                ];
            })
            ->all();
    }

    public static function getCourseModules(?Course $course): array
    {
        if (! $course) {
            return [];
        }

        $storedModules = self::arrayPayloadValue($course->modules);

        if (! empty($storedModules)) {
            return collect($storedModules)
                ->values()
                ->mapWithKeys(fn ($module, int $index) => [
                    'module-' . ($index + 1) => is_array($module)
                        ? $module
                        : ['title' => (string) $module, 'description' => 'Complete this module to progress.'],
                ])
                ->all();
        }

        return $course->tasks()
            ->orderBy('created_at')
            ->get()
            ->values()
            ->mapWithKeys(fn ($task, int $index) => [
                'module-' . ($index + 1) => [
                    'title' => $task->title,
                    'description' => $task->description ?? 'Complete this module to progress.',
                ],
            ])
            ->all();
    }

    protected static function mapCourseModel(Course $course): array
    {
        $payload = ['id' => $course->getKey()];

        foreach ($course->getFillable() as $field) {
            $payload[$field] = $course->getAttribute($field);
        }

        foreach (self::courseJsonFields($course) as $field) {
            $payload[$field] = self::arrayPayloadValue($payload[$field] ?? []);
        }

        return $payload;
    }

    protected static function prepareCoursePayload(array $course): array
    {
        $course['slug'] = filled($course['slug'] ?? null)
            ? $course['slug']
            : Str::slug($course['title'] ?? 'course');
        $course['title'] = $course['title'] ?? 'Untitled Course';
        $course['category'] = $course['category'] ?? 'Internship';
        $course['level'] = $course['level'] ?? 'Beginner';
        $course['duration_months'] = (int) ($course['duration_months'] ?? 0);
        $course['duration'] = $course['duration'] ?? self::formatDuration($course['duration_months']);
        $course['fee'] = $course['fee'] ?? 0;

        foreach (self::defaultJsonFields() as $field) {
            $course[$field] = self::arrayPayloadValue($course[$field] ?? []);
        }

        if (empty($course['curriculum'])) {
            $course['curriculum'] = self::buildMappedCurriculum(
                $course['title'],
                $course['category'],
                $course['modules']
            );
        }

        $course['menu_group'] = self::menuGroup($course);
        $course['menu_group_label'] = self::menuGroupLabel($course);
        $course['hero_badge'] = $course['hero_badge'] ?: 'Structured practical learning';
        $course['career_path'] = $course['career_path'] ?: 'Career-focused guided track';
        $course['hero'] = self::buildCourseHero($course);

        return $course;
    }

    protected static function legacyJsonCourses(): array
    {
        return collect(glob(resource_path('data/courses/*.json')) ?: [])
            ->map(fn (string $file) => json_decode(file_get_contents($file), true))
            ->filter(fn ($course) => is_array($course) && filled($course['slug'] ?? null))
            ->values()
            ->all();
    }

    protected static function legacyJsonCourseBySlug(string $slug): ?array
    {
        $file = resource_path("data/courses/{$slug}.json");

        if (! is_file($file)) {
            return null;
        }

        $course = json_decode(file_get_contents($file), true);

        return is_array($course) ? $course : null;
    }

    protected static function courseJsonFields(Course $course): array
    {
        return collect($course->getCasts())
            ->filter(fn (string $cast) => in_array($cast, ['array', 'json', 'object', 'collection'], true))
            ->keys()
            ->intersect($course->getFillable())
            ->values()
            ->all();
    }

    protected static function defaultJsonFields(): array
    {
        return [
            'program_overview',
            'why_choose',
            'testimonials',
            'faq',
            'curriculum',
            'modules',
            'phases',
            'outcome',
        ];
    }

    protected static function arrayPayloadValue(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value instanceof Collection) {
            return $value->all();
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    protected static function buildCourseHero(array $course): array
    {
        $hero = self::arrayPayloadValue($course['hero'] ?? []);
        $primaryCta = self::arrayPayloadValue($hero['primary_cta'] ?? []);
        $secondaryCta = self::arrayPayloadValue($hero['secondary_cta'] ?? []);
        $overview = self::arrayPayloadValue($course['program_overview'] ?? []);

        return [
            'title' => $hero['title'] ?? $course['title'],
            'subtitle' => $hero['subtitle'] ?? $course['description'] ?? null,
            'trusted_badge' => $hero['trusted_badge'] ?? $course['hero_badge'] ?? $course['category'],
            'level' => $hero['level'] ?? $course['level'] ?? null,
            'duration' => $hero['duration'] ?? $course['duration'] ?? null,
            'features' => self::heroFeatures($course, $hero),
            'primary_cta' => [
                'label' => $primaryCta['label'] ?? 'Reserve Your Seat',
                'href' => $primaryCta['href'] ?? '#enroll-now',
            ],
            'secondary_cta' => [
                'label' => $secondaryCta['label'] ?? 'View Curriculum',
                'href' => $secondaryCta['href'] ?? (! empty($course['curriculum']) ? '#curriculum' : null),
            ],
            'trust_label' => $hero['trust_label'] ?? (! empty($hero['logos'] ?? []) ? 'Trusted by learners from' : null),
            'logos' => self::arrayPayloadValue($hero['logos'] ?? $course['company_logos'] ?? []),
            'stats' => array_filter([
                'learners' => $hero['learner_count'] ?? $course['learner_count'] ?? null,
                'mentors' => $hero['mentor_count'] ?? $course['mentor_count'] ?? null,
                'projects' => $hero['projects_count'] ?? $course['projects_count'] ?? self::countCourseProjects($course),
            ]),
            'overview_stats' => self::arrayPayloadValue($overview['stats'] ?? []),
        ];
    }

    protected static function heroFeatures(array $course, array $hero): array
    {
        $features = $hero['features']
            ?? $course['feature_points']
            ?? $course['features']
            ?? [];

        if (empty($features)) {
            $features = array_values(array_filter([
                'Choose portfolio projects from your selected level',
                ! empty($course['level']) ? "{$course['level']} internship certificate and mentor-guided learning" : null,
                ! empty($course['modules'][0]) ? "Real-world assignments in {$course['modules'][0]}" : 'Real-world assignments designed for career growth',
            ]));
        }

        return collect($features)
            ->map(fn ($feature) => is_array($feature) ? ($feature['title'] ?? $feature['label'] ?? $feature['text'] ?? null) : $feature)
            ->filter(fn ($feature) => is_string($feature) && $feature !== '')
            ->take(4)
            ->values()
            ->all();
    }

    protected static function countCourseProjects(array $course): ?int
    {
        $curriculum = self::arrayPayloadValue($course['curriculum'] ?? []);

        if (! empty($curriculum)) {
            $projectCount = collect($curriculum)
                ->filter(fn ($section) => is_array($section) && array_key_exists('project_no', $section))
                ->count();

            if ($projectCount > 0) {
                return $projectCount;
            }

            $taskCount = collect($curriculum)
                ->sum(fn ($section) => is_array($section) ? count($section['tasks'] ?? []) : 0);

            if ($taskCount > 0) {
                return $taskCount;
            }
        }

        return collect(self::arrayPayloadValue($course['phases'] ?? []))
            ->sum(fn ($phase) => collect($phase['modules'] ?? [])->sum(fn ($module) => count($module['tasks'] ?? [])))
            ?: null;
    }

    protected static function menuGroup(array $course): string
    {
        $category = strtolower($course['category'] ?? '');
        $title = strtolower($course['title'] ?? '');

        return match (true) {
            str_contains($title, 'ui') || str_contains($title, 'ux') || str_contains($title, 'design') => 'Design & Product',
            str_contains($title, 'data') || str_contains($title, 'analytics') || str_contains($title, 'machine learning') => 'Data & Analytics',
            str_contains($title, 'cloud') || str_contains($title, 'aws') || str_contains($title, 'devops') => 'Cloud & Infrastructure',
            str_contains($category, 'business') || str_contains($title, 'marketing') => 'Business & Marketing',
            str_contains($category, 'law') || str_contains($title, 'law') || str_contains($title, 'legal') => 'Law & Legal Tech',
            str_contains($category, 'media') || str_contains($title, 'journalism') || str_contains($title, 'communication') => 'Media & Communication',
            str_contains($category, 'engineering') || str_contains($title, 'civil') || str_contains($title, 'mechanical') => 'Engineering',
            default => 'AI Remote Internships',
        };
    }

    protected static function menuGroupLabel(array $course): string
    {
        return (($course['category'] ?? null) === 'Internship') ? 'Our Programs' : 'AI Remote Internships';
    }

    protected static function formatDuration(?int $months): ?string
    {
        return $months ? $months . ' ' . Str::plural('Month', $months) : null;
    }

    protected static function buildMappedCurriculum(string $courseTitle, string $category, array $modules = []): array
    {
        $projectTitles = self::projectTitlesFor($courseTitle, $modules);
        $projectCategory = self::curriculumCategory($courseTitle, $category);

        return collect(range(1, 50))->map(function (int $projectNo) use ($courseTitle, $projectCategory, $projectTitles) {
            $title = $projectTitles[$projectNo - 1] ?? "Portfolio Project {$projectNo}";

            return [
                'project_no' => $projectNo,
                'title' => "Project {$projectNo}: {$title}",
                'category' => $projectCategory,
                'difficulty' => self::projectDifficulty($projectNo),
                'estimated_hours' => self::projectHours($projectNo),
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

        return collect(array_slice($templates, 0, 4 + ($projectNo % 5)))
            ->values()
            ->map(fn (array $task, int $index) => [
                'task_no' => $index + 1,
                'title' => $task[0],
                'assignment' => "{$task[1]} Apply this to {$projectTitle} for {$courseTitle}.",
                'submission' => $task[2],
                'ai_review' => $task[3],
            ])
            ->all();
    }

    protected static function projectTitlesFor(string $courseTitle, array $modules = []): array
    {
        $title = strtolower($courseTitle . ' ' . implode(' ', array_map('strval', $modules)));

        return match (true) {
            str_contains($title, 'frontend') || str_contains($title, 'web') || str_contains($title, 'react') => self::frontendProjectTitles(),
            str_contains($title, 'ui') || str_contains($title, 'ux') || str_contains($title, 'design') => self::designProjectTitles(),
            str_contains($title, 'data') || str_contains($title, 'machine learning') || str_contains($title, 'analytics') => self::dataProjectTitles(),
            default => self::generalProjectTitles(),
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

    protected static function projectDifficulty(int $projectNo): string
    {
        return match (true) {
            $projectNo <= 12 => 'Beginner',
            $projectNo <= 28 => 'Intermediate',
            $projectNo <= 42 => 'Advanced',
            default => 'Advanced Capstone',
        };
    }

    protected static function projectHours(int $projectNo): int
    {
        return match (true) {
            $projectNo <= 12 => 4 + ($projectNo % 2),
            $projectNo <= 28 => 6 + ($projectNo % 3),
            $projectNo <= 42 => 9 + ($projectNo % 4),
            default => 12 + ($projectNo % 5),
        };
    }

    protected static function frontendProjectTitles(): array
    {
        return [
            'Responsive Landing Page', 'Portfolio Homepage', 'Cafe Menu Interface', 'Pricing Table Experience', 'FAQ Accordion System', 'Image Gallery Layout', 'Agency Landing Page', 'Timeline Interface', 'Dashboard Shell', 'Newsletter Signup Flow',
            'Product Detail Page', 'Blog Article Template', 'Login and Registration UI', 'Responsive Navbar System', 'Card Grid Marketplace', 'Profile Settings Page', 'Checkout Summary UI', 'Admin Table Interface', 'Search Results Page', 'Weather App UI',
            'React State Panel', 'Reusable Button Library', 'Component Props Showcase', 'Tabbed Content UI', 'Form Validation Experience', 'API Data Listing Page', 'Filtered Product Catalog', 'Todo Workflow App', 'Kanban Board Interface', 'Analytics Dashboard',
            'Theme Toggle System', 'Accessible Modal Flow', 'Multi-Step Form Wizard', 'Notification Center UI', 'Calendar Event Layout', 'Responsive Blog Platform', 'E-commerce Cart Experience', 'Portfolio Case Study Page', 'Design System Starter', 'Performance Optimized Landing',
            'Progressive Web App Shell', 'Realtime Chat Interface', 'Role-Based Dashboard UI', 'Headless CMS Frontend', 'Frontend Testing Suite', 'Micro-Interaction Library', 'Data Visualization Screen', 'Full SaaS Marketing Site', 'Capstone Product Interface', 'Frontend Architecture Portfolio',
        ];
    }

    protected static function designProjectTitles(): array
    {
        return [
            'Persona and Problem Framing Board', 'Mobile App Wireframe Set', 'Landing Page Wireframe', 'Moodboard and Visual Direction', 'Design Tokens Starter', 'Button Component States', 'Navigation Pattern Study', 'Signup Flow Redesign', 'Dashboard Information Architecture', 'E-commerce Product Page',
            'Checkout Flow UX', 'Accessibility Contrast Audit', 'Microcopy Improvement Sprint', 'Empty State Design Pack', 'Mobile Banking Screen', 'Food Delivery App Flow', 'Travel Booking Search Flow', 'SaaS Settings Experience', 'Onboarding Journey Map', 'User Interview Synthesis',
            'Competitive UX Audit', 'Clickable Prototype Sprint', 'Usability Test Plan', 'Design System Foundations', 'Responsive Web Mockup', 'Developer Handoff Sheet', 'Figma Auto Layout Library', 'Interactive Component Prototype', 'Admin Dashboard Redesign', 'Healthcare Appointment Flow',
            'EdTech Learning Dashboard', 'FinTech Wallet Flow', 'CRM Contact Management UI', 'B2B Product Trial Flow', 'Mobile App Design System', 'Research to Prototype Case Study', 'Service Blueprint Design', 'Conversion-Focused Landing Page', 'UX Metrics Review', 'Product Requirements to Screens',
            'Advanced Prototype with States', 'Cross-Platform UI Kit', 'Design QA Checklist', 'Stakeholder Presentation Deck', 'Portfolio Case Study Rewrite', 'A/B Variant Design Set', 'Analytics-Informed Redesign', 'End-to-End Product Prototype', 'Capstone Product Design Case Study', 'Portfolio Design System Showcase',
        ];
    }

    protected static function dataProjectTitles(): array
    {
        return [
            'Dataset Cleaning Notebook', 'Sales Summary Dashboard', 'Customer Segmentation Report', 'Survey Insights Analysis', 'Exploratory Data Analysis Pack', 'CSV Quality Audit', 'Excel to Python Workflow', 'KPI Scorecard Dashboard', 'Marketing Funnel Analysis', 'Inventory Trend Report',
            'HR Attrition Insights', 'Financial Expense Tracker', 'Web Traffic Analytics', 'A/B Test Interpretation', 'Correlation Study Notebook', 'Time Series Forecast Starter', 'Classification Baseline Model', 'Regression Prediction Model', 'Model Evaluation Report', 'Feature Engineering Notebook',
            'Power BI Executive Dashboard', 'SQL Reporting Dataset', 'ETL Pipeline Prototype', 'Data Dictionary and Governance Sheet', 'Customer Churn Analysis', 'Sentiment Analysis Mini Project', 'Recommendation Logic Prototype', 'Anomaly Detection Report', 'Big Data Pipeline Blueprint', 'Spark Transformation Exercise',
            'Cloud Data Warehouse Plan', 'Dashboard Storytelling Case Study', 'Model Bias Review', 'Experiment Tracking Sheet', 'API Data Collection Project', 'Automated Data Refresh Workflow', 'Forecasting Dashboard', 'Business Metrics Deep Dive', 'Interactive Analytics App', 'Data Quality Monitoring Plan',
            'ML Deployment Readiness Checklist', 'Advanced Feature Pipeline', 'Stakeholder Insight Memo', 'End-to-End Analytics Case Study', 'Predictive Dashboard Prototype', 'Portfolio Data Story', 'Capstone Decision Intelligence Project', 'Production Analytics Blueprint', 'Advanced ML Portfolio Project', 'Data Science Portfolio Showcase',
        ];
    }

    protected static function generalProjectTitles(): array
    {
        return [
            'Foundation Concept Map', 'Tool Setup and Workflow Board', 'Mini Case Study', 'Process Documentation Pack', 'Starter Portfolio Artifact', 'Industry Terminology Glossary', 'Practical Checklist Build', 'Simple Scenario Analysis', 'Reference Board and Notes', 'Client Brief Interpretation',
            'Workflow Diagram', 'Quality Review Sheet', 'Problem Statement Report', 'Applied Calculation Exercise', 'Tool-Based Practice File', 'Resource Planning Sheet', 'Risk and Assumption Register', 'Draft Output Review', 'Submission-Ready Mini Project', 'Peer Review Improvement',
            'Intermediate Case Study', 'Real-World Dataset or Site Review', 'Standards Alignment Checklist', 'Documentation Sprint', 'Comparative Analysis Report', 'Prototype or Model Build', 'Operational Workflow Plan', 'Stakeholder Summary Memo', 'Metrics and Evaluation Sheet', 'Automation Opportunity Map',
            'Advanced Tool Practice', 'Integrated Project Draft', 'Quality Assurance Review', 'Performance Improvement Plan', 'Compliance and Safety Review', 'Portfolio Case Study', 'Presentation Deck Build', 'Client-Ready Report', 'Multi-Step Workflow Project', 'Decision Matrix Exercise',
            'Advanced Scenario Simulation', 'End-to-End Implementation Plan', 'Review and Remediation Pack', 'Professional Documentation Set', 'Capstone Planning Sprint', 'Capstone Build Sprint', 'Capstone Validation Sprint', 'Capstone Presentation', 'Advanced Portfolio Case Study', 'Professional Portfolio Showcase',
        ];
    }
}
