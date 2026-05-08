<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add new columns to courses table if they don't exist
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'slug')) {
                $table->string('slug')->unique()->after('title');
            }
            if (!Schema::hasColumn('courses', 'level')) {
                $table->string('level')->default('Beginner')->after('slug');
            }
            if (!Schema::hasColumn('courses', 'category')) {
                $table->string('category')->default('Internship')->after('level');
            }
            if (!Schema::hasColumn('courses', 'image')) {
                $table->string('image')->nullable()->after('category');
            }
            if (!Schema::hasColumn('courses', 'hero_badge')) {
                $table->text('hero_badge')->nullable()->after('image');
            }
            if (!Schema::hasColumn('courses', 'career_path')) {
                $table->text('career_path')->nullable()->after('hero_badge');
            }
            if (!Schema::hasColumn('courses', 'program_overview')) {
                $table->json('program_overview')->nullable()->after('career_path');
            }
            if (!Schema::hasColumn('courses', 'why_choose')) {
                $table->json('why_choose')->nullable()->after('program_overview');
            }
            if (!Schema::hasColumn('courses', 'testimonials')) {
                $table->json('testimonials')->nullable()->after('why_choose');
            }
            if (!Schema::hasColumn('courses', 'faq')) {
                $table->json('faq')->nullable()->after('testimonials');
            }
            if (!Schema::hasColumn('courses', 'curriculum')) {
                $table->json('curriculum')->nullable()->after('faq');
            }
            if (!Schema::hasColumn('courses', 'modules')) {
                $table->json('modules')->nullable()->after('curriculum');
            }
            if (!Schema::hasColumn('courses', 'phases')) {
                $table->json('phases')->nullable()->after('modules');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'level',
                'category',
                'image',
                'hero_badge',
                'career_path',
                'program_overview',
                'why_choose',
                'testimonials',
                'faq',
                'curriculum',
                'modules',
                'phases',
            ]);
        });
    }
};
