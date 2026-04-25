@props(['course'])

@php
$whyChoose = $course['why_choose'] ?? [];
$testimonials = $course['testimonials'] ?? [];
$faqItems = $course['faq'] ?? [];
$whyIcons = [
    '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />',
    '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
    '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.567-3 3.5S10.343 15 12 15s3-1.567 3-3.5S13.657 8 12 8zm0 0V5m0 10v4m7-7h-4M9 12H5" />',
];
$testimonialMeta = $course['menu_group'] ?? 'Engineers Clinic Learner';
@endphp

@if (!empty($whyChoose) || !empty($testimonials) || !empty($faqItems))
<section class="relative overflow-hidden bg-bgWhite px-6 py-20 sm:px-10 lg:px-14">
    <div
        class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(34,197,94,0.08),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(249,115,22,0.08),_transparent_32%)]">
    </div>
    <div class="pointer-events-none absolute left-0 top-12 h-48 w-48 rounded-full bg-brandSoft blur-3xl"></div>
    <div class="pointer-events-none absolute right-0 bottom-0 h-56 w-56 rounded-full bg-secondarySoft blur-3xl"></div>

    <div class="relative mx-auto max-w-6xl space-y-20">
        @if (!empty($whyChoose))
        <div>
            <div class="mx-auto max-w-3xl text-center">
                <span
                    class="inline-flex items-center rounded-full border border-brand/10 bg-brandSoft px-4 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-brand">
                    Why Choose This Course
                </span>
                <h2 class="mt-5 text-3xl font-semibold leading-tight text-textPrimary sm:text-4xl">
                    Strong reasons learners choose this track over passive learning
                </h2>
                <p class="mt-4 text-base leading-8 text-textSecondary">
                    Clear, high-signal benefits designed to make the decision easier and faster.
                </p>
            </div>

            <div class="mt-12 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($whyChoose as $index => $item)
                <article
                    class="group relative overflow-hidden rounded-[1.75rem] border border-borderLight bg-white p-6 shadow-[0_18px_45px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1 hover:border-brand/20 hover:shadow-[0_24px_60px_rgba(21,128,61,0.12)]">
                    <div class="absolute inset-x-6 top-0 h-px bg-gradient-to-r from-brand/0 via-brand/30 to-secondary/0"></div>
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand to-brandLight text-white shadow-lg shadow-brand/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                {!! $whyIcons[$index % count($whyIcons)] !!}
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-textPrimary">
                                {{ $item['title'] ?? '' }}
                            </h3>
                            <p class="mt-3 text-sm leading-7 text-textSecondary">
                                {{ $item['description'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif

        @if (!empty($testimonials))
        <div>
            <div class="mx-auto max-w-3xl text-center">
                <span
                    class="inline-flex items-center rounded-full border border-secondary/10 bg-secondarySoft px-4 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-secondary">
                    Social Proof
                </span>
                <h2 class="mt-5 text-3xl font-semibold leading-tight text-textPrimary sm:text-4xl">
                    Real learner signals that build trust before they enroll
                </h2>
                <p class="mt-4 text-base leading-8 text-textSecondary">
                    Testimonial cards designed to feel credible, clear, and easy to scan.
                </p>
            </div>

            <div class="mt-12 grid gap-6 lg:grid-cols-2">
                @foreach ($testimonials as $testimonial)
                <article
                    class="relative overflow-hidden rounded-[1.85rem] border border-borderLight bg-white p-7 shadow-[0_20px_50px_rgba(15,23,42,0.07)] transition duration-300 hover:-translate-y-1 hover:border-secondary/20 hover:shadow-[0_24px_60px_rgba(15,23,42,0.1)]">
                    <div class="absolute right-6 top-6 text-5xl font-semibold leading-none text-brand/10">&ldquo;</div>
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brandSoft via-brandSoft to-secondarySoft text-base font-semibold text-brand">
                                {{ strtoupper(substr($testimonial['name'] ?? 'L', 0, 1)) }}
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-textPrimary">
                                    {{ $testimonial['name'] ?? '' }}
                                </h3>
                                <p class="text-sm text-textSecondary">
                                    {{ $testimonial['role'] ?? '' }}
                                </p>
                                <p class="text-xs uppercase tracking-[0.16em] text-textMuted">
                                    {{ $testimonialMeta }}
                                </p>
                            </div>
                        </div>
                        <span
                            class="inline-flex rounded-full border border-borderLight bg-bgMain px-3 py-1 text-xs font-semibold text-secondary">
                            5.0
                        </span>
                    </div>

                    <div class="mt-6 flex items-center gap-1 text-secondary">
                        @for ($star = 0; $star < 5; $star++)
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.922-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.176 0l-2.8 2.034c-.784.57-1.838-.196-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.719c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 00.951-.69l1.068-3.292z" />
                        </svg>
                        @endfor
                    </div>

                    <p class="mt-5 text-base leading-8 text-textSecondary">
                        {{ $testimonial['text'] ?? '' }}
                    </p>
                </article>
                @endforeach
            </div>
        </div>
        @endif

        @if (!empty($faqItems))
        <div x-data="{ activeFaq: 0 }">
            <div class="mx-auto max-w-3xl text-center">
                <span
                    class="inline-flex items-center rounded-full border border-brand/10 bg-brandSoft px-4 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-brand">
                    FAQs
                </span>
                <h2 class="mt-5 text-3xl font-semibold leading-tight text-textPrimary sm:text-4xl">
                    Clear answers for the final questions learners usually ask
                </h2>
                <p class="mt-4 text-base leading-8 text-textSecondary">
                    Short, direct answers that remove hesitation without slowing down the decision.
                </p>
            </div>

            <div class="mx-auto mt-12 max-w-4xl space-y-4">
                @foreach ($faqItems as $index => $faq)
                <div
                    class="overflow-hidden rounded-[1.5rem] border border-borderLight bg-white shadow-[0_16px_40px_rgba(15,23,42,0.06)] transition duration-300"
                    :class="activeFaq === {{ $index }} ? 'border-brand/25 shadow-[0_24px_60px_rgba(21,128,61,0.12)]' : ''">
                    <button type="button"
                        class="flex w-full items-center justify-between gap-4 px-6 py-5 text-left sm:px-7"
                        @click="activeFaq = activeFaq === {{ $index }} ? -1 : {{ $index }}"
                        :aria-expanded="(activeFaq === {{ $index }}).toString()">
                        <div class="pr-4">
                            <p class="text-base font-semibold leading-7 text-textPrimary sm:text-lg">
                                {{ $faq['question'] ?? '' }}
                            </p>
                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-bgMain text-brand transition duration-300"
                            :class="activeFaq === {{ $index }} ? 'rotate-45 bg-brand text-white' : ''">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                            </svg>
                        </div>
                    </button>

                    <div class="overflow-hidden border-t border-borderLight/70 bg-gradient-to-b from-bgMain/50 to-white transition-all duration-500"
                        x-ref="faq{{ $index }}"
                        :style="activeFaq === {{ $index }}
                            ? 'max-height: ' + $refs['faq{{ $index }}'].scrollHeight + 'px; opacity: 1;'
                            : 'max-height: 0px; opacity: 0;'">
                        <div class="px-6 py-5 sm:px-7">
                            <p class="text-sm leading-7 text-textSecondary sm:text-base">
                                {{ $faq['answer'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-brand via-brandDark to-textPrimary px-6 py-12 text-center shadow-[0_30px_80px_rgba(15,23,42,0.2)] sm:px-10">
            <div
                class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_transparent_34%),radial-gradient(circle_at_bottom_right,_rgba(249,115,22,0.24),_transparent_35%)]">
            </div>
            <div class="relative mx-auto max-w-3xl">
                <span
                    class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-white/85">
                    Final Step
                </span>
                <h2 class="mt-5 text-3xl font-semibold leading-tight text-white sm:text-4xl">
                    Ready to start your journey?
                </h2>
                <p class="mt-4 text-base leading-8 text-white/80">
                    Join a structured learning path built to turn clarity into output and output into confidence.
                </p>
                <div class="mt-8">
                    <a href="#enroll-now"
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-brand shadow-[0_18px_40px_rgba(255,255,255,0.18)] transition duration-300 hover:-translate-y-0.5 hover:bg-bgMain">
                        Enroll Now
                    </a>
                </div>
                <p class="mt-4 text-sm font-medium text-white/75">
                    Limited seats available and the next batch is filling soon.
                </p>
            </div>
        </div>
    </div>
</section>
@endif
