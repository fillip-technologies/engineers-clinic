@php
    $faqs = [
        ['q' => 'Is Engineers Clinic a course website?', 'a' => 'No. It is a project-based learning platform where students purchase projects, complete milestones, submit GitHub work, receive review, and earn certificates.'],
        ['q' => 'What happens after I purchase a project?', 'a' => 'You receive a personal workspace with milestone-based tasks, submission steps, progress tracking, and certificate eligibility details.'],
        ['q' => 'Do I need to upload code to GitHub?', 'a' => 'Yes, for technical projects. GitHub submission helps create visible proof of work that recruiters can inspect.'],
        ['q' => 'When do I receive the certificate?', 'a' => 'You receive the industry certificate after completing the required milestones and getting your project submission reviewed.'],
        ['q' => 'Is this beginner friendly?', 'a' => 'Yes. Projects are organized by level, so beginners can start with guided foundational projects while advanced students can choose deeper builds.'],
        ['q' => 'Can I use the certificate on LinkedIn and resume?', 'a' => 'Yes. The certificate is designed to be LinkedIn ready, resume ready, and verification friendly.'],
    ];
@endphp

<section class="relative isolate overflow-hidden bg-[#FAFBFF] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-24 top-20 -z-10 h-80 w-80 rounded-full bg-[#6D5DF6]/10 blur-3xl"></div>

    <div class="mx-auto max-w-4xl px-6">
        <div class="mb-10 text-center">
            <span class="inline-flex rounded-full border border-[#ECEBFF] bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">FAQ</span>
            <h2 class="mt-5 text-3xl font-black leading-tight text-[#161326] sm:text-4xl lg:text-5xl">Questions students ask before starting.</h2>
        </div>

        <div class="space-y-4">
            @foreach ($faqs as $index => $faq)
                <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }" class="overflow-hidden rounded-[1.5rem] border border-[#ECEBFF] bg-white shadow-[0_14px_34px_rgba(15,10,42,0.05)] transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF]">
                    <button @click="open = !open" class="flex w-full items-center justify-between gap-4 p-5 text-left sm:p-6">
                        <span class="text-base font-black text-[#161326]">{{ $faq['q'] }}</span>
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-[#F5F3FF] text-[#6D5DF6] transition duration-300" :class="open ? 'rotate-45 bg-[#EEE9FF] text-[#5A4AE3]' : ''">
                            <i class="fi fi-rr-plus-small"></i>
                        </span>
                    </button>
                    <div x-show="open" x-transition>
                        <p class="border-t border-[#ECEBFF] px-5 pb-6 pt-0 text-sm leading-7 text-[#6B7280] sm:px-6">
                            {{ $faq['a'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
