@php
$partnershipErrors = $errors->partnershipDiscussion;
@endphp

<div
    x-data="{
        open: @js($partnershipErrors->any()),
        values: {
            full_name: @js(old('full_name', '')),
            institution_name: @js(old('institution_name', '')),
            official_email: @js(old('official_email', '')),
            phone: @js(old('phone', '')),
            designation: @js(old('designation', '')),
            number_of_students: @js(old('number_of_students', '')),
            department_stream: @js(old('department_stream', '')),
            message: @js(old('message', '')),
        },
        clientErrors: {},
        fieldError(field) {
            return this.clientErrors[field] || '';
        },
        clearField(field) {
            delete this.clientErrors[field];
        },
        validate() {
            const errors = {};
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phonePattern = /^[0-9+\-\s()]{7,20}$/;

            if (!this.values.full_name.trim()) errors.full_name = 'Full name is required.';
            if (!this.values.institution_name.trim()) errors.institution_name = 'College / institution name is required.';
            if (!this.values.official_email.trim()) {
                errors.official_email = 'Official email is required.';
            } else if (!emailPattern.test(this.values.official_email.trim())) {
                errors.official_email = 'Enter a valid official email address.';
            }

            if (!this.values.phone.trim()) {
                errors.phone = 'Phone number is required.';
            } else if (!phonePattern.test(this.values.phone.trim())) {
                errors.phone = 'Enter a valid phone number.';
            }

            if (!this.values.designation.trim()) errors.designation = 'Designation is required.';

            if (!this.values.number_of_students) {
                errors.number_of_students = 'Number of students is required.';
            } else if (Number(this.values.number_of_students) < 1) {
                errors.number_of_students = 'Enter at least 1 student.';
            }

            if (!this.values.department_stream.trim()) errors.department_stream = 'Department / stream is required.';
            if (!this.values.message.trim()) errors.message = 'Message is required.';

            this.clientErrors = errors;

            return Object.keys(errors).length === 0;
        },

        submit(event) {
            if (!this.validate()) {
                event.preventDefault();
            }
        },

        close() {
            this.open = false;
            this.clientErrors = {};
        }
    }"
    @open-partnership-discussion.window="open = true"
    @keydown.escape.window="close()">

    <style>
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c4b5fd;
            border-radius: 999px;
        }
    </style>

    @if (session('partnership_discussion_success'))
    <div class="fixed inset-x-4 top-4 z-[110] mx-auto max-w-xl rounded-2xl border border-[#D9F99D] bg-white px-5 py-4 text-sm font-medium text-[#166534] shadow-[0_18px_50px_rgba(22,8,64,0.16)]">
        {{ session('partnership_discussion_success') }}
    </div>
    @endif

    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-[#0B021F]/70 p-4 backdrop-blur-md"
        role="dialog"
        aria-modal="true"
        aria-labelledby="partnership-discussion-title">

        <div class="absolute inset-0" @click="close()"></div>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-5 scale-[0.97] opacity-0"
            x-transition:enter-end="translate-y-0 scale-100 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0 scale-100 opacity-100"
            x-transition:leave-end="translate-y-5 scale-[0.97] opacity-0"
            class="relative max-h-[92vh] w-full max-w-7xl overflow-y-auto rounded-[36px] border border-white/20 bg-white shadow-[0_40px_120px_rgba(15,23,42,0.28)]">

            <button
                type="button"
                @click="close()"
                class="absolute right-5 top-5 z-20 flex h-12 w-12 items-center justify-center rounded-full border border-white/20 bg-white/90 text-[#7C5CFC] shadow-lg backdrop-blur-md transition-all duration-300 hover:rotate-90 hover:scale-105 hover:bg-[#7C5CFC] hover:text-white"
                aria-label="Close partnership discussion form">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>

            <div class="grid lg:grid-cols-[1.08fr_1fr]">

                <!-- LEFT SIDE -->
                <div class="relative hidden min-h-[760px] overflow-hidden bg-[#0F082A] lg:block">

                    <img
                        src="/images/college-image.png"
                        alt="Engineers Clinic college partnership discussion"
                        class="absolute inset-0 h-full w-full object-cover">

                    <div class="absolute inset-0 bg-gradient-to-br from-[#160840]/80 via-[#160840]/45 to-[#7C5CFC]/20"></div>

                    <div class="absolute inset-x-0 bottom-0 z-10 p-10 text-white">

                        <div class="mb-6 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-medium backdrop-blur-md">
                            Trusted by Colleges Across India
                        </div>

                        <h3 class="max-w-md text-4xl font-bold leading-tight">
                            Build Industry-Ready Students With Engineers Clinic
                        </h3>

                        <p class="mt-5 max-w-lg text-sm leading-7 text-white/80">
                            Partner with us to deliver internships, live projects, certifications, and placement-focused learning experiences for your students.
                        </p>

                        <div class="mt-10 flex gap-12">

                            <div>
                                <p class="text-3xl font-bold">50+</p>
                                <p class="mt-1 text-sm text-white/70">
                                    Institution Partners
                                </p>
                            </div>

                            <div>
                                <p class="text-3xl font-bold">10K+</p>
                                <p class="mt-1 text-sm text-white/70">
                                    Students Trained
                                </p>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="relative bg-white p-8 sm:p-10 lg:px-14 lg:py-12 overflow-y-auto">

                    <!-- <p class="inline-flex items-center rounded-full bg-[#F3F0FF] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-[#7C5CFC]">
                        Partnership Desk
                    </p> -->

                    <h2
                        id="partnership-discussion-title"
                        class=" max-w-lg text-4xl font-bold leading-[1.1] tracking-[-0.03em] text-[#160840]">
                        Partner With Engineers Clinic
                    </h2>

                    <p class="mt-5 max-w-xl text-[15px] leading-7 text-[#5B4B8A]">
                        Tell us about your institution and our partnership team will connect with you for the next steps.
                    </p>

                    <div class="mt-6 h-px w-full bg-gradient-to-r from-[#E9E2FF] to-transparent"></div>

                    <form
                        method="POST"
                        action="{{ route('college.partnership-discussion.store') }}"
                        class="mt-8 space-y-6"
                        novalidate
                        @submit="submit($event)">
                        @csrf

                        <div class="grid gap-6 sm:grid-cols-2">

                            <!-- Full Name -->
                            <div>
                                <label for="partnership_full_name" class="text-[13px] font-semibold uppercase tracking-wide text-[#4B3D75]">
                                    Full Name *
                                </label>

                                <input
                                    id="partnership_full_name"
                                    name="full_name"
                                    type="text"
                                    x-model="values.full_name"
                                    @input="clearField('full_name')"
                                    class="mt-2 h-14 w-full rounded-2xl border border-[#E8E2FF] bg-[#FCFBFF] px-5 text-sm font-medium text-[#160840] shadow-sm outline-none transition-all duration-300 placeholder:text-[#9B8CC9] focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/10">

                                <p x-show="fieldError('full_name')" x-text="fieldError('full_name')" class="mt-2 text-xs font-medium text-red-600"></p>
                                @error('full_name', 'partnershipDiscussion')
                                <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Institution -->
                            <div>
                                <label for="partnership_institution_name" class="text-[13px] font-semibold uppercase tracking-wide text-[#4B3D75]">
                                    College / Institution *
                                </label>

                                <input
                                    id="partnership_institution_name"
                                    name="institution_name"
                                    type="text"
                                    x-model="values.institution_name"
                                    @input="clearField('institution_name')"
                                    class="mt-2 h-14 w-full rounded-2xl border border-[#E8E2FF] bg-[#FCFBFF] px-5 text-sm font-medium text-[#160840] shadow-sm outline-none transition-all duration-300 placeholder:text-[#9B8CC9] focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/10">

                                <p x-show="fieldError('institution_name')" x-text="fieldError('institution_name')" class="mt-2 text-xs font-medium text-red-600"></p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="partnership_official_email" class="text-[13px] font-semibold uppercase tracking-wide text-[#4B3D75]">
                                    Official Email *
                                </label>

                                <input
                                    id="partnership_official_email"
                                    name="official_email"
                                    type="email"
                                    x-model="values.official_email"
                                    @input="clearField('official_email')"
                                    class="mt-2 h-14 w-full rounded-2xl border border-[#E8E2FF] bg-[#FCFBFF] px-5 text-sm font-medium text-[#160840] shadow-sm outline-none transition-all duration-300 placeholder:text-[#9B8CC9] focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/10">

                                <p x-show="fieldError('official_email')" x-text="fieldError('official_email')" class="mt-2 text-xs font-medium text-red-600"></p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="partnership_phone" class="text-[13px] font-semibold uppercase tracking-wide text-[#4B3D75]">
                                    Phone Number *
                                </label>

                                <input
                                    id="partnership_phone"
                                    name="phone"
                                    type="tel"
                                    x-model="values.phone"
                                    @input="clearField('phone')"
                                    class="mt-2 h-14 w-full rounded-2xl border border-[#E8E2FF] bg-[#FCFBFF] px-5 text-sm font-medium text-[#160840] shadow-sm outline-none transition-all duration-300 placeholder:text-[#9B8CC9] focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/10">

                                <p x-show="fieldError('phone')" x-text="fieldError('phone')" class="mt-2 text-xs font-medium text-red-600"></p>
                            </div>

                            <!-- Designation -->
                            <div>
                                <label for="partnership_designation" class="text-[13px] font-semibold uppercase tracking-wide text-[#4B3D75]">
                                    Designation *
                                </label>

                                <input
                                    id="partnership_designation"
                                    name="designation"
                                    type="text"
                                    x-model="values.designation"
                                    @input="clearField('designation')"
                                    class="mt-2 h-14 w-full rounded-2xl border border-[#E8E2FF] bg-[#FCFBFF] px-5 text-sm font-medium text-[#160840] shadow-sm outline-none transition-all duration-300 placeholder:text-[#9B8CC9] focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/10">
                            </div>

                            <!-- Students -->
                            <div>
                                <label for="partnership_number_of_students" class="text-[13px] font-semibold uppercase tracking-wide text-[#4B3D75]">
                                    Number of Students *
                                </label>

                                <input
                                    id="partnership_number_of_students"
                                    name="number_of_students"
                                    type="number"
                                    min="1"
                                    x-model="values.number_of_students"
                                    @input="clearField('number_of_students')"
                                    class="mt-2 h-14 w-full rounded-2xl border border-[#E8E2FF] bg-[#FCFBFF] px-5 text-sm font-medium text-[#160840] shadow-sm outline-none transition-all duration-300 placeholder:text-[#9B8CC9] focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/10">
                            </div>

                        </div>

                        <!-- Department -->
                        <div>
                            <label for="partnership_department_stream" class="text-[13px] font-semibold uppercase tracking-wide text-[#4B3D75]">
                                Department / Stream *
                            </label>

                            <input
                                id="partnership_department_stream"
                                name="department_stream"
                                type="text"
                                x-model="values.department_stream"
                                @input="clearField('department_stream')"
                                class="mt-2 h-14 w-full rounded-2xl border border-[#E8E2FF] bg-[#FCFBFF] px-5 text-sm font-medium text-[#160840] shadow-sm outline-none transition-all duration-300 placeholder:text-[#9B8CC9] focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/10">
                        </div>

                        <!-- Message -->
                        <div>
                            <label for="partnership_message" class="text-[13px] font-semibold uppercase tracking-wide text-[#4B3D75]">
                                Message *
                            </label>

                            <textarea
                                id="partnership_message"
                                name="message"
                                rows="5"
                                x-model="values.message"
                                @input="clearField('message')"
                                class="mt-2 w-full resize-none rounded-2xl border border-[#E8E2FF] bg-[#FCFBFF] px-5 py-4 text-sm font-medium text-[#160840] shadow-sm outline-none transition-all duration-300 placeholder:text-[#9B8CC9] focus:border-[#7C5CFC] focus:bg-white focus:ring-4 focus:ring-[#7C5CFC]/10"></textarea>
                        </div>

                        <!-- BUTTON -->
                        <button
                            type="submit"
                            class="group relative mt-4 flex h-14 w-full items-center justify-center overflow-hidden rounded-2xl bg-gradient-to-r from-[#7C5CFC] to-[#6D4AFF] px-6 text-sm font-semibold text-white shadow-[0_20px_50px_rgba(124,92,252,0.32)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_30px_60px_rgba(124,92,252,0.45)]">
                            <span class="relative z-10">
                                Request Partnership Discussion
                            </span>
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>