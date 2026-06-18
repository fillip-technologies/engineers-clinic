<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\EnrollmentImport;
use App\Models\College;
use App\Models\CollegeInternshipPurchase;
use App\Models\CollegePaymentTransaction;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Services\EnrollmentBulkImportService;
use App\Services\OnboardingMailer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CollegeController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'college_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'contact_person' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $role = Role::firstOrCreate(['name' => 'college']);
            $user = User::create([
                'name' => $validated['contact_person'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $role->id,
            ]);
            $user->college()->create([
                'college_name' => $validated['college_name'],
            ]);
            return $user;
        });

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'College registered successfully.',
            'token' => $token,
            'college_id' => $user->college->id,
        ], 201);
    }

    public function paymentStatus(College $college): JsonResponse
    {
        $this->authorizeCollegeAccess($college);

        $latest = $college->paymentTransactions()
            ->where('purpose', 'dashboard_access')
            ->latest('submitted_at')
            ->first();

        return response()->json([
            'college_id' => $college->id,
            'payment_status' => $college->payment_status,
            'latest_transaction' => $latest ? [
                'id' => $latest->id,
                'status' => $latest->status,
                'amount' => $latest->amount,
                'payment_mode' => $latest->payment_mode,
                'submitted_at' => $latest->submitted_at,
            ] : null,
        ]);
    }

    public function submitPayment(Request $request, College $college): JsonResponse
    {
        $this->authorizeCollegeAccess($college);

        $validated = $request->validate([
            'payment_mode' => ['required', 'in:online,offline'],
            'utr_number' => ['nullable', 'required_if:payment_mode,offline', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $transaction = CollegePaymentTransaction::create([
            'college_id' => $college->id,
            'purpose' => 'dashboard_access',
            'amount' => $validated['amount'],
            'payment_mode' => $validated['payment_mode'],
            'status' => $validated['payment_mode'] === 'offline' ? 'verification_pending' : 'pending',
            'utr_number' => $validated['utr_number'] ?? null,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'message' => 'Payment submitted for review.',
            'transaction_id' => $transaction->id,
            'status' => $transaction->status,
        ], 201);
    }

    public function students(College $college): JsonResponse
    {
        $this->authorizeCollegeAccess($college);

        $students = Student::with(['user', 'enrollments.course'])
            ->where('college_id', $college->id)
            ->get()
            ->map(fn (Student $s) => [
                'id' => $s->id,
                'name' => $s->user?->name,
                'email' => $s->user?->email,
                'level' => $s->level,
                'enrollments_count' => $s->enrollments->count(),
            ]);

        return response()->json(['students' => $students]);
    }

    public function addStudent(Request $request, College $college): JsonResponse
    {
        $this->authorizeCollegeAccess($college);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $password = $validated['password'] ?? \Illuminate\Support\Str::random(12);

        $user = DB::transaction(function () use ($validated, $college, $password) {
            $role = Role::firstOrCreate(['name' => 'student']);
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role_id' => $role->id,
            ]);
            $user->student()->create([
                'college_id' => $college->id,
                'level' => $validated['level'],
            ]);
            app(OnboardingMailer::class)->send($user, $password, 'student');
            return $user;
        });

        return response()->json([
            'message' => 'Student created and welcome email sent.',
            'student_id' => $user->student->id,
        ], 201);
    }

    public function bulkImportStudents(Request $request, College $college): JsonResponse
    {
        $this->authorizeCollegeAccess($college);

        $request->validate(['file' => ['required', 'file']]);

        $file = $request->file('file');
        $service = app(EnrollmentBulkImportService::class);

        $errors = $service->validate($file);
        if (! empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $outcome = $service->import($file, $college->id);

        return response()->json([
            'results' => $outcome['results'],
            'errors' => $outcome['errors'],
            'summary' => [
                'created' => collect($outcome['results'])->where('status', 'created')->count(),
                'enrolled' => collect($outcome['results'])->where('status', 'enrolled')->count(),
                'skipped' => collect($outcome['results'])->where('status', 'skipped')->count(),
                'failed' => count($outcome['errors']),
            ],
        ]);
    }

    public function createInternshipPurchase(Request $request, College $college): JsonResponse
    {
        $this->authorizeCollegeAccess($college);

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'seats' => ['required', 'integer', 'min:1', 'max:500'],
            'payment_mode' => ['required', 'in:online,offline'],
            'utr_number' => ['nullable', 'required_if:payment_mode,offline', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $result = DB::transaction(function () use ($validated, $college) {
            $transaction = CollegePaymentTransaction::create([
                'college_id' => $college->id,
                'purpose' => 'seat_purchase',
                'amount' => $validated['amount'],
                'payment_mode' => $validated['payment_mode'],
                'status' => $validated['payment_mode'] === 'offline' ? 'verification_pending' : 'pending',
                'utr_number' => $validated['utr_number'] ?? null,
                'submitted_at' => now(),
            ]);

            $purchase = CollegeInternshipPurchase::create([
                'college_id' => $college->id,
                'course_id' => $validated['course_id'],
                'transaction_id' => $transaction->id,
                'seats_purchased' => $validated['seats'],
                'seats_used' => 0,
                'price_per_seat' => $validated['amount'] / $validated['seats'],
            ]);

            return ['transaction_id' => $transaction->id, 'purchase_id' => $purchase->id];
        });

        return response()->json([
            'message' => 'Internship seat purchase submitted.',
            ...$result,
        ], 201);
    }

    private function authorizeCollegeAccess(College $college): void
    {
        $user = request()->user();
        $isAdmin = $user?->role?->name === 'admin';
        $isOwner = $user?->college?->id === $college->id;

        abort_unless($isAdmin || $isOwner, 403, 'Access denied.');
    }
}
