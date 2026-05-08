@props([
    'course',
])

<article class="grid gap-5 rounded-[1.5rem] bg-white px-5 py-5 transition hover:bg-slate-50/70 sm:grid-cols-[5.5rem_minmax(0,1fr)_auto] sm:items-center">
    <div class="overflow-hidden rounded-[1.25rem] bg-slate-100">
        <img src="{{ $course['image'] }}" alt="{{ $course['title'] }}" class="h-20 w-full object-cover sm:h-24" />
    </div>

    <div class="min-w-0">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-start xl:justify-between">
            <div class="min-w-0">
                <h3 class="truncate text-lg font-semibold text-slate-900">{{ $course['title'] }}</h3>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">{{ $course['description'] }}</p>
            </div>

            <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $course['status'] === 'Completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                {{ $course['status'] }}
            </span>
        </div>

        <div class="mt-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0 flex-1">
                <div class="flex items-center justify-between gap-4 text-sm">
                    <p class="font-medium text-slate-700">{{ $course['completed_lessons'] }}/{{ $course['total_lessons'] }} lessons</p>
                    <p class="font-semibold text-slate-500">{{ $course['progress'] }}%</p>
                </div>
                <div class="mt-3 h-1.5 rounded-full bg-slate-200">
                    <div class="h-1.5 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ $course['progress'] }}%"></div>
                </div>
            </div>

            <a href="{{ route('student.course.workspace', ['id' => $course['id']]) }}"
                class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primaryLight">
                Continue Learning
            </a>
        </div>
    </div>
</article>
