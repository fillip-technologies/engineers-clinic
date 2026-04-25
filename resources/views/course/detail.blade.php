    @extends('layouts.app')

    @section('content')

    <section class="relative overflow-hidden bg-gradient-to-br from-bgMain via-white to-bgSoft px-6 py-14 sm:px-10 lg:px-14 lg:py-20">
        <div
            class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(34,197,94,0.14),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(249,115,22,0.12),_transparent_32%)]">
        </div>
        <div class="pointer-events-none absolute -left-16 top-10 h-56 w-56 rounded-full bg-brandSoft blur-3xl"></div>
        <div class="pointer-events-none absolute -right-12 bottom-0 h-72 w-72 rounded-full bg-secondarySoft blur-3xl"></div>

        <div class="relative mx-auto grid max-w-7xl gap-12 lg:grid-cols-[minmax(0,1.08fr)_420px] lg:items-start">
            <div class="max-w-2xl">
                <div
                    class="inline-flex flex-wrap items-center gap-3 rounded-full border border-brand/15 bg-white/90 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-brand shadow-[0_16px_40px_rgba(21,128,61,0.08)] backdrop-blur">
                    <!-- <span>{{ $course['menu_group_label'] ?? 'AI Remote Internships' }}</span> -->
                    <span class="h-1.5 w-1.5 rounded-full bg-brand"></span>
                    <span>Trusted by 1000+ learners</span>
                </div>

                <p class="mt-6 text-sm font-semibold uppercase tracking-[0.24em] text-secondary">
                    {{ $course['hero_badge'] ?? 'Structured practical learning' }}
                </p>

                <h1 class="mt-4 text-4xl font-semibold leading-[1.08] text-textPrimary sm:text-5xl lg:text-[3.65rem]">
                    {{ $course['title'] }}
                </h1>

                <p class="mt-6 max-w-xl text-base leading-8 text-textSecondary sm:text-lg">
                    {{ $course['description'] }}
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <div class="inline-flex items-center gap-2 rounded-full border border-borderLight bg-white px-4 py-2 text-sm font-medium text-textPrimary shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-brand"></span>
                        <span>{{ $course['duration'] }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-borderLight bg-white px-4 py-2 text-sm font-medium text-textPrimary shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-secondary"></span>
                        <span>{{ $course['level'] }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-borderLight bg-white px-4 py-2 text-sm font-medium text-textPrimary shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-brandLight"></span>
                        <span>{{ $course['career_path'] ?? 'Career-focused guided track' }}</span>
                    </div>
                </div>

                <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
                    <a href="#enroll-now"
                        class="inline-flex items-center justify-center rounded-2xl bg-brand px-6 py-3.5 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(21,128,61,0.28)] transition duration-300 hover:-translate-y-0.5 hover:bg-brandDark hover:shadow-[0_22px_50px_rgba(21,128,61,0.34)]">
                        Reserve Your Seat
                    </a>
                    <p class="text-sm text-textSecondary">
                        No spam, quick confirmation, and a counselor follow-up once you submit.
                    </p>
                </div>
            </div>

            <div id="enroll-now"
                class="relative overflow-hidden rounded-[2rem] border border-borderLight bg-white p-7 shadow-[0_28px_80px_rgba(15,23,42,0.12)] sm:p-8">
                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-brand via-brandLight to-secondary"></div>

                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand">Secure your seat</p>
                        <h2 class="mt-2 text-2xl font-semibold text-textPrimary">Enroll Now</h2>
                    </div>
                    <span
                        class="inline-flex items-center rounded-full border border-secondary/15 bg-secondarySoft px-3 py-1 text-xs font-semibold uppercase tracking-[0.14em] text-secondary">
                        Limited seats
                    </span>
                </div>

                <p class="mt-3 text-sm leading-7 text-textSecondary">
                    Fill in your details to get priority access for the upcoming batch and course guidance.
                </p>



                <form method="POST" action="#" class="mt-7 space-y-5">
                    @csrf

                    <input type="hidden" name="course_slug" value="{{ $course['slug'] }}">
                    <input type="hidden" name="course_title" value="{{ $course['title'] }}">

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-textPrimary">Full Name</label>
                            <input type="text" placeholder="Write full name"
                                class="mt-2 w-full rounded-2xl border border-borderLight bg-white px-4 py-3.5 text-sm text-textPrimary shadow-sm outline-none transition duration-300 placeholder:text-textMuted focus:border-brand focus:ring-4 focus:ring-brandSoft/60">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-textPrimary">Email Address</label>
                            <input type="email" placeholder="Write email address"
                                class="mt-2 w-full rounded-2xl border border-borderLight bg-white px-4 py-3.5 text-sm text-textPrimary shadow-sm outline-none transition duration-300 placeholder:text-textMuted focus:border-brand focus:ring-4 focus:ring-brandSoft/60">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-textPrimary">Phone Number</label>
                            <input type="tel" placeholder="Write phone number"
                                class="mt-2 w-full rounded-2xl border border-borderLight bg-white px-4 py-3.5 text-sm text-textPrimary shadow-sm outline-none transition duration-300 placeholder:text-textMuted focus:border-brand focus:ring-4 focus:ring-brandSoft/60">
                        </div>

                        <div>
                            <label class="text-sm font-medium text-textPrimary">Location</label>
                            <input type="text" placeholder="Write location"
                                class="mt-2 w-full rounded-2xl border border-borderLight bg-white px-4 py-3.5 text-sm text-textPrimary shadow-sm outline-none transition duration-300 placeholder:text-textMuted focus:border-brand focus:ring-4 focus:ring-brandSoft/60">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-sm font-medium text-textPrimary">College Name</label>
                            <input id="collegeInput" type="text" placeholder="Write college name"
                                class="mt-2 w-full rounded-2xl border border-borderLight bg-white px-4 py-3.5 text-sm text-textPrimary shadow-sm outline-none transition duration-300 placeholder:text-textMuted focus:border-brand focus:ring-4 focus:ring-brandSoft/60">
                        </div>

                        <div id="extraFields"
                            class="sm:col-span-2 max-h-0 space-y-5 overflow-hidden opacity-0 transition-all duration-500">
                            <div>
                                <label class="text-sm font-medium text-textPrimary">Course</label>
                                <select
                                    class="mt-2 w-full rounded-2xl border border-borderLight bg-white px-4 py-3.5 text-sm text-textPrimary shadow-sm outline-none transition duration-300 focus:border-brand focus:ring-4 focus:ring-brandSoft/60">
                                    <option>Search a course...</option>
                                    <option>{{ $course['title'] }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-textPrimary">Message (Optional)</label>
                                <textarea rows="4" placeholder="Any specific information?"
                                    class="mt-2 w-full rounded-2xl border border-borderLight bg-white px-4 py-3.5 text-sm text-textPrimary shadow-sm outline-none transition duration-300 placeholder:text-textMuted focus:border-brand focus:ring-4 focus:ring-brandSoft/60"></textarea>
                            </div>
                        </div>
                    </div>

                    <button
                        class="w-full rounded-2xl bg-brand px-5 py-4 text-sm font-semibold text-white shadow-[0_20px_45px_rgba(21,128,61,0.28)] transition duration-300 hover:-translate-y-0.5 hover:bg-brandDark hover:shadow-[0_24px_55px_rgba(21,128,61,0.34)]">
                        Enroll Now
                    </button>

                    <p class="text-center text-xs font-medium tracking-[0.08em] text-textMuted">
                        Trusted by learners across practical, career-focused tracks.
                    </p>
                </form>
            </div>
        </div>
    </section>

    @if(isset($course['program_overview']))
    @include('course.program-overview.program-overview', ['course' => $course])
    @endif

    @if(!empty($course['curriculum']))
    @include('course.curriculum.curriculum', ['course' => $course])
    @endif

    @if(!empty($course['why_choose']) || !empty($course['testimonials']) || !empty($course['faq']))
    @include('course.conversion.sections', ['course' => $course])
    @endif

    @endsection

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const college = document.getElementById('collegeInput');
            const extra = document.getElementById('extraFields');

            if (college && extra) {
                college.addEventListener('input', () => {
                    if (college.value.trim() !== "") {
                        extra.classList.remove('opacity-0', 'max-h-0');
                        extra.classList.add('opacity-100', 'max-h-[420px]');
                    } else {
                        extra.classList.add('opacity-0', 'max-h-0');
                        extra.classList.remove('opacity-100', 'max-h-[420px]');
                    }
                });
            }
        });
    </script>