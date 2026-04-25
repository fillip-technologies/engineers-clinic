<section class="rounded-[1.75rem] border border-glassBorder bg-white p-6 shadow-sm sm:p-8">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Enrollment Summary</p>
            <h1 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Enrollment ID // EC-2026-0012
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-8 text-textSecondary">
                Welcome back to your learning space. Keep an eye on your courses, ongoing progress, and next milestones.
            </p>
        </div>

        <div class="rounded-[1.25rem] border border-glassBorder bg-slate-50 px-5 py-4">
            <p class="text-sm font-semibold text-textMuted">Current Track</p>
            <p class="mt-2 text-xl font-semibold text-textPrimary">Full Stack Development Internship</p>
        </div>
    </div>
</section>

<section class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
    <x-common.stat-card label="Enrolled Internship Courses" value="05" accent="primary" />
    <x-common.stat-card label="Active Internship Courses" value="03" accent="secondary" />
    <x-common.stat-card label="Completed Internship Courses" value="02" accent="glass" />
</section>

<section class="mt-8 rounded-[1.75rem] border border-glassBorder bg-white p-6 shadow-sm sm:p-8">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">In Progress Courses</p>
            <h2 class="mt-3 text-2xl font-semibold text-textPrimary sm:text-3xl">Keep moving through your active roadmap</h2>
        </div>
        <a href="#" class="text-sm font-semibold text-primaryLight transition hover:text-primary">View all courses</a>
    </div>

    <div class="mt-8 grid gap-5 xl:grid-cols-3">
        <x-student.course-card title="Full Stack Development" duration="6 Months" progress="72" lessonCount="24" />
        <x-student.course-card title="Frontend Development" duration="4 Months" progress="48" lessonCount="18" />
        <x-student.course-card title="UI/UX Design" duration="4 Months" progress="31" lessonCount="16" />
    </div>
</section>
