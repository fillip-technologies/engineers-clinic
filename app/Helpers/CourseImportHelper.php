<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class CourseImportHelper
{
    public static function readJsonFile(UploadedFile $file): array
    {
        $json = json_decode(file_get_contents($file->getRealPath()), true);

        if (! is_array($json) || json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON format.');
        }

        if (empty($json['title'])) {
            throw new \RuntimeException('Course title missing.');
        }

        return $json;
    }

    public static function courseSlug(array $payload): string
    {
        return Str::slug($payload['slug'] ?? $payload['title']);
    }

    public static function coursePayload(array $payload): array
    {
        return [
            'title' => $payload['title'],
            'slug' => self::courseSlug($payload),
            'description' => $payload['description'] ?? null,
            'level' => $payload['level'] ?? 'Beginner',
            'category' => $payload['category'] ?? 'Internship',
            'image' => $payload['image'] ?? null,
            'hero_badge' => $payload['hero_badge'] ?? null,
            'career_path' => $payload['career_path'] ?? null,
            'duration_months' => self::extractMonths($payload['duration_months'] ?? $payload['duration'] ?? null),
            'fee' => $payload['fee'] ?? 0,
            'modules' => $payload['modules'] ?? [],
            'phases' => $payload['phases'] ?? [],
            'curriculum' => $payload['curriculum'] ?? [],
            'program_overview' => $payload['program_overview'] ?? [],
            'why_choose' => $payload['why_choose'] ?? [],
            'testimonials' => $payload['testimonials'] ?? [],
            'faq' => $payload['faq'] ?? [],
            'outcome' => $payload['outcome'] ?? [],
        ];
    }

    public static function extractMonths(mixed $duration): int
    {
        if (is_numeric($duration)) {
            return max(1, (int) $duration);
        }

        preg_match('/\d+/', (string) $duration, $matches);

        return max(1, (int) ($matches[0] ?? 1));
    }
}
