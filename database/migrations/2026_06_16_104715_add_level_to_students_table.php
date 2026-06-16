<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('level')->nullable()->after('course_name');
        });

        // Backfill: students who already have enrollments get their earliest
        // enrolled course's level, so the new level gate doesn't lock them
        // out of work they already started. Students with no enrollments
        // are left null until their college assigns a level.
        $rows = DB::table('students')
            ->whereNull('level')
            ->pluck('id');

        foreach ($rows as $studentId) {
            $courseLevel = DB::table('enrollments')
                ->join('courses', 'courses.id', '=', 'enrollments.course_id')
                ->where('enrollments.student_id', $studentId)
                ->orderBy('enrollments.enrollment_date')
                ->value('courses.level');

            if ($courseLevel) {
                DB::table('students')->where('id', $studentId)->update(['level' => $courseLevel]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
