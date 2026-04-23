<section class="bg-white py-20 px-6 sm:px-10 lg:px-14">
    @php
        $programOverviewImage = $data['image'] ?? ($course['image'] ?? '/images/college-tie-up-illustration.svg');
        $programOverviewTitle = $course['title'] ?? ($data['title'] ?? 'Course');
    @endphp

    <div class="mx-auto max-w-7xl grid lg:grid-cols-2 gap-12 items-center">

        <!-- LEFT SIDE -->
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-primary mb-6">
                Program Overview
            </p>

            <div class="space-y-6">
                @foreach ($data['features'] as $feature)
                    <div class="flex items-start gap-4">
                        
                        <!-- ICON -->
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                            <span class="text-primary text-lg">•</span>
                        </div>

                        <!-- TEXT -->
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">
                                {{ $feature['title'] }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $feature['description'] }}
                            </p>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- CTA -->
            <div class="mt-10 flex flex-wrap items-center gap-6">
                
                <a href="{{ url('/login') }}"
                   class="inline-flex items-center gap-2 rounded-full border border-red-500 px-5 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 transition">
                    ↓ {{ $data['cta']['button_text'] }}
                </a>

                <p class="text-sm text-gray-600">
                    {{ $data['cta']['batch_info'] }}
                </p>

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="relative flex justify-center">

            <!-- IMAGE -->
            <img src="{{ $programOverviewImage }}"
                 alt="{{ $programOverviewTitle }} overview illustration"
                 class="w-[280px] rounded-2xl object-cover shadow-lg" />

            <!-- STATS -->
            @foreach ($data['stats'] as $index => $stat)
                <div class="absolute 
                    {{ $index == 0 ? '-top-6 left-0' : '' }}
                    {{ $index == 1 ? 'bottom-6 left-0' : '' }}
                    {{ $index == 2 ? '-top-6 right-0' : '' }}
                    bg-white rounded-xl shadow-md px-4 py-3">

                    <p class="text-blue-600 font-semibold text-lg">
                        {{ $stat['value'] }}
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $stat['label'] }}
                    </p>
                </div>
            @endforeach

        </div>

    </div>
</section>
