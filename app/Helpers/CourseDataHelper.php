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

        usort($courses, fn (array $first, array $second) => strcmp($first['title'], $second['title']));

        return $courses;
    }

    public static function loadCourseBySlug(string $slug): ?array
    {
        $courseFile = resource_path("data/courses/{$slug}.json");

        if (! is_file($courseFile)) {
            return null;
        }

        $course = json_decode(file_get_contents($courseFile), true);

        if (! is_array($course)) {
            return null;
        }

        return self::attachCourseMeta($course);
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
