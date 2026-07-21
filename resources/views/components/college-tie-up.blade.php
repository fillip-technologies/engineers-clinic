<section class="relative overflow-hidden bg-white py-14 sm:py-16">
    <div class="container-main">
        <div class="grid items-center gap-8 rounded-[2rem] border border-[#ECEBFF] bg-[#FAFBFF] p-6 shadow-[0_24px_70px_rgba(15,10,42,0.07)] lg:grid-cols-[0.9fr_1.1fr] lg:p-8">
            <div>
                <span class="inline-flex rounded-full bg-[#6D5DF6]/10 px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">College Tie-up Program</span>
                <h2 class="mt-5 text-3xl font-black leading-tight text-[#161326] sm:text-4xl">
                    Bring project-based learning to your campus.
                </h2>
                <p class="mt-4 max-w-xl text-base leading-8 text-[#6B7280]">
                    We partner with institutions to run structured project workspaces, GitHub submissions, mentor reviews, certificates, and placement-ready progress reports.
                </p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-partnership-discussion'))" class="inline-flex min-h-12 items-center justify-center rounded-2xl bg-[#6D5DF6] px-5 py-3 text-sm font-black text-white shadow-[0_16px_38px_rgba(109,93,246,0.25)] transition hover:-translate-y-1 hover:bg-[#5A4AE3]">
                        Request College Tie-up
                    </button>
                    <a href="{{ route('college.tieup') }}#college-benefits" class="inline-flex min-h-12 items-center justify-center rounded-2xl border border-[#D9D6FF] bg-white px-5 py-3 text-sm font-black text-[#161326] transition hover:-translate-y-1 hover:bg-[#F5F3FF] hover:text-[#161326]">
                        View Benefits
                    </a>
                </div>
            </div>

            <div class="overflow-hidden rounded-[1.5rem] border border-[#ECEBFF] bg-white py-6 shadow-[0_16px_44px_rgba(15,10,42,0.06)]">
                <div class="flex w-max gap-4 ec-marquee">
                    @foreach (array_merge([
                        'IIT Partner Cell', 'NIT Innovation Hub', 'Tech University', 'Global Institute', 'Engineering College', 'Design School', 'Management Campus', 'Law Academy'
                    ], [
                        'IIT Partner Cell', 'NIT Innovation Hub', 'Tech University', 'Global Institute', 'Engineering College', 'Design School', 'Management Campus', 'Law Academy'
                    ]) as $college)
                        <div class="flex h-20 w-52 shrink-0 items-center justify-center rounded-2xl border border-[#ECEBFF] bg-gradient-to-br from-white to-[#FAFBFF] px-5 text-center text-sm font-black text-[#161326] shadow-[0_10px_26px_rgba(15,10,42,0.05)]">
                            {{ $college }}
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 grid gap-3 px-6 sm:grid-cols-3">
                    @foreach (['Campus onboarding', 'MoU ready', 'Coordinator support'] as $item)
                        <div class="rounded-2xl bg-[#6D5DF6]/10 px-4 py-3 text-sm font-black text-[#6D5DF6]">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
