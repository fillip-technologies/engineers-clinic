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
        Schema::create('course_workspaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')
            ->constrained('courses')
            ->onDelete('cascade');

            $table->string('title');

            $table->string('track')->nullable();

            $table->string('headline')->nullable();

            $table->longText('summary')->nullable();

            $table->integer('progress')->default(0);

            $table->string('next_milestone')->nullable();

            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_workspaces');
    }
};
