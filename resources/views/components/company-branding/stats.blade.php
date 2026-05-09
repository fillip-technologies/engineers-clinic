@php
    $stats = [
        ['value' => '10k+', 'label' => 'reachable student and early-career profiles'],
        ['value' => '12+', 'label' => 'domain-based audience communities'],
        ['value' => '30+', 'label' => 'campaign-ready learning and career tracks'],
        ['value' => '4-layer', 'label' => 'LMS, webinar, community, and campus distribution'],
    ];
@endphp

<section class="border-y border-borderLight bg-bgWhite">
    <div class="mx-auto max-w-7xl px-6 py-8">
        <div class="grid gap-px overflow-hidden rounded-xl border border-borderLight bg-borderLight shadow-[0_18px_54px_rgba(22,8,64,0.06)] sm:grid-cols-2 lg:grid-cols-4">
            @foreach($stats as $stat)
                <div class="group bg-bgWhite p-6 transition duration-300 hover:bg-bgSoft">
                    <span class="mb-5 block h-1 w-10 rounded-full bg-brand/70 transition duration-300 group-hover:w-16"></span>
                    <p class="text-3xl font-semibold tracking-tight text-textPrimary">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-sm leading-6 text-textSecondary">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
