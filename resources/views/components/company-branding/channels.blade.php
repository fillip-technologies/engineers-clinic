@php
    $channels = [
        ['title' => 'LMS placements', 'body' => 'Contextual visibility inside learning journeys, project modules, skill communities, and career-track touchpoints.', 'signal' => 'High intent'],
        ['title' => 'Webinar funnels', 'body' => 'Hosted sessions for product education, employer stories, certification explainers, and hiring awareness.', 'signal' => 'Registrations'],
        ['title' => 'Campus outreach', 'body' => 'Coordinator-led distribution across relevant departments, student groups, and college-facing updates.', 'signal' => 'Local reach'],
        ['title' => 'Community media', 'body' => 'Campaign narratives across newsletters, social posts, student updates, reminders, and event communication.', 'signal' => 'Repeat recall'],
    ];
@endphp

<section id="branding-channels" class="bg-bgWhite py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-[0.72fr_1fr] lg:items-start">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Distribution network</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Reach audiences through owned, contextual, and measurable channels.
            </h2>
            <p class="mt-5 text-base leading-8 text-textSecondary">
                Campaigns are mapped by objective, audience segment, content format, and expected action so every placement has a clear job to do.
            </p>
            <div class="mt-7 rounded-xl border border-borderLight bg-bgSoft p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-textMuted">Placement strategy</p>
                <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                    @foreach(['Awareness', 'Registration', 'Consideration', 'Qualified interest'] as $goal)
                        <span class="rounded-md border border-borderLight bg-bgWhite px-3 py-2 font-medium text-textPrimary">{{ $goal }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-borderLight bg-bgWhite shadow-[0_22px_62px_rgba(22,8,64,0.08)]">
            <div class="grid border-b border-borderLight bg-bgSoft px-5 py-4 text-xs font-semibold uppercase tracking-[0.14em] text-textMuted sm:grid-cols-[210px_1fr_120px]">
                <span>Channel</span>
                <span>Campaign role</span>
                <span class="hidden sm:block">Signal</span>
            </div>
            @foreach($channels as $channel)
                <article class="group grid gap-3 border-b border-borderLight p-5 transition duration-300 last:border-b-0 hover:bg-bgSoft sm:grid-cols-[210px_1fr_120px] sm:gap-8">
                    <h3 class="font-semibold text-textPrimary">{{ $channel['title'] }}</h3>
                    <p class="text-sm leading-7 text-textSecondary">{{ $channel['body'] }}</p>
                    <span class="h-fit w-fit rounded-full border border-brand/20 bg-brandSoft px-3 py-1 text-xs font-semibold text-brand sm:justify-self-end">{{ $channel['signal'] }}</span>
                </article>
            @endforeach
        </div>
    </div>
</section>
