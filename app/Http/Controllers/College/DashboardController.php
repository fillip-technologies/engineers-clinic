<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\CollegeInternshipPurchase;
use App\Models\CollegeInternshipSeatAllocation;
use App\Models\CollegePaymentTransaction;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Services\EnrollmentBulkImportService;
use App\Services\OnboardingMailer;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // dd($user = Auth::user());
        return view('dashboard.home', $this->frontendAdminData('college-dashboard', 'college'));
    }

    public function payment()
    {
        $college = $this->currentCollegeOrFail();

        return view('dashboard.college.payment', [
            'college' => $college,
            'paymentAmount' => $this->collegePaymentAmount(),
            'razorpayKey' => config('services.razorpay.key'),
            ...$this->frontendAdminData('college-payment'),
        ]);
    }

    public function paymentStore(Request $request, RazorpayService $razorpay)
    {
        $validated = $request->validate([
            'payment_mode' => ['required', Rule::in(['online', 'offline'])],
            'utr_number' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('payment_mode') === 'offline'),
                'string',
                'max:100',
            ],
            'payment_proof' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
        ], [
            'utr_number.required' => 'Please enter the UTR number for offline payment.',
            'payment_proof.mimes' => 'Payment proof must be a JPG, PNG, or PDF file.',
            'payment_proof.max' => 'Payment proof may not be larger than 2 MB.',
        ]);

        $college = $this->currentCollegeOrFail();

        if ($validated['payment_mode'] === 'online') {
            $amount = $this->collegePaymentAmount();

            abort_if($amount <= 0, 422, 'College payment amount is not configured.');

            $receipt = 'college_' . $college->id . '_' . Str::lower(Str::random(8));
            $razorpayOrder = $razorpay->createOrder($amount, $receipt, [
                'college_id' => (string) $college->id,
                'user_id' => (string) Auth::id(),
            ]);

            $college->update([
                'payment_mode' => 'online',
                'utr_number' => null,
                'payment_proof_path' => null,
                'payment_status' => 'pending',
                'payment_amount' => $amount,
                'payment_submitted_at' => now(),
                'razorpay_order_id' => $razorpayOrder->id,
                'razorpay_payment_id' => null,
                'razorpay_signature' => null,
                'payment_reviewed_by' => null,
                'payment_reviewed_at' => null,
                'payment_rejection_reason' => null,
            ]);

            return redirect()->route('college.payment')->with('open_razorpay', true);
        }

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')
                ->store('college-payment-proofs', 'local');
        }

        $college->update([
            'payment_mode' => $validated['payment_mode'],
            'utr_number' => $validated['utr_number'],
            'payment_proof_path' => $proofPath,
            'payment_status' => 'pending',
            'payment_amount' => $this->collegePaymentAmount(),
            'payment_submitted_at' => now(),
            'razorpay_order_id' => null,
            'razorpay_payment_id' => null,
            'razorpay_signature' => null,
            'payment_reviewed_by' => null,
            'payment_reviewed_at' => null,
            'payment_rejection_reason' => null,
        ]);

        return redirect()->route('college.payment')
            ->with('success', 'Offline payment details submitted successfully. Your UTR number is pending admin approval.');
    }

    public function paymentVerify(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_payment_id' => ['required', 'string', 'max:255'],
            'razorpay_order_id' => ['required', 'string', 'max:255'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        $college = $this->currentCollegeOrFail();

        abort_unless($college->payment_mode === 'online', 422, 'Online payment has not been started for this college.');
        abort_unless($college->razorpay_order_id === $validated['razorpay_order_id'], 422, 'The payment order does not match this college.');

        $razorpay->verifyPaymentSignature($validated);

        $college->update([
            'payment_status' => 'approved',
            'payment_submitted_at' => $college->payment_submitted_at ?? now(),
            'razorpay_payment_id' => $validated['razorpay_payment_id'],
            'razorpay_signature' => $validated['razorpay_signature'],
            'payment_reviewed_by' => null,
            'payment_reviewed_at' => now(),
            'payment_rejection_reason' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified. Dashboard access is now active.',
            'redirect_url' => route('college.dashboard'),
        ]);
    }

    public function courses()
    {
        $college = $this->currentCollegeOrFail();

        $courses = Course::query()
            ->withCount([
                'enrollments as college_enrollments_count' => fn ($query) => $query
                    ->whereHas('student', fn ($query) => $query->where('college_id', $college->id)),
                'enrollments as college_completed_count' => fn ($query) => $query
                    ->where('status', 'completed')
                    ->whereHas('student', fn ($query) => $query->where('college_id', $college->id)),
            ])
            ->orderBy('title')
            ->get()
            ->map(fn (Course $course) => [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'level' => $course->level ?? 'Beginner',
                'category' => $course->category ?? 'Internship',
                'duration' => $course->duration_months ? $course->duration_months . ' months' : 'Self paced',
                'fee' => $course->fee !== null ? 'Rs. ' . number_format((float) $course->fee, 2) : 'Free',
                'enrollments' => (int) $course->college_enrollments_count,
                'completed' => (int) $course->college_completed_count,
                'completion' => $course->college_enrollments_count
                    ? round($course->college_completed_count * 100 / $course->college_enrollments_count) . '%'
                    : '0%',
            ]);

        return view('dashboard.college.courses', [
            'courses' => $courses,
            ...$this->frontendAdminData('college-courses'),
        ]);
    }

    public function settings()
    {
        return view('dashboard.college.settings', [
            'college' => $this->currentCollegeOrFail(),
            'user' => Auth::user(),
            ...$this->frontendAdminData('common-settings'),
        ]);
    }

    public function settingsUpdate(Request $request)
    {
        $user = Auth::user();
        $college = $this->currentCollegeOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'college_name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (filled($validated['password'] ?? null)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        $college->update([
            'college_name' => $validated['college_name'],
            'contact_number' => $validated['contact_number'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return redirect()->route('college.settings')->with('success', 'Settings updated successfully.');
    }

    public function index()
    {
        $user = Auth::user();

        // Get college profile
        $college = College::where('user_id', $user->id)->first();

        // Get students for this college (or empty collection if no college)
        $students = $college
            ? Student::where('college_id', $college->id)->with('user')->get()
            : collect();

        // Calculate stats with defaults
        $totalStudents = $students->count();
        $activeInternships = $students->filter(function($student) {
            return $student->enrollments()->whereIn('status', ['active', 'pending'])->exists();
        })->count();
        $completed = $students->filter(function($student) {
            return $student->enrollments()->where('status', 'completed')->exists();
        })->count();

        // Get monthly enrollment data for chart
        $monthlyData = Enrollment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month');

        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData->get($i, 0);
        }

        // Provide default college object if none exists
        if (!$college) {
            $college = (object) ['college_name' => $user->name];
        }

        return view('pages.college.dashboard', compact(
            'college',
            'students',
            'totalStudents',
            'activeInternships',
            'completed',
            'chartData'
        ));
    }

    public function studentManagement()
    {
        return view('dashboard.college.student-management', [
            'students' => $this->collegeStudentManagementData(),
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function studentCreate()
    {
        return view('dashboard.college.student-create', [
            'courseOptions' => $this->collegeStudentCourseOptions(),
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function studentStore(Request $request)
    {
        $college = $this->currentCollegeOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'course_name' => ['nullable', 'string', 'max:255'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
        ]);

        $password = Str::random(12);

        DB::transaction(function () use ($validated, $college, $password) {
            $role = Role::firstOrCreate(['name' => 'student']);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role_id' => $role->id,
            ]);

            $user->student()->create([
                'college_id' => $college->id,
                'course_name' => $validated['course_name'] ?? null,
                'level' => $validated['level'],
            ]);

            app(OnboardingMailer::class)->send($user, $password, 'student');
        });

        return redirect()->route('college.students')->with('success', 'Student account created successfully.');
    }

    public function studentEdit()
    {
        $students = $this->collegeStudentManagementData();

        return view('dashboard.college.student-edit', [
            'student' => $students[1] ?? $students[0] ?? null,
            'courseOptions' => $this->collegeStudentCourseOptions(),
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function studentShow()
    {
        $students = $this->collegeStudentManagementData();

        return view('dashboard.college.student-show', [
            'student' => $students[0] ?? null,
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function enrollments()
    {
        return view('dashboard.college.enrollments.index', [
            'enrollments' => $this->collegeEnrollmentsData(),
            'courses' => $this->collegeEnrollmentCourseOptions(),
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function enrollmentCreate()
    {
        return view('dashboard.college.enrollments.create', [
            'students' => $this->collegeEnrollmentStudentOptions()->values()->toArray(),
            'courses' => $this->collegeEnrollmentCourseOptions(),
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function enrollmentStore(Request $request)
    {
        $college = $this->currentCollegeOrFail();
        $studentIds = $college->students()->pluck('id')->all();

        $isNewStudent = blank($request->input('student_id'));

        $validated = $request->validate([
            'student_id' => [
                'nullable',
                Rule::requiredIf(fn () => blank($request->input('new_student_name')) && blank($request->input('new_student_email'))),
                Rule::in($studentIds),
                Rule::unique('enrollments')->where(fn ($query) => $query->where('course_id', $request->course_id)),
            ],
            'new_student_name' => ['nullable', Rule::requiredIf($isNewStudent), 'string', 'max:255'],
            'new_student_email' => ['nullable', Rule::requiredIf($isNewStudent), 'email', 'max:255', 'unique:users,email'],
            'new_student_password' => ['nullable', Rule::requiredIf($isNewStudent), 'string', 'min:8', 'confirmed'],
            'course_id' => ['required', 'exists:courses,id'],
            'enrollment_date' => ['required', 'date'],
            'status' => ['required', 'in:pending,active,completed,cancelled'],
        ], [
            'student_id.required' => 'Select an existing student or enter new student details.',
            'student_id.in' => 'You can only enroll students from your college.',
            'student_id.unique' => 'This student is already enrolled in the selected course.',
            'new_student_password.required' => 'A password is required when creating a new student account.',
            'new_student_password.confirmed' => 'The passwords do not match.',
            'new_student_password.min' => 'Password must be at least 8 characters.',
        ]);

        $password = $validated['new_student_password'] ?? Str::random(12);

        DB::transaction(function () use ($validated, $college, $password) {
            $student = null;
            $courseLevel = Course::find($validated['course_id'])?->level ?? 'Beginner';

            if (!empty($validated['student_id'])) {
                $student = $college->students()->findOrFail($validated['student_id']);

                if (blank($student->level)) {
                    $student->update(['level' => $courseLevel]);
                }
            } else {
                $role = Role::firstOrCreate(['name' => 'student']);

                $user = User::create([
                    'name' => $validated['new_student_name'],
                    'email' => $validated['new_student_email'],
                    'password' => Hash::make($password),
                    'role_id' => $role->id,
                ]);

                $student = $user->student()->create([
                    'college_id' => $college->id,
                    'course_name' => Course::find($validated['course_id'])?->title,
                    'level' => $courseLevel,
                ]);

                app(OnboardingMailer::class)->send($user, $password, 'student');
            }

            Enrollment::create([
                'student_id'      => $student->id,
                'course_id'       => $validated['course_id'],
                'enrollment_date' => $validated['enrollment_date'],
                'progress'        => 0,
                'status'          => $validated['status'],
                'sponsor_type'    => 'college',
            ]);
        });

        return redirect()->route('college.enrollments')->with('success', 'Enrollment created successfully.');
    }

    public function enrollmentBulkUpload()
    {
        $college = $this->currentCollegeOrFail();

        return view('dashboard.college.enrollments.bulk-upload', [
            'courses' => $this->collegeEnrollmentCourseOptions(),
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function enrollmentBulkUploadStore(Request $request, EnrollmentBulkImportService $importService)
    {
        $college = $this->currentCollegeOrFail();

        // Laravel-level file presence check
        $request->validate([
            'enrollment_file' => ['required', 'file'],
        ], [
            'enrollment_file.required' => 'Please select a file to upload.',
            'enrollment_file.file'     => 'The upload must be a file.',
        ]);

        $file = $request->file('enrollment_file');

        // Deep security validation (MIME, magic bytes, macros, size, name)
        $fileErrors = $importService->validate($file);

        if (! empty($fileErrors)) {
            return back()
                ->withInput()
                ->with('bulk_errors', array_map(fn ($msg) => ['row' => 'File', 'message' => $msg], $fileErrors));
        }

        $outcome = $importService->import($file, $college->id);

        $created  = collect($outcome['results'])->where('status', 'created')->count();
        $enrolled = collect($outcome['results'])->where('status', 'enrolled')->count();
        $skipped  = collect($outcome['results'])->where('status', 'skipped')->count();
        $failed   = count($outcome['errors']);

        return back()->with([
            'bulk_results'  => $outcome['results'],
            'bulk_errors'   => $outcome['errors'],
            'bulk_summary'  => compact('created', 'enrolled', 'skipped', 'failed'),
        ]);
    }

    public function enrollmentBulkTemplate(EnrollmentBulkImportService $importService)
    {
        $csv = $importService->templateCsv();

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="enrollment-template.csv"',
        ]);
    }

    public function enrollmentEdit()
    {
        $enrollments = $this->collegeEnrollmentsData();

        return view('dashboard.college.enrollments.edit', [
            'enrollment' => $enrollments[1] ?? $enrollments[0] ?? null,
            'students' => $this->collegeEnrollmentStudentOptions()->values()->toArray(),
            'courses' => $this->collegeEnrollmentCourseOptions(),
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function enrollmentShow()
    {
        $enrollments = $this->collegeEnrollmentsData();

        return view('dashboard.college.enrollments.show', [
            'enrollment' => $enrollments[0] ?? null,
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    // ── Phase 3: Seat purchase & allocation ───────────────────────────────

    public function internships()
    {
        $college = $this->currentCollegeOrFail();

        $internships = Course::where('type', 'internship')
            ->where('is_sponsorable', true)
            ->orderBy('title')
            ->get()
            ->map(function (Course $course) use ($college) {
                $purchased = CollegeInternshipPurchase::where('college_id', $college->id)
                    ->where('course_id', $course->id)
                    ->whereHas('transaction', fn ($q) => $q->where('status', 'approved'))
                    ->sum('seats_purchased');
                $used = CollegeInternshipPurchase::where('college_id', $college->id)
                    ->where('course_id', $course->id)
                    ->whereHas('transaction', fn ($q) => $q->where('status', 'approved'))
                    ->sum('seats_used');

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'level' => $course->level ?? 'Beginner',
                    'category' => $course->category ?? 'Internship',
                    'duration' => $course->duration_months ? $course->duration_months . ' months' : 'Self paced',
                    'fee' => $course->fee !== null ? 'Rs. ' . number_format((float) $course->fee, 2) : 'Contact us',
                    'seats_purchased' => (int) $purchased,
                    'seats_used' => (int) $used,
                    'seats_available' => max(0, (int) $purchased - (int) $used),
                    'purchase_url' => route('college.internships.purchase.create', $course->id),
                ];
            });

        return view('dashboard.college.internships.index', [
            'internships' => $internships,
            ...$this->frontendAdminData('college-internships'),
        ]);
    }

    public function internshipPurchaseCreate(Course $course)
    {
        abort_unless($course->type === 'internship' && $course->is_sponsorable, 404, 'This course is not available for seat sponsorship.');

        $college = $this->currentCollegeOrFail();

        return view('dashboard.college.internships.purchase', [
            'course' => $course,
            'college' => $college,
            'pricePerSeat' => $course->fee,
            'razorpayKey' => config('services.razorpay.key'),
            ...$this->frontendAdminData('college-internships'),
        ]);
    }

    public function internshipPurchaseStore(Course $course, Request $request, RazorpayService $razorpay)
    {
        abort_unless($course->type === 'internship' && $course->is_sponsorable, 404);

        $college = $this->currentCollegeOrFail();

        $validated = $request->validate([
            'seats' => ['required', 'integer', 'min:1', 'max:500'],
            'payment_mode' => ['required', Rule::in(['online', 'offline'])],
            'utr_number' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('payment_mode') === 'offline'),
                'string',
                'max:100',
            ],
            'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $seats = (int) $validated['seats'];
        $pricePerSeat = (float) ($course->fee ?? 0);
        $totalAmount = $seats * $pricePerSeat;

        if ($validated['payment_mode'] === 'online') {
            abort_if($totalAmount <= 0, 422, 'Course fee is not configured for online payment.');

            $receipt = 'seat_' . $college->id . '_' . $course->id . '_' . Str::lower(Str::random(8));
            $razorpayOrder = $razorpay->createOrder($totalAmount, $receipt, [
                'college_id' => (string) $college->id,
                'course_id' => (string) $course->id,
                'seats' => (string) $seats,
            ]);

            DB::transaction(function () use ($college, $course, $seats, $pricePerSeat, $totalAmount, $razorpayOrder) {
                $transaction = CollegePaymentTransaction::create([
                    'college_id' => $college->id,
                    'purpose' => 'seat_purchase',
                    'amount' => $totalAmount,
                    'payment_mode' => 'online',
                    'status' => 'pending',
                    'razorpay_order_id' => $razorpayOrder->id,
                    'submitted_at' => now(),
                ]);

                CollegeInternshipPurchase::create([
                    'college_id' => $college->id,
                    'course_id' => $course->id,
                    'transaction_id' => $transaction->id,
                    'seats_purchased' => $seats,
                    'seats_used' => 0,
                    'price_per_seat' => $pricePerSeat,
                ]);
            });

            return redirect()->route('college.internships.purchase.create', $course->id)
                ->with('open_razorpay', true)
                ->with('razorpay_order_id', $razorpayOrder->id)
                ->with('razorpay_amount', $totalAmount)
                ->with('pending_seats', $seats)
                ->with('pending_course_id', $course->id);
        }

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('college-payment-proofs', 'local');
        }

        DB::transaction(function () use ($college, $course, $seats, $pricePerSeat, $totalAmount, $validated, $proofPath) {
            $transaction = CollegePaymentTransaction::create([
                'college_id' => $college->id,
                'purpose' => 'seat_purchase',
                'amount' => $totalAmount,
                'payment_mode' => 'offline',
                'status' => filled($validated['utr_number']) ? 'verification_pending' : 'pending',
                'utr_number' => $validated['utr_number'] ?? null,
                'payment_proof_path' => $proofPath,
                'submitted_at' => now(),
            ]);

            CollegeInternshipPurchase::create([
                'college_id' => $college->id,
                'course_id' => $course->id,
                'transaction_id' => $transaction->id,
                'seats_purchased' => $seats,
                'seats_used' => 0,
                'price_per_seat' => $pricePerSeat,
            ]);
        });

        return redirect()->route('college.purchases')
            ->with('success', 'Seat purchase submitted. Admin will verify the payment shortly.');
    }

    public function internshipPurchaseVerify(Course $course, Request $request, RazorpayService $razorpay): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_payment_id' => ['required', 'string', 'max:255'],
            'razorpay_order_id' => ['required', 'string', 'max:255'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        $college = $this->currentCollegeOrFail();

        $transaction = CollegePaymentTransaction::where('college_id', $college->id)
            ->where('purpose', 'seat_purchase')
            ->where('razorpay_order_id', $validated['razorpay_order_id'])
            ->where('status', 'pending')
            ->firstOrFail();

        $razorpay->verifyPaymentSignature($validated);

        $transaction->update([
            'status' => 'approved',
            'razorpay_payment_id' => $validated['razorpay_payment_id'],
            'razorpay_signature' => $validated['razorpay_signature'],
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment verified. Seats are now active.',
            'redirect_url' => route('college.purchases'),
        ]);
    }

    public function purchaseIndex()
    {
        $college = $this->currentCollegeOrFail();

        $purchases = CollegeInternshipPurchase::with(['course', 'transaction'])
            ->where('college_id', $college->id)
            ->latest()
            ->get()
            ->map(fn (CollegeInternshipPurchase $p) => [
                'id' => $p->id,
                'course_title' => $p->course?->title ?? 'Unknown',
                'seats_purchased' => $p->seats_purchased,
                'seats_used' => $p->seats_used,
                'seats_remaining' => $p->seatsRemaining(),
                'price_per_seat' => 'Rs. ' . number_format((float) $p->price_per_seat, 2),
                'total_amount' => 'Rs. ' . number_format($p->seats_purchased * (float) $p->price_per_seat, 2),
                'payment_status' => $p->transaction?->status ?? 'pending',
                'payment_mode' => $p->transaction?->payment_mode ?? 'offline',
                'submitted_at' => $p->transaction?->submitted_at?->format('M d, Y') ?? 'N/A',
                'allocate_url' => route('college.purchases.allocations', $p->id),
                'is_active' => $p->transaction?->status === 'approved',
            ]);

        return view('dashboard.college.internships.purchases', [
            'purchases' => $purchases,
            ...$this->frontendAdminData('college-internships'),
        ]);
    }

    public function seatAllocations(CollegeInternshipPurchase $purchase)
    {
        $college = $this->currentCollegeOrFail();
        abort_unless($purchase->college_id === $college->id, 403);
        abort_unless($purchase->transaction?->status === 'approved', 422, 'Payment not yet approved. Cannot allocate seats.');

        $allocations = CollegeInternshipSeatAllocation::with(['student.user', 'enrollment.course'])
            ->where('purchase_id', $purchase->id)
            ->get()
            ->map(fn (CollegeInternshipSeatAllocation $a) => [
                'id' => $a->id,
                'student_name' => $a->student?->user?->name ?? 'Unknown',
                'student_email' => $a->student?->user?->email ?? '',
                'enrollment_status' => $a->enrollment?->status ?? 'Pending enrollment',
                'allocated_at' => $a->allocated_at?->format('M d, Y') ?? 'N/A',
                'destroy_url' => route('college.purchases.allocations.destroy', [$purchase->id, $a->id]),
            ]);

        $availableStudents = $this->collegeEnrollmentStudentOptions()
            ->filter(fn ($s) => ! $allocations->pluck('student_name')
                ->contains($s['name']));

        return view('dashboard.college.internships.allocations', [
            'purchase' => $purchase,
            'course' => $purchase->course,
            'allocations' => $allocations,
            'availableStudents' => $availableStudents,
            ...$this->frontendAdminData('college-internships'),
        ]);
    }

    public function seatAllocationStore(CollegeInternshipPurchase $purchase, Request $request)
    {
        $college = $this->currentCollegeOrFail();
        abort_unless($purchase->college_id === $college->id, 403);

        $studentIds = $college->students()->pluck('id')->all();

        $validated = $request->validate([
            'student_id' => ['required', 'integer', Rule::in($studentIds)],
        ], [
            'student_id.required' => 'Please select a student.',
            'student_id.in' => 'You can only allocate seats to students in your college.',
        ]);

        DB::transaction(function () use ($purchase, $validated, $college) {
            $freshPurchase = CollegeInternshipPurchase::where('id', $purchase->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_if($freshPurchase->transaction?->status !== 'approved', 422, 'Payment not approved.');
            abort_if($freshPurchase->seats_used >= $freshPurchase->seats_purchased, 422, 'No seats remaining for this purchase.');

            $alreadyAllocated = CollegeInternshipSeatAllocation::where('purchase_id', $purchase->id)
                ->where('student_id', $validated['student_id'])
                ->exists();

            if ($alreadyAllocated) {
                abort(422, 'This student already has a seat allocated for this purchase.');
            }

            $enrollment = Enrollment::firstOrCreate(
                ['student_id' => $validated['student_id'], 'course_id' => $freshPurchase->course_id],
                [
                    'enrollment_date' => now(),
                    'progress' => 0,
                    'status' => 'active',
                    'sponsor_type' => 'college',
                ]
            );

            $allocation = CollegeInternshipSeatAllocation::create([
                'purchase_id' => $freshPurchase->id,
                'student_id' => $validated['student_id'],
                'enrollment_id' => $enrollment->id,
                'allocated_by' => Auth::id(),
                'allocated_at' => now(),
            ]);

            $enrollment->update(['seat_allocation_id' => $allocation->id]);

            $freshPurchase->increment('seats_used');

            $student = Student::with('user')->find($validated['student_id']);
            if ($student?->user) {
                try {
                    app(OnboardingMailer::class)->send($student->user, null, 'student');
                } catch (\Throwable) {
                    // mail failure is non-fatal
                }
            }
        });

        return redirect()->route('college.purchases.allocations', $purchase->id)
            ->with('success', 'Seat allocated and student enrolled successfully.');
    }

    public function seatAllocationDestroy(CollegeInternshipPurchase $purchase, CollegeInternshipSeatAllocation $allocation)
    {
        $college = $this->currentCollegeOrFail();
        abort_unless($purchase->college_id === $college->id, 403);
        abort_unless($allocation->purchase_id === $purchase->id, 403);

        DB::transaction(function () use ($purchase, $allocation) {
            if ($allocation->enrollment_id) {
                Enrollment::where('id', $allocation->enrollment_id)
                    ->update(['status' => 'cancelled', 'seat_allocation_id' => null]);
            }
            $allocation->delete();
            $purchase->decrement('seats_used');
        });

        return redirect()->route('college.purchases.allocations', $purchase->id)
            ->with('success', 'Seat allocation removed and enrollment cancelled.');
    }

    protected function frontendAdminData(string $activePage): array
    {
        $user = Auth::user();

        return [
            'sidebarSections' => $this->dashboardSidebarSections(),
            'activeDashboardPage' => $activePage,
            'sidebarUserName' => $user ? $user->name : 'Guest',
            'sidebarUserMeta' => $user && $user->email ? $user->email : 'Unified Dashboard',
            'navbarUserName' => $user ? explode(' ', $user->name)[0] : 'Guest',
            'collegeStudents' => $this->dashboardCollegeStudents(),
            ...$this->dashboardCollegeOverviewData(),
        ];
    }

    protected function currentCollegeOrFail(): College
    {
        $user = Auth::user();

        abort_unless($user, 403, 'Please login to access your college account.');

        $college = $user->college;

        if (! $college && $user->role?->name === 'college') {
            $college = College::whereNull('user_id')
                ->where('college_name', $user->name)
                ->first();

            if ($college) {
                $college->update(['user_id' => $user->id]);
            } else {
                $college = $user->college()->create([
                    'college_name' => $user->name,
                    'address' => null,
                    'contact_number' => null,
                ]);
            }
        }

        abort_unless($college, 403, 'Your college account is not linked to a college profile.');

        return $college;
    }

    protected function collegePaymentAmount(): float
    {
        return (float) config('services.college_payment.amount', 1000);
    }

    protected function dashboardSidebarSections(): array
    {
        $commonItems = [
            [
                'key' => 'common-settings',
                'label' => 'Settings',
                'icon' => 'fi fi-rr-settings',
                'href' => route('college.settings'),
            ],
            [
                'key' => 'common-logout',
                'label' => 'Logout',
                'icon' => 'fi fi-rr-exit',
                'href' => route('logout'),
                'method' => 'POST',
            ],
        ];

        return [
            [
                'label' => 'For College',
                'items' => [
                    [
                        'key' => 'college-dashboard',
                        'label' => 'Dashboard',
                        'icon' => 'fi fi-rr-apps',
                        'href' => route('college.dashboard'),
                    ],
                    [
                        'key' => 'college-students',
                        'label' => 'Manage Students',
                        'icon' => 'fi fi-rr-users',
                        'href' => route('college.students'),
                    ],
                    [
                        'key' => 'college-enrollments',
                        'label' => 'Enrollments',
                        'icon' => 'fi fi-rr-user-plus',
                        'href' => route('college.enrollments'),
                    ],
                    [
                        'key' => 'college-internships',
                        'label' => 'Internship Seats',
                        'icon' => 'fi fi-rr-rocket',
                        'href' => route('college.internships'),
                    ],
                    [
                        'key' => 'college-purchases',
                        'label' => 'My Purchases',
                        'icon' => 'fi fi-rr-shopping-cart',
                        'href' => route('college.purchases'),
                    ],
                    [
                        'key' => 'college-payment',
                        'label' => 'Payment',
                        'icon' => 'fi fi-rr-credit-card',
                        'href' => route('college.payment'),
                    ],
                    [
                        'key' => 'college-courses',
                        'label' => 'Courses',
                        'icon' => 'fi fi-rr-book-alt',
                        'href' => route('college.courses'),
                    ],
                ],
            ],
            [
                'label' => 'Account',
                'items' => $commonItems,
            ],
        ];
    }

    protected function dashboardCollegeStudents(): array
    {
        $query = Student::with([
            'user',
            'enrollments' => function ($query) {
                $query->latest('enrollment_date')->limit(1)->with('course');
            },
        ]);

        $college = College::where('user_id', Auth::id())->first();
        if ($college) {
            $query->where('college_id', $college->id);
        }

        $students = $query->limit(5)->get()->map(function (Student $student) {
            $latestEnrollment = $student->enrollments->first();
            $progress = $latestEnrollment?->progress;

            return [
                'name' => $student->user?->name ?? 'Unknown Student',
                'email' => $student->user?->email ?? '',
                'course' => $student->course_name ?? $latestEnrollment?->course?->title ?? 'Not enrolled',
                'progress' => $progress !== null ? $progress . '%' : '0%',
                'status' => $latestEnrollment?->status === 'completed' ? 'Completed' : 'Active',
                'joined' => $student->created_at?->diffForHumans() ?? 'Just now',
            ];
        })->toArray();

        return $students ?: array_map(fn (array $student) => [
            'name' => $student['name'],
            'email' => $student['email'],
            'course' => $student['course'],
            'progress' => $student['progress'],
            'status' => $student['status'],
            'joined' => $student['joined'],
        ], $this->collegeDemoStudents());
    }

    protected function dashboardCollegeOverviewData(): array
    {
        $college = College::where('user_id', Auth::id())->first();

        if (! $college) {
            return $this->collegeDashboardFallbackData();
        }

        $studentIds = Student::where('college_id', $college->id)->pluck('id');
        $totalStudents = $studentIds->count();
        $totalEnrollments = Enrollment::whereIn('student_id', $studentIds)->count();
        $completedEnrollments = Enrollment::whereIn('student_id', $studentIds)
            ->where('status', 'completed')
            ->count();
        $activeStudents = Student::where('college_id', $college->id)
            ->whereHas('enrollments', fn ($query) => $query->whereIn('status', ['active', 'pending']))
            ->count();
        $placementRate = $totalEnrollments ? round($completedEnrollments * 100 / $totalEnrollments) : 0;

        $recentStudents = Student::with(['user', 'enrollments.course'])
            ->whereIn('id', $studentIds)
            ->latest('created_at')
            ->limit(4)
            ->get()
            ->map(function (Student $student) {
                $latestEnrollment = $student->enrollments->sortByDesc('enrollment_date')->first();

                return [
                    'name' => $student->user?->name ?? 'Unknown Student',
                    'course' => $student->course_name ?? $latestEnrollment?->course?->title ?? 'Not enrolled',
                    'status' => $latestEnrollment?->status === 'completed' ? 'Completed' : 'Active',
                    'joined' => $student->created_at?->diffForHumans() ?? 'Just now',
                ];
            })->toArray();

        $enrollments = Enrollment::with(['student.user', 'course'])
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('updated_at')
            ->get();

        $topCourses = $enrollments
            ->groupBy(fn (Enrollment $enrollment) => $enrollment->course?->title ?? 'Unknown')
            ->map(function ($group, $courseName) {
                $count = $group->count();
                $completed = $group->where('status', 'completed')->count();

                return [
                    'name' => $courseName,
                    'enrollments' => $count,
                    'completion' => $count ? round($completed * 100 / $count) . '%' : '0%',
                ];
            })
            ->sortByDesc(fn ($course) => $course['enrollments'])
            ->take(4)
            ->values()
            ->toArray();

        if (empty($topCourses)) {
            $topCourses = $this->dashboardCourseCards($college);
        }

        $activities = $enrollments->take(4)->map(function (Enrollment $enrollment) {
            $studentName = $enrollment->student->user?->name ?? 'Student';
            $courseTitle = $enrollment->course?->title ?? 'course';
            $isCompleted = $enrollment->status === 'completed';

            return [
                'title' => $isCompleted
                    ? "{$studentName} completed {$courseTitle}"
                    : "{$studentName} enrolled in {$courseTitle}",
                'time' => $enrollment->updated_at?->diffForHumans() ?? 'Just now',
                'tone' => $isCompleted ? 'green' : 'blue',
            ];
        })->toArray();

        $announcements = Notification::where('user_id', Auth::id())
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn (Notification $notification) => [
                'title' => $notification->message,
                'meta' => 'College update',
            ])->toArray();

        if (empty($announcements)) {
            $announcements = [
                ['title' => 'Placement readiness review scheduled for Friday', 'meta' => 'Academic coordination'],
                ['title' => 'Q3 student engagement report is now available', 'meta' => 'Analytics update'],
                ['title' => 'Internship mentor session opens next week', 'meta' => 'Program notice'],
            ];
        }

        $statCards = [
            [
                'label' => 'Total Students',
                'value' => number_format($totalStudents),
                'change' => '+0%',
                'icon' => 'fi fi-rr-users',
                'classes' => 'from-blue-500/15 to-cyan-400/10 text-blue-700',
            ],
            [
                'label' => 'Active Students',
                'value' => number_format($activeStudents),
                'change' => '+0%',
                'icon' => 'fi fi-rr-chart-line-up',
                'classes' => 'from-violet-500/15 to-indigo-400/10 text-violet-700',
            ],
            [
                'label' => 'Total Enrollments',
                'value' => number_format($totalEnrollments),
                'change' => '+0%',
                'icon' => 'fi fi-rr-book-alt',
                'classes' => 'from-emerald-500/15 to-lime-400/10 text-emerald-700',
            ],
            [
                'label' => 'Placement Rate',
                'value' => "{$placementRate}%",
                'change' => '+0%',
                'icon' => 'fi fi-rr-briefcase',
                'classes' => 'from-orange-500/15 to-amber-400/10 text-orange-700',
            ],
        ];

        $growthRows = Student::where('college_id', $college->id)
            ->selectRaw("DATE_FORMAT(created_at, '%b') as month_label, DATE_FORMAT(created_at, '%Y-%m') as month_key, COUNT(*) as total")
            ->groupBy('month_key', 'month_label')
            ->orderBy('month_key')
            ->limit(7)
            ->get();

        $collegeChartData = [
            'studentGrowth' => [
                'labels' => $growthRows->pluck('month_label')->values()->all(),
                'data' => $growthRows->pluck('total')->map(fn ($value) => (int) $value)->values()->all(),
            ],
            'enrollmentDistribution' => [
                'labels' => collect($topCourses)->pluck('name')->values()->all(),
                'data' => collect($topCourses)->pluck('enrollments')->map(fn ($value) => (int) $value)->values()->all(),
            ],
            'placementStats' => [
                'labels' => ['Completed', 'In progress'],
                'data' => [$completedEnrollments, max($totalEnrollments - $completedEnrollments, 0)],
            ],
            'engagement' => [
                'labels' => ['Active', 'Inactive'],
                'active' => [$activeStudents],
                'inactive' => [max($totalStudents - $activeStudents, 0)],
            ],
        ];

        if ($totalStudents === 1 && $totalEnrollments === 1) {
            return $this->collegeDashboardFallbackData($college);
        }

        return compact('recentStudents', 'topCourses', 'activities', 'announcements', 'statCards', 'collegeChartData');
    }

    protected function collegeDashboardFallbackData(?College $college = null): array
    {
        $topCourses = $this->dashboardCourseCards($college, true);

        return [
            'recentStudents' => array_map(fn (array $student) => [
                'name' => $student['name'],
                'course' => $student['course'],
                'status' => $student['status'],
                'joined' => $student['joined'],
            ], array_slice($this->collegeDemoStudents(), 0, 5)),
            'topCourses' => $topCourses,
            'activities' => [
                ['title' => 'Riya Verma submitted her capstone project review', 'time' => 'Today, 10:30 AM', 'tone' => 'green'],
                ['title' => 'Aarav Sharma enrolled in ' . ($topCourses[0]['name'] ?? 'Full Stack Web Development'), 'time' => 'Today, 09:15 AM', 'tone' => 'blue'],
                ['title' => 'Mentor feedback cycle completed for Data Analytics batch', 'time' => 'Yesterday', 'tone' => 'purple'],
                ['title' => 'Placement readiness report generated for final-year cohort', 'time' => '2 days ago', 'tone' => 'orange'],
                ['title' => 'New UI/UX portfolio sprint opened for second-year students', 'time' => '3 days ago', 'tone' => 'blue'],
            ],
            'announcements' => [
                ['title' => 'June internship orientation starts Monday at 11:00 AM', 'meta' => 'Academic coordination'],
                ['title' => 'Project review window is open for all active batches', 'meta' => 'Program update'],
                ['title' => 'Placement readiness session scheduled for Friday', 'meta' => 'Career services'],
                ['title' => 'Faculty coordinator report will be shared this week', 'meta' => 'Operations'],
            ],
            'statCards' => [
                ['label' => 'Total Students', 'value' => '246', 'change' => '+18%', 'icon' => 'fi fi-rr-users', 'classes' => 'from-blue-500/15 to-cyan-400/10 text-blue-700'],
                ['label' => 'Active Students', 'value' => '193', 'change' => '+14%', 'icon' => 'fi fi-rr-chart-line-up', 'classes' => 'from-violet-500/15 to-indigo-400/10 text-violet-700'],
                ['label' => 'Total Enrollments', 'value' => '318', 'change' => '+22%', 'icon' => 'fi fi-rr-book-alt', 'classes' => 'from-emerald-500/15 to-lime-400/10 text-emerald-700'],
                ['label' => 'Placement Rate', 'value' => '84%', 'change' => '+9%', 'icon' => 'fi fi-rr-briefcase', 'classes' => 'from-orange-500/15 to-amber-400/10 text-orange-700'],
            ],
            'collegeChartData' => [
                'studentGrowth' => ['labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'], 'data' => [42, 58, 74, 106, 151, 193]],
                'enrollmentDistribution' => [
                    'labels' => collect($topCourses)->pluck('name')->take(4)->values()->all(),
                    'data' => collect($topCourses)->pluck('enrollments')->take(4)->values()->all(),
                ],
                'placementStats' => ['labels' => ['Placed / Ready', 'In progress'], 'data' => [84, 16]],
                'engagement' => ['labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'], 'active' => [132, 156, 178, 193], 'inactive' => [39, 32, 24, 18]],
            ],
        ];
    }

    protected function collegeDemoStudents(): array
    {
        return [
            ['name' => 'Aarav Sharma', 'email' => 'aarav.sharma@example.edu', 'course' => 'Full Stack Web Development', 'progress' => '82%', 'status' => 'Active', 'joined' => '2 days ago', 'joined_date' => 'June 09, 2026'],
            ['name' => 'Priya Nair', 'email' => 'priya.nair@example.edu', 'course' => 'Data Analytics with Power BI', 'progress' => '76%', 'status' => 'Active', 'joined' => '5 days ago', 'joined_date' => 'June 06, 2026'],
            ['name' => 'Karan Mehta', 'email' => 'karan.mehta@example.edu', 'course' => 'UI/UX Product Design', 'progress' => '100%', 'status' => 'Completed', 'joined' => '1 week ago', 'joined_date' => 'June 03, 2026'],
            ['name' => 'Simran Kaur', 'email' => 'simran.kaur@example.edu', 'course' => 'Python for AI Projects', 'progress' => '68%', 'status' => 'Active', 'joined' => '2 weeks ago', 'joined_date' => 'May 28, 2026'],
            ['name' => 'Riya Verma', 'email' => 'riya.verma@example.edu', 'course' => 'Cloud DevOps Foundation', 'progress' => '91%', 'status' => 'Active', 'joined' => '3 weeks ago', 'joined_date' => 'May 21, 2026'],
        ];
    }

    protected function dashboardCourseCards(?College $college = null, bool $useDemoMetrics = false): array
    {
        $query = Course::query()->orderBy('title')->limit(4);

        if ($college) {
            $query->withCount([
                'enrollments as college_enrollments_count' => fn ($query) => $query
                    ->whereHas('student', fn ($query) => $query->where('college_id', $college->id)),
                'enrollments as college_completed_count' => fn ($query) => $query
                    ->where('status', 'completed')
                    ->whereHas('student', fn ($query) => $query->where('college_id', $college->id)),
            ]);
        }

        $demoMetrics = [
            ['enrollments' => 92, 'completion' => '86%'],
            ['enrollments' => 78, 'completion' => '81%'],
            ['enrollments' => 64, 'completion' => '74%'],
            ['enrollments' => 84, 'completion' => '79%'],
        ];

        $courses = $query->get()->values()->map(function (Course $course, int $index) use ($college, $useDemoMetrics, $demoMetrics) {
            $metric = $demoMetrics[$index] ?? ['enrollments' => 24, 'completion' => '72%'];
            $enrollments = $useDemoMetrics ? $metric['enrollments'] : ($college ? (int) $course->college_enrollments_count : 0);
            $completed = $college && ! $useDemoMetrics ? (int) $course->college_completed_count : 0;

            return [
                'name' => $course->title,
                'category' => $course->category ?? 'Internship',
                'duration' => $course->duration_months ? $course->duration_months . ' months' : 'Self paced',
                'fee' => $course->fee !== null ? 'Rs. ' . number_format((float) $course->fee, 2) : 'Free',
                'enrollments' => $enrollments,
                'completion' => $useDemoMetrics ? $metric['completion'] : ($enrollments ? round($completed * 100 / $enrollments) . '%' : '0%'),
            ];
        })->toArray();

        return $courses ?: [
            ['name' => 'Full Stack Web Development', 'category' => 'Technology', 'duration' => '6 months', 'fee' => 'Rs. 12,000.00', 'enrollments' => 92, 'completion' => '86%'],
            ['name' => 'Data Analytics with Power BI', 'category' => 'Technology', 'duration' => '4 months', 'fee' => 'Rs. 9,500.00', 'enrollments' => 78, 'completion' => '81%'],
            ['name' => 'UI/UX Product Design', 'category' => 'Design', 'duration' => '3 months', 'fee' => 'Rs. 8,000.00', 'enrollments' => 64, 'completion' => '74%'],
            ['name' => 'Python for AI Projects', 'category' => 'Technology', 'duration' => '4 months', 'fee' => 'Rs. 10,000.00', 'enrollments' => 84, 'completion' => '79%'],
        ];
    }

    protected function collegeStudentManagementData(): array
    {
        $query = Student::with([
            'user',
            'enrollments' => function ($query) {
                $query->latest('enrollment_date')->limit(1)->with('course');
            },
        ]);

        $college = College::where('user_id', Auth::id())->first();
        if ($college) {
            $query->where('college_id', $college->id);
        }

        $students = $query->get()->map(function (Student $student) {
            $latestEnrollment = $student->enrollments->first();

            return [
                'name' => $student->user?->name ?? 'Unknown Student',
                'email' => $student->user?->email ?? '',
                'course' => $student->course_name ?? $latestEnrollment?->course?->title ?? 'Not enrolled',
                'level' => $student->level ?? 'Not set',
                'status' => $latestEnrollment?->status === 'completed' ? 'Completed' : 'Active',
                'joined_date' => $student->created_at?->format('F d, Y') ?? 'N/A',
            ];
        })->toArray();

        return $students ?: array_map(fn (array $student) => [
            'name' => $student['name'],
            'email' => $student['email'],
            'course' => $student['course'],
            'level' => $student['level'] ?? 'Not set',
            'status' => $student['status'],
            'joined_date' => $student['joined_date'],
        ], $this->collegeDemoStudents());
    }

    protected function collegeStudentCourseOptions(): array
    {
        return Course::orderBy('title')->pluck('title')->toArray();
    }

    protected function collegeEnrollmentsData(): array
    {
        $query = Enrollment::with(['student.user', 'course'])
            ->orderBy('enrollment_date', 'desc');

        $college = College::where('user_id', Auth::id())->first();
        if ($college) {
            $query->whereHas('student', fn ($query) => $query->where('college_id', $college->id));
        }

        return $query->get()
            ->map(fn (Enrollment $enrollment) => [
                'student_name' => $enrollment->student->user?->name ?? 'Unknown Student',
                'course_name' => $enrollment->course?->title ?? 'Unknown Course',
                'enrollment_date' => $enrollment->enrollment_date?->format('F d, Y') ?? 'N/A',
                'progress' => $enrollment->progress,
                'status' => match ($enrollment->status) {
                    'completed'  => 'Completed',
                    'cancelled'  => 'Cancelled',
                    'pending'    => 'Pending',
                    default      => 'Active',
                },
                'last_activity' => $enrollment->updated_at?->diffForHumans() ?? 'No activity yet',
            ])->toArray();
    }

    protected function collegeEnrollmentStudentOptions(): \Illuminate\Support\Collection
    {
        $query = Student::with('user')->orderBy('id');

        $college = College::where('user_id', Auth::id())->first();
        if ($college) {
            $query->where('college_id', $college->id);
        }

        return $query->get()
            ->map(fn (Student $student) => [
                'id' => $student->id,
                'name' => $student->user?->name ?? 'Unknown Student',
                'email' => $student->user?->email,
            ]);
    }

    protected function collegeEnrollmentCourseOptions(): array
    {
        return Course::orderBy('title')
            ->get(['id', 'title'])
            ->map(fn (Course $course) => [
                'id' => $course->id,
                'title' => $course->title,
            ])
            ->toArray();
    }
}
