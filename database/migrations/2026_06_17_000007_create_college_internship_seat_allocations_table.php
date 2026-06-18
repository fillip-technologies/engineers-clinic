<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('college_internship_seat_allocations');
        Schema::create('college_internship_seat_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('college_internship_purchases')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->nullOnDelete();
            $table->foreignId('allocated_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('allocated_at')->useCurrent();
            $table->timestamps();

            $table->unique(['purchase_id', 'student_id'], 'cisa_purchase_student_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('college_internship_seat_allocations');
    }
};
