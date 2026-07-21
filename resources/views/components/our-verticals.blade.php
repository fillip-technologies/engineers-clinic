@php
    $verticals = [
        'Technosys Managment',
        'Fillip Technologies',
        'Redn Technologies',
        'propelxp.com',
    ];
@endphp

<section class="bg-white py-12 sm:py-16">
    <div class="container-main">
        <div class="mx-auto max-w-3xl text-center">
            <span class="text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">Our Verticals</span>
            <h2 class="mt-3 text-2xl font-black leading-tight text-[#161326] sm:text-3xl">
                Built across focused technology and growth brands.
            </h2>
            <p class="mt-4 text-base leading-7 text-[#6B7280]">
                Our ecosystem brings together training, technology, consulting, and product-led learning initiatives.
            </p>
        </div>

        <div class="mx-auto mt-8 grid max-w-5xl gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($verticals as $vertical)
                <div class="flex min-h-28 items-center justify-center rounded-2xl border border-[#ECEBFF] bg-[#FAFBFF] px-5 text-center shadow-[0_12px_32px_rgba(15,10,42,0.05)]">
                    <h3 class="text-base font-black leading-6 text-[#161326]">
                        {{ $vertical }}
                    </h3>
                </div>
            @endforeach
        </div>
    </div>
</section>
