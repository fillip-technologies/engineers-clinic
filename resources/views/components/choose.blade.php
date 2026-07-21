@php
    $testimonials = [
        ['quote' => 'The workspace made the project feel clear. I knew exactly what to build, when to submit, and how to improve my GitHub.', 'name' => 'Shreya', 'role' => 'Frontend Student', 'avatar' => 'images/students/image-8.jpg'],
        ['quote' => 'Before this, my GitHub was empty. Now I have a reviewed project and a certificate I can confidently add to my resume.', 'name' => 'Aman Kumar', 'role' => 'Full Stack Student', 'avatar' => 'images/students/image-2.jpg'],
        ['quote' => 'The milestone tasks helped me stop watching random videos and actually finish a complete project.', 'name' => 'Sneha Patel', 'role' => 'UI/UX Student', 'avatar' => 'images/students/image-3.jpg'],
        ['quote' => 'Review feedback was the most useful part. It helped me understand what industry-quality project submission means.', 'name' => 'Rohit Verma', 'role' => 'Python Student', 'avatar' => 'images/students/image-4.jpg'],
        ['quote' => 'I used the certificate and GitHub repo in my interview. It gave me something concrete to talk about.', 'name' => 'Priya Singh', 'role' => 'Marketing Student', 'avatar' => 'images/students/image-5.jpg'],
        ['quote' => 'The flow is simple: choose, build, push, review, certify. That structure kept me consistent.', 'name' => 'Arjun Mehta', 'role' => 'React Student', 'avatar' => 'images/students/image-6.jpg'],
    ];
@endphp

<section class="relative isolate overflow-hidden bg-white py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10 bg-[radial-gradient(circle_at_12%_18%,rgba(109,93,246,0.10),transparent_28%),radial-gradient(circle_at_88%_22%,rgba(168,85,247,0.10),transparent_26%)]"></div>

    <div class="container-main">
        <div class="mx-auto mb-12 max-w-3xl text-center">
            <span class="inline-flex rounded-full border border-[#ECEBFF] bg-[#FAFBFF] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">Testimonials</span>
            <h2 class="mt-5 text-3xl font-black leading-tight text-[#161326] sm:text-4xl lg:text-5xl">Students feel the difference when learning becomes buildable.</h2>
            <p class="mt-5 text-base leading-8 text-[#6B7280]">Real feedback from students using projects, GitHub submissions, and review-based certification to prove practical skills.</p>
        </div>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($testimonials as $testimonial)
                <article class="group rounded-[2rem] border border-[#ECEBFF] bg-white p-6 shadow-[0_18px_48px_rgba(15,10,42,0.06)] transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF] hover:shadow-[0_26px_70px_rgba(109,93,246,0.14)]">
                    <div class="flex items-center gap-1 text-[#A855F7]">
                        @foreach (range(1, 5) as $star)
                            <span class="text-sm">&#9733;</span>
                        @endforeach
                    </div>
                    <p class="mt-5 text-base font-medium leading-8 text-[#161326]">"{{ $testimonial['quote'] }}"</p>
                    <div class="mt-6 flex items-center gap-3 border-t border-[#ECEBFF] pt-5">
                        <img src="{{ asset($testimonial['avatar']) }}" alt="{{ $testimonial['name'] }}" class="h-12 w-12 rounded-full object-cover object-top ring-4 ring-[#ECEBFF]">
                        <div>
                            <p class="font-black text-[#161326]">{{ $testimonial['name'] }}</p>
                            <p class="text-sm font-bold text-[#6B7280]">{{ $testimonial['role'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
