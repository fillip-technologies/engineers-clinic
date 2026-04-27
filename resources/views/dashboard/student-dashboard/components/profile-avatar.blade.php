@props([
    'avatar',
    'name',
])

<section class="rounded-[1.5rem] border border-slate-200/70 bg-white p-6 shadow-sm">
    <div class="flex flex-col items-center text-center">
        <img src="{{ $avatar }}" alt="{{ $name }}"
            class="h-28 w-28 rounded-full object-cover ring-4 ring-blue-100 shadow-md" />
        <p class="mt-4 text-lg font-semibold text-slate-900">Profile Photo</p>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Update your photo to keep your student profile fresh and recognizable.
        </p>

        <label
            class="mt-5 inline-flex cursor-pointer items-center justify-center rounded-xl bg-primarySoft px-5 py-3 text-sm font-semibold text-primaryLight transition hover:bg-blue-100">
            Change Photo
            <input type="file" class="hidden" />
        </label>
    </div>
</section>
