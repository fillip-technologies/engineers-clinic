<section class="relative isolate overflow-hidden bg-white py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-24 top-12 -z-10 h-80 w-80 rounded-full bg-[#6D5DF6]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-12 -z-10 h-80 w-80 rounded-full bg-[#A855F7]/10 blur-3xl"></div>

    <div class="container-main">
        <div class="grid items-center gap-10 lg:grid-cols-[0.95fr_1.05fr] lg:gap-14">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full border border-[#ECEBFF] bg-[#FAFBFF] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">
                    Why Students Struggle
                </span>
                <h2 class="mt-5 max-w-2xl text-3xl font-black leading-tight tracking-tight text-[#161326] sm:text-4xl lg:text-5xl">
                    Tutorials do not get you selected.
                    <span class="block bg-gradient-to-r from-[#6D5DF6] to-[#A855F7] bg-clip-text text-transparent">Proof of work does.</span>
                </h2>
                <p class="mt-5 max-w-xl text-base leading-8 text-[#6B7280]">
                    Most students know concepts, but interviews ask for evidence: what you built, how you solved problems, and whether your GitHub shows consistent practical work.
                </p>

                <div class="mt-8 grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['title' => 'Watching tutorials only', 'desc' => 'Passive learning feels productive but leaves no proof.'],
                        ['title' => 'No finished projects', 'desc' => 'Half-built demos do not show ownership.'],
                        ['title' => 'Empty GitHub profile', 'desc' => 'Recruiters cannot see your workflow.'],
                        ['title' => 'No practical experience', 'desc' => 'Interview answers stay theoretical.'],
                    ] as $problem)
                        <div class="rounded-3xl border border-[#ECEBFF] bg-[#FAFBFF] p-5 transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF] hover:shadow-[0_16px_38px_rgba(109,93,246,0.10)]">
                            <div class="mb-4 grid h-10 w-10 place-items-center rounded-2xl bg-red-50 text-red-500">
                                <i class="fi fi-rr-cross-small"></i>
                            </div>
                            <h3 class="text-base font-black text-[#161326]">{{ $problem['title'] }}</h3>
                            <p class="mt-2 text-sm leading-6 text-[#6B7280]">{{ $problem['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="relative">
                <div class="rounded-[2rem] border border-[#ECEBFF] bg-white p-4 shadow-[0_30px_90px_rgba(15,10,42,0.10)]">
                    <div class="rounded-[1.5rem] border border-[#ECEBFF] bg-gradient-to-br from-white via-[#FCFBFF] to-[#F5F3FF] p-6 sm:p-8">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#6D5DF6]">Engineers Clinic Method</p>
                                <h3 class="mt-2 text-2xl font-black text-[#161326]">From learning to evidence</h3>
                            </div>
                            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-[#F5F3FF] text-[#6D5DF6] backdrop-blur">
                                <i class="fi fi-rr-check"></i>
                            </span>
                        </div>

                        <div class="mt-8 space-y-4">
                            @foreach ([
                                ['step' => '01', 'title' => 'Level-based projects', 'desc' => 'Beginner, intermediate, and advanced projects match your current skills.'],
                                ['step' => '02', 'title' => 'Personal workspace', 'desc' => 'Every student gets a clear project board with milestones and tasks.'],
                                ['step' => '03', 'title' => 'GitHub + review', 'desc' => 'Submit working code, receive review, and improve your portfolio.'],
                                ['step' => '04', 'title' => 'Industry certificate', 'desc' => 'Earn a certificate only after completing and submitting the project.'],
                            ] as $solution)
                                <div class="group flex gap-4 rounded-3xl border border-[#ECEBFF] bg-white p-4 backdrop-blur-xl transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF] hover:shadow-[0_16px_38px_rgba(109,93,246,0.10)]">
                                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-[#F5F3FF] text-sm font-black text-[#6D5DF6]">{{ $solution['step'] }}</span>
                                    <div>
                                        <h4 class="font-black text-[#161326]">{{ $solution['title'] }}</h4>
                                        <p class="mt-1 text-sm leading-6 text-[#6B7280]">{{ $solution['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
