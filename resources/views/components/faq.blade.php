<section class="bg-bgWhite py-16 sm:py-20">

    <div class="max-w-4xl mx-auto px-6">

        <!-- TITLE -->
        <div class="mb-10 text-center">
            <h2 class="text-2xl md:text-4xl font-bold leading-tight text-textPrimary">
                Frequently Asked 
                <span class="bg-gradient-to-r from-brand via-brandLight to-secondary bg-clip-text text-transparent">
                    Questions
                </span>
            </h2>
        </div>

        <!-- FAQ LIST -->
        <div class="space-y-4">

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight bg-cardBg rounded-xl p-4 transition hover:border-brandLight hover:shadow-sm sm:p-5">

                <button @click="open = !open"
                    class="w-full flex items-start justify-between gap-4 text-left">

                    <span class="font-medium text-textPrimary">
                        What is this internship program?
                    </span>

                    <span class="text-xl text-brand" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    It’s a task-based internship where you learn by completing real-world projects and assignments.
                </p>

            </div>

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight bg-cardBg rounded-xl p-4 transition hover:border-brandLight hover:shadow-sm sm:p-5">

                <button @click="open = !open"
                    class="w-full flex items-start justify-between gap-4 text-left">

                    <span class="font-medium text-textPrimary">
                        Will I get a certificate?
                    </span>

                    <span class="text-xl text-brand" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    Yes, you will receive a verified certificate after completing all tasks successfully.
                </p>

            </div>

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight bg-cardBg rounded-xl p-4 transition hover:border-brandLight hover:shadow-sm sm:p-5">

                <button @click="open = !open"
                    class="w-full flex items-start justify-between gap-4 text-left">

                    <span class="font-medium text-textPrimary">
                        Is this internship beginner friendly?
                    </span>

                    <span class="text-xl text-brand" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    Absolutely! The program is designed for beginners with step-by-step guidance.
                </p>

            </div>

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight bg-cardBg rounded-xl p-4 transition hover:border-brandLight hover:shadow-sm sm:p-5">

                <button @click="open = !open"
                    class="w-full flex items-start justify-between gap-4 text-left">

                    <span class="font-medium text-textPrimary">
                        How long does the internship last?
                    </span>

                    <span class="text-xl text-brand" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    Typically 1–3 months depending on the track you choose.
                </p>

            </div>

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight bg-cardBg rounded-xl p-4 transition hover:border-brandLight hover:shadow-sm sm:p-5">

                <button @click="open = !open"
                    class="w-full flex items-start justify-between gap-4 text-left">

                    <span class="font-medium text-textPrimary">
                        Do I get placement support?
                    </span>

                    <span class="text-xl text-brand" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    Yes, we guide you with resume building, projects, and interview preparation.
                </p>

            </div>

        </div>

    </div>

</section>
