@props([
    'course',
])

@php
    $progress = (int) ($course['progress'] ?? 0);
    $status = $course['status'] ?? 'In Progress';
    $isCompleted = strtolower($status) === 'completed';
@endphp

<article class="rounded-[1.5rem] bg-white px-5 py-5 transition duration-200 hover:bg-slate-50 sm:px-6">
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex min-w-0 flex-1 items-start gap-4 sm:gap-5">
            <img src="{{ $course['image'] }}"
                alt="{{ $course['title'] }}"
                class="h-24 w-24 rounded-[1.25rem] object-cover ring-1 ring-slate-200 sm:h-28 sm:w-28" />

            <div class="min-w-0 flex-1">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <h3 class="truncate text-xl font-semibold text-textPrimary">{{ $course['title'] }}</h3>
                        @if (!empty($course['instructor']))
                            <p class="mt-1 text-sm text-textSecondary">Instructor: {{ $course['instructor'] }}</p>
                        @endif
                    </div>

                    <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $isCompleted ? 'bg-emerald-50 text-emerald-600' : 'bg-primarySoft text-primaryLight' }}">
                        {{ $status }}
                    </span>
                </div>

                <div class="mt-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-textMuted">{{ $progress }}% completed</span>
                        @if (!empty($course['last_accessed']))
                            <span class="text-textMuted">Last accessed {{ $course['last_accessed'] }}</span>
                        @endif
                    </div>

                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary to-primaryLight" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center lg:justify-end">
            <a href="#"
                class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Continue Learning
            </a>
        </div>
    </div>
</article>
