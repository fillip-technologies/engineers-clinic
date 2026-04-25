@extends('layouts.frontend-admin')

@section('content')
    <div class="mx-auto max-w-5xl">
        <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ $profile['avatar'] }}" alt="{{ $profile['name'] }}"
                        class="h-20 w-20 rounded-full object-cover ring-4 ring-blue-100 shadow-md" />
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">My Profile</p>
                        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Edit Profile</h1>
                        <p class="mt-2 text-base text-slate-600">Update your personal details using this frontend-only form.</p>
                    </div>
                </div>

                <a href="{{ route('dashboard.student.profile') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Back to Profile
                </a>
            </div>
        </section>

        <div class="mt-8 grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
            @include('dashboard.student-dashboard.components.profile-avatar', [
                'avatar' => $profile['avatar'],
                'name' => $profile['name'],
            ])

            @include('dashboard.student-dashboard.components.profile-form', ['profile' => $profile])
        </div>
    </div>
@endsection
