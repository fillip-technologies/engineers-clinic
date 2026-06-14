<?php

namespace App\Services;

use App\Imports\EnrollmentImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EnrollmentBulkImportService
{
    /** Allowed MIME types for Excel files — no macros, no XML exploits. */
    private const ALLOWED_MIMES = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
        'application/vnd.ms-excel',                                           // .xls
        'text/csv',                                                            // .csv
        'text/plain',                                                          // .csv on some OS
    ];

    /** Allowed file extensions (checked alongside MIME — both must pass). */
    private const ALLOWED_EXTENSIONS = ['xlsx', 'xls', 'csv'];

    /** Maximum file size in bytes (2 MB). */
    private const MAX_BYTES = 2 * 1024 * 1024;

    /** Maximum rows per upload. */
    private const MAX_ROWS = 200;

    public function validate(UploadedFile $file): array
    {
        $errors = [];

        // 1. File must have actually uploaded
        if (! $file->isValid()) {
            $errors[] = 'The file could not be uploaded. Error code: ' . $file->getError();
            return $errors;
        }

        // 2. Size check
        if ($file->getSize() > self::MAX_BYTES) {
            $errors[] = 'File is too large. Maximum allowed size is 2 MB.';
        }

        // 3. Extension check (case-insensitive)
        $extension = strtolower($file->getClientOriginalExtension());
        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            $errors[] = 'Only .xlsx, .xls, and .csv files are allowed. Got .' . $extension;
        }

        // 4. Real MIME type check (reads file bytes, not just the browser header)
        $mimeType = $file->getMimeType();
        if (! in_array($mimeType, self::ALLOWED_MIMES, true)) {
            $errors[] = 'Invalid file type detected (' . $mimeType . '). Upload a genuine Excel or CSV file.';
        }

        // 5. Block files with macro extensions even if disguised
        $originalName = strtolower($file->getClientOriginalName());
        foreach (['xlsm', 'xlam', 'xltm', 'xla', 'xlb', 'xlc'] as $macro) {
            if (str_ends_with($originalName, '.' . $macro)) {
                $errors[] = 'Macro-enabled Office files (' . $macro . ') are not allowed for security reasons.';
                break;
            }
        }

        // 6. File name safety (no path traversal characters)
        if (preg_match('/[\/\\\\<>:"|?*]/', $file->getClientOriginalName())) {
            $errors[] = 'File name contains invalid characters.';
        }

        // 7. Peek at first 8 bytes for known dangerous signatures
        $handle = fopen($file->getRealPath(), 'rb');
        if ($handle) {
            $magic = fread($handle, 8);
            fclose($handle);

            // Block executable / script headers
            $dangerousSignatures = [
                "\x4D\x5A",             // PE/MZ Windows executable
                "\x7F\x45\x4C\x46",    // ELF binary
                "<?php",                // PHP script
                "<script",              // HTML script injection
            ];

            foreach ($dangerousSignatures as $sig) {
                if (str_starts_with($magic, $sig)) {
                    $errors[] = 'The uploaded file appears to contain executable or script content and was rejected.';
                    break;
                }
            }
        }

        return $errors;
    }

    public function import(UploadedFile $file, int $collegeId): array
    {
        $import = new EnrollmentImport($collegeId);

        try {
            Excel::import($import, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return [
                'results' => [],
                'errors'  => array_map(
                    fn ($failure) => ['row' => $failure->row(), 'message' => implode(', ', $failure->errors())],
                    $e->failures()
                ),
            ];
        } catch (\Throwable $e) {
            Log::error('EnrollmentBulkImport failed', ['error' => $e->getMessage()]);
            return [
                'results' => [],
                'errors'  => [['row' => 'File', 'message' => 'Could not read the file. Ensure it is a valid Excel or CSV and is not password-protected.']],
            ];
        }

        return [
            'results' => $import->results,
            'errors'  => $import->errors,
        ];
    }

    /** Generate and return a CSV template as a string. */
    public function templateCsv(): string
    {
        $headers = ['student_name', 'student_email', 'password', 'course_title', 'status'];

        $example = [
            ['Priya Sharma', 'priya.sharma@example.com', 'SecurePass1', 'Full Stack Web Development', 'ongoing'],
            ['Amit Verma', 'amit.verma@example.com', 'SecurePass2', 'Data Analytics with Power BI', 'ongoing'],
        ];

        $csv = implode(',', $headers) . "\n";
        foreach ($example as $row) {
            $csv .= implode(',', array_map(fn ($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";
        }

        return $csv;
    }
}
