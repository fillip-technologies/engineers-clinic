<?php

namespace App\Http\Controllers;

use App\Helpers\CourseDataHelper;
use App\Models\College;
use App\Models\Course;
use App\Models\Role;
use App\Models\User;
use App\Services\OnboardingMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $courses = CourseDataHelper::loadAllCourses();

        return view('pages.home', compact('courses'));
    }

    public function collegeTieup()
    {
        return view('pages.college-tieup');
    }

    public function companyBranding()
    {
        return view('pages.company-branding');
    }

    public function login()
    {
        return view('pages.login');
    }

    public function redirectDashboardByRole($role)
    {
        return redirect()->route('dashboard');
    }

    public function signup($role)
    {
        $roles = [
            'student' => [
                'label' => 'Student',
                'eyebrow' => 'Learning Access',
                'title' => 'Create your student account',
                'description' => 'Join Engineers Clinic to explore practical learning, internship-ready modules, and guided progress built for engineering students.',
                'button' => 'Sign up as Student',
                'fields' => [
                    ['label' => 'Full Name', 'type' => 'text', 'name' => 'student_name', 'placeholder' => 'Enter your full name'],
                    ['label' => 'Student Email', 'type' => 'email', 'name' => 'student_email', 'placeholder' => 'Enter your student email'],
                    ['label' => 'College Name', 'type' => 'text', 'name' => 'student_college', 'placeholder' => 'Enter your college name'],
                    ['label' => 'Password', 'type' => 'password', 'name' => 'student_password', 'placeholder' => 'Create a password'],
                    ['label' => 'Confirm Password', 'type' => 'password', 'name' => 'student_password_confirmation', 'placeholder' => 'Confirm your password'],
                ],
            ],
            'college' => [
                'label' => 'College',
                'eyebrow' => 'Partnership Access',
                'title' => 'Create your college account',
                'description' => 'Register your institution to coordinate partnerships, track student engagement, and connect with Engineers Clinic programs.',
                'button' => 'Sign up as College',
                'fields' => [
                    ['label' => 'College Name', 'type' => 'text', 'name' => 'college_name', 'placeholder' => 'Enter your college name'],
                    ['label' => 'Official Email', 'type' => 'email', 'name' => 'college_email', 'placeholder' => 'Enter your official email'],
                    ['label' => 'Contact Person', 'type' => 'text', 'name' => 'college_contact', 'placeholder' => 'Enter contact person name'],
                    [
                        'label'  => 'Number of Students (Level-wise)',
                        'hint'   => 'How many students do you plan to enrol at each level?',
                        'type'   => 'level_group',
                        'levels' => [
                            ['label' => 'Beginner',     'name' => 'students_beginner',     'dot' => 'bg-emerald-500'],
                            ['label' => 'Intermediate', 'name' => 'students_intermediate', 'dot' => 'bg-blue-500'],
                            ['label' => 'Advanced',     'name' => 'students_advanced',     'dot' => 'bg-violet-500'],
                        ],
                    ],
                    ['label' => 'Password', 'type' => 'password', 'name' => 'college_password', 'placeholder' => 'Create a password'],
                    ['label' => 'Confirm Password', 'type' => 'password', 'name' => 'college_password_confirmation', 'placeholder' => 'Confirm your password'],
                ],
            ],
        ];

        abort_unless(isset($roles[$role]), 404);

        $data = [
            'role' => $role,
            'signup' => $roles[$role],
        ];

        if ($role === 'student') {
            $colleges = College::all()->pluck('college_name', 'id')->toArray();
            $data['colleges'] = $colleges;
            $data['signup']['fields'][2]['type'] = 'select';
            $data['signup']['fields'][2]['options'] = ['other' => 'Other'] + $colleges;
            array_splice($data['signup']['fields'], 3, 0, [[
                'label' => 'Enter Your College Name',
                'type' => 'text',
                'name' => 'student_college_other',
                'placeholder' => 'e.g. ABC Institute of Technology',
                'conditional' => true,
            ]]);
        }

        return view('pages.signup', $data);
    }

    public function signupSubmit(Request $request, string $role)
    {
        $roles = ['student', 'college'];
        abort_unless(in_array($role, $roles, true), 404);

        if ($role === 'student') {
            $rules = [
                'student_name' => 'required|string|max:255',
                'student_email' => 'required|string|email|max:255|unique:users,email',
                'student_college' => 'required|string',
                'student_password' => 'required|string|min:8|confirmed',
            ];

            if ($request->student_college !== 'other') {
                $rules['student_college'] = 'required|exists:colleges,id';
            }

            if ($request->student_college === 'other') {
                $rules['student_college_other'] = 'required|string|max:255';
            }

            $validated = $request->validate($rules);

            $roleRecord = Role::firstOrCreate(['name' => 'student']);

            $user = User::create([
                'name' => $validated['student_name'],
                'email' => $validated['student_email'],
                'password' => Hash::make($validated['student_password']),
                'role_id' => $roleRecord->id,
            ]);

            if ($validated['student_college'] === 'other') {
                $college = College::create([
                    'user_id' => null,
                    'college_name' => $validated['student_college_other'],
                    'address' => null,
                    'contact_number' => null,
                ]);
            } else {
                $college = College::findOrFail($validated['student_college']);
            }

            $user->student()->create([
                'college_id' => $college->id,
                'course_name' => null,
            ]);

            app(OnboardingMailer::class)->send($user, $validated['student_password'], 'student');
        } else {
            $validated = $request->validate([
                'college_name'          => 'required|string|max:255',
                'college_email'         => 'required|string|email|max:255|unique:users,email',
                'college_contact'       => 'required|string|max:255',
                'college_password'      => 'required|string|min:8|confirmed',
                'students_beginner'     => 'nullable|integer|min:0|max:9999',
                'students_intermediate' => 'nullable|integer|min:0|max:9999',
                'students_advanced'     => 'nullable|integer|min:0|max:9999',
            ]);

            $roleRecord = Role::firstOrCreate(['name' => 'college']);

            $user = User::create([
                'name'     => $validated['college_contact'],
                'email'    => $validated['college_email'],
                'password' => Hash::make($validated['college_password']),
                'role_id'  => $roleRecord->id,
            ]);

            $user->college()->create([
                'college_name'          => $validated['college_name'],
                'address'               => null,
                'contact_number'        => null,
                'students_beginner'     => $validated['students_beginner'] ?? null,
                'students_intermediate' => $validated['students_intermediate'] ?? null,
                'students_advanced'     => $validated['students_advanced'] ?? null,
            ]);

            app(OnboardingMailer::class)->send($user, $validated['college_password'], 'college');
        }

        Auth::login($user);

        return match ($role) {
            'college' => redirect()->route('college.payment'),
            'student' => redirect()->intended('/dashboard'),
        };
    }

    public function courseDetail($slug)
    {
        return $this->show($slug);
    }

    public function show($slug)
    {
        $course = CourseDataHelper::loadCourseBySlug($slug);

        abort_unless($course, 404);

        $dbCourse = Course::where('slug', $slug)->first();

        return view('course.detail', compact('course', 'dbCourse'));
    }

    public function about()
    {
        $about = [
            'eyebrow' => 'About Engineers Clinic',
            'title' => 'We build internship-first learning that feels practical from day one.',
            'description' => 'Engineers Clinic helps students and early professionals move from theory to execution with guided programs, clear learning paths, and industry-shaped skill tracks.',
            'pillars' => [
                [
                    'title' => 'Structured tracks',
                    'description' => 'Programs are grouped like our mega menu so learners can move quickly toward the domain that fits their goals.',
                ],
                [
                    'title' => 'Project-led learning',
                    'description' => 'Every path is designed around implementation, output, and confidence building rather than passive theory.',
                ],
                [
                    'title' => 'Career clarity',
                    'description' => 'From enrollment to guidance, we focus on helping learners understand where each skill track can take them.',
                ],
            ],
            'stats' => [
                ['value' => '10+', 'label' => 'Career-focused learning tracks'],
                ['value' => 'Project-led', 'label' => 'Hands-on delivery model'],
                ['value' => 'Student-first', 'label' => 'Built for accessible guidance'],
            ],
        ];

        return view('pages.about', compact('about'));
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function comingSoonEnterpriseServices()
    {
        return view('pages.coming-soon', [
            'title' => 'Enterprise Services & Products — Coming Soon',
            'description' => 'We\'re building a marketplace for verified services and products tailored for students, colleges, and young professionals. Stay tuned.',
            'features' => ['Service Listings', 'Verified Sellers', 'Student Discounts'],
        ]);
    }

    public function comingSoonAiTools()
    {
        return view('pages.coming-soon', [
            'title' => 'AI Tools — Coming Soon',
            'description' => 'Smart AI-powered tools to help you with resume building, interview prep, code reviews, and career planning. Launching soon.',
            'features' => ['AI Resume Builder', 'Interview Coach', 'Code Reviewer'],
        ]);
    }

    public function comingSoonBlog()
    {
        return view('pages.coming-soon', [
            'title' => 'Blog — Coming Soon',
            'description' => 'Insights, tutorials, career tips, and success stories from the Engineers Clinic community. We\'re preparing great content for you.',
            'features' => ['Career Guides', 'Tech Tutorials', 'Student Stories'],
        ]);
    }
}
