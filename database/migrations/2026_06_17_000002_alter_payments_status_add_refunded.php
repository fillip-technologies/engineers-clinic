<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Widen enum to hold both old ('completed') and new ('success','refunded') values
        DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','completed','success','failed','refunded') DEFAULT 'pending'");

        // Step 2: Rename 'completed' -> 'success'
        DB::statement("UPDATE payments SET status = 'success' WHERE status = 'completed'");

        // Step 3: Remove 'completed' from enum now that no rows use it
        DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','success','failed','refunded') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("UPDATE payments SET status = 'completed' WHERE status = 'success'");
        DB::statement("ALTER TABLE payments MODIFY status ENUM('pending','completed','failed') DEFAULT 'pending'");
    }
};
