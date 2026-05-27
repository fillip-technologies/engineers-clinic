<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email');
            $table->string('course_slug')->nullable();
            $table->string('course_title')->nullable();
            $table->boolean('consent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_enquiries');
    }
};
