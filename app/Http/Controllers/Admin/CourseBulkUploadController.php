<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CourseImportHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Throwable;

class CourseBulkUploadController extends Controller
{
    public function index()
    {
        return view('Admin.courses.bulk-upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'json_files' => ['required', 'array'],
            'json_files.*' => ['required', 'file', 'mimetypes:application/json,text/plain,text/json', 'mimes:json,txt'],
        ]);

        $results = [];

        foreach ($request->file('json_files', []) as $file) {
            try {
                $payload = CourseImportHelper::readJsonFile($file);
                $course = Course::updateOrCreate(
                    ['slug' => CourseImportHelper::courseSlug($payload)],
                    CourseImportHelper::coursePayload($payload)
                );

                $results[] = [
                    'file' => $file->getClientOriginalName(),
                    'status' => 'Success',
                    'course' => $course->title,
                    'slug' => $course->slug,
                ];
            } catch (Throwable $e) {
                $results[] = [
                    'file' => $file->getClientOriginalName(),
                    'status' => 'Failed',
                    'message' => $e->getMessage(),
                ];
            }
        }

        return back()->with('results', $results);
    }
}
