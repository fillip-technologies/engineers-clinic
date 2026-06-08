<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            if (! Schema::hasColumn('colleges', 'payment_reviewed_by')) {
                $table->foreignId('payment_reviewed_by')->nullable()->after('payment_submitted_at')->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('colleges', 'payment_reviewed_at')) {
                $table->timestamp('payment_reviewed_at')->nullable()->after('payment_reviewed_by');
            }

            if (! Schema::hasColumn('colleges', 'payment_rejection_reason')) {
                $table->text('payment_rejection_reason')->nullable()->after('payment_reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            if (Schema::hasColumn('colleges', 'payment_reviewed_by')) {
                $table->dropConstrainedForeignId('payment_reviewed_by');
            }

            foreach (['payment_rejection_reason', 'payment_reviewed_at'] as $column) {
                if (Schema::hasColumn('colleges', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
