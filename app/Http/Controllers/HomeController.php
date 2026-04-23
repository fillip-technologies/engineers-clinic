<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected array $courseFamilies = [
        'full-stack' => [
            'menu_group' => 'Computer Science & IT',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Build complete web products',
            'career_path' => 'Frontend + backend engineering track',
        ],
        'frontend-development' => [
            'menu_group' => 'Computer Science & IT',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Build strong computing fundamentals',
            'career_path' => 'Computer science and software foundations track',
        ],
        'backend-development' => [
            'menu_group' => 'Computer Science & IT',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Build secure APIs and systems',
            'career_path' => 'Server-side engineering track',
        ],
        'ui-ux-design' => [
            'menu_group' => 'Computer Science & IT',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Shape product experiences end to end',
            'career_path' => 'Product design and UX workflow track',
        ],
        'graphic-design' => [
            'menu_group' => 'Business & Management',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Create strong brand visuals',
            'career_path' => 'Creative design and branding track',
        ],
        'cyber-security' => [
            'menu_group' => 'Computer Science & IT',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Develop a security-first mindset',
            'career_path' => 'Security analysis and audit track',
        ],
        'cloud-computing' => [
            'menu_group' => 'Computer Science & IT',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Deploy and scale modern apps',
            'career_path' => 'Infrastructure and cloud delivery track',
        ],
        'digital-marketing' => [
            'menu_group' => 'Business & Management',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Drive measurable growth campaigns',
            'career_path' => 'Performance marketing track',
        ],
        'seo-search-engine-optimization' => [
            'menu_group' => 'Business & Management',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Improve organic search visibility',
            'career_path' => 'SEO strategy and optimization track',
        ],
        'google-ads' => [
            'menu_group' => 'Business & Management',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Launch paid search campaigns',
            'career_path' => 'Paid acquisition and ads track',
        ],
        'meta-ads-facebook-instagram-ads' => [
            'menu_group' => 'Business & Management',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Scale social performance campaigns',
            'career_path' => 'Paid social and funnel track',
        ],
        'business-analytics' => [
            'menu_group' => 'Business & Management',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Turn reporting into decisions',
            'career_path' => 'Analytics and reporting track',
        ],
        'mobile-app-development' => [
            'menu_group' => 'Computer Science & IT',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Ship mobile products with confidence',
            'career_path' => 'Mobile engineering track',
        ],
    ];

    public function index()
    {
        return view('pages.home');
    }

    public function login()
    {
        return view('pages.login');
    }

    public function redirectDashboardByRole($role)
    {
        if ($role === 'college') {
            return redirect('/college/dashboard');
        }

        return redirect('/dashboard');
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
                    ['label' => 'Password', 'type' => 'password', 'name' => 'college_password', 'placeholder' => 'Create a password'],
                ],
            ],
        ];

        abort_unless(isset($roles[$role]), 404);

        return view('pages.signup', [
            'role' => $role,
            'signup' => $roles[$role],
        ]);
    }

    public function enrolledCourses()
    {
        $enrolledCourses = [
            [
                'title' => 'Full Stack Development Internship',
                'image' => '/images/courses/full-stack-development.svg',
                'instructor' => 'Rajesh Kumar',
                'progress' => 72,
                'status' => 'In Progress',
                'last_accessed' => '2 hours ago',
            ],
            [
                'title' => 'Frontend Development Internship',
                'image' => '/images/courses/frontend-development.svg',
                'instructor' => 'Priya Sharma',
                'progress' => 48,
                'status' => 'In Progress',
                'last_accessed' => 'Yesterday',
            ],
            [
                'title' => 'UI/UX Design Internship',
                'image' => '/images/courses/ui-ux-design.svg',
                'instructor' => 'Amit Tiwari',
                'progress' => 100,
                'status' => 'Completed',
                'last_accessed' => '3 days ago',
            ],
        ];

        return view('dashboard.enrolled-courses', compact('enrolledCourses'));
    }

    public function courseDetail($slug)
    {
        $path = resource_path('data/courses.json');
        $courses = json_decode(File::get($path), true) ?? [];

        $course = collect($courses)->firstWhere('slug', $slug);

        abort_unless($course, 404);

        $meta = $this->courseFamilies[$slug] ?? [
            'menu_group' => 'AI Remote Internships',
            'menu_group_label' => 'Our Programs',
            'hero_badge' => 'Structured practical learning',
            'career_path' => 'Career-focused guided track',
        ];

        $course['menu_group'] = $meta['menu_group'];
        $course['menu_group_label'] = $meta['menu_group_label'];
        $course['hero_badge'] = $meta['hero_badge'];
        $course['career_path'] = $meta['career_path'];

        return view('course.detail', compact('course'));
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
}
