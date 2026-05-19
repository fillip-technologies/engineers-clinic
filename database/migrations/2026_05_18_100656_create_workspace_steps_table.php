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
        Schema::create('workspace_steps', function (Blueprint $table) {
            $table->id();


            $table->foreignId('workspace_id')
                ->constrained('course_workspaces')
                ->onDelete('cascade');

            $table->integer('step_no')->default(1);

            $table->string('slug')->nullable();

            $table->string('nav_label')->nullable();

            $table->string('title');

            $table->longText('description')->nullable();

            $table->string('status')->default('Locked');

            $table->string('state')->default('locked');

            $table->boolean('active')->default(false);

            $table->longText('build_goal')->nullable();

            $table->longText('why_text')->nullable();

            $table->longText('lesson')->nullable();

            $table->string('file_name')->nullable();

            $table->longText('code_snippet')->nullable();

            $table->longText('expected_output')->nullable();

            $table->longText('preview_title')->nullable();

            $table->longText('task')->nullable();

            $table->longText('hint')->nullable();

            $table->longText('mentor_tip')->nullable();

            $table->json('preview_points')->nullable();

            $table->json('mistakes')->nullable();

            $table->json('tips')->nullable();

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_steps');
    }
};
