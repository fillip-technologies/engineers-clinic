@props([
    'image' => '/images/add-dashboard.png',
])

<section class="relative isolate overflow-hidden bg-[#FAFBFF] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute left-1/2 top-0 h-[32rem] w-[32rem] -translate-x-1/2 rounded-full bg-[#6D5DF6]/10 blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 h-96 w-96 rounded-full bg-[#A855F7]/10 blur-3xl"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(109,93,246,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(109,93,246,0.04)_1px,transparent_1px)] bg-[size:44px_44px]"></div>
    </div>

    <div class="container-main">
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex rounded-full border border-[#ECEBFF] bg-[#F5F3FF] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">Student Workspace</span>
            <h2 class="mt-5 text-3xl font-black leading-tight text-[#161326] sm:text-4xl lg:text-5xl">
                A personal dashboard for every project.
            </h2>
            <p class="mt-5 text-base leading-8 text-[#6B7280]">
                Notion clarity, Jira structure, Linear polish: tasks, milestones, GitHub, reviews, and certificate status in one place.
            </p>
        </div>

        <div class="relative mx-auto mt-12 max-w-6xl ec-float">
            <div class="absolute inset-x-10 bottom-0 top-10 rounded-[2rem] bg-[#6D5DF6]/12 blur-3xl"></div>
            <div class="relative overflow-hidden rounded-[2rem] border border-[#ECEBFF] bg-white/80 p-3 shadow-[0_34px_100px_rgba(15,10,42,0.10)] backdrop-blur-2xl">
                <div class="h-[360px] overflow-hidden rounded-[1.5rem] border border-[#ECEBFF] bg-[#FAFBFF] sm:h-[460px] lg:h-[620px]">
                    <img src="{{ asset(ltrim($image, '/')) }}" alt="A personal dashboard for every project" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.01]">
                </div>
            </div>
        </div>
    </div>
</section>
