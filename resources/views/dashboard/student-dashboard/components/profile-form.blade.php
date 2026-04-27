@props([
    'profile',
])

<section class="rounded-[1.5rem] border border-slate-200/70 bg-white p-6 shadow-sm">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Profile Details</p>
        <h2 class="mt-3 text-2xl font-semibold text-slate-900">Update your information</h2>
    </div>

    <form class="mt-6 grid gap-6">
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-slate-700" for="full_name">Full Name</label>
                <input id="full_name" type="text" value="{{ $profile['name'] }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100" />
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="email">Email</label>
                <input id="email" type="email" value="{{ $profile['email'] }}"
                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-500 outline-none" readonly />
            </div>
        </div>

        <div class="max-w-sm">
            <label class="text-sm font-medium text-slate-700" for="age">Age</label>
            <input id="age" type="text" value="{{ $profile['age'] }}"
                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100" />
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <button type="button"
                class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:bg-primaryLight">
                Save Changes
            </button>
            <button type="button"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                Cancel
            </button>
        </div>
    </form>
</section>
