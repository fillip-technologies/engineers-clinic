<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreignId('seat_allocation_id')
                ->nullable()
                ->after('sponsor_type')
                ->constrained('college_internship_seat_allocations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\CollegeInternshipSeatAllocation::class, 'seat_allocation_id');
            $table->dropColumn('seat_allocation_id');
        });
    }
};
