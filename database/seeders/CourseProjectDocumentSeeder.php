<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class CourseProjectDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $path = $this->documentPath();
        $courses = $this->parseDocument($path);
        $lookup = $this->courseLookup();
        $updated = 0;
        $skipped = [];

        foreach ($courses as $sourceTitle => $projects) {
            $course = $lookup[Str::slug($sourceTitle)] ?? null;

            if (! $course) {
                $skipped[] = $sourceTitle;
                continue;
            }

            $course->forceFill([
                'curriculum' => $this->buildCurriculum($course, $projects),
                'modules' => $projects
                    ->pluck('category')
                    ->unique()
                    ->values()
                    ->all(),
            ])->save();

            $updated++;
        }

        $this->command?->info("Updated {$updated} courses with project curricula from {$path}.");

        if ($skipped !== []) {
            $this->command?->warn('No matching course found for: ' . implode(', ', $skipped));
        }
    }

    protected function documentPath(): string
    {
        $candidates = array_filter([
            env('EC_PROJECTS_DOCX_PATH'),
            base_path('Projects For EC.docx'),
            storage_path('app/Projects For EC.docx'),
            PHP_OS_FAMILY === 'Windows' ? 'C:\\Users\\dell\\Downloads\\Projects For EC.docx' : null,
        ]);

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        throw new RuntimeException(
            'Projects DOCX not found. Set EC_PROJECTS_DOCX_PATH or place "Projects For EC.docx" in the project root/storage/app.'
        );
    }

    protected function parseDocument(string $path): Collection
    {
        $knownTopics = $this->knownTopicSlugs();
        $courses = collect();
        $currentCourse = null;
        $currentCategory = null;

        foreach ($this->documentLines($path) as $line) {
            if ($topic = $this->topicFromLine($line, $knownTopics)) {
                $currentCourse = $topic;
                $currentCategory = null;
                $courses->put($currentCourse, collect());
                continue;
            }

            if (! $currentCourse) {
                continue;
            }

            if ($category = $this->categoryFromLine($line)) {
                $currentCategory = $category;
                continue;
            }

            foreach ($this->projectCandidates($line) as $candidate) {
                if ($project = $this->projectFromLine($candidate, $currentCategory)) {
                    $courses[$currentCourse]->push($project);
                }
            }
        }

        return $courses->filter(fn (Collection $projects) => $projects->isNotEmpty());
    }

    protected function documentLines(string $path): array
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new RuntimeException("Unable to open projects DOCX at {$path}.");
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (! is_string($xml) || $xml === '') {
            throw new RuntimeException("Unable to read word/document.xml from {$path}.");
        }

        $text = preg_replace('/<w:tab\s*\/>/i', ' ', $xml);
        $text = preg_replace('/<w:br\s*\/>/i', "\n", $text);
        $text = preg_replace('/<\/w:p>/i', "\n", $text);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_XML1, 'UTF-8');

        return collect(preg_split("/\r\n|\n|\r/", $text) ?: [])
            ->map(fn (string $line) => trim(preg_replace('/\s+/u', ' ', $line) ?? ''))
            ->filter()
            ->values()
            ->all();
    }

    protected function knownTopicSlugs(): array
    {
        return collect(config('internship_topics', []))
            ->flatMap(fn (array $level) => collect($level['categories'] ?? [])->flatten())
            ->mapWithKeys(fn (string $topic) => [Str::slug($topic) => $topic])
            ->all();
    }

    protected function topicFromLine(string $line, array $knownTopics): ?string
    {
        $candidate = preg_replace('/^Topic\s*[:-]\s*/i', '', $line);
        $candidate = preg_replace('/^\d+\.\s*/', '', $candidate ?? '');
        $candidate = preg_replace('/\s+topic$/i', '', $candidate ?? '');
        $candidate = trim($candidate ?? '', " .\t\n\r\0\x0B");

        return $knownTopics[Str::slug($candidate)] ?? null;
    }

    protected function categoryFromLine(string $line): ?string
    {
        if (! preg_match('/Category\s+\d+\s*:\s*(.+)$/i', $line, $matches)) {
            return null;
        }

        return trim($matches[1]);
    }

    protected function projectCandidates(string $line): array
    {
        if (preg_match('/\b\d+\.\s+[A-Z]/', $line)) {
            preg_match_all('/\b\d+\.\s+[A-Z].*?(?=\s+\d+\.\s+[A-Z]|$)/', $line, $matches);

            return array_map('trim', $matches[0] ?? []);
        }

        if (
            str_starts_with($line, '(')
            || preg_match('/^Category\b/i', $line)
            || str_contains($line, 'AI Engine Grades')
            || str_contains($line, 'Make.com')
            || str_contains($line, 'master list')
            || str_contains($line, 'will submit')
            || str_starts_with($line, 'Your AI')
            || str_starts_with($line, 'You cannot have')
        ) {
            return [];
        }

        return [$line];
    }

    protected function projectFromLine(string $line, ?string $category): ?array
    {
        $line = trim(preg_replace('/^\d+\.\s*/', '', $line) ?? '');

        if ($line === '' || ! str_contains($line, '(')) {
            return null;
        }

        [$title, $description] = $this->splitProjectText($line);

        if ($title === '') {
            return null;
        }

        return [
            'title' => $title,
            'description' => $description,
            'category' => $category ?: 'Portfolio Projects',
        ];
    }

    protected function splitProjectText(string $line): array
    {
        $open = $this->outerDescriptionStart($line);

        if ($open === null) {
            $open = strpos($line, '(');
        }

        $title = trim(substr($line, 0, $open));
        $description = trim(substr($line, $open + 1));
        $description = trim($description, " )\t\n\r\0\x0B");

        return [$title, $description];
    }

    protected function outerDescriptionStart(string $line): ?int
    {
        $depth = 0;

        for ($index = strlen($line) - 1; $index >= 0; $index--) {
            $char = $line[$index];

            if ($char === ')') {
                $depth++;
            }

            if ($char === '(') {
                $depth--;

                if ($depth === 0) {
                    return $index;
                }
            }
        }

        return null;
    }

    protected function courseLookup(): array
    {
        return Course::query()
            ->get()
            ->mapWithKeys(fn (Course $course) => [
                Str::slug($course->title) => $course,
            ])
            ->all();
    }

    protected function buildCurriculum(Course $course, Collection $projects): array
    {
        return $projects
            ->values()
            ->map(fn (array $project, int $index) => [
                'project_no' => $index + 1,
                'title' => 'Project ' . ($index + 1) . ': ' . $project['title'],
                'category' => $project['category'],
                'difficulty' => $this->difficultyFor($index + 1),
                'estimated_hours' => $this->estimatedHoursFor($index + 1),
                'description' => $project['description'] ?: "Build a practical {$course->title} portfolio project.",
                'tasks' => $this->projectTasks($course->title, $project['title'], $project['description']),
            ])
            ->all();
    }

    protected function difficultyFor(int $projectNo): string
    {
        return match (true) {
            $projectNo <= 14 => 'Beginner',
            $projectNo <= 35 => 'Intermediate',
            $projectNo <= 42 => 'Advanced',
            default => 'Advanced Capstone',
        };
    }

    protected function estimatedHoursFor(int $projectNo): int
    {
        return match (true) {
            $projectNo <= 14 => 4 + ($projectNo % 2),
            $projectNo <= 35 => 6 + ($projectNo % 3),
            $projectNo <= 42 => 10 + ($projectNo % 3),
            default => 12 + ($projectNo % 5),
        };
    }

    protected function projectTasks(string $courseTitle, string $projectTitle, string $description): array
    {
        return [
            [
                'task_no' => 1,
                'title' => 'Define Project Brief',
                'assignment' => "Write the objective, scope, inputs, constraints, and acceptance criteria for {$projectTitle} in {$courseTitle}.",
                'submission' => 'Brief document with objective, scope, assumptions, and acceptance criteria.',
                'ai_review' => 'Checks clarity, scope, realism, and alignment with the selected course topic.',
            ],
            [
                'task_no' => 2,
                'title' => 'Plan Workflow',
                'assignment' => "Break {$projectTitle} into milestones, required tools, source data, validation rules, and final output format.",
                'submission' => 'Milestone checklist with tools, data requirements, and review plan.',
                'ai_review' => 'Checks sequencing, tool fit, completeness, and execution readiness.',
            ],
            [
                'task_no' => 3,
                'title' => 'Build Core Output',
                'assignment' => $description ?: "Create the main working artifact for {$projectTitle}.",
                'submission' => 'Project file, repository, design link, spreadsheet, report, or implementation artifact.',
                'ai_review' => 'Checks whether the artifact is complete, structured, practical, and reviewable.',
            ],
            [
                'task_no' => 4,
                'title' => 'Document and Review',
                'assignment' => "Explain key decisions, test {$projectTitle} against the brief, and record fixes or limitations.",
                'submission' => 'README, decision log, quality checklist, screenshots, or demo notes.',
                'ai_review' => 'Checks reasoning quality, evidence of review, and portfolio presentation readiness.',
            ],
        ];
    }
}
