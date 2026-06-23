@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Account</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Settings</h1>
            <p class="mt-3 max-w-2xl text-base leading-8 text-slate-600">
                Update your login email, display name, and password.
            </p>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.settings.update') }}" class="grid gap-6 lg:grid-cols-2">
            @csrf
            @method('PATCH')

            {{-- Name --}}
            <div>
                <label for="name" class="text-sm font-semibold text-slate-600">Full Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="text-sm font-semibold text-slate-600">Login Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="text-sm font-semibold text-slate-600">Phone Number</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}"
                    placeholder="Optional"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            {{-- Spacer for grid alignment --}}
            <div class="hidden lg:block"></div>

            {{-- Password --}}
            <div>
                <label for="password" class="text-sm font-semibold text-slate-600">New Password</label>
                <input id="password" name="password" type="password" placeholder="Leave blank to keep current"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
                <p class="mt-1.5 text-xs text-slate-400">Minimum 8 characters.</p>
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-semibold text-slate-600">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Re-enter new password"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            <div class="lg:col-span-2 flex items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                    Save Settings
                </button>
                <a href="{{ route('dashboard.student.profile') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Back to Profile
                </a>
            </div>
        </form>
    </section>
@endsection
