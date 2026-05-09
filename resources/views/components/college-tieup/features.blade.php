@php
    $features = [
        ['title' => 'College dashboard', 'body' => 'A single view for enrolled students, active tracks, completion status, and academic updates.'],
        ['title' => 'Learning analytics', 'body' => 'Track progress, task completion, cohort engagement, and domain-wise adoption patterns.'],
        ['title' => 'Attendance visibility', 'body' => 'Maintain session participation records and identify students who need coordinator follow-up.'],
        ['title' => 'Institution reports', 'body' => 'Generate structured summaries for HOD reviews, placement meetings, and internal reporting.'],
        ['title' => 'LMS access', 'body' => 'Students work through guided learning modules, project tasks, and milestone-based submissions.'],
        ['title' => 'Placement support', 'body' => 'Project portfolios, interview readiness signals, and career guidance support employability conversations.'],
    ];
@endphp

<section class="bg-bgSoft py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1fr] lg:items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Features for colleges</p>
                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Operational controls for academic teams and placement cells.
                </h2>
                <p class="mt-5 text-base leading-8 text-textSecondary">
                    The platform experience is designed to help colleges coordinate learning activity, monitor student progress, and keep stakeholders informed.
                </p>
            </div>

            <div class="overflow-hidden rounded-xl border border-borderLight bg-bgWhite">
                @foreach($features as $feature)
                    <div class="grid gap-3 border-b border-borderLight p-5 last:border-b-0 sm:grid-cols-[220px_1fr] sm:gap-8">
                        <h3 class="font-semibold text-textPrimary">{{ $feature['title'] }}</h3>
                        <p class="text-sm leading-7 text-textSecondary">{{ $feature['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
