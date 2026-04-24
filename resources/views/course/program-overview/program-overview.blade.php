@props(['course'])

@php
$overview = $course['program_overview'] ?? [];
@endphp

<section class="bg-bgWhite px-6 py-20 text-center">
    <div class="max-w-5xl mx-auto">

        <!-- TITLE -->
        <h2 class="text-3xl sm:text-4xl font-semibold text-textPrimary leading-tight">
            {{ $course['title'] ?? 'Course Overview' }} 
            <span class="text-brand">Career Growth</span>
        </h2>

        <!-- SUBTEXT -->
        <p class="mt-5 text-textSecondary max-w-2xl mx-auto">
            {{ $course['description'] ?? '' }}
        </p>

        <!-- CARDS -->
        <div class="mt-14 grid md:grid-cols-3 gap-8">

            @foreach ($overview['stats'] ?? [] as $stat)
            <div class="text-center">

                <!-- ICON -->
                <div class="w-12 h-12 mx-auto mb-4 flex items-center justify-center rounded-full bg-brandSoft text-brand text-lg font-semibold">
                    +
                </div>

                <!-- VALUE -->
                <h3 class="font-semibold text-textPrimary">
                    {{ $stat['value'] ?? '' }}
                </h3>

                <!-- LABEL -->
                <p class="text-sm text-textSecondary mt-2">
                    {{ $stat['label'] ?? '' }}
                </p>

            </div>
            @endforeach

        </div>

    </div>
</section>  