@php
    $tracks = [
        ['title' => 'Engineering & Technology', 'items' => 'Web ecosystems, cloud systems, data analytics, AI, mobile development'],
        ['title' => 'Core Engineering', 'items' => 'Civil design, BIM, structural systems, mechanical automation, IoT power grids'],
        ['title' => 'Business & Communication', 'items' => 'Digital marketing, PR strategy, journalism, corporate communication'],
        ['title' => 'Design, Law & Emerging Domains', 'items' => 'UI/UX, product design, legal research, digital law, blockchain systems'],
    ];
@endphp

<section class="bg-bgWhite py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-10 lg:grid-cols-[0.72fr_1fr] lg:items-start">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Programs and tracks</p>
                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Multi-domain learning tracks for institution-wide rollout.
                </h2>
                <p class="mt-5 text-base leading-8 text-textSecondary">
                    Colleges can map tracks by department, semester, or placement objective while keeping delivery and reporting under one structured partnership model.
                </p>
            </div>

            <div class="rounded-xl border border-borderLight bg-bgWhite shadow-sm">
                @foreach($tracks as $track)
                    <article class="grid gap-4 border-b border-borderLight p-6 last:border-b-0 sm:grid-cols-[240px_1fr]">
                        <h3 class="text-lg font-semibold text-textPrimary">{{ $track['title'] }}</h3>
                        <p class="text-sm leading-7 text-textSecondary">{{ $track['items'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
