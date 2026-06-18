<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Widen enum to include BOTH old ('ongoing') and new ('active','pending','cancelled')
        // so the UPDATE in step 2 won't be rejected by MySQL strict mode
        DB::statement("ALTER TABLE enrollments MODIFY status ENUM('pending','active','ongoing','completed','cancelled') DEFAULT 'ongoing'");

        // Step 2: Rename old 'ongoing' rows to 'active'
        DB::statement("UPDATE enrollments SET status = 'active' WHERE status = 'ongoing'");

        // Step 3: Remove 'ongoing' from the enum now that no rows use it
        DB::statement("ALTER TABLE enrollments MODIFY status ENUM('pending','active','completed','cancelled') DEFAULT 'pending'");

        if (! Schema::hasColumn('enrollments', 'sponsor_type')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->enum('sponsor_type', ['self', 'college'])->default('self')->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('sponsor_type');
        });

        // Revert active -> ongoing, drop pending and cancelled
        DB::statement("UPDATE enrollments SET status = 'ongoing' WHERE status = 'active'");
        DB::statement("ALTER TABLE enrollments MODIFY status ENUM('ongoing','completed') DEFAULT 'ongoing'");
    }
};
