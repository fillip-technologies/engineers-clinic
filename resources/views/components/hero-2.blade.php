<section class="relative isolate overflow-hidden bg-white py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-24 top-20 -z-10 h-80 w-80 rounded-full bg-[#22C55E]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-16 -z-10 h-96 w-96 rounded-full bg-[#6D5DF6]/12 blur-3xl"></div>

    <div class="container-main">
        <div class="grid items-center gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
            <div>
                <span class="inline-flex rounded-full border border-[#ECEBFF] bg-[#FAFBFF] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">Certificate Showcase</span>
                <h2 class="mt-5 text-3xl font-black leading-tight tracking-tight text-[#161326] sm:text-4xl lg:text-5xl">
                    A certificate backed by completed project work.
                </h2>
                <p class="mt-5 max-w-xl text-base leading-8 text-[#6B7280]">
                    Your certificate is designed for LinkedIn, resume sharing, and recruiter verification, with QR-ready proof connected to your project journey.
                </p>

                <div class="mt-7 grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['icon' => 'fi-rr-qr-scan', 'title' => 'QR Verification', 'desc' => 'Easy authenticity check.'],
                        ['icon' => 'fi-rr-brand', 'title' => 'LinkedIn Ready', 'desc' => 'Share your achievement.'],
                        ['icon' => 'fi-rr-document', 'title' => 'Resume Ready', 'desc' => 'Add credible project proof.'],
                        ['icon' => 'fi-rr-code-branch', 'title' => 'GitHub Proof', 'desc' => 'Connect work with outcome.'],
                    ] as $item)
                        <div class="rounded-3xl border border-[#ECEBFF] bg-[#FAFBFF] p-5">
                            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-[#6D5DF6]/10 text-[#6D5DF6]"><i class="fi {{ $item['icon'] }}"></i></span>
                            <h3 class="mt-4 font-black text-[#161326]">{{ $item['title'] }}</h3>
                            <p class="mt-1 text-sm text-[#6B7280]">{{ $item['desc'] }}</p>
                        </div>
                    @endforeach
                </div>

                <a href="#certificate-sample" class="mt-8 inline-flex min-h-12 items-center justify-center gap-2 rounded-2xl bg-[#6D5DF6] px-6 py-3 text-sm font-black text-white shadow-[0_18px_45px_rgba(109,93,246,0.28)] transition hover:-translate-y-1 hover:bg-[#5A4AE3]">
                    View Sample Certificate
                </a>
            </div>

            <div id="certificate-sample" class="relative">
                <div class="absolute inset-x-10 bottom-0 top-16 rounded-[2rem] bg-[#6D5DF6]/20 blur-3xl"></div>
                <div class="relative rotate-[-2deg] rounded-[2rem] border border-[#ECEBFF] bg-white p-4 shadow-[0_30px_90px_rgba(15,10,42,0.14)] transition duration-500 hover:rotate-0">
                    <div class="rounded-[1.5rem] border border-[#ECEBFF] bg-gradient-to-br from-white via-[#FAFBFF] to-[#ECEBFF] p-8">
                        <div class="flex items-start justify-between gap-6">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.24em] text-[#6D5DF6]">Engineers Clinic</p>
                                <h3 class="mt-8 text-4xl font-black leading-tight text-[#161326]">Certificate of Project Completion</h3>
                                <p class="mt-5 max-w-md text-sm leading-7 text-[#6B7280]">
                                    Awarded for successfully completing a milestone-based industry project, GitHub submission, and review process.
                                </p>
                            </div>
                            <div class="grid h-24 w-24 shrink-0 grid-cols-5 gap-1 rounded-2xl bg-[#0F0A2A] p-3">
                                @foreach (range(1, 25) as $dot)
                                    <span class="{{ in_array($dot, [1,3,5,7,9,13,17,19,21,23,25]) ? 'bg-white' : 'bg-white/25' }} rounded-sm"></span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-10 rounded-3xl border border-[#ECEBFF] bg-white/80 p-5">
                            <p class="text-xs font-black uppercase tracking-[0.16em] text-[#6B7280]">Issued To</p>
                            <p class="mt-2 text-2xl font-black text-[#161326]">Student Name</p>
                            <div class="mt-5 h-2 rounded-full bg-[#ECEBFF]"><div class="h-2 w-4/5 rounded-full bg-[#22C55E]"></div></div>
                        </div>

                        <div class="mt-8 flex flex-wrap items-center justify-between gap-4 border-t border-[#ECEBFF] pt-6">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.16em] text-[#6B7280]">Certificate ID</p>
                                <p class="mt-1 font-black text-[#161326]">EC-PBL-2026-001</p>
                            </div>
                            <div class="rounded-full bg-[#22C55E]/10 px-4 py-2 text-sm font-black text-[#22C55E]">Verified</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
