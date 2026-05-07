@extends('layouts.app')

@section('content')
    <section
        class="relative overflow-hidden bg-gradient-to-br from-bgMain via-bgWhite to-bgSoft px-6 py-16 sm:px-10 lg:px-14 lg:py-20">
        <div
            class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(34,197,94,0.15),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(249,115,22,0.15),_transparent_36%)]">
        </div>
        <div class="absolute left-0 top-20 h-72 w-72 rounded-full bg-brandSoft blur-3xl"></div>
        <div class="absolute right-0 top-1/3 h-80 w-80 rounded-full bg-secondarySoft blur-3xl"></div>

        <div class="relative mx-auto max-w-6xl">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.32em] text-brandLight">
                        {{ $signup['eyebrow'] }}
                    </p>
                    <h1 class="mt-5 text-4xl font-semibold tracking-tight text-textPrimary sm:text-5xl lg:text-6xl">
                        {{ $signup['title'] }}
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-textSecondary sm:text-lg">
                        {{ $signup['description'] }}
                    </p>
                    <p class="mt-8 text-sm text-textSecondary">
                        Already have an account?
                        <a href="{{ url('/login') }}" class="font-semibold text-brand transition hover:text-brandDark">
                            Go to login
                        </a>
                    </p>
                </div>

                <div class="w-full">
                    <div
                        class="rounded-[2rem] border border-borderLight bg-cardBg p-4 shadow-2xl shadow-glowGreen/30 backdrop-blur">
                        <div class="rounded-[1.75rem] border border-white/70 bg-white/95 p-6 sm:p-7">
                            <div class="rounded-[1.5rem] border border-borderLight bg-bgWhite p-6 shadow-xl shadow-glowGreen/10">
                                <div class="max-w-2xl">
                                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">
                                        {{ $signup['label'] }} Registration
                                    </p>
                                    <h2 class="mt-3 text-2xl font-semibold text-textPrimary sm:text-3xl">
                                        Complete your signup details
                                    </h2>
                                    <p class="mt-4 text-sm leading-7 text-textSecondary sm:text-base">
                                        This form is ready for the next backend step. Once you add form handling, these
                                        fields can post directly into your registration flow.
                                    </p>
                                </div>

                                <form method="POST" action="{{ route('signup.submit', ['role' => $role]) }}" class="mt-8">
                                    @csrf

                                    <div class="grid gap-5">
                                        @foreach ($signup['fields'] as $field)
                                            <div class="{{ isset($field['conditional']) && $field['conditional'] ? 'college-other hidden' : '' }}">
                                                <label class="text-sm font-medium text-textSecondary"
                                                    for="{{ $field['name'] }}">
                                                    {{ $field['label'] }}
                                                </label>
                                                @if($field['type'] === 'select')
                                                    <select id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                                                        class="mt-2 w-full rounded-2xl border border-borderLight bg-bgSoft px-4 py-3 text-sm text-textPrimary outline-none transition placeholder:text-textMuted focus:border-brand focus:bg-bgWhite focus:ring-4 focus:ring-brandSoft">
                                                        <option value="">Select an option</option>
                                                        @foreach($field['options'] as $value => $label)
                                                            <option value="{{ $value }}" {{ old($field['name']) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <input id="{{ $field['name'] }}" type="{{ $field['type'] }}"
                                                        name="{{ $field['name'] }}" value="{{ old($field['name']) }}"
                                                        placeholder="{{ $field['placeholder'] }}"
                                                        class="mt-2 w-full rounded-2xl border border-borderLight bg-bgSoft px-4 py-3 text-sm text-textPrimary outline-none transition placeholder:text-textMuted focus:border-brand focus:bg-bgWhite focus:ring-4 focus:ring-brandSoft" />
                                                @endif
                                                @error($field['name'])
                                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="submit"
                                        class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-brand to-secondary px-5 py-3 text-sm font-semibold text-white transition hover:from-brandDark hover:to-secondary">
                                        {{ $signup['button'] }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        @if($role === 'student')
        document.getElementById('student_college').addEventListener('change', function() {
            const otherField = document.querySelector('.college-other');
            if (this.value === 'other') {
                otherField.classList.remove('hidden');
            } else {
                otherField.classList.add('hidden');
            }
        });
        @endif
    </script>
@endsection
