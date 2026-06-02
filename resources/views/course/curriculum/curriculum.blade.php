@props(['course'])

@php
$curriculum = $course['curriculum'] ?? [];
// dd($curriculum);
$totalModules = count($curriculum);
@endphp

<section id="curriculum" class="relative overflow-hidden bg-gradient-to-b from-bgWhite via-bgMain/60 to-bgSoft px-6 py-20 sm:px-10 lg:px-14">
    <div
        class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(34,197,94,0.08),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(249,115,22,0.08),_transparent_34%)]">
    </div>
    <div class="pointer-events-none absolute -left-16 top-20 h-56 w-56 rounded-full bg-brandSoft blur-3xl"></div>
    <div class="pointer-events-none absolute -right-10 bottom-16 h-64 w-64 rounded-full bg-secondarySoft blur-3xl"></div>

    <div class="relative mx-auto max-w-6xl" x-data="{ activeModule: 0 }">
        <div class="mx-auto max-w-3xl text-center">
            <span
                class="inline-flex items-center rounded-full border border-brand/10 bg-brandSoft px-4 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-brand">
                Curriculum
            </span>
            <h2 class="mt-5 text-3xl font-semibold leading-tight text-textPrimary sm:text-4xl">
                Build practical skills through structured module work
            </h2>
            <p class="mt-4 text-base leading-8 text-textSecondary">
                Every module is sequenced to help learners move from guided basics to stronger, portfolio-worthy execution.
            </p>
        </div>

        {{-- <div class="mt-8 rounded-[1.75rem] border border-borderLight bg-white/80 p-5 shadow-[0_16px_40px_rgba(15,23,42,0.05)] backdrop-blur sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-textMuted">Learning path</p>
                    <p class="mt-2 text-lg font-semibold text-textPrimary">{{ $totalModules }} modules with applied tasks</p>
                </div>
                <div class="flex items-center gap-3">
                    @foreach ($curriculum as $index => $module)
                    <span class="h-2.5 w-12 rounded-full bg-borderLight transition-all duration-300"
                        :class="activeModule >= {{ $index }} ? 'bg-brand' : 'bg-borderLight'"></span>
                    @endforeach
                </div>
            </div>
        </div> --}}

        <div class="mt-10 space-y-4">
            @foreach ($curriculum as $index => $module)
            <div
                class="overflow-hidden rounded-[1.9rem] border border-borderLight bg-white shadow-[0_16px_40px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-0.5 hover:shadow-[0_22px_55px_rgba(15,23,42,0.08)]"
                :class="activeModule === {{ $index }} ? 'border-brand/30 shadow-[0_26px_70px_rgba(21,128,61,0.12)]' : ''">
                <button type="button"
                    class="flex w-full items-center justify-between gap-4 px-6 py-6 text-left sm:px-7"
                    @click="activeModule = activeModule === {{ $index }} ? -1 : {{ $index }}"
                    :aria-expanded="(activeModule === {{ $index }}).toString()">
                    <div class="flex min-w-0 items-start gap-4 sm:gap-5">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-bgMain text-sm font-semibold text-textPrimary transition duration-300"
                            :class="activeModule === {{ $index }} ? 'bg-brand text-white shadow-lg shadow-brand/20' : ''">
                            {{ $index + 1 }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand">
                                    Module {{ $index + 1 }}
                                </p>
                                <span
                                    class="inline-flex rounded-full border border-borderLight bg-bgMain px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.16em] text-textMuted">
                                    {{ count($module['tasks'] ?? []) }} tasks
                                </span>
                            </div>
                            <h3 class="mt-3 text-lg font-semibold leading-7 text-textPrimary sm:text-xl">
                                {{ $module['title'] ?? '' }}
                            </h3>
                            <p class="mt-2 text-sm leading-7 text-textSecondary">
                                Structured assignments, submission-ready outputs, and review checkpoints inside this module.
                            </p>
                        </div>
                    </div>

                    <div class="flex shrink-0 flex-col items-end gap-3">
                        <div class="h-1.5 w-20 overflow-hidden rounded-full bg-borderLight">
                            <div class="h-full rounded-full bg-gradient-to-r from-brand to-brandLight transition-all duration-500"
                                :style="activeModule === {{ $index }} ? 'width: 100%' : 'width: 40%'"></div>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-secondarySoft/60 text-secondary transition duration-300"
                            :class="activeModule === {{ $index }} ? 'rotate-45 bg-brand text-white' : ''">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                            </svg>
                        </div>
                    </div>
                </button>

                <div class="overflow-hidden border-t border-borderLight/80 bg-gradient-to-b from-bgMain/70 to-white transition-all duration-500"
                    x-ref="module{{ $index }}"
                    :style="activeModule === {{ $index }}
                            ? 'max-height: ' + $refs['module{{ $index }}'].scrollHeight + 'px; opacity: 1;'
                            : 'max-height: 0px; opacity: 0;'">
                    <div class="grid gap-4 px-6 py-6 sm:px-7 sm:py-7">
                        @foreach ($module['tasks'] ?? [] as $task)
                        <div
                            class="rounded-[1.5rem] border border-borderLight bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-brand/25 hover:shadow-[0_18px_40px_rgba(15,23,42,0.08)]">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="max-w-2xl">
                                    <h4 class="text-base font-semibold text-textPrimary sm:text-lg">
                                        {{ $task['title'] ?? '' }}
                                    </h4>
                                    <p class="mt-3 text-sm leading-7 text-textSecondary">
                                        {{ $task['assignment'] ?? '' }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex rounded-full bg-brandSoft px-3 py-1 text-xs font-medium text-brand">
                                    Applied Task
                                </span>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-2xl bg-bgMain px-4 py-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-textMuted">
                                        Submission
                                    </p>
                                    <p class="mt-2 text-sm font-medium leading-6 text-textPrimary">
                                        {{ $task['submission'] ?? '' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-secondarySoft/30 px-4 py-4">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-secondary">
                                        AI Review Focus
                                    </p>
                                    <p class="mt-2 text-sm font-medium leading-6 text-textPrimary">
                                        {{ $task['ai_review'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
