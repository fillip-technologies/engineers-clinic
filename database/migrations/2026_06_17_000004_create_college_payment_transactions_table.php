<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('college_payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained()->cascadeOnDelete();
            $table->enum('purpose', ['dashboard_access', 'seat_purchase'])->default('dashboard_access');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_mode', ['online', 'offline']);
            $table->enum('status', ['pending', 'verification_pending', 'approved', 'rejected'])->default('pending');
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->string('utr_number')->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        // Also add payment_proof_path to colleges for Phase 1 screenshot support
        Schema::table('colleges', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('utr_number');
        });
    }

    public function down(): void
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropColumn('payment_proof_path');
        });

        Schema::dropIfExists('college_payment_transactions');
    }
};
