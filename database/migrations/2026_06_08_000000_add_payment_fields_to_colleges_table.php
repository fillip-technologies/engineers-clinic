<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            if (! Schema::hasColumn('colleges', 'payment_mode')) {
                $table->string('payment_mode')->nullable()->after('contact_number');
            }

            if (! Schema::hasColumn('colleges', 'utr_number')) {
                $table->string('utr_number')->nullable()->after('payment_mode');
            }

            if (! Schema::hasColumn('colleges', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('utr_number');
            }

            if (! Schema::hasColumn('colleges', 'payment_submitted_at')) {
                $table->timestamp('payment_submitted_at')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            foreach (['payment_submitted_at', 'payment_status', 'utr_number', 'payment_mode'] as $column) {
                if (Schema::hasColumn('colleges', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
