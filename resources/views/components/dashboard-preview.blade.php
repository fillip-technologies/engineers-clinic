@props([
    'image' => '/images/add-dashboard.png',
])

<style>
    .dashboard-preview-bg {
        background:
            radial-gradient(circle at 18% 18%, rgba(124, 92, 252, 0.18), transparent 28%),
            radial-gradient(circle at 82% 76%, rgba(167, 139, 250, 0.12), transparent 24%),
            linear-gradient(135deg, #160840 0%, #120735 50%, #0B041F 100%);
    }

    .dashboard-preview-grid {
        background-image:
            linear-gradient(rgba(238, 245, 255, 0.06) 1px, transparent 1px),
            linear-gradient(90deg, rgba(238, 245, 255, 0.06) 1px, transparent 1px),
            linear-gradient(115deg, transparent 0%, transparent 44%, rgba(245, 200, 66, 0.1) 50%, transparent 56%, transparent 100%);
        background-size: 46px 46px, 46px 46px, 220% 220%;
        animation: dashboardPreviewGrid 10s linear infinite;
        mask-image: linear-gradient(to bottom, transparent, black 18%, black 82%, transparent);
    }

    .dashboard-preview-float {
        animation: dashboardPreviewFloat 7s ease-in-out infinite;
    }

    @keyframes dashboardPreviewGrid {
        0% {
            background-position: 0 0, 0 0, 0% 50%;
        }

        100% {
            background-position: 46px 46px, 46px 46px, 100% 50%;
        }
    }

    @keyframes dashboardPreviewFloat {
        0%, 100% {
            transform: translate3d(0, 0, 0);
        }

        50% {
            transform: translate3d(0, -10px, 0);
        }
    }
</style>

<section class="dashboard-preview-bg relative isolate overflow-hidden py-12 text-white sm:py-16 lg:py-20">
    <div class="pointer-events-none absolute inset-0">
        <div class="dashboard-preview-grid absolute inset-0 opacity-70"></div>
        <div class="absolute -left-20 top-10 h-64 w-64 rounded-full bg-[#7C5CFC]/18 blur-[120px]"></div>
        <div class="absolute -right-16 bottom-0 h-64 w-64 rounded-full bg-[#A78BFA]/14 blur-[120px]"></div>
        <div class="absolute left-1/2 top-1/2 h-56 w-56 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#EEF5FF]/8 blur-[110px]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-6">
        <div class="mx-auto max-w-3xl text-center">
            <div class="inline-flex items-center gap-3 rounded-full border border-white/14 bg-white/[0.07] px-4 py-2 backdrop-blur-xl">
                <span class="h-2 w-2 rounded-full bg-[#F5C842] shadow-[0_0_18px_rgba(245,200,66,0.8)]"></span>
                <!-- <span class="text-xs font-semibold uppercase tracking-[0.2em] text-[#EEF5FF]/82">
                    Student Dashboard
                </span> -->
            </div>

            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl lg:text-5xl">
                Track your internship journey like a real product ecosystem.
            </h2>

            <!-- <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-[#EEF5FF]/72">
                Monitor project progress, manage submissions, unlock certificates, and build practical skills through a structured dashboard experience.
            </p> -->

            <!-- <div class="mt-7 flex flex-wrap justify-center gap-3">
                @foreach (['Project Progress', 'Task Submissions', 'Verified Certificates', 'Learning Streaks'] as $feature)
                    <span class="rounded-full border border-white/12 bg-white/[0.07] px-4 py-2 text-sm text-[#EEF5FF]/84 backdrop-blur-xl transition duration-300 hover:border-[#A78BFA]/35 hover:bg-white/[0.1] hover:text-white">
                        {{ $feature }}
                    </span>
                @endforeach
            </div> -->
        </div>

        <div class="relative mx-auto mt-8 max-w-5xl sm:mt-10">
            <div class="absolute inset-x-8 bottom-0 top-10 rounded-[28px] bg-[#7C5CFC]/22 blur-[60px]"></div>
            <div class="absolute inset-x-20 bottom-6 top-16 rounded-[28px] bg-[#A78BFA]/14 blur-[70px]"></div>

            <div class="dashboard-preview-float relative overflow-hidden rounded-2xl border border-white/14 bg-white/[0.08] p-2 shadow-[0_28px_90px_rgba(5,2,18,0.52)] backdrop-blur-2xl transition duration-500 hover:-translate-y-1 hover:border-[#A78BFA]/35">
                <img
                    src="{{ asset(ltrim($image, '/')) }}"
                    alt="Student dashboard preview"
                    class="block w-full rounded-xl object-cover"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';"
                />

                <div class="hidden min-h-[260px] place-items-center rounded-xl border border-white/10 bg-white/[0.04] px-6 text-center sm:min-h-[420px]">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#A78BFA]">Dashboard Preview</p>
                        <p class="mt-3 text-sm leading-7 text-[#EEF5FF]/68">
                            Add your dashboard mockup at <span class="text-white">/public/images/dashboard-preview.webp</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
