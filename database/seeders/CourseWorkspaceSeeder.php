<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseWorkspace;
use Illuminate\Database\Seeder;

class CourseWorkspaceSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $courses = collect([
                Course::firstOrCreate(
                    ['slug' => 'full-stack-engineering-bootcamp'],
                    [
                        'title' => 'Full Stack Engineering Bootcamp',
                        'description' => 'Build practical full-stack projects with guided workspace steps.',
                        'level' => 'Beginner',
                        'category' => 'Internship',
                        'duration_months' => 3,
                        'fee' => 0,
                    ]
                ),
            ]);
        }

        foreach ($courses as $course) {
            $workspace = CourseWorkspace::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => 'Developer Portfolio Platform',
                ],
                [
                    'track' => $course->title,
                    'headline' => 'Developer Portfolio Platform',
                    'summary' => 'Follow the steps one by one. Read the explanation, try the code, check the output, and mark the step complete when you are done.',
                    'progress' => 20,
                    'next_milestone' => 'Continue from Authentication',
                    'status' => true,
                ]
            );

            foreach ($this->steps() as $step) {
                $workspace->steps()->updateOrCreate(
                    ['slug' => $step['slug']],
                    [
                        'step_no' => $step['number'],
                        'nav_label' => $step['nav_label'],
                        'title' => $step['title'],
                        'description' => $step['description'],
                        'status' => $step['status'],
                        'state' => $step['state'],
                        'active' => $step['active'],
                        'build_goal' => $step['build'],
                        'why_text' => $step['why'],
                        'lesson' => $step['lesson'],
                        'file_name' => $step['file'],
                        'code_snippet' => $step['code'],
                        'expected_output' => $step['expected_output'],
                        'preview_title' => $step['preview_title'],
                        'task' => $step['task'],
                        'hint' => $step['hint'],
                        'mentor_tip' => $step['mentor_tip'],
                        'preview_points' => $step['preview_points'],
                        'mistakes' => $step['mistakes'],
                        'tips' => $step['tips'],
                        'sort_order' => $step['number'],
                    ]
                );
            }

            foreach ($this->resources() as $category => $items) {
                foreach ($items as $index => $resource) {
                    $workspace->resources()->updateOrCreate(
                        [
                            'category' => $category,
                            'label' => $resource['label'],
                        ],
                        [
                            'description' => $resource['description'],
                            'icon' => $resource['icon'],
                            'href' => $resource['href'],
                            'sort_order' => $index + 1,
                        ]
                    );
                }
            }

            $workspace->goals()->updateOrCreate(
                ['type' => 'daily'],
                [
                    'title' => 'Complete Step 2',
                    'body' => 'Build the login form and check that validation errors are easy to understand.',
                    'duration' => '45-60 min',
                ]
            );
        }
    }

    protected function steps(): array
    {
        return [
            [
                'number' => 1,
                'slug' => 'setup-project',
                'nav_label' => 'Setup Project',
                'title' => 'Setup Project',
                'description' => 'Create a clean Laravel project structure and prepare your first commit.',
                'status' => 'Completed',
                'state' => 'completed',
                'active' => false,
                'build' => 'A clean starter project with Git initialized, routes ready, and a short README.',
                'why' => 'A clear foundation prevents confusion later when the project grows.',
                'lesson' => 'Before building features, make sure your folders, routes, and README explain what the project is about.',
                'file' => 'terminal',
                'code' => "mkdir portfolio-platform\ncd portfolio-platform\ngit init\nphp artisan serve",
                'expected_output' => 'The local Laravel app opens in your browser and your terminal shows the development server URL.',
                'preview_title' => 'Your browser should show the Laravel welcome screen or your starter homepage.',
                'preview_points' => ['Project opens locally', 'README explains the goal', 'Git repository is ready'],
                'task' => 'Create a README.md and write the project goal, features, and setup command.',
                'mistakes' => ['Starting feature work before Git is initialized', 'Keeping the README empty', 'Putting all files in one messy folder'],
                'tips' => ['Commit early with a simple message like setup project foundation.', 'Keep your first version small and working.'],
                'hint' => 'If php artisan serve fails, check that dependencies are installed with composer install.',
                'mentor_tip' => 'A clean setup is not wasted time. It makes debugging much easier later.',
            ],
            [
                'number' => 2,
                'slug' => 'authentication',
                'nav_label' => 'Authentication',
                'title' => 'Authentication',
                'description' => 'Build a simple login flow with validation and clear error messages.',
                'status' => 'In Progress',
                'state' => 'active',
                'active' => true,
                'build' => 'A login page where students can enter email and password safely.',
                'why' => 'Authentication protects private dashboards, student data, and project submissions.',
                'lesson' => 'A beginner-friendly login page should be simple: email, password, validation errors, and one clear submit button.',
                'file' => 'routes/web.php',
                'code' => "Route::get('/login', [AuthController::class, 'showLogin'])->name('login');\nRoute::post('/login', [AuthController::class, 'login'])->name('login.submit');",
                'expected_output' => 'The login page opens. Empty fields show helpful validation messages instead of confusing errors.',
                'preview_title' => 'Your login page should look like a centered form with email, password, and a blue login button.',
                'preview_points' => ['Email input is visible', 'Password input is visible', 'Validation message appears below the field'],
                'task' => 'Create the login form and show validation errors for empty email and password fields.',
                'mistakes' => ['Hiding validation errors', 'Using unclear button text', 'Redirecting users without feedback'],
                'tips' => ['Use labels above inputs.', 'Keep the form narrow so it is easy to scan.', 'Test with empty fields before testing correct login.'],
                'hint' => 'Start with the Blade form first, then connect the controller after the layout is clear.',
                'mentor_tip' => 'Authentication feels hard because it has several small pieces. Build one piece at a time.',
            ],
            [
                'number' => 3,
                'slug' => 'dashboard-ui',
                'nav_label' => 'Dashboard UI',
                'title' => 'Dashboard UI',
                'description' => 'Create the first student dashboard screen with one clear next action.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build' => 'A simple dashboard that welcomes the student and shows what to do next.',
                'why' => 'The dashboard is the student home base. It should reduce confusion, not add more choices.',
                'lesson' => 'Keep the first dashboard version focused: welcome text, progress, and one continue button.',
                'file' => 'dashboard.blade.php',
                'code' => "<section class=\"space-y-4\">\n    <h1>Welcome back</h1>\n    <p>Continue your latest project task.</p>\n    <a href=\"#steps\">Continue learning</a>\n</section>",
                'expected_output' => 'A calm dashboard screen with a welcome message and one obvious next step.',
                'preview_title' => 'Your dashboard should show a welcome message, current project, and continue button.',
                'preview_points' => ['No clutter', 'One primary button', 'Readable spacing on mobile'],
                'task' => 'Build a dashboard header with project name, progress bar, and continue button.',
                'mistakes' => ['Adding too many stats too early', 'Making every button look primary', 'Using tiny text'],
                'tips' => ['Use one main action per section.', 'Check mobile spacing before adding more content.'],
                'hint' => 'Sketch the dashboard on paper first: title, progress, button.',
                'mentor_tip' => 'Simple screens are not incomplete. Simple screens are easier for users to trust.',
            ],
            [
                'number' => 4,
                'slug' => 'crud',
                'nav_label' => 'CRUD',
                'title' => 'CRUD Features',
                'description' => 'Add create, read, update, and delete actions for project records.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build' => 'A small feature where users can add, edit, view, and delete a project item.',
                'why' => 'Most real web apps are built around CRUD. Learning it gives you practical backend confidence.',
                'lesson' => 'Start with Create and Read before adding Update and Delete. This keeps the workflow easier.',
                'file' => 'ProjectController.php',
                'code' => <<<'PHP'
public function store(Request $request)
{
    $data = $request->validate([
        'title' => ['required', 'max:100'],
    ]);

    Project::create($data);

    return redirect()->back();
}
PHP,
                'expected_output' => 'Submitting the form creates a new project item and shows it in the list.',
                'preview_title' => 'Your CRUD screen should show a form on top and a list of saved items below.',
                'preview_points' => ['Create form works', 'Items appear after submit', 'Validation errors are readable'],
                'task' => 'Create the add-project form and show saved items in a simple list.',
                'mistakes' => ['Skipping validation', 'Deleting records without confirmation', 'Mixing too much logic in Blade'],
                'tips' => ['Validate first.', 'Build create/read before update/delete.', 'Use clear empty states.'],
                'hint' => 'If data does not save, check fillable fields on the model.',
                'mentor_tip' => 'CRUD becomes easier when you treat each action as its own small lesson.',
            ],
            [
                'number' => 5,
                'slug' => 'deployment',
                'nav_label' => 'Deployment',
                'title' => 'Deployment',
                'description' => 'Prepare your project for sharing with a mentor or reviewer.',
                'status' => 'Locked',
                'state' => 'locked',
                'active' => false,
                'build' => 'A final project link, screenshots, and notes about what you learned.',
                'why' => 'Deployment turns your local work into something other people can review and use.',
                'lesson' => 'Before sharing, check environment variables, screenshots, README, and basic mobile layout.',
                'file' => '.env.example',
                'code' => "APP_NAME=\"Portfolio Platform\"\nAPP_ENV=production\nAPP_DEBUG=false\nAPP_URL=https://your-project-url.com",
                'expected_output' => 'Your deployed project opens from a public URL without debug errors.',
                'preview_title' => 'Your final project should open from a public link and show the main screen correctly.',
                'preview_points' => ['Public link works', 'README has setup steps', 'Screenshots are attached'],
                'task' => 'Prepare your GitHub link, screenshot, and short learning summary.',
                'mistakes' => ['Leaving APP_DEBUG=true', 'Forgetting screenshots', 'Submitting without testing the link'],
                'tips' => ['Open your link in an incognito window.', 'Add screenshots to your README.', 'Write what you learned in plain words.'],
                'hint' => 'If the deployed page is blank, check logs and environment variables first.',
                'mentor_tip' => 'A clear submission makes your effort easy to review.',
            ],
        ];
    }

    protected function resources(): array
    {
        return [
            'Documentation' => [
                ['label' => 'Laravel Docs', 'description' => 'Routes, controllers, validation, and Blade basics.', 'icon' => 'fi fi-rr-document', 'href' => 'https://laravel.com/docs'],
                ['label' => 'Laravel Validation', 'description' => 'Learn how request validation works.', 'icon' => 'fi fi-rr-shield-check', 'href' => 'https://laravel.com/docs/validation'],
            ],
            'Videos' => [
                ['label' => 'YouTube Laravel Basics', 'description' => 'Watch a beginner route/controller walkthrough.', 'icon' => 'fi fi-rr-play-alt', 'href' => 'https://www.youtube.com/results?search_query=laravel+beginner+project+tutorial'],
                ['label' => 'Authentication Tutorial', 'description' => 'See how login forms are usually built.', 'icon' => 'fi fi-rr-user-lock', 'href' => 'https://www.youtube.com/results?search_query=laravel+authentication+tutorial'],
            ],
            'Examples' => [
                ['label' => 'GitHub Example', 'description' => 'Review a simple Laravel project structure.', 'icon' => 'fi fi-rr-code-branch', 'href' => 'https://github.com/search?q=laravel+portfolio+project&type=repositories'],
                ['label' => 'UI Inspiration', 'description' => 'Look at simple login and dashboard layouts.', 'icon' => 'fi fi-rr-layout-fluid', 'href' => 'https://dribbble.com/search/dashboard-login'],
            ],
        ];
    }
}
