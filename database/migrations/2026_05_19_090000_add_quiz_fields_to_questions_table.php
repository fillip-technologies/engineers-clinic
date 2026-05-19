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
        Schema::table('questions', function (Blueprint $table) {
            if (! Schema::hasColumn('questions', 'quiz_id')) {
                $table->foreignId('quiz_id')->after('id')->constrained()->cascadeOnDelete();
            }

            if (! Schema::hasColumn('questions', 'question_text')) {
                $table->text('question_text')->after('quiz_id');
            }

            if (! Schema::hasColumn('questions', 'option_a')) {
                $table->string('option_a')->after('question_text');
            }

            if (! Schema::hasColumn('questions', 'option_b')) {
                $table->string('option_b')->after('option_a');
            }

            if (! Schema::hasColumn('questions', 'option_c')) {
                $table->string('option_c')->after('option_b');
            }

            if (! Schema::hasColumn('questions', 'option_d')) {
                $table->string('option_d')->after('option_c');
            }

            if (! Schema::hasColumn('questions', 'correct_option')) {
                $table->enum('correct_option', ['a', 'b', 'c', 'd'])->after('option_d');
            }

            if (! Schema::hasColumn('questions', 'marks')) {
                $table->unsignedInteger('marks')->default(1)->after('correct_option');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
