@extends('layouts.app')

@section('content')
    @php
        $roles = [
            'student' => [
                'label' => 'Student',
                'eyebrow' => 'Learning Portal',
                'title' => 'Student Login',
                'description' =>
                    'Continue your learning journey with access to enrolled modules, saved study material, practice systems, and progress tracking built for real engineering skill development.',
                'signup_label' => 'Sign up as Student',
                'signup_url' => route('signup', ['role' => 'student']),
                'fields' => [
                    ['label' => 'Student Email', 'type' => 'email', 'name' => 'student_email', 'placeholder' => 'Enter your student email'],
                    ['label' => 'Password', 'type' => 'password', 'name' => 'student_password', 'placeholder' => 'Enter your password'],
                ],
            ],
            'admin' => [
                'label' => 'Admin',
                'eyebrow' => 'Platform Control',
                'title' => 'Admin login for platform management and operations',
                'description' =>
                    'Sign in to manage academy operations, review platform activity, publish updates, and monitor internal workflows from one secure control point.',
                'highlights' => ['Manage platform activity', 'Review internal workflows', 'Control content and access'],
                'fields' => [
                    ['label' => 'Admin Email', 'type' => 'email', 'name' => 'admin_email', 'placeholder' => 'Enter your admin email'],
                    ['label' => 'Password', 'type' => 'password', 'name' => 'admin_password', 'placeholder' => 'Enter your password'],
                ],
            ],
            'college' => [
                'label' => 'College',
                'eyebrow' => 'Partner Access',
                'title' => 'College login for partnerships, coordination, and institutional access',
                'description' =>
                    'Use the college portal to manage institutional coordination, review student engagement, and stay aligned with partnership activities across Engineers Clinic.',
                'highlights' => ['Manage institution access', 'Coordinate student activity', 'Review partnership updates'],
                'signup_label' => 'Sign up as College',
                'signup_url' => route('signup', ['role' => 'college']),
                'fields' => [
                    ['label' => 'College Email', 'type' => 'email', 'name' => 'college_email', 'placeholder' => 'Enter your college email'],
                    ['label' => 'Password', 'type' => 'password', 'name' => 'college_password', 'placeholder' => 'Enter your password'],
                ],
            ],
        ];

        $loginConfig = [
            'activeRole' => 'student',
            'roles' => $roles,
            'studentDashboardUrl' => route('dashboard'),
            'collegeDashboardUrl' => route('college.dashboard'),
        ];
    @endphp

    <section
        class="relative overflow-hidden bg-gradient-to-br from-bgMain via-bgWhite to-bgSoft px-6 py-16 sm:px-10 lg:px-14 lg:py-20">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(124,92,252,0.20),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(245,200,66,0.20),_transparent_38%)]">
        </div>
        <div class="absolute left-0 top-20 h-72 w-72 rounded-full bg-brandSoft blur-3xl"></div>
        <div class="absolute right-0 top-1/3 h-80 w-80 rounded-full bg-secondarySoft blur-3xl"></div>

        <div class="relative mx-auto max-w-7xl" x-data='@json($loginConfig)'>
            <div class="grid gap-10 lg:grid-cols-[0.88fr_1.12fr] lg:items-center">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.32em] text-brandLight">Secure Access</p>
                    <h1 class="mt-5 text-4xl font-semibold tracking-tight text-textPrimary sm:text-5xl lg:text-6xl">
                        Login to access
                        <span class="bg-gradient-to-r from-brand to-secondary bg-clip-text text-transparent">
                            your practical learning journey.
                        </span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-textSecondary sm:text-lg">
                        Choose your access type and continue into the Engineers Clinic platform with a cleaner, faster,
                        and more focused login experience.
                    </p>

                </div>

                <div class="w-full">
                    <div
                        class="rounded-[2rem] border border-white/50 bg-white/25 p-4 shadow-2xl shadow-brand/20 backdrop-blur-2xl">
                        <div
                            class="rounded-[1.75rem] border border-white/70 bg-white/85 p-6 shadow-xl shadow-glowPurple backdrop-blur-xl sm:p-8">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Secure Portal</p>
                                <h2 class="mt-3 text-3xl font-semibold tracking-tight text-textPrimary">Login</h2>
                                <p class="mt-2 text-sm leading-6 text-textSecondary">Access your learning portal</p>
                            </div>

                            <form method="POST" action="{{ route('loginpost') }}" class="mt-8">
                                @csrf
                                <input type="hidden" name="role" :value="activeRole">

                                <div class="grid gap-5">
                                    <div>
                                        <label class="text-sm font-medium text-textSecondary" for="email">
                                            Email Address
                                        </label>
                                        <input id="email" type="email" name="email"
                                            placeholder="Enter your email address"
                                            class="mt-2 w-full rounded-2xl border border-borderLight bg-bgSoft px-4 py-3 text-sm text-textPrimary outline-none transition placeholder:text-textMuted focus:border-brand focus:bg-bgWhite focus:ring-4 focus:ring-brandSoft" />
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium text-textSecondary" for="password">
                                            Password
                                        </label>
                                        <input id="password" type="password" name="password"
                                            placeholder="Enter your password"
                                            class="mt-2 w-full rounded-2xl border border-borderLight bg-bgSoft px-4 py-3 text-sm text-textPrimary outline-none transition placeholder:text-textMuted focus:border-brand focus:bg-bgWhite focus:ring-4 focus:ring-brandSoft" />
                                    </div>
                                </div>

                                <button type="submit"
                                    class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-brand to-secondary px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand/25 transition hover:from-brandDark hover:to-secondary">
                                    Login
                                </button>

                                <div class="mt-6 text-center">
                                    <p class="text-sm font-medium text-textSecondary">Login as:</p>
                                    <div class="mt-3 flex flex-wrap justify-center gap-3">
                                        <template x-for="roleKey in ['student', 'college', 'admin']" :key="roleKey">
                                            <button type="button"
                                                class="inline-flex items-center justify-center rounded-full border px-4 py-2 text-sm font-semibold transition"
                                                :class="activeRole === roleKey
                                                    ? 'border-transparent bg-gradient-to-r from-brand to-secondary text-white shadow-lg shadow-brand/25'
                                                    : 'border-borderLight bg-white/70 text-textSecondary hover:border-brand/40 hover:bg-brandSoft hover:text-textPrimary'"
                                                @click="activeRole = roleKey"
                                                x-text="roles[roleKey].label"></button>
                                        </template>
                                    </div>
                                </div>

                                <div x-show="roles[activeRole].signup_url" x-cloak class="mt-4">
                                    <p class="text-center text-sm text-textSecondary">
                                        New here?
                                        <a :href="roles[activeRole].signup_url"
                                            class="font-semibold text-brand transition hover:text-brandDark"
                                            x-text="roles[activeRole].signup_label"></a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
