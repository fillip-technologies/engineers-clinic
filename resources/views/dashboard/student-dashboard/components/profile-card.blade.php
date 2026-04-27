@props([
    'profile',
])

<section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <img src="{{ $profile['avatar'] }}" alt="{{ $profile['name'] }}"
                class="h-20 w-20 rounded-full object-cover ring-4 ring-blue-100 shadow-md" />
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">My Profile</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">{{ $profile['name'] }}</h1>
                <p class="mt-2 text-base text-slate-600">{{ $profile['email'] }}</p>
            </div>
        </div>

        <a href="{{ route('dashboard.student.profile.edit') }}"
            class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
            Edit Profile
        </a>
    </div>
</section>
