<section class="relative isolate overflow-hidden bg-white py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute -left-24 top-20 -z-10 h-80 w-80 rounded-full bg-[#22C55E]/8 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-24 bottom-16 -z-10 h-96 w-96 rounded-full bg-[#6D5DF6]/10 blur-3xl"></div>

    <div class="container-main">
        <div class="grid items-center gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
            <div>
                <span class="inline-flex rounded-full border border-[#ECEBFF] bg-[#F5F3FF] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">Certificate Showcase</span>
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
                        <div class="rounded-3xl border border-[#ECEBFF] bg-white p-5 transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF] hover:shadow-[0_16px_38px_rgba(109,93,246,0.10)]">
                            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-[#F5F3FF] text-[#6D5DF6]"><i class="fi {{ $item['icon'] }}"></i></span>
                            <h3 class="mt-4 font-black text-[#161326]">{{ $item['title'] }}</h3>
                            <p class="mt-1 text-sm text-[#6B7280]">{{ $item['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="certificate-sample" class="relative">
                <div class="absolute inset-x-10 bottom-0 top-16 rounded-[2rem] bg-[#6D5DF6]/10 blur-3xl"></div>
                <img src="{{ asset('images/ec-cer.png') }}" alt="Engineers Clinic certificate sample" class="relative h-[360px] w-full object-contain transition duration-500 hover:scale-[1.02] sm:h-[460px] lg:h-[620px]">
            </div>
        </div>
    </div>
</section>
