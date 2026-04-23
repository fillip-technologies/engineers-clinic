<section class="bg-bgWhite py-20">

    <div class="max-w-4xl mx-auto px-6">

        <!-- TITLE -->
        <div class="mb-10 text-center">
            <h2 class="text-2xl md:text-4xl font-bold text-textPrimary">
                Frequently Asked 
                <span class="bg-gradient-to-r from-green-600 via-green-500 to-orange-500 bg-clip-text text-transparent">
                    Questions
                </span>
            </h2>
        </div>

        <!-- FAQ LIST -->
        <div class="space-y-4">

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight rounded-xl p-5 transition hover:shadow-sm">

                <button @click="open = !open"
                    class="w-full flex justify-between items-center text-left">

                    <span class="font-medium text-textPrimary">
                        What is this internship program?
                    </span>

                    <span class="text-xl text-textSecondary" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    It’s a task-based internship where you learn by completing real-world projects and assignments.
                </p>

            </div>

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight rounded-xl p-5 transition hover:shadow-sm">

                <button @click="open = !open"
                    class="w-full flex justify-between items-center text-left">

                    <span class="font-medium text-textPrimary">
                        Will I get a certificate?
                    </span>

                    <span class="text-xl text-textSecondary" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    Yes, you will receive a verified certificate after completing all tasks successfully.
                </p>

            </div>

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight rounded-xl p-5 transition hover:shadow-sm">

                <button @click="open = !open"
                    class="w-full flex justify-between items-center text-left">

                    <span class="font-medium text-textPrimary">
                        Is this internship beginner friendly?
                    </span>

                    <span class="text-xl text-textSecondary" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    Absolutely! The program is designed for beginners with step-by-step guidance.
                </p>

            </div>

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight rounded-xl p-5 transition hover:shadow-sm">

                <button @click="open = !open"
                    class="w-full flex justify-between items-center text-left">

                    <span class="font-medium text-textPrimary">
                        How long does the internship last?
                    </span>

                    <span class="text-xl text-textSecondary" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    Typically 1–3 months depending on the track you choose.
                </p>

            </div>

            <!-- ITEM -->
            <div x-data="{ open: false }"
                class="border border-borderLight rounded-xl p-5 transition hover:shadow-sm">

                <button @click="open = !open"
                    class="w-full flex justify-between items-center text-left">

                    <span class="font-medium text-textPrimary">
                        Do I get placement support?
                    </span>

                    <span class="text-xl text-textSecondary" x-text="open ? '-' : '+'"></span>
                </button>

                <p x-show="open" x-transition
                    class="mt-3 text-sm text-textSecondary">
                    Yes, we guide you with resume building, projects, and interview preparation.
                </p>

            </div>

        </div>

    </div>

</section>