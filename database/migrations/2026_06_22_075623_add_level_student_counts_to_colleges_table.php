<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->unsignedSmallInteger('students_beginner')->nullable()->after('contact_number');
            $table->unsignedSmallInteger('students_intermediate')->nullable()->after('students_beginner');
            $table->unsignedSmallInteger('students_advanced')->nullable()->after('students_intermediate');
        });
    }

    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropColumn(['students_beginner', 'students_intermediate', 'students_advanced']);
        });
    }
};
