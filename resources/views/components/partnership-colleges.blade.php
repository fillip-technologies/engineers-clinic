@php
    $collegeLogos = [
        ['name' => 'VIT', 'file' => 'vit.png'],
        ['name' => 'SRM Institute', 'file' => 'srm.png'],
        ['name' => 'SASTRA University', 'file' => 'sastra.png'],
        ['name' => 'Manipal University', 'file' => 'manipal.png'],
        ['name' => 'IIT Kanpur', 'file' => 'iitkanpur.png'],
        ['name' => 'IIT Bombay', 'file' => 'IITBombay.png'],
        ['name' => 'GNDU', 'file' => 'gndu.png'],
        ['name' => 'DTU', 'file' => 'dtu.png'],
        ['name' => 'Christ University', 'file' => 'Christ.png'],
        ['name' => 'Bennett University', 'file' => 'Bennett.webp'],
        ['name' => 'Amity University', 'file' => 'AMITY.png'],
    ];
@endphp

<section class="bg-[#FAFBFF] py-12 sm:py-16">
    <div class="container-main">
        <div class="border-y border-[#ECEBFF] py-10">
            <div class="mx-auto max-w-3xl text-center">
                <span class="text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">Partnership Colleges</span>
                <h2 class="mt-3 text-2xl font-black leading-tight text-[#161326] sm:text-3xl">
                    Trusted by students from leading campuses.
                </h2>
                <p class="mt-4 text-base leading-7 text-[#6B7280]">
                    Our programs support learners across respected colleges and universities with practical project-based training.
                </p>
            </div>

            <div class="mx-auto mt-8 grid max-w-6xl grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                @foreach ($collegeLogos as $college)
                    <div
                        class="flex items-center justify-center overflow-hidden rounded-2xl border border-[#ECEBFF] bg-white px-5 shadow-[0_12px_32px_rgba(15,10,42,0.05)]"
                        style="height: 88px;"
                    >
                        <img
                            src="{{ asset('images/companylogo/' . $college['file']) }}"
                            alt="{{ $college['name'] }} logo"
                            class="object-contain"
                            style="display: block; max-height: 56px; max-width: 128px; width: auto; height: auto;"
                            loading="lazy"
                        >
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
