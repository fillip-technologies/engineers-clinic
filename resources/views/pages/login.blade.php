@extends('layouts.app')

@section('content')

@php
$roles = [
'student' => [
'label' => 'Student',
'signup_label' => 'Sign up as Student',
'signup_url' => route('signup', ['role' => 'student']),
],

'college' => [
'label' => 'College',
'signup_label' => 'Sign up as College',
'signup_url' => route('signup', ['role' => 'college']),
],

'admin' => [
'label' => 'Admin',
],
];

$loginConfig = [
'activeRole' => 'student',
'roles' => $roles,
];
@endphp

<section
    class="relative overflow-hidden bg-gradient-to-br from-[#F8F7FF] via-white to-[#F4F0FF] px-6 py-16 sm:px-10 lg:px-14 lg:py-20">

    <!-- Background Glow -->
    <div
        class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(124,92,252,0.18),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(124,92,252,0.12),_transparent_38%)]">
    </div>

    <div class="absolute left-0 top-20 h-72 w-72 rounded-full bg-[#7C5CFC]/20 blur-3xl"></div>
    <div class="absolute right-0 top-1/3 h-80 w-80 rounded-full bg-[#7C5CFC]/10 blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl" x-data='@json($loginConfig)'>

        <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">

            <!-- LEFT SIDE ROBOT -->
            <div class="relative flex items-center justify-center min-h-[650px]">

                <!-- Glow -->
                <div
                    class="absolute h-[420px] w-[420px] rounded-full bg-[#7C5CFC]/30 blur-[120px]">
                </div>

                <!-- Spline Robot -->
                <div class="relative w-full h-[650px] overflow-hidden">

                    <!-- Purple Glow -->
                    <div
                        class="absolute inset-0 flex items-center justify-center">

                        <div
                            class="w-[420px] h-[420px] rounded-full bg-[#7C5CFC]/30 blur-[120px]">
                        </div>

                    </div>

                    <!-- Spline Robot -->
                    <iframe
                        src='https://my.spline.design/robotfollowcursorforlandingpage-f11Rc2js8cf5Tfzla4AM0K3F/'

                        frameborder="0"
                        width="100%"
                        height="100%"
                        class="relative z-10 h-[calc(100%+72px)] w-full border-0">
                    </iframe>

                </div>

            </div>

            <!-- RIGHT SIDE LOGIN -->
            <div class="w-full">

                <div
                    class="rounded-[2rem] border border-white/50 bg-white/25 p-4 shadow-2xl shadow-[#7C5CFC]/10 backdrop-blur-2xl">

                    <div
                        class="rounded-[1.75rem] border border-white/70 bg-white/90 p-6 shadow-xl backdrop-blur-xl sm:p-8">

                        <!-- Heading -->
                        <div>

                            <p
                                class="text-sm font-semibold uppercase tracking-[0.18em] text-[#7C5CFC]">
                                Secure Portal
                            </p>

                            <h2
                                class="mt-3 text-3xl font-semibold tracking-tight text-[#160840]">
                                Login
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-gray-500">
                                Access your Engineers Clinic portal
                            </p>

                        </div>

                        @if($errors->any())
                            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <ul class="space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Form -->
                        <form method="POST" action="{{ route('loginpost') }}"
                            class="mt-8">

                            @csrf

                            <input type="hidden" name="role"
                                :value="activeRole">

                            <div class="grid gap-5">

                                <!-- Email -->
                                <div>

                                    <label
                                        class="text-sm font-medium text-gray-600">
                                        Email Address
                                    </label>

                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="Enter your email address"
                                        required
                                        class="mt-2 w-full rounded-2xl border border-gray-200 bg-[#F8F7FF] px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/15" />

                                </div>

                                <!-- Password -->
                                <div>

                                    <label
                                        class="text-sm font-medium text-gray-600">
                                        Password
                                    </label>

                                    <input
                                        type="password"
                                        name="password"
                                        placeholder="Enter your password"
                                        required
                                        class="mt-2 w-full rounded-2xl border border-gray-200 bg-[#F8F7FF] px-4 py-3 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/15" />

                                </div>

                            </div>

                            <!-- Login Button -->
                            <button
                                type="submit"
                                class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-[#7C5CFC] to-[#9B7BFF] px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#7C5CFC]/25 transition hover:scale-[1.01]">

                                Login

                            </button>

                            <!-- Roles -->
                            <div class="mt-6 text-center">

                                <p class="text-sm font-medium text-gray-500">
                                    Login as:
                                </p>

                                <div
                                    class="mt-3 flex flex-wrap justify-center gap-3">

                                    <template
                                        x-for="roleKey in ['student', 'college', 'admin']"
                                        :key="roleKey">

                                        <button
                                            type="button"
                                            class="inline-flex items-center justify-center rounded-full border px-4 py-2 text-sm font-semibold transition"
                                            :class="activeRole === roleKey
                                                ? 'border-transparent bg-gradient-to-r from-[#7C5CFC] to-[#9B7BFF] text-white shadow-lg shadow-[#7C5CFC]/20'
                                                : 'border-gray-200 bg-white text-gray-600 hover:border-[#7C5CFC]/40 hover:bg-[#7C5CFC]/5 hover:text-[#160840]'"
                                            @click="activeRole = roleKey"
                                            x-text="roles[roleKey].label">

                                        </button>

                                    </template>

                                </div>

                            </div>

                            <!-- Signup -->
                            <div x-show="roles[activeRole].signup_url"
                                x-cloak class="mt-4">

                                <p class="text-center text-sm text-gray-500">

                                    New here?

                                    <a :href="roles[activeRole].signup_url"
                                        class="font-semibold text-[#7C5CFC] transition hover:text-[#160840]"
                                        x-text="roles[activeRole].signup_label">
                                    </a>

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
