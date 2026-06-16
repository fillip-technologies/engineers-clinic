<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Services\OnboardingMailer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;

class EnrollmentImport implements ToCollection, WithHeadingRow, WithLimit
{
    public array $results = [];
    public array $errors  = [];

    private int $collegeId;
    private array $courseCache = [];
    private array $emailCache  = [];

    public function __construct(int $collegeId)
    {
        $this->collegeId = $collegeId;
    }

    public function limit(): int
    {
        return 200;
    }

    public function collection(Collection $rows): void
    {
        if ($rows->isEmpty()) {
            $this->errors[] = ['row' => 'File', 'message' => 'The uploaded file contains no data rows.'];
            return;
        }

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 because row 1 is the heading

            $data = $this->sanitizeRow($row->toArray());

            $validation = $this->validateRow($data, $rowNumber);
            if ($validation !== null) {
                $this->errors[] = $validation;
                continue;
            }

            $course = $this->resolveCourse($data['course_title']);
            if (! $course) {
                $this->errors[] = [
                    'row'     => $rowNumber,
                    'message' => "Row {$rowNumber}: Course \"{$data['course_title']}\" not found. Check the course title matches exactly.",
                ];
                continue;
            }

            try {
                $this->processRow($data, $course, $rowNumber);
            } catch (\Throwable $e) {
                Log::error('EnrollmentImport row failed', ['row' => $rowNumber, 'error' => $e->getMessage()]);
                $this->errors[] = ['row' => $rowNumber, 'message' => "Row {$rowNumber}: Unexpected error — " . $e->getMessage()];
            }
        }
    }

    private function sanitizeRow(array $raw): array
    {
        $sanitized = [];

        foreach ($raw as $key => $value) {
            // Strip null bytes, leading/trailing whitespace, HTML tags
            $clean = is_string($value)
                ? strip_tags(str_replace("\0", '', trim((string) $value)))
                : $value;

            // Truncate excessively long values to prevent abuse
            if (is_string($clean) && mb_strlen($clean) > 500) {
                $clean = mb_substr($clean, 0, 500);
            }

            $sanitized[strtolower((string) $key)] = $clean;
        }

        return $sanitized;
    }

    private function validateRow(array $data, int $rowNumber): ?array
    {
        $rules = [
            'student_name'  => ['required', 'string', 'max:255'],
            'student_email' => ['required', 'email:rfc', 'max:255'],
            'password'      => ['required', 'string', 'min:8', 'max:128'],
            'course_title'  => ['required', 'string', 'max:255'],
            'status'        => ['nullable', 'string', 'in:ongoing,completed,active'],
        ];

        $validator = Validator::make($data, $rules, [
            'student_name.required'  => "Row {$rowNumber}: Student name is required.",
            'student_name.max'       => "Row {$rowNumber}: Student name is too long (max 255 chars).",
            'student_email.required' => "Row {$rowNumber}: Email is required.",
            'student_email.email'    => "Row {$rowNumber}: \"{$data['student_email']}\" is not a valid email.",
            'password.required'      => "Row {$rowNumber}: Password is required.",
            'password.min'           => "Row {$rowNumber}: Password must be at least 8 characters.",
            'password.max'           => "Row {$rowNumber}: Password is too long.",
            'course_title.required'  => "Row {$rowNumber}: Course title is required.",
            'status.in'              => "Row {$rowNumber}: Status must be \"ongoing\" or \"completed\".",
        ]);

        if ($validator->fails()) {
            return ['row' => $rowNumber, 'message' => $validator->errors()->first()];
        }

        // Block obviously malicious email patterns
        $email = strtolower($data['student_email']);
        if (
            str_contains($email, '..') ||
            preg_match('/[<>\'";{}\\\\]/', $email) ||
            preg_match('/^\./', $email) ||
            preg_match('/\.$/', $email)
        ) {
            return ['row' => $rowNumber, 'message' => "Row {$rowNumber}: Email \"{$data['student_email']}\" contains invalid characters."];
        }

        // Block SQL / script injection patterns in name
        if (preg_match('/(<script|javascript:|on\w+=|UNION\s+SELECT|DROP\s+TABLE|INSERT\s+INTO)/i', $data['student_name'])) {
            return ['row' => $rowNumber, 'message' => "Row {$rowNumber}: Student name contains disallowed content."];
        }

        // Prevent duplicate emails within the same upload
        $emailKey = strtolower($data['student_email']);
        if (isset($this->emailCache[$emailKey])) {
            return ['row' => $rowNumber, 'message' => "Row {$rowNumber}: Email \"{$data['student_email']}\" appears more than once in the file."];
        }
        $this->emailCache[$emailKey] = true;

        return null;
    }

    private function resolveCourse(string $title): ?Course
    {
        $key = strtolower(trim($title));

        if (! array_key_exists($key, $this->courseCache)) {
            $this->courseCache[$key] = Course::whereRaw('LOWER(title) = ?', [$key])->first();
        }

        return $this->courseCache[$key];
    }

    private function processRow(array $data, Course $course, int $rowNumber): void
    {
        $email  = strtolower(trim($data['student_email']));
        $status = in_array(strtolower((string) ($data['status'] ?? '')), ['completed'], true) ? 'completed' : 'ongoing';

        DB::transaction(function () use ($data, $email, $course, $status, $rowNumber) {
            $existingUser = User::where('email', $email)->first();
            $isNew        = $existingUser === null;

            if ($isNew) {
                $role = Role::firstOrCreate(['name' => 'student']);

                $user = User::create([
                    'name'     => $data['student_name'],
                    'email'    => $email,
                    'password' => Hash::make($data['password']),
                    'role_id'  => $role->id,
                ]);

                $student = $user->student()->firstOrCreate([
                    'college_id'  => $this->collegeId,
                    'course_name' => $course->title,
                ], [
                    'level' => $course->level ?? 'Beginner',
                ]);
            } else {
                $user    = $existingUser;
                $student = Student::where('user_id', $user->id)->first();

                if (! $student) {
                    $student = $user->student()->create([
                        'college_id'  => $this->collegeId,
                        'course_name' => $course->title,
                        'level'       => $course->level ?? 'Beginner',
                    ]);
                } elseif ((int) $student->college_id !== (int) $this->collegeId) {
                    $this->errors[] = [
                        'row'     => $rowNumber,
                        'message' => "Row {$rowNumber}: Email \"{$email}\" is registered to a different college.",
                    ];
                    return;
                } elseif (blank($student->level)) {
                    $student->update(['level' => $course->level ?? 'Beginner']);
                }
            }

            // Skip if already enrolled in this course
            $alreadyEnrolled = Enrollment::where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->exists();

            if ($alreadyEnrolled) {
                $this->results[] = [
                    'row'    => $rowNumber,
                    'name'   => $data['student_name'],
                    'email'  => $email,
                    'course' => $course->title,
                    'status' => 'skipped',
                    'note'   => 'Already enrolled — skipped.',
                ];
                return;
            }

            Enrollment::create([
                'student_id'      => $student->id,
                'course_id'       => $course->id,
                'enrollment_date' => now(),
                'progress'        => 0,
                'status'          => $status,
            ]);

            if ($isNew) {
                try {
                    app(OnboardingMailer::class)->send($user, $data['password'], 'student');
                } catch (\Throwable $e) {
                    Log::warning('Bulk import: welcome email failed', ['email' => $email, 'error' => $e->getMessage()]);
                }
            }

            $this->results[] = [
                'row'    => $rowNumber,
                'name'   => $data['student_name'],
                'email'  => $email,
                'course' => $course->title,
                'status' => $isNew ? 'created' : 'enrolled',
                'note'   => $isNew ? 'Account created & email sent.' : 'Enrolled in course.',
            ];
        });
    }
}
