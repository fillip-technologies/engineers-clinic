@php
    $features = [
        ['icon' => 'fi-rr-folder-open', 'title' => 'Level-Based Projects', 'desc' => 'Choose beginner, intermediate, or advanced projects that match your current skill level.', 'from' => '#EEF2FF', 'to' => '#F5F3FF', 'accent' => '#6D5DF6', 'glow' => 'rgba(109, 93, 246, 0.22)'],
        ['icon' => 'fi-rr-apps', 'title' => 'Personal Workspace', 'desc' => 'Track milestones, tasks, submissions, and progress in one guided project board.', 'from' => '#ECFEFF', 'to' => '#F0FDFA', 'accent' => '#0EA5E9', 'glow' => 'rgba(14, 165, 233, 0.22)'],
        ['icon' => 'fi-rr-code-branch', 'title' => 'GitHub Workflow', 'desc' => 'Build the habit companies expect: commits, repositories, and visible project proof.', 'from' => '#F0FDF4', 'to' => '#ECFDF5', 'accent' => '#22C55E', 'glow' => 'rgba(34, 197, 94, 0.22)'],
        ['icon' => 'fi-rr-comment-check', 'title' => 'Project Review', 'desc' => 'Submit your work for review and improve it before certificate approval.', 'from' => '#FFF7ED', 'to' => '#FEF3C7', 'accent' => '#F97316', 'glow' => 'rgba(249, 115, 22, 0.2)'],
        ['icon' => 'fi-rr-badge-check', 'title' => 'Verified Certificate', 'desc' => 'Earn a shareable certificate after completing milestone-based project work.', 'from' => '#FDF2F8', 'to' => '#FAE8FF', 'accent' => '#EC4899', 'glow' => 'rgba(236, 72, 153, 0.2)'],
        ['icon' => 'fi-rr-briefcase', 'title' => 'Career Proof', 'desc' => 'Use projects, GitHub, reviews, and certificates to strengthen interviews and resumes.', 'from' => '#EFF6FF', 'to' => '#F5F3FF', 'accent' => '#2563EB', 'glow' => 'rgba(37, 99, 235, 0.2)'],
    ];
@endphp

<section class="relative isolate overflow-hidden bg-[#FAFBFF] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-24 top-10 -z-10 h-80 w-80 rounded-full bg-[#6D5DF6]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-10 -z-10 h-80 w-80 rounded-full bg-[#22C55E]/10 blur-3xl"></div>

    <div class="container-main">
        <div class="mx-auto mb-12 max-w-3xl text-center">
            <span class="inline-flex rounded-full border border-[#ECEBFF] bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">Why Choose Us</span>
            <h2 class="mt-5 text-3xl font-black leading-tight text-[#161326] sm:text-4xl lg:text-5xl">
                Everything students need to turn learning into proof.
            </h2>
            <p class="mt-5 text-base leading-8 text-[#6B7280]">
                A premium project-based system designed around outcomes recruiters can actually inspect.
            </p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($features as $feature)
                <div class="group relative overflow-hidden rounded-[2rem] border border-white bg-white p-6 shadow-[0_18px_48px_rgba(15,10,42,0.06)] transition duration-300 hover:scale-[1.02] hover:shadow-[0_26px_70px_rgba(15,10,42,0.12)]" style="background: linear-gradient(135deg, {{ $feature['from'] }}, {{ $feature['to'] }});">
                    <div class="absolute -right-14 -top-14 h-36 w-36 rounded-full blur-2xl transition duration-300 group-hover:scale-125" style="background: {{ $feature['glow'] }};"></div>
                    <div class="absolute bottom-0 left-0 h-24 w-24 rounded-full blur-2xl" style="background: {{ $feature['glow'] }};"></div>
                    <div class="relative">
                        <span class="grid h-14 w-14 place-items-center rounded-2xl bg-white/80 text-2xl shadow-[0_14px_32px_rgba(15,10,42,0.08)] transition duration-300 group-hover:-translate-y-1" style="color: {{ $feature['accent'] }};">
                            <i class="fi {{ $feature['icon'] }}"></i>
                        </span>
                        <h3 class="mt-6 text-xl font-black text-[#161326]">{{ $feature['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[#6B7280]">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
