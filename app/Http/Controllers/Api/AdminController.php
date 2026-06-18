<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\CollegeInternshipPurchase;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function colleges(Request $request): JsonResponse
    {
        $status = $request->query('status');

        $query = College::with('user')->latest();

        if ($status === 'pending') {
            $query->where('payment_status', 'pending');
        } elseif ($status) {
            $query->where('payment_status', $status);
        }

        $colleges = $query->get()->map(fn (College $c) => [
            'id' => $c->id,
            'college_name' => $c->college_name,
            'contact_person' => $c->user?->name,
            'email' => $c->user?->email,
            'payment_status' => $c->payment_status,
            'payment_mode' => $c->payment_mode,
        ]);

        return response()->json(['colleges' => $colleges]);
    }

    public function approveCollege(College $college): JsonResponse
    {
        $college->update([
            'payment_status' => 'approved',
            'payment_reviewed_by' => Auth::id(),
            'payment_reviewed_at' => now(),
        ]);

        return response()->json(['message' => 'College approved.']);
    }

    public function rejectCollege(Request $request, College $college): JsonResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $college->update([
            'payment_status' => 'rejected',
            'payment_reviewed_by' => Auth::id(),
            'payment_reviewed_at' => now(),
            'payment_rejection_reason' => $validated['reason'],
        ]);

        return response()->json(['message' => 'College rejected.']);
    }

    public function revenueReport(): JsonResponse
    {
        $selfPay = Payment::where('status', 'success')->sum('amount');
        $college = \App\Models\CollegePaymentTransaction::where('status', 'approved')->sum('amount');

        return response()->json([
            'self_pay_revenue' => $selfPay,
            'college_revenue' => $college,
            'total_revenue' => $selfPay + $college,
            'currency' => 'INR',
        ]);
    }

    public function enrollmentsReport(): JsonResponse
    {
        $byStatus = Enrollment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $bySponsor = Enrollment::select('sponsor_type', DB::raw('COUNT(*) as count'))
            ->groupBy('sponsor_type')
            ->pluck('count', 'sponsor_type');

        return response()->json([
            'total' => Enrollment::count(),
            'by_status' => $byStatus,
            'by_sponsor' => $bySponsor,
        ]);
    }

    public function collegesReport(): JsonResponse
    {
        return response()->json([
            'total' => College::count(),
            'approved' => College::where('payment_status', 'approved')->count(),
            'pending' => College::where('payment_status', 'pending')->count(),
            'rejected' => College::where('payment_status', 'rejected')->count(),
        ]);
    }

    public function studentsReport(): JsonResponse
    {
        $byLevel = Student::select('level', DB::raw('COUNT(*) as count'))
            ->groupBy('level')
            ->pluck('count', 'level');

        return response()->json([
            'total' => Student::count(),
            'by_level' => $byLevel,
            'with_enrollments' => Student::has('enrollments')->count(),
        ]);
    }
}
