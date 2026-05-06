@props([
    'profile',
])

<section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold text-primary">Profile Details</p>
            <h2 class="mt-2 text-xl font-semibold text-slate-950">Personal information</h2>
        </div>
        <p class="text-sm text-slate-500" x-show="! editMode">Use edit mode to update account details.</p>
    </div>

    <div class="mt-6" x-show="! editMode">
        <dl class="grid gap-4 md:grid-cols-2">
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Name</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['name'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Email</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['email'] }}</dd>
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Age</dt>
                {{-- <dd class="mt-1 text-sm font-medium text-slate-950">{{ $profile['age'] }}</dd> --}}
            </div>
            <div class="rounded-lg border border-slate-200 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Profile photo</dt>
                <dd class="mt-2 flex items-center gap-3">
                    <img src="{{ $profile['avatar'] }}" alt="{{ $profile['name'] }}" class="h-10 w-10 rounded-full object-cover" />
                    <span class="text-sm font-medium text-slate-950">Current photo</span>
                </dd>
            </div>
        </dl>
    </div>

    <form class="mt-6 grid gap-5" x-show="editMode" x-cloak>
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="text-sm font-medium text-slate-700" for="full_name">Name</label>
                <input id="full_name" type="text" value="{{ $profile['name'] }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="email">Email</label>
                <input id="email" type="email" value="{{ $profile['email'] }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="age">Age</label>
                <input id="age" type="text" value="{{ $profile['age'] }}"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700" for="profile_photo">Profile photo</label>
                <input id="profile_photo" type="file"
                    class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200" />
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5">
            <button type="button" @click="editMode = false"
                class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                Save Changes
            </button>
            <button type="button" @click="editMode = false"
                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Cancel
            </button>
        </div>
    </form>
</section>
