@php
    $stats = [
        ['value' => '50+', 'label' => 'college conversations supported'],
        ['value' => '10k+', 'label' => 'student learning journeys'],
        ['value' => '300+', 'label' => 'guided project tasks'],
        ['value' => '12+', 'label' => 'career domains'],
    ];
@endphp

<section class="border-y border-borderLight bg-bgWhite">
    <div class="mx-auto max-w-7xl px-6 py-8">
        <div class="grid gap-px overflow-hidden rounded-xl border border-borderLight bg-borderLight sm:grid-cols-2 lg:grid-cols-4">
            @foreach($stats as $stat)
                <div class="bg-bgWhite p-6">
                    <p class="text-3xl font-semibold tracking-tight text-textPrimary">{{ $stat['value'] }}</p>
                    <p class="mt-2 text-sm leading-6 text-textSecondary">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
