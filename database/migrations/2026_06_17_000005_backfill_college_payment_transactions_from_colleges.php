<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrate existing college payment data to the new transactions table.
        // Only migrate rows that have a submitted payment (have a submitted_at timestamp
        // or a utr_number / razorpay payment id).
        DB::table('colleges')
            ->whereNotNull('payment_submitted_at')
            ->orderBy('id')
            ->each(function (object $college) {
                $mode = $college->payment_mode ?? 'offline';

                $status = match ($college->payment_status) {
                    'approved'  => 'approved',
                    'rejected'  => 'rejected',
                    'pending'   => $mode === 'offline' && filled($college->utr_number)
                        ? 'verification_pending'
                        : 'pending',
                    default     => 'pending',
                };

                DB::table('college_payment_transactions')->insert([
                    'college_id'           => $college->id,
                    'purpose'              => 'dashboard_access',
                    'amount'               => $college->payment_amount ?? 0,
                    'payment_mode'         => $mode,
                    'status'               => $status,
                    'razorpay_order_id'    => $college->razorpay_order_id,
                    'razorpay_payment_id'  => $college->razorpay_payment_id,
                    'razorpay_signature'   => $college->razorpay_signature,
                    'utr_number'           => $college->utr_number,
                    'payment_proof_path'   => $college->payment_proof_path ?? null,
                    'submitted_at'         => $college->payment_submitted_at,
                    'reviewed_by'          => $college->payment_reviewed_by,
                    'reviewed_at'          => $college->payment_reviewed_at,
                    'rejection_reason'     => $college->payment_rejection_reason,
                    'created_at'           => $college->payment_submitted_at ?? now(),
                    'updated_at'           => $college->payment_reviewed_at ?? $college->payment_submitted_at ?? now(),
                ]);
            });
    }

    public function down(): void
    {
        // Remove only the rows that were migrated from the colleges table
        // (purpose = dashboard_access). Leave any seat_purchase rows untouched.
        DB::table('college_payment_transactions')
            ->where('purpose', 'dashboard_access')
            ->delete();
    }
};
