@php
    $technologyGroups = [
        [
            'title' => 'Technology & Data',
            'items' => [
                ['name' => 'React', 'slug' => 'react', 'color' => '61DAFB'],
                ['name' => 'Python', 'slug' => 'python', 'color' => '3776AB'],
                ['name' => 'AWS', 'initials' => 'AWS'],
                ['name' => 'AI', 'initials' => 'AI'],
                ['name' => 'DevOps', 'slug' => 'docker', 'color' => '2496ED'],
                ['name' => 'Cyber Security', 'initials' => 'CS'],
            ],
        ],
        [
            'title' => 'Business & Management',
            'items' => [
                ['name' => 'Marketing', 'slug' => 'googleads', 'color' => '4285F4'],
                ['name' => 'Finance', 'slug' => 'stripe', 'color' => '635BFF'],
                ['name' => 'CRM', 'initials' => 'CRM'],
                ['name' => 'Analytics', 'slug' => 'googleanalytics', 'color' => 'E37400'],
            ],
        ],
        [
            'title' => 'Engineering',
            'items' => [
                ['name' => 'AutoCAD', 'slug' => 'autodesk', 'color' => '0696D7'],
                ['name' => 'BIM', 'slug' => 'autodesk', 'color' => '0696D7'],
                ['name' => 'Robotics', 'slug' => 'ros', 'color' => '22314E'],
                ['name' => 'CFD', 'slug' => 'ansys', 'color' => 'FFB71B'],
            ],
        ],
        [
            'title' => 'Law & Legal Studies',
            'items' => [
                ['name' => 'Corporate Law', 'initials' => 'CL'],
                ['name' => 'IP Law', 'initials' => 'IP'],
                ['name' => 'Cyber Law', 'initials' => 'CY'],
            ],
        ],
        [
            'title' => 'Media & Communication',
            'items' => [
                ['name' => 'UI/UX', 'slug' => 'figma', 'color' => 'F24E1E'],
                ['name' => 'Motion Graphics', 'initials' => 'MG'],
                ['name' => 'SEO', 'slug' => 'google', 'color' => '4285F4'],
                ['name' => 'Branding', 'initials' => 'BR'],
            ],
        ],
        [
    'title' => 'Artificial Intelligence',
    'items' => [
        ['name' => 'Machine Learning', 'initials' => 'ML'],
        ['name' => 'Deep Learning', 'initials' => 'DL'],
        ['name' => 'ChatGPT', 'initials' => 'GPT'],
        ['name' => 'TensorFlow', 'slug' => 'tensorflow', 'color' => 'FF6F00'],
        ['name' => 'Computer Vision', 'initials' => 'CV'],
        ['name' => 'NLP', 'initials' => 'NLP'],
          ],
       ],
    ];
@endphp

<section class="bg-gradient-to-br from-[#f7f5ff] to-[#eef6ff] py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mx-auto max-w-3xl text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">
                Technologies We Provide
            </p>

            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Build skills with modern technologies
            </h2>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($technologyGroups as $group)
                <article class="rounded-xl border border-borderLight bg-white/85 p-5 shadow-sm backdrop-blur transition duration-300 hover:-translate-y-1 hover:border-brand/30 hover:shadow-[0_18px_44px_rgba(22,8,64,0.08)]">
                    <h3 class="text-lg font-semibold text-textPrimary">
                        {{ $group['title'] }}
                    </h3>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        @foreach ($group['items'] as $item)
                            <div class="flex min-h-16 items-center gap-3 rounded-lg border border-borderLight bg-white px-3 py-3">
                                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-bgSoft">
                                    @if (isset($item['slug']))
                                        <img
                                            src="https://cdn.simpleicons.org/{{ $item['slug'] }}/{{ $item['color'] }}"
                                            alt="{{ $item['name'] }}"
                                            class="h-5 w-5 object-contain"
                                        >
                                    @else
                                        <span class="text-xs font-bold text-brand">{{ $item['initials'] }}</span>
                                    @endif
                                </span>
                                <span class="text-sm font-semibold leading-5 text-textPrimary">
                                    {{ $item['name'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
