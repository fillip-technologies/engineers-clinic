@php
    $testimonials = [
        ['class' => 'wc-card-left-top', 'quote' => 'The internship helped me understand how real projects are planned, built, reviewed, and improved.', 'name' => 'Rohit Kumar', 'role' => 'Frontend Intern', 'avatar' => 'https://i.pravatar.cc/120?img=12', 'style' => 'quote-small'],
        ['class' => 'wc-card-left-mid', 'quote' => 'I was very impressed by the structure. Every task felt practical and connected to real work.', 'name' => 'Nisha Sharma', 'role' => 'Data Analytics Intern', 'avatar' => 'https://i.pravatar.cc/120?img=32', 'style' => 'text'],
        ['class' => 'wc-card-feature', 'quote' => 'I really appreciate the mentorship and project feedback. It made learning feel clear and professional.', 'name' => 'Sneha Patel', 'role' => 'UI/UX Intern', 'avatar' => 'https://i.pravatar.cc/140?img=47', 'style' => 'stars'],
        ['class' => 'wc-card-photo', 'quote' => 'More than online tasks, this internship gave me confidence with real output.', 'name' => 'Ananya Verma', 'role' => 'Python Intern', 'avatar' => 'https://i.pravatar.cc/300?img=44', 'style' => 'photo'],
        ['class' => 'wc-card-right-top', 'quote' => 'Good job! The course flow, support, and assignments helped me stay consistent.', 'name' => 'Priya Singh', 'role' => 'Marketing Intern', 'avatar' => 'https://i.pravatar.cc/120?img=5', 'style' => 'center'],
        ['class' => 'wc-card-right-mid', 'quote' => 'The best part was the feedback. I learned what to improve instead of only watching videos.', 'name' => 'Arjun Mehta', 'role' => 'Backend Intern', 'avatar' => 'https://i.pravatar.cc/160?img=59', 'style' => 'split'],
        ['class' => 'wc-card-bottom-wide', 'quote' => 'I was very impressed. The platform, assignments, and mentor support helped me complete my first portfolio project.', 'name' => 'Aditi Rao', 'role' => 'Full Stack Intern', 'avatar' => 'https://i.pravatar.cc/120?img=25', 'style' => 'wide'],
        ['class' => 'wc-card-bottom-mid', 'quote' => 'Tasks were simple to follow but strong enough to build real skill.', 'name' => 'Kavya Jain', 'role' => 'Design Intern', 'avatar' => 'https://i.pravatar.cc/120?img=49', 'style' => 'bubble'],
        ['class' => 'wc-card-bottom-right', 'quote' => 'Engineers Clinic gave me direction, deadlines, and a practical way to learn.', 'name' => 'Basit Ahmad', 'role' => 'Cloud Intern', 'avatar' => 'https://i.pravatar.cc/120?img=15', 'style' => 'text'],
    ];
@endphp

