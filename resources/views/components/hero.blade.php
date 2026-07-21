<section id="heroSection" class="relative isolate overflow-hidden bg-[#FAFBFF] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute right-8 top-1/2 h-[28rem] w-[28rem] -translate-y-1/2 rounded-full bg-[#6D5DF6]/8 blur-3xl"></div>
    </div>

    <div class="container-main">
        <div class="grid items-center gap-12 lg:grid-cols-[1fr_1fr] lg:gap-16">
            <div class="ec-fade-up">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#ECEBFF] bg-[#F5F3FF] px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-[#6D5DF6]">
                    <span class="h-2 w-2 rounded-full bg-[#22C55E]"></span>
                    Project-Based Learning Platform
                </span>

                <h1 class="mt-8 max-w-3xl text-4xl font-black leading-[1.02] tracking-tight text-[#161326] sm:text-5xl lg:text-[4rem]">
                    Build real projects. Prove you are job-ready.
                </h1>

                <p class="mt-6 max-w-[35rem] text-lg font-medium leading-8 text-[#6B7280]">
                    Pick a project for your level, complete guided milestones, publish your code to GitHub, and earn a certificate backed by reviewed work.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="#courses" class="inline-flex min-h-12 items-center justify-center rounded-2xl bg-[#6D5DF6] px-7 py-3 text-sm font-black text-white shadow-[0_14px_34px_rgba(109,93,246,0.24)] transition duration-300 hover:-translate-y-1 hover:bg-[#5A4AE3]">
                        Explore Projects
                    </a>
                    <a href="#how-it-works" class="inline-flex min-h-12 items-center justify-center rounded-2xl border border-[#D9D6FF] bg-white px-7 py-3 text-sm font-black text-[#161326] transition duration-300 hover:-translate-y-1 hover:bg-[#F5F3FF] hover:text-[#161326]">
                        See How It Works
                    </a>
                </div>

                <!-- <div class="mt-10 grid max-w-xl grid-cols-3 gap-4">
                    @foreach ([
                        ['value' => '6K+', 'label' => 'Students'],
                        ['value' => '320+', 'label' => 'Projects'],
                        ['value' => '98%', 'label' => 'Completion'],
                    ] as $trust)
                        <div class="rounded-3xl border border-[#ECEBFF] bg-white p-5 transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF] hover:shadow-[0_16px_38px_rgba(109,93,246,0.10)]">
                            <p class="text-2xl font-black text-[#161326]">{{ $trust['value'] }}</p>
                            <p class="mt-1 text-xs font-bold uppercase tracking-[0.12em] text-[#8A8FA3]">{{ $trust['label'] }}</p>
                        </div>
                    @endforeach
                </div> -->

                <!-- <div class="mt-7 flex flex-wrap items-center gap-2 text-sm font-bold text-[#6B7280]">
                    @foreach (['Build Projects', 'Complete Tasks', 'Push GitHub', 'Earn Certificate'] as $item)
                        <span class="inline-flex items-center gap-2 rounded-full border border-[#ECEBFF] bg-white px-3 py-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#6D5DF6]"></span>
                            {{ $item }}
                        </span>
                    @endforeach
                </div> -->
            </div>

            <div class="relative flex min-h-[410px] items-start justify-center pt-2 ec-fade-up sm:min-h-[470px] lg:min-h-[520px] lg:pt-6">
                <div class="pointer-events-none absolute left-1/2 top-[42%] h-[25rem] w-[25rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#6D5DF6]/10 blur-3xl sm:h-[31rem] sm:w-[31rem]"></div>
                <div class="pointer-events-none absolute bottom-16 left-1/2 h-16 w-[68%] -translate-x-1/2 rounded-full bg-[#D9D6FF]/45 blur-2xl"></div>

                <img src="{{ asset('images/hero-new-girl.png') }}"
                    alt="Student building a project in a guided workspace"
                    class="relative z-10 h-auto max-h-[410px] w-full max-w-[31rem] object-contain object-center sm:max-h-[500px] sm:max-w-[35rem] lg:max-h-[575px] lg:max-w-[38rem]">

                <div class="absolute left-4 top-14 hidden rounded-2xl border border-[#ECEBFF] bg-white/90 px-4 py-3 shadow-[0_14px_34px_rgba(15,10,42,0.08)] backdrop-blur-xl sm:block">
                    <p class="text-xs font-black uppercase tracking-[0.12em] text-[#8A8FA3]">GitHub</p>
                    <p class="mt-1 text-sm font-black text-[#161326]">Connected</p>
                </div>

                <div class="absolute right-4 top-[38%] hidden rounded-2xl border border-[#ECEBFF] bg-white/90 px-4 py-3 shadow-[0_14px_34px_rgba(15,10,42,0.08)] backdrop-blur-xl md:block">
                    <p class="text-xs font-black uppercase tracking-[0.12em] text-[#8A8FA3]">Task</p>
                    <p class="mt-1 text-sm font-black text-[#161326]">Completed</p>
                </div>

                <div class="absolute bottom-16 left-8 hidden rounded-2xl border border-[#ECEBFF] bg-white/90 px-4 py-3 shadow-[0_14px_34px_rgba(15,10,42,0.08)] backdrop-blur-xl lg:block">
                    <p class="text-xs font-black uppercase tracking-[0.12em] text-[#8A8FA3]">Certificate</p>
                    <p class="mt-1 text-sm font-black text-[#161326]">Ready</p>
                </div>
            </div>
        </div>
    </div>
</section>
