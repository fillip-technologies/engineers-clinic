<section class="bg-bgWhite py-16 sm:py-20 lg:py-24">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING -->
        <div class="mb-10 text-center sm:mb-16 lg:text-left">
            <h2 class="text-3xl md:text-5xl font-bold leading-tight text-textPrimary">
                Real Results from
                <span class="bg-gradient-to-r from-brand via-secondary to-accent bg-clip-text text-transparent">
                    Internship Students
                </span>
            </h2>
        </div>

        <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 items-start">

            <!-- LEFT FEATURED -->
            <div class="relative bg-bgWhite border border-borderLight rounded-3xl p-6 shadow-lg transition sm:p-8">

                <p id="featuredText" class="text-base text-textPrimary leading-relaxed mb-6 sm:mb-8 sm:text-lg">
                    “Before joining, I had zero confidence in building projects. This internship gave me real-world tasks and clarity.”
                </p>

                <div class="flex items-center gap-4">
                    <img id="featuredImg" src="https://i.pravatar.cc/60?img=15"
                        class="w-12 h-12 rounded-full">
                    <div>
                        <p id="featuredName" class="font-semibold text-textPrimary">Rohit Kumar</p>
                        <p id="featuredRole" class="text-sm text-textSecondary">Frontend Developer</p>
                    </div>
                </div>

            </div>

            <!-- RIGHT VERTICAL SCROLL -->
            <div class="relative">

                <!-- FADE TOP -->
                <div class="absolute top-0 left-0 w-full h-16 bg-gradient-to-b from-bgWhite to-transparent z-10"></div>

                <!-- FADE BOTTOM -->
                <div class="absolute bottom-0 left-0 w-full h-16 bg-gradient-to-t from-bgWhite to-transparent z-10"></div>

                <div class="vertical-marquee">

                    <div class="vertical-track">

                        <!-- CARD -->
                        <div onclick="changeTestimonial(this)"
                            data-text="AI tools made learning super fast. I completed tasks in half the time."
                            data-name="Sneha Patel"
                            data-role="UI/UX Intern"
                            data-img="https://i.pravatar.cc/60?img=22"
                            class="card">
                            <p>“AI tools made learning super fast…”</p>
                            <span>Sneha Patel</span>
                        </div>

                        <div onclick="changeTestimonial(this)"
                            data-text="Best part was structured tasks. I finally understood real development flow."
                            data-name="Arjun Mehta"
                            data-role="Backend Intern"
                            data-img="https://i.pravatar.cc/60?img=30"
                            class="card">
                            <p>“Structured tasks helped a lot…”</p>
                            <span>Arjun Mehta</span>
                        </div>

                        <div onclick="changeTestimonial(this)"
                            data-text="Certificate + projects helped me get shortlisted in multiple companies."
                            data-name="Priya Singh"
                            data-role="Data Science Intern"
                            data-img="https://i.pravatar.cc/60?img=45"
                            class="card">
                            <p>“Got shortlisted easily…”</p>
                            <span>Priya Singh</span>
                        </div>

                        <!-- DUPLICATE FOR INFINITE -->
                        <div onclick="changeTestimonial(this)"
                            data-text="AI tools made learning super fast. I completed tasks in half the time."
                            data-name="Sneha Patel"
                            data-role="UI/UX Intern"
                            data-img="https://i.pravatar.cc/60?img=22"
                            class="card">
                            <p>“AI tools made learning super fast…”</p>
                            <span>Sneha Patel</span>
                        </div>

                        <div onclick="changeTestimonial(this)"
                            data-text="Best part was structured tasks. I finally understood real development flow."
                            data-name="Arjun Mehta"
                            data-role="Backend Intern"
                            data-img="https://i.pravatar.cc/60?img=30"
                            class="card">
                            <p>“Structured tasks helped a lot…”</p>
                            <span>Arjun Mehta</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- CSS -->
<style>
    .vertical-marquee {
        height: 320px;
        overflow: hidden;
        position: relative;
    }

    .vertical-track {
        display: flex;
        flex-direction: column;
        gap: 16px;
        animation: scrollY 20s linear infinite;
    }

    @keyframes scrollY {
        0% {
            transform: translateY(0);
        }

        100% {
            transform: translateY(-50%);
        }
    }

    /* Pause on hover */
    .vertical-track:hover {
        animation-play-state: paused;
    }

    /* CARD STYLE */
    .card {
        cursor: pointer;
        background: white;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 14px;
        transition: all 0.3s ease;
    }

    .card:hover {
        border-color: #6366F1;
        transform: translateX(5px);
    }

    .card p {
        font-size: 14px;
        color: #555;
        margin-bottom: 6px;
    }

    .card span {
        font-size: 12px;
        font-weight: 600;
    }

    @media (min-width: 640px) {
        .vertical-marquee {
            height: 420px;
        }
    }
</style>

<!-- JS -->
<script>
    function changeTestimonial(el) {
        document.getElementById('featuredText').innerText = "“" + el.dataset.text + "”";
        document.getElementById('featuredName').innerText = el.dataset.name;
        document.getElementById('featuredRole').innerText = el.dataset.role;
        document.getElementById('featuredImg').src = el.dataset.img;
    }
</script>
