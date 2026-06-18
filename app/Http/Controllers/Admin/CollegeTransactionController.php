<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollegePaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollegeTransactionController extends Controller
{
    public function index()
    {
        $pending = CollegePaymentTransaction::with(['college', 'reviewer', 'internshipPurchases.course'])
            ->whereIn('status', ['pending', 'verification_pending'])
            ->latest('submitted_at')
            ->get();

        $recent = CollegePaymentTransaction::with(['college', 'reviewer', 'internshipPurchases.course'])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('reviewed_at')
            ->limit(20)
            ->get();

        return view('Admin.college-transactions.index', compact('pending', 'recent'));
    }

    public function approve(CollegePaymentTransaction $transaction)
    {
        abort_if($transaction->status === 'approved', 422, 'Already approved.');

        $transaction->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Payment transaction approved. College can now allocate seats.');
    }

    public function reject(Request $request, CollegePaymentTransaction $transaction)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection.',
        ]);

        $transaction->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Transaction rejected. College will be notified.');
    }
}
