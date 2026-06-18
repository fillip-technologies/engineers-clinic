<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\CollegeInternshipPurchase;
use App\Models\CollegePaymentTransaction;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function revenue()
    {
        $selfPayRevenue = Payment::where('status', 'success')->sum('amount');

        $collegeRevenue = CollegePaymentTransaction::where('status', 'approved')->sum('amount');

        $selfPayBreakdown = Payment::with('course')
            ->where('status', 'success')
            ->select('course_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('course_id')
            ->latest('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'course' => $row->course?->title ?? 'Unknown',
                'total' => 'Rs. ' . number_format((float) $row->total, 2),
                'count' => $row->count,
            ]);

        $collegePurposeBreakdown = CollegePaymentTransaction::where('status', 'approved')
            ->select('purpose', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('purpose')
            ->get()
            ->map(fn ($row) => [
                'purpose' => ucfirst(str_replace('_', ' ', $row->purpose)),
                'total' => 'Rs. ' . number_format((float) $row->total, 2),
                'count' => $row->count,
            ]);

        $monthly = Payment::where('status', 'success')
            ->selectRaw("DATE_FORMAT(payment_date, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return view('Admin.reports.revenue', compact(
            'selfPayRevenue',
            'collegeRevenue',
            'selfPayBreakdown',
            'collegePurposeBreakdown',
            'monthly'
        ));
    }

    public function seatUtilization()
    {
        $purchases = CollegeInternshipPurchase::with(['college', 'course', 'transaction'])
            ->whereHas('transaction', fn ($q) => $q->where('status', 'approved'))
            ->get()
            ->map(fn (CollegeInternshipPurchase $p) => [
                'college' => $p->college?->college_name ?? 'Unknown',
                'course' => $p->course?->title ?? 'Unknown',
                'seats_purchased' => $p->seats_purchased,
                'seats_used' => $p->seats_used,
                'seats_remaining' => $p->seatsRemaining(),
                'utilization' => $p->seats_purchased > 0
                    ? round($p->seats_used * 100 / $p->seats_purchased) . '%'
                    : '0%',
                'approved_at' => $p->transaction?->reviewed_at?->format('M d, Y') ?? 'N/A',
            ]);

        $totalPurchased = $purchases->sum('seats_purchased');
        $totalUsed = $purchases->sum('seats_used');
        $overallUtilization = $totalPurchased > 0 ? round($totalUsed * 100 / $totalPurchased) . '%' : '0%';

        return view('Admin.reports.seat-utilization', compact('purchases', 'totalPurchased', 'totalUsed', 'overallUtilization'));
    }

    public function enrollments()
    {
        $total = Enrollment::count();

        $byStatus = Enrollment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $bySponsor = Enrollment::select('sponsor_type', DB::raw('COUNT(*) as count'))
            ->groupBy('sponsor_type')
            ->pluck('count', 'sponsor_type')
            ->toArray();

        $byCourse = Enrollment::with('course')
            ->select('course_id',
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active"),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"))
            ->groupBy('course_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'course' => $row->course?->title ?? 'Unknown',
                'total' => (int) $row->total,
                'active' => (int) $row->active,
                'completed' => (int) $row->completed,
            ])
            ->toArray();

        $monthly = Enrollment::selectRaw("DATE_FORMAT(enrollment_date, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get()
            ->map(fn ($row) => ['month' => $row->month, 'count' => (int) $row->count])
            ->toArray();

        return view('Admin.reports.enrollments', compact(
            'total',
            'byStatus',
            'bySponsor',
            'byCourse',
            'monthly'
        ));
    }
}
