@extends('layouts.app')

@section('content')

<section class="bg-bgMain px-6 py-14 sm:px-10 lg:px-14">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-start">

        <!-- LEFT SIDE -->
        <div>

            <p class="text-xs font-semibold uppercase tracking-widest text-brand">
                Course Detail
            </p>

            <h1 class="mt-3 text-3xl sm:text-4xl font-semibold text-textPrimary leading-tight">
                {{ $course['title'] }}
            </h1>

            <p class="mt-4 text-textSecondary max-w-lg">
                {{ $course['description'] }}
            </p>

            <!-- INFO -->
            <div class="mt-6 flex gap-10 text-sm">
                <div>
                    <p class="text-textMuted">Duration</p>
                    <p class="font-medium text-textPrimary">{{ $course['duration'] }}</p>
                </div>
                <div>
                    <p class="text-textMuted">Level</p>
                    <p class="font-medium text-textPrimary">{{ $course['level'] }}</p>
                </div>
            </div>

            <!-- BENEFITS -->
            <div class="mt-8 space-y-3 text-sm text-textSecondary">
                <p>✔ Hands-on real-world projects</p>
                <p>✔ Industry-relevant curriculum</p>
                <p>✔ Guided learning roadmap</p>
                <p>✔ Certification after completion</p>
            </div>

            <!-- HIGHLIGHT BOX -->
            <div class="mt-8 p-5 rounded-xl bg-brandSoft border border-borderLight">
                <p class="text-sm font-medium text-textPrimary">
                    Limited seats available for this batch.
                </p>
                <p class="text-xs text-textMuted mt-1">
                    Early enrollment increases your chances of selection.
                </p>
            </div>

        </div>

        <!-- RIGHT SIDE FORM -->
        <!-- RIGHT SIDE FORM -->
        <div id="enroll-now" class="bg-bgWhite border border-borderLight rounded-2xl p-8 shadow-sm">

            <h2 class="text-lg font-semibold text-textPrimary">
                Enroll Now
            </h2>
            <p class="text-sm text-textSecondary mt-1">
                Fill the form below to secure your seat.
            </p>

            <form method="POST" action="#" class="mt-6 space-y-6">
                @csrf

                <input type="hidden" name="course_slug" value="{{ $course['slug'] }}">
                <input type="hidden" name="course_title" value="{{ $course['title'] }}">

                <!-- GRID -->
                <div class="grid sm:grid-cols-2 gap-6">

                    <div>
                        <label class="text-sm text-textPrimary">Full Name</label>
                        <input type="text" placeholder="Write full name"
                            class="mt-2 w-full rounded-xl border border-borderLight bg-bgSoft px-4 py-3 text-sm focus:border-brand focus:ring-2 focus:ring-brandSoft outline-none">
                    </div>

                    <div>
                        <label class="text-sm text-textPrimary">Email Address</label>
                        <input type="email" placeholder="Write email address"
                            class="mt-2 w-full rounded-xl border border-borderLight bg-bgSoft px-4 py-3 text-sm focus:border-brand focus:ring-2 focus:ring-brandSoft outline-none">
                    </div>

                    <div>
                        <label class="text-sm text-textPrimary">Phone Number</label>
                        <input type="tel" placeholder="Write phone number"
                            class="mt-2 w-full rounded-xl border border-borderLight bg-bgSoft px-4 py-3 text-sm focus:border-brand focus:ring-2 focus:ring-brandSoft outline-none">
                    </div>

                    <div>
                        <label class="text-sm text-textPrimary">Location</label>
                        <input type="text" placeholder="Write location"
                            class="mt-2 w-full rounded-xl border border-borderLight bg-bgSoft px-4 py-3 text-sm focus:border-brand focus:ring-2 focus:ring-brandSoft outline-none">
                    </div>

                    <!-- 🔥 TRIGGER FIELD -->
                    <div class="sm:col-span-2">
                        <label class="text-sm text-textPrimary">College Name</label>
                        <input id="collegeInput" type="text" placeholder="Write college name"
                            class="mt-2 w-full rounded-xl border border-borderLight bg-bgSoft px-4 py-3 text-sm focus:border-brand focus:ring-2 focus:ring-brandSoft outline-none">
                    </div>

                    <!-- 🔥 HIDDEN FIELDS -->
                    <div id="extraFields" class="sm:col-span-2 opacity-0 max-h-0 overflow-hidden transition-all duration-500 space-y-6">

                        <div>
                            <label class="text-sm text-textPrimary">Course</label>
                            <select
                                class="mt-2 w-full rounded-xl border border-borderLight bg-bgSoft px-4 py-3 text-sm focus:border-brand focus:ring-2 focus:ring-brandSoft outline-none">
                                <option>Search a course...</option>
                                <option>{{ $course['title'] }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm text-textPrimary">Message (Optional)</label>
                            <textarea rows="4" placeholder="Any specific information?"
                                class="mt-2 w-full rounded-xl border border-borderLight bg-bgSoft px-4 py-3 text-sm focus:border-brand focus:ring-2 focus:ring-brandSoft outline-none"></textarea>
                        </div>

                    </div>

                </div>

                <!-- CTA -->
                <button
                    class="w-full bg-brand text-white py-3 rounded-xl text-sm font-semibold hover:bg-brandDark transition">
                    Enroll Now
                </button>

            </form>
        </div>

    </div>
</section>

@if(isset($course['program_overview']))
@include('course.program-overview.program-overview', ['course' => $course])
@endif

@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    const college = document.getElementById('collegeInput');
    const extra = document.getElementById('extraFields');

    if (college) {
        college.addEventListener('input', () => {
            if (college.value.trim() !== "") {
                extra.classList.remove('opacity-0', 'max-h-0');
                extra.classList.add('opacity-100', 'max-h-[400px]');
            } else {
                extra.classList.add('opacity-0', 'max-h-0');
                extra.classList.remove('opacity-100', 'max-h-[400px]');
            }
        });
    }
});
</script>