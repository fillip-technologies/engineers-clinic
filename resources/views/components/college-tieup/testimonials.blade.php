@php
    $testimonials = [
        [
            'quote' => 'The most useful part was the structure. Our placement team could see which students were active, which projects were completed, and where follow-up was needed.',
            'name' => 'Placement Coordinator',
            'meta' => 'Engineering College Partner',
        ],
        [
            'quote' => 'Engineers Clinic helped us convert internship participation into visible project outcomes. The coordination model worked well for department-level planning.',
            'name' => 'Head of Department',
            'meta' => 'Computer Science Department',
        ],
    ];
@endphp

<section class="bg-bgWhite py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Institution signals</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Trusted by teams who care about delivery, reporting, and outcomes.
            </h2>
        </div>

        <div class="mt-10 grid gap-5 lg:grid-cols-2">
            @foreach($testimonials as $testimonial)
                <figure class="rounded-lg border border-borderLight bg-bgWhite p-7 shadow-sm">
                    <blockquote class="text-base leading-8 text-textSecondary">
                        "{{ $testimonial['quote'] }}"
                    </blockquote>
                    <figcaption class="mt-6 border-t border-borderLight pt-5">
                        <p class="font-semibold text-textPrimary">{{ $testimonial['name'] }}</p>
                        <p class="mt-1 text-sm text-textMuted">{{ $testimonial['meta'] }}</p>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
