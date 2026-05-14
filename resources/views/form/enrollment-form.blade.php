<!-- ANIMATED FORM WRAPPER -->
<div class="relative overflow-hidden rounded-[2rem] p-[1px]">

    <!-- ANIMATED BORDER -->
    <div
        class="absolute inset-0 rounded-[2rem] bg-[conic-gradient(from_180deg_at_50%_50%,#5B5BF6,#8B5CF6,#C084FC,#5B5BF6)] animate-[spin_8s_linear_infinite] opacity-70">
    </div>

    <!-- FORM CARD -->
    <div
        class="relative overflow-hidden rounded-[calc(2rem-1px)] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)]"

        x-data="{
            step: 1,
            level: @js($selectedLevel ?? 'Beginner')
        }">

        <!-- TOP -->
        <div class="border-b border-[#F1F5F9] px-8 py-7">

            <!-- STEPPER -->
            <div class="flex items-center gap-4">

                <template x-for="item in [1,2,3,4]" :key="item">

                    <div class="flex items-center flex-1">

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-semibold transition"
                            :class="step >= item
                                ? 'bg-[#5B5BF6] text-white'
                                : 'bg-[#F1F5F9] text-[#64748B]'">

                            <span x-text="item"></span>

                        </div>

                        <div x-show="item != 4"
                            class="mx-3 h-[1px] flex-1 bg-[#E2E8F0]">
                        </div>

                    </div>

                </template>

            </div>

            <!-- LABEL -->
            <div class="mt-5">

                <p
                    class="text-xs font-semibold uppercase tracking-[0.18em] text-[#5B5BF6]">

                    Enrollment Form

                </p>

                <h2
                    class="mt-2 text-[2rem] font-bold tracking-tight text-[#111827]">

                    Secure your seat

                </h2>

            </div>

        </div>

        <!-- FORM -->
        <div class="p-8">

            <!-- STEP 1 -->
            <div x-show="step === 1" class="space-y-5">

                <!-- FULL NAME -->
                <div class="relative">

                    <input type="text"
                        placeholder=" "
                        class="peer w-full rounded-2xl border border-[#E2E8F0] bg-white px-5 pt-7 pb-3 text-[15px] text-[#111827] outline-none transition duration-300 focus:border-[#5B5BF6] focus:ring-4 focus:ring-[#5B5BF6]/10">

                    <label
                        class="absolute left-5 top-5 bg-white px-1 text-[15px] text-[#94A3B8] transition-all duration-200
                        peer-focus:-top-2
                        peer-focus:text-xs
                        peer-focus:font-medium
                        peer-focus:text-[#5B5BF6]
                        peer-[:not(:placeholder-shown)]:-top-2
                        peer-[:not(:placeholder-shown)]:text-xs
                        peer-[:not(:placeholder-shown)]:font-medium
                        peer-[:not(:placeholder-shown)]:text-[#5B5BF6]">

                        Full Name *

                    </label>

                </div>

                <!-- EMAIL -->
                <div class="relative">

                    <input type="email"
                        placeholder=" "
                        class="peer w-full rounded-2xl border border-[#E2E8F0] bg-white px-5 pt-7 pb-3 text-[15px] text-[#111827] outline-none transition duration-300 focus:border-[#5B5BF6] focus:ring-4 focus:ring-[#5B5BF6]/10">

                    <label
                        class="absolute left-5 top-5 bg-white px-1 text-[15px] text-[#94A3B8] transition-all duration-200
                        peer-focus:-top-2
                        peer-focus:text-xs
                        peer-focus:font-medium
                        peer-focus:text-[#5B5BF6]
                        peer-[:not(:placeholder-shown)]:-top-2
                        peer-[:not(:placeholder-shown)]:text-xs
                        peer-[:not(:placeholder-shown)]:font-medium
                        peer-[:not(:placeholder-shown)]:text-[#5B5BF6]">

                        Email Address *

                    </label>

                </div>

            </div>

            <!-- STEP 2 -->
            <div x-show="step === 2" class="space-y-5">

                <!-- PHONE -->
                <div class="relative">

                    <input type="tel"
                        placeholder=" "
                        class="peer w-full rounded-2xl border border-[#E2E8F0] bg-white px-5 pt-7 pb-3 text-[15px] text-[#111827] outline-none transition duration-300 focus:border-[#5B5BF6] focus:ring-4 focus:ring-[#5B5BF6]/10">

                    <label
                        class="absolute left-5 top-5 bg-white px-1 text-[15px] text-[#94A3B8] transition-all duration-200
                        peer-focus:-top-2
                        peer-focus:text-xs
                        peer-focus:font-medium
                        peer-focus:text-[#5B5BF6]
                        peer-[:not(:placeholder-shown)]:-top-2
                        peer-[:not(:placeholder-shown)]:text-xs
                        peer-[:not(:placeholder-shown)]:font-medium
                        peer-[:not(:placeholder-shown)]:text-[#5B5BF6]">

                        Phone Number *

                    </label>

                </div>

                <!-- LOCATION -->
                <div class="relative">

                    <input type="text"
                        placeholder=" "
                        class="peer w-full rounded-2xl border border-[#E2E8F0] bg-white px-5 pt-7 pb-3 text-[15px] text-[#111827] outline-none transition duration-300 focus:border-[#5B5BF6] focus:ring-4 focus:ring-[#5B5BF6]/10">

                    <label
                        class="absolute left-5 top-5 bg-white px-1 text-[15px] text-[#94A3B8] transition-all duration-200
                        peer-focus:-top-2
                        peer-focus:text-xs
                        peer-focus:font-medium
                        peer-focus:text-[#5B5BF6]
                        peer-[:not(:placeholder-shown)]:-top-2
                        peer-[:not(:placeholder-shown)]:text-xs
                        peer-[:not(:placeholder-shown)]:font-medium
                        peer-[:not(:placeholder-shown)]:text-[#5B5BF6]">

                        Location *

                    </label>

                </div>

            </div>

            <!-- STEP 3 -->
            <div x-show="step === 3" class="space-y-5">

                <!-- COLLEGE -->
                <div class="relative">

                    <input type="text"
                        placeholder=" "
                        class="peer w-full rounded-2xl border border-[#E2E8F0] bg-white px-5 pt-7 pb-3 text-[15px] text-[#111827] outline-none transition duration-300 focus:border-[#5B5BF6] focus:ring-4 focus:ring-[#5B5BF6]/10">

                    <label
                        class="absolute left-5 top-5 bg-white px-1 text-[15px] text-[#94A3B8] transition-all duration-200
                        peer-focus:-top-2
                        peer-focus:text-xs
                        peer-focus:font-medium
                        peer-focus:text-[#5B5BF6]
                        peer-[:not(:placeholder-shown)]:-top-2
                        peer-[:not(:placeholder-shown)]:text-xs
                        peer-[:not(:placeholder-shown)]:font-medium
                        peer-[:not(:placeholder-shown)]:text-[#5B5BF6]">

                        College Name *

                    </label>

                </div>

            </div>

            <!-- STEP 4 -->
            <div x-show="step === 4" class="space-y-5">

                <div>

                    <label class="text-sm font-medium text-[#111827]">
                        Internship Level
                    </label>

                    <div class="mt-4 space-y-3">

                        <template x-for="item in ['Beginner','Intermediate','Advanced']">

                            <label
                                class="flex cursor-pointer items-center justify-between rounded-2xl border px-5 py-4 transition duration-300"
                                :class="level === item
                                    ? 'border-[#5B5BF6] bg-[#F8F8FF]'
                                    : 'border-[#E2E8F0] bg-white hover:border-[#CBD5E1]'">

                                <div>

                                    <p class="text-sm font-semibold text-[#111827]"
                                        x-text="item"></p>

                                    <p class="mt-1 text-xs text-[#64748B]">
                                        Choose any 3 projects
                                    </p>

                                </div>

                                <div
                                    class="flex h-5 w-5 items-center justify-center rounded-full border-2"
                                    :class="level === item
                                        ? 'border-[#5B5BF6]'
                                        : 'border-[#CBD5E1]'">

                                    <div x-show="level === item"
                                        class="h-2.5 w-2.5 rounded-full bg-[#5B5BF6]">
                                    </div>

                                </div>

                                <input type="radio"
                                    x-model="level"
                                    :value="item"
                                    class="hidden">

                            </label>

                        </template>

                    </div>

                </div>

            </div>

            <!-- BUTTONS -->
            <div class="mt-8 flex items-center justify-between">

                <!-- BACK -->
                <button type="button"
                    x-show="step > 1"
                    @click="step--"
                    class="rounded-xl border border-[#E2E8F0] px-5 py-3 text-sm font-semibold text-[#111827] transition hover:border-[#5B5BF6] hover:text-[#5B5BF6]">

                    Back

                </button>

                <div x-show="step === 1"></div>

                <!-- NEXT -->
                <button type="button"
                    x-show="step < 4"
                    @click="step++"
                    class="ml-auto rounded-xl bg-[#5B5BF6] px-7 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-[#4F46E5]">

                    Continue

                </button>

                <!-- SUBMIT -->
                <button type="submit"
                    x-show="step === 4"
                    class="ml-auto rounded-xl bg-[#5B5BF6] px-7 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-[#4F46E5]">

                    Reserve Your Seat

                </button>

            </div>

            <!-- FOOTER -->
            <div class="mt-8 border-t border-[#F1F5F9] pt-6">

                <p class="text-sm text-green-600 text-center">
                    Your information is secure and will never be shared.
                </p>

            </div>

        </div>

    </div>

</div>