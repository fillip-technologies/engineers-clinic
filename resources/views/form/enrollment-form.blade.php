@php
    $courseModel     = $courseModel ?? null;
    $selectedLevel   = old('level', $selectedLevel ?? 'Beginner');
    $courseStream    = $courseModel?->category ?? null;

    $authUser        = auth()->user();
    $authStudent     = $authUser?->student;
    $authCollege     = $authStudent?->college;

    $curriculumProjects = [];
    if ($courseModel?->curriculum) {
        $curriculumProjects = collect($courseModel->curriculum)
            ->values()
            ->map(fn($item, $idx) => [
                'id'          => $item['project_no'] ?? ($idx + 1),
                'title'       => $item['title'] ?? 'Project ' . ($idx + 1),
                'description' => $item['description'] ?? '',
                'hours'       => $item['estimated_hours'] ?? null,
            ])
            ->all();
    }
@endphp

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
            level: @js($selectedLevel ?? 'Beginner'),
            selectedProjectIds: [],
            projectDropdownOpen: false,
            curriculumProjects: @js($curriculumProjects),
            toggleProject(id) {
                const idx = this.selectedProjectIds.indexOf(id);
                if (idx >= 0) { this.selectedProjectIds.splice(idx, 1); }
                else if (this.selectedProjectIds.length < 3) { this.selectedProjectIds.push(id); }
            },
            isProjectSelected(id) { return this.selectedProjectIds.includes(id); },
            projectTitle(id) {
                const p = this.curriculumProjects.find(p => p.id === id);
                return p ? p.title : ('Project ' + id);
            }
        }">

        <!-- TOP -->
        <div class="border-b border-[#F1F5F9] px-8 py-7">

            <!-- STEPPER -->
            <div class="flex items-center gap-4">

                <template x-for="item in [1,2,3,4,5,6]" :key="item">

                    <div class="flex items-center flex-1">

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full text-xs font-semibold transition"
                            :class="step >= item
                                ? 'bg-[#5B5BF6] text-white'
                                : 'bg-[#F1F5F9] text-[#64748B]'">

                            <span x-text="item"></span>

                        </div>

                        <div x-show="item != 6"
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
        <form method="POST" action="{{ $courseModel ? route('payments.checkout.start', ['course' => $courseModel->slug]) : '#' }}" class="p-8">
            @csrf
            @if($courseModel)
                <input type="hidden" name="course_id" value="{{ $courseModel->id }}">
            @endif
            <input type="hidden" name="level" value="{{ $selectedLevel }}" x-model="level">

            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <!-- STEP 1 -->
            <div x-show="step === 1" class="space-y-5">

                <!-- FULL NAME -->
                <div class="relative">

                    <input type="text"
                        name="name"
                        value="{{ old('name', auth()->user()?->name) }}"
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
                        name="email"
                        value="{{ old('email', auth()->user()?->email) }}"
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
                        name="phone"
                        value="{{ old('phone', $authUser?->phone) }}"
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
                        name="location"
                        value="{{ old('location', $authCollege?->address) }}"
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
                        name="college"
                        value="{{ old('college', $authCollege?->college_name) }}"
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

            <!-- STEP 5 — Auto-selected Course -->
            <div x-show="step === 5" class="space-y-5">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#5B5BF6]">Your Selected Track</p>
                    <p class="mt-1 text-sm text-[#64748B]">This course has been pre-selected based on the page you're enrolling from.</p>
                </div>

                @if($courseModel)
                <div class="rounded-2xl border border-[#5B5BF6]/30 bg-[#F8F8FF] px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#5B5BF6]/10">
                            <i class="fi fi-rr-book-alt text-xl text-[#5B5BF6] leading-none"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-base font-bold text-[#111827]">{{ $courseModel->title }}</p>
                            @if($courseStream)
                            <p class="mt-0.5 text-xs text-[#64748B]">Topic: <span class="font-semibold">{{ $courseStream }}</span></p>
                            @endif
                            <p class="mt-1 text-xs text-[#64748B]">Level: <span class="font-semibold" x-text="level"></span></p>
                            <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                <i class="fi fi-rr-check text-[10px] leading-none"></i>
                                Auto-selected
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center text-xs text-[#64748B]">Click Continue to choose your projects from this course's curriculum.</p>
                @else
                <div class="rounded-2xl border border-dashed border-[#E2E8F0] bg-[#F8FAFC] py-8 text-center">
                    <p class="text-sm font-semibold text-[#64748B]">No course selected.</p>
                </div>
                @endif

                <!-- Auto-pass course + stream to backend -->
                @if($courseModel)
                    <input type="hidden" name="selected_courses[]" value="{{ $courseModel->id }}">
                    <input type="hidden" name="stream" value="{{ $courseStream }}">
                @endif

            </div>

            <!-- STEP 6 — Select Projects from Curriculum -->
            <div x-show="step === 6" class="space-y-4">

                <div>
                    <p class="text-sm font-semibold text-[#111827]">Choose Your Projects</p>
                    <p class="mt-0.5 text-xs text-[#64748B]">Pick up to 3 projects from the curriculum.</p>
                </div>

                <template x-if="curriculumProjects.length === 0">
                    <div class="rounded-2xl border border-dashed border-[#E2E8F0] bg-[#F8FAFC] py-8 text-center">
                        <p class="text-sm font-semibold text-[#64748B]">No curriculum projects found for this course.</p>
                    </div>
                </template>

                <!-- Dropdown trigger -->
                <div x-show="curriculumProjects.length > 0" class="relative">
                    <button type="button"
                        @click="projectDropdownOpen = !projectDropdownOpen"
                        @keydown.escape.window="projectDropdownOpen = false"
                        class="w-full flex items-center justify-between rounded-2xl border px-5 py-4 text-sm transition duration-300 bg-white outline-none"
                        :class="projectDropdownOpen ? 'border-[#5B5BF6] ring-4 ring-[#5B5BF6]/10' : 'border-[#E2E8F0] hover:border-[#CBD5E1]'">
                        <span :class="selectedProjectIds.length === 0 ? 'text-[#94A3B8]' : 'font-semibold text-[#111827]'">
                            <span x-show="selectedProjectIds.length === 0">Select projects (up to 3)</span>
                            <span x-show="selectedProjectIds.length > 0"
                                x-text="selectedProjectIds.length + ' project' + (selectedProjectIds.length !== 1 ? 's' : '') + ' selected'"></span>
                        </span>
                        <div class="flex items-center gap-2">
                            <span class="rounded-lg bg-[#F1F5F9] px-2 py-0.5 text-xs font-bold text-[#5B5BF6]"
                                x-text="selectedProjectIds.length + ' / 3'"></span>
                            <svg class="h-4 w-4 text-[#94A3B8] transition-transform duration-200"
                                :class="projectDropdownOpen ? 'rotate-180' : ''"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </button>

                    <!-- Dropdown panel -->
                    <div x-cloak x-show="projectDropdownOpen"
                        @click.outside="projectDropdownOpen = false"
                        class="absolute left-0 right-0 z-50 mt-2 overflow-hidden rounded-2xl border border-[#E2E8F0] bg-white shadow-xl">
                        <div class="max-h-56 overflow-y-auto">
                        <template x-for="project in curriculumProjects" :key="project.id">
                            <div
                                @click="toggleProject(project.id)"
                                class="flex items-start gap-3 border-b border-[#F1F5F9] px-4 py-3.5 last:border-0 transition"
                                :class="isProjectSelected(project.id)
                                    ? 'bg-[#F8F8FF] cursor-pointer'
                                    : (selectedProjectIds.length >= 3
                                        ? 'cursor-not-allowed opacity-40'
                                        : 'cursor-pointer hover:bg-[#F8FAFC]')">
                                <!-- Checkbox -->
                                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-md border-2 transition"
                                    :class="isProjectSelected(project.id) ? 'border-[#5B5BF6] bg-[#5B5BF6]' : 'border-[#CBD5E1] bg-white'">
                                    <svg x-show="isProjectSelected(project.id)" class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <!-- Details -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-[#111827]">
                                        <span class="text-[#5B5BF6]" x-text="project.id + '. '"></span><span x-text="project.title"></span>
                                    </p>
                                    <p x-show="project.description"
                                        class="mt-0.5 text-xs text-[#64748B] line-clamp-1"
                                        x-text="project.description"></p>
                                    <p x-show="project.hours"
                                        class="mt-0.5 text-[11px] text-[#94A3B8]">
                                        <span x-text="project.hours"></span> hrs
                                    </p>
                                </div>
                            </div>
                        </template>
                        </div>
                    </div>
                </div>

                <!-- Selected chips -->
                <div x-show="selectedProjectIds.length > 0" class="flex flex-wrap gap-2">
                    <template x-for="id in selectedProjectIds" :key="id">
                        <div class="flex items-center gap-1.5 rounded-xl border border-[#5B5BF6]/30 bg-[#F8F8FF] px-3 py-1.5 text-xs font-semibold text-[#5B5BF6]">
                            <span x-text="projectTitle(id)" class="max-w-[160px] truncate"></span>
                            <button type="button" @click="toggleProject(id)"
                                class="ml-1 flex h-4 w-4 shrink-0 items-center justify-center rounded-full text-[#5B5BF6] hover:bg-red-100 hover:text-red-500 transition">
                                <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>

                <template x-if="selectedProjectIds.length >= 3">
                    <div class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-semibold text-emerald-700">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        3 projects selected — you're ready to proceed!
                    </div>
                </template>

                <!-- Hidden inputs for selected project nos -->
                <template x-for="id in selectedProjectIds" :key="id">
                    <input type="hidden" name="selected_project_nos[]" :value="id">
                </template>

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
                    x-show="step < 6"
                    @click="step++"
                    class="ml-auto rounded-xl bg-[#5B5BF6] px-7 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-[#4F46E5]">

                    Continue

                </button>

                <!-- SUBMIT -->
                <button type="submit"
                    x-show="step === 6"
                    :disabled="(curriculumProjects.length > 0 && selectedProjectIds.length === 0) || @js(!$courseModel)"
                    class="ml-auto rounded-xl bg-[#5B5BF6] px-7 py-3 text-sm font-semibold text-white transition duration-300 hover:bg-[#4F46E5] disabled:opacity-50 disabled:cursor-not-allowed">

                    {{ $courseModel ? 'Reserve Your Seat' : 'Course Unavailable' }}

                </button>

            </div>

            <!-- FOOTER -->
            <div class="mt-8 border-t border-[#F1F5F9] pt-6">

                <p class="text-sm text-green-600 text-center">
                    Your information is secure and will never be shared.
                </p>

            </div>

        </form>

    </div>

</div>
