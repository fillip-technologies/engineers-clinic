@php
$programs = [
['badge' => 'Starter', 'title' => 'Beginner Project Sprint', 'image' => '/images/master1.png', 'price' => '99', 'best' => false],
['badge' => 'Most Popular', 'title' => 'Intermediate Workspace', 'image' => '/images/master2.png', 'price' => '299', 'best' => true],
['badge' => 'Advanced', 'title' => 'Industry Project Review', 'image' => '/images/master3.png', 'price' => '499', 'best' => false],
];
@endphp

<section x-data="{ enrollmentOpen: false }" x-init="$watch('enrollmentOpen', value => { document.documentElement.classList.toggle('overflow-hidden', value); document.body.classList.toggle('overflow-hidden', value); })" @keydown.escape.window="enrollmentOpen = false" class="relative isolate overflow-hidden bg-white py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute left-0 top-20 h-80 w-80 rounded-full bg-[#6D5DF6]/10 blur-3xl"></div>
        <div class="absolute bottom-10 right-0 h-80 w-80 rounded-full bg-[#A855F7]/10 blur-3xl"></div>
    </div>

    <div class="container-main">
        <div class="mx-auto max-w-3xl text-center">

            <h2 class="mt-5 text-3xl font-black leading-tight text-[#161326] sm:text-4xl lg:text-5xl">Start with the right project plan.</h2>
            <p class="mt-5 text-base leading-8 text-[#6B7280]">Choose a workspace experience based on your current level and the depth of review you need.</p>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            @foreach ($programs as $program)
            <article class="group relative flex min-h-full flex-col overflow-hidden rounded-[2rem] border {{ $program['best'] ? 'border-[#6D5DF6] bg-[#FCFBFF] text-[#161326] shadow-[0_30px_90px_rgba(109,93,246,0.16)]' : 'border-[#ECEBFF] bg-white text-[#161326] shadow-[0_18px_48px_rgba(15,10,42,0.07)]' }} p-4 transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF] hover:shadow-[0_26px_70px_rgba(109,93,246,0.14)]">
                <div class="relative h-52 overflow-hidden rounded-[1.5rem] bg-[#FAFBFF]">
                    <img src="{{ $program['image'] }}" alt="{{ $program['title'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    <span class="absolute left-4 top-4 rounded-full bg-[#F5F3FF] px-3 py-1.5 text-xs font-black text-[#6D5DF6]">{{ $program['badge'] }}</span>
                </div>

                <div class="flex flex-1 flex-col p-4">
                    <h3 class="text-2xl font-black text-[#161326]">{{ $program['title'] }}</h3>
                    <div class="mt-5 flex items-end gap-2">
                        <span class="text-5xl font-black text-[#161326]">&#8377;{{ $program['price'] }}</span>
                        <span class="pb-2 text-sm font-bold text-[#8A8FA3]">one-time</span>
                    </div>

                    <div class="mt-6 space-y-3">
                        @foreach (['Personal workspace', 'Milestone task board', 'GitHub submission', 'Review eligibility', 'Industry certificate'] as $feature)
                        <div class="flex items-center gap-3">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-[#22C55E]/10 text-[#22C55E]"><i class="fi fi-rr-check text-xs"></i></span>
                            <span class="text-sm font-bold text-[#6B7280]">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="mt-auto inline-flex min-h-12 items-center justify-center rounded-2xl bg-[#6D5DF6] px-5 py-3 text-sm font-black text-white transition hover:-translate-y-1 hover:bg-[#5A4AE3]" @click="enrollmentOpen = true">
                        Enroll Now
                    </button>
                </div>
            </article>
            @endforeach
        </div>
    </div>

    <div x-cloak x-show="enrollmentOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[140] overflow-y-auto bg-slate-950/70 px-4 py-6 backdrop-blur-sm sm:px-6 lg:px-8" role="dialog" aria-modal="true" @click="enrollmentOpen = false">
        <div class="flex min-h-full items-center justify-center">
            <div x-show="enrollmentOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-4 scale-[0.98] opacity-0" x-transition:enter-end="translate-y-0 scale-100 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0 scale-100 opacity-100" x-transition:leave-end="translate-y-4 scale-[0.98] opacity-0" class="relative w-full max-w-3xl" @click.stop>
                <button type="button" class="absolute right-4 top-4 z-20 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-slate-500 shadow-lg transition hover:text-slate-900" @click="enrollmentOpen = false" aria-label="Close enrollment form">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 5l10 10" />
                        <path d="M15 5 5 15" />
                    </svg>
                </button>
                @include('form.enrollment-form', ['selectedLevel' => 'Beginner'])
            </div>
        </div>
    </div>
</section>