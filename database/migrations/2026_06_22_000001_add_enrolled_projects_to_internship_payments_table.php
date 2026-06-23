<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('internship_payments', function (Blueprint $table) {
            $table->json('enrolled_projects')->nullable()->after('receipt');
        });
    }

    public function down(): void
    {
        Schema::table('internship_payments', function (Blueprint $table) {
            $table->dropColumn('enrolled_projects');
        });
    }
};