<style>
    .wc-collage-section {
        overflow: hidden;
        background:
            radial-gradient(circle at 12% 16%, rgba(124, 92, 252, 0.12), transparent 28%),
            radial-gradient(circle at 88% 18%, rgba(245, 200, 66, 0.14), transparent 26%),
            linear-gradient(135deg, #f7f5ff 0%, #ffffff 45%, #eef6ff 100%);
    }

    .wc-collage {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 18px;
        align-items: stretch;
    }

    .wc-card {
        position: relative;
        border: 1px solid rgba(226, 217, 255, 0.86);
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.92);
        padding: 18px;
        color: #23212d;
        box-shadow: 0 14px 34px rgba(22, 8, 64, 0.08);
        backdrop-filter: blur(14px);
    }

    .wc-quote-mark {
        font-family: Georgia, serif;
        font-size: 54px;
        font-weight: 700;
        line-height: 0.75;
        color: #160840;
    }

    .wc-avatar {
        height: 54px;
        width: 54px;
        border: 4px solid #ffffff;
        border-radius: 999px;
        object-fit: cover;
        box-shadow: 0 8px 18px rgba(22, 8, 64, 0.14);
    }

    .wc-avatar-sm {
        height: 42px;
        width: 42px;
    }

    .wc-photo {
        height: 190px;
        width: 100%;
        border-radius: 7px;
        object-fit: cover;
        object-position: top;
    }

    .wc-stars {
        color: #f5c842;
        font-size: 16px;
        letter-spacing: 2px;
    }

    .wc-card-left-top {
        grid-column: 1 / span 3;
        grid-row: 1;
    }

    .wc-card-left-mid {
        grid-column: 1 / span 3;
        grid-row: 2;
    }

    .wc-card-feature {
        grid-column: 4 / span 3;
        grid-row: 1 / span 2;
        text-align: center;
    }

    .wc-card-photo {
        grid-column: 7 / span 2;
        grid-row: 1 / span 2;
    }

    .wc-card-right-top {
        grid-column: 9 / span 4;
        grid-row: 1;
        text-align: center;
    }

    .wc-card-right-mid {
        grid-column: 9 / span 4;
        grid-row: 2;
    }

    .wc-card-bottom-wide {
        grid-column: 1 / span 5;
        grid-row: 3;
        text-align: center;
    }

    .wc-card-bottom-mid {
        grid-column: 6 / span 3;
        grid-row: 3;
        text-align: center;
    }

    .wc-card-bottom-right {
        grid-column: 9 / span 4;
        grid-row: 3;
    }

    @media (max-width: 1024px) {
        .wc-collage {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .wc-card-left-top,
        .wc-card-left-mid,
        .wc-card-feature,
        .wc-card-photo,
        .wc-card-right-top,
        .wc-card-right-mid,
        .wc-card-bottom-wide,
        .wc-card-bottom-mid,
        .wc-card-bottom-right {
            grid-column: auto;
            grid-row: auto;
        }

        .wc-card-right-mid,
        .wc-card-bottom-wide {
            grid-column: span 2;
        }
    }

    @media (max-width: 640px) {
        .wc-collage {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .wc-card,
        .wc-card-right-mid,
        .wc-card-bottom-wide {
            grid-column: auto;
        }

        .wc-card {
            padding: 16px;
        }

        .wc-photo {
            height: 220px;
        }
    }
</style>

<section class="wc-collage-section py-14 sm:py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mx-auto mb-10 max-w-3xl text-center sm:mb-12">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">
                Student Success Stories
            </p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Our students say it best
            </h2>
            <p class="mx-auto mt-4 max-w-2xl text-base leading-7 text-textSecondary">
                Real feedback from learners who built practical skills through guided projects, mentor support, and internship-style tasks.
            </p>
        </div>

        <div class="wc-collage">
            @foreach ($testimonials as $testimonial)
                <article class="wc-card {{ $testimonial['class'] }}">
                    @if ($testimonial['style'] === 'quote-small')
                        <div class="flex items-start gap-3">
                            <span class="wc-quote-mark">&ldquo;</span>
                            <div>
                                <p class="text-xs leading-5 text-[#55515e]">{{ $testimonial['quote'] }}</p>
                                <div class="mt-3 flex items-center gap-3">
                                    <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="wc-avatar wc-avatar-sm">
                                    <div>
                                        <p class="text-xs font-bold">{{ $testimonial['name'] }}</p>
                                        <p class="text-[10px] text-[#77727f]">{{ $testimonial['role'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($testimonial['style'] === 'stars')
                        <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="wc-avatar mx-auto">
                        <div class="wc-stars mt-5">★★★★★</div>
                        <h3 class="mt-3 text-lg font-bold">I really appreciate!!</h3>
                        <p class="mt-3 text-xs leading-5 text-[#55515e]">{{ $testimonial['quote'] }}</p>
                        <p class="mt-5 text-xs font-bold">{{ $testimonial['name'] }}</p>
                        <p class="text-[10px] text-[#77727f]">{{ $testimonial['role'] }}</p>
                        <span class="absolute bottom-2 right-6 font-serif text-6xl leading-none text-brandDark">&rdquo;</span>
                    @elseif ($testimonial['style'] === 'photo')
                        <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="wc-photo">
                        <p class="mt-3 text-xs leading-5 text-[#55515e]">{{ $testimonial['quote'] }}</p>
                        <p class="mt-3 text-xs font-bold">{{ $testimonial['name'] }}</p>
                    @elseif ($testimonial['style'] === 'center')
                        <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="wc-avatar mx-auto">
                        <h3 class="mt-4 text-sm font-bold">Good Job!</h3>
                        <p class="mt-2 text-xs leading-5 text-[#55515e]">{{ $testimonial['quote'] }}</p>
                        <p class="mt-3 text-[10px] font-bold">{{ $testimonial['name'] }}</p>
                    @elseif ($testimonial['style'] === 'split')
                        <div class="grid items-center gap-4 sm:grid-cols-[118px_1fr]">
                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="h-28 w-28 rounded-md object-cover object-top">
                            <div>
                                <span class="font-serif text-3xl leading-none">&ldquo;</span>
                                <p class="mt-1 text-xs leading-5 text-[#55515e]">{{ $testimonial['quote'] }}</p>
                                <p class="mt-4 text-xs font-bold">{{ $testimonial['name'] }}</p>
                                <p class="text-[10px] text-[#77727f]">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                    @elseif ($testimonial['style'] === 'wide')
                        <h3 class="text-sm font-bold">I was very impressed!</h3>
                        <p class="mt-2 text-xs leading-5 text-[#55515e]">{{ $testimonial['quote'] }}</p>
                    @elseif ($testimonial['style'] === 'bubble')
                        <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="wc-avatar mx-auto">
                        <p class="mt-5 text-xs leading-5 text-[#55515e]">&ldquo;{{ $testimonial['quote'] }}&rdquo;</p>
                        <p class="mt-4 text-xs font-bold">{{ $testimonial['name'] }}</p>
                    @else
                        <p class="text-xs leading-5 text-[#55515e]">&ldquo;{{ $testimonial['quote'] }}&rdquo;</p>
                        <div class="mt-4 flex items-center justify-end gap-3">
                            <div class="text-right">
                                <p class="text-xs font-bold">{{ $testimonial['name'] }}</p>
                                <p class="text-[10px] text-[#77727f]">{{ $testimonial['role'] }}</p>
                            </div>
                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" class="wc-avatar wc-avatar-sm">
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
