<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('college_internship_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->constrained('college_payment_transactions')->cascadeOnDelete();
            $table->unsignedInteger('seats_purchased');
            $table->unsignedInteger('seats_used')->default(0);
            $table->decimal('price_per_seat', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('college_internship_purchases');
    }
};
