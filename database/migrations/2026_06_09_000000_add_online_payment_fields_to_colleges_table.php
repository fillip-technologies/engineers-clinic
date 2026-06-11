<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            if (! Schema::hasColumn('colleges', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->after('payment_status');
            }

            if (! Schema::hasColumn('colleges', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->after('payment_submitted_at');
            }

            if (! Schema::hasColumn('colleges', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            }

            if (! Schema::hasColumn('colleges', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable()->after('razorpay_payment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            foreach (['razorpay_signature', 'razorpay_payment_id', 'razorpay_order_id', 'payment_amount'] as $column) {
                if (Schema::hasColumn('colleges', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
