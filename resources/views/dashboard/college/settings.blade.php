@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Account</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Settings</h1>
            <p class="mt-3 max-w-2xl text-base leading-8 text-slate-600">
                Keep your college profile, contact details, and login credentials up to date.
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

        <form method="POST" action="{{ route('college.settings.update') }}" class="grid gap-6 lg:grid-cols-2">
            @csrf
            @method('PATCH')

            <div>
                <label for="name" class="text-sm font-semibold text-slate-600">Contact Person</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            <div>
                <label for="email" class="text-sm font-semibold text-slate-600">Login Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            <div>
                <label for="college_name" class="text-sm font-semibold text-slate-600">College Name</label>
                <input id="college_name" name="college_name" type="text" value="{{ old('college_name', $college->college_name) }}" required
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            <div>
                <label for="contact_number" class="text-sm font-semibold text-slate-600">Contact Number</label>
                <input id="contact_number" name="contact_number" type="text" value="{{ old('contact_number', $college->contact_number) }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            <div class="lg:col-span-2">
                <label for="address" class="text-sm font-semibold text-slate-600">Address</label>
                <textarea id="address" name="address" rows="4"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">{{ old('address', $college->address) }}</textarea>
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-slate-600">New Password</label>
                <input id="password" name="password" type="password"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            <div>
                <label for="password_confirmation" class="text-sm font-semibold text-slate-600">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-primarySoft">
            </div>

            <div class="lg:col-span-2">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-primary px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                    Save Settings
                </button>
            </div>
        </form>
    </section>
@endsection
