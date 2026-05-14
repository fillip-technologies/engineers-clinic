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
    @keydown.escape.window="close()"
>
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
        class="fixed inset-0 z-[100] flex items-center justify-center bg-[#160840]/60 p-4 backdrop-blur-md"
        role="dialog"
        aria-modal="true"
        aria-labelledby="partnership-discussion-title"
    >
        <div class="absolute inset-0" @click="close()"></div>

        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-y-5 scale-[0.97] opacity-0"
            x-transition:enter-end="translate-y-0 scale-100 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0 scale-100 opacity-100"
            x-transition:leave-end="translate-y-5 scale-[0.97] opacity-0"
            class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto rounded-[2rem] bg-white shadow-[0_30px_90px_rgba(22,8,64,0.26)]"
        >
            <button
                type="button"
                @click="close()"
                class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-full border border-[#E2D9FF] bg-white text-[#8B7FBF] shadow-sm transition hover:border-[#7C5CFC] hover:text-[#7C5CFC]"
                aria-label="Close partnership discussion form"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                </svg>
            </button>

            <div class="grid lg:grid-cols-[1.08fr_1fr]">
                <div class="relative hidden min-h-[620px] overflow-hidden bg-[#160840] lg:block">
                    <img
                        src="/images/college-image.png"
                        alt="Engineers Clinic college partnership discussion"
                        class="absolute inset-0 h-full w-full object-cover"
                    >
                    <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(22,8,64,0.10),rgba(22,8,64,0.22))]"></div>
                </div>

                <div class="p-6 sm:p-8 lg:p-10">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#7C5CFC]">Partnership desk</p>
                    <h2 id="partnership-discussion-title" class="mt-3 pr-12 text-2xl font-semibold tracking-tight text-[#160840] sm:text-3xl">
                        Request Partnership Discussion
                    </h2>
                    <p class="mt-3 text-sm leading-6 text-[#3D2090]">
                        Tell us about your institution and the partnership team will contact you with the next steps.
                    </p>

                    <form method="POST" action="{{ route('college.partnership-discussion.store') }}" class="mt-8 space-y-5" novalidate @submit="submit($event)">
                        @csrf

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="partnership_full_name" class="text-sm font-semibold text-[#160840]">Full Name *</label>
                                <input id="partnership_full_name" name="full_name" type="text" x-model="values.full_name" @input="clearField('full_name')" class="mt-2 w-full rounded-2xl border border-[#E2D9FF] bg-white px-4 py-3 text-sm text-[#160840] outline-none transition focus:border-[#7C5CFC] focus:ring-4 focus:ring-[#7C5CFC]/10">
                                <p x-show="fieldError('full_name')" x-text="fieldError('full_name')" class="mt-2 text-xs font-medium text-red-600"></p>
                                @error('full_name', 'partnershipDiscussion')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="partnership_institution_name" class="text-sm font-semibold text-[#160840]">College / Institution Name *</label>
                                <input id="partnership_institution_name" name="institution_name" type="text" x-model="values.institution_name" @input="clearField('institution_name')" class="mt-2 w-full rounded-2xl border border-[#E2D9FF] bg-white px-4 py-3 text-sm text-[#160840] outline-none transition focus:border-[#7C5CFC] focus:ring-4 focus:ring-[#7C5CFC]/10">
                                <p x-show="fieldError('institution_name')" x-text="fieldError('institution_name')" class="mt-2 text-xs font-medium text-red-600"></p>
                                @error('institution_name', 'partnershipDiscussion')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="partnership_official_email" class="text-sm font-semibold text-[#160840]">Official Email *</label>
                                <input id="partnership_official_email" name="official_email" type="email" x-model="values.official_email" @input="clearField('official_email')" class="mt-2 w-full rounded-2xl border border-[#E2D9FF] bg-white px-4 py-3 text-sm text-[#160840] outline-none transition focus:border-[#7C5CFC] focus:ring-4 focus:ring-[#7C5CFC]/10">
                                <p x-show="fieldError('official_email')" x-text="fieldError('official_email')" class="mt-2 text-xs font-medium text-red-600"></p>
                                @error('official_email', 'partnershipDiscussion')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="partnership_phone" class="text-sm font-semibold text-[#160840]">Phone Number *</label>
                                <input id="partnership_phone" name="phone" type="tel" x-model="values.phone" @input="clearField('phone')" class="mt-2 w-full rounded-2xl border border-[#E2D9FF] bg-white px-4 py-3 text-sm text-[#160840] outline-none transition focus:border-[#7C5CFC] focus:ring-4 focus:ring-[#7C5CFC]/10">
                                <p x-show="fieldError('phone')" x-text="fieldError('phone')" class="mt-2 text-xs font-medium text-red-600"></p>
                                @error('phone', 'partnershipDiscussion')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="partnership_designation" class="text-sm font-semibold text-[#160840]">Designation *</label>
                                <input id="partnership_designation" name="designation" type="text" x-model="values.designation" @input="clearField('designation')" class="mt-2 w-full rounded-2xl border border-[#E2D9FF] bg-white px-4 py-3 text-sm text-[#160840] outline-none transition focus:border-[#7C5CFC] focus:ring-4 focus:ring-[#7C5CFC]/10">
                                <p x-show="fieldError('designation')" x-text="fieldError('designation')" class="mt-2 text-xs font-medium text-red-600"></p>
                                @error('designation', 'partnershipDiscussion')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="partnership_number_of_students" class="text-sm font-semibold text-[#160840]">Number of Students *</label>
                                <input id="partnership_number_of_students" name="number_of_students" type="number" min="1" x-model="values.number_of_students" @input="clearField('number_of_students')" class="mt-2 w-full rounded-2xl border border-[#E2D9FF] bg-white px-4 py-3 text-sm text-[#160840] outline-none transition focus:border-[#7C5CFC] focus:ring-4 focus:ring-[#7C5CFC]/10">
                                <p x-show="fieldError('number_of_students')" x-text="fieldError('number_of_students')" class="mt-2 text-xs font-medium text-red-600"></p>
                                @error('number_of_students', 'partnershipDiscussion')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label for="partnership_department_stream" class="text-sm font-semibold text-[#160840]">Department / Stream *</label>
                            <input id="partnership_department_stream" name="department_stream" type="text" x-model="values.department_stream" @input="clearField('department_stream')" class="mt-2 w-full rounded-2xl border border-[#E2D9FF] bg-white px-4 py-3 text-sm text-[#160840] outline-none transition focus:border-[#7C5CFC] focus:ring-4 focus:ring-[#7C5CFC]/10">
                            <p x-show="fieldError('department_stream')" x-text="fieldError('department_stream')" class="mt-2 text-xs font-medium text-red-600"></p>
                            @error('department_stream', 'partnershipDiscussion')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="partnership_message" class="text-sm font-semibold text-[#160840]">Message *</label>
                            <textarea id="partnership_message" name="message" rows="4" x-model="values.message" @input="clearField('message')" class="mt-2 w-full resize-none rounded-2xl border border-[#E2D9FF] bg-white px-4 py-3 text-sm text-[#160840] outline-none transition focus:border-[#7C5CFC] focus:ring-4 focus:ring-[#7C5CFC]/10"></textarea>
                            <p x-show="fieldError('message')" x-text="fieldError('message')" class="mt-2 text-xs font-medium text-red-600"></p>
                            @error('message', 'partnershipDiscussion')<p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-[#7C5CFC] px-6 py-3.5 text-sm font-semibold text-white shadow-[0_16px_34px_rgba(124,92,252,0.24)] transition hover:bg-[#160840]">
                            Submit Partnership Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
