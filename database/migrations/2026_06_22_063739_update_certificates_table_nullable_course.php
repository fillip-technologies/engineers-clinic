<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->string('certificate_number', 30)->nullable()->unique()->after('certificate_url');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('certificate_number');
        });
    }
};
