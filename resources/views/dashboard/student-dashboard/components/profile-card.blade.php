@props([
    'profile',
])

<section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-4">
            <img src="{{ $profile['avatar'] }}" alt="{{ $profile['name'] }}"
                class="h-16 w-16 rounded-full object-cover ring-2 ring-slate-100 sm:h-20 sm:w-20" />
            <div>
                <p class="text-sm font-semibold text-primary">Account</p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">{{ $profile['name'] }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $profile['email'] }}</p>
            </div>
        </div>

        <button type="button" x-show="! editMode" @click="editMode = true"
            class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
            Edit Profile
        </button>
    </div>
</section>
