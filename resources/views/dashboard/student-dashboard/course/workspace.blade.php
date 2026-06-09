<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $workspace['title'] }} Workspace - {{ config('app.name', 'Engineers Clinic') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Inter, sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        [id] {
            scroll-margin-top: 6rem;
        }
    </style>
</head>

<body class="min-h-screen bg-[#F7F8FB] text-slate-950">
    <div class="flex min-h-screen"
        x-data="{
            mobileSidebarOpen: false,
            currentSection: 'overview',
            sections: @js(collect($sidebarItems)->pluck('target')->values()),
            totalSteps: {{ count($steps) }},
            completedSteps: @js(collect($steps)->where('state', 'completed')->pluck('number')->values()),
            progressMessage: '',
            stepErrors: {},
            submissionUnlocked: @js($workspace['submission_unlocked']),
            submission: {
                name: @js($workspace['student_name']),
                email: @js($workspace['student_email']),
                githubUrl: '',
                learningNote: '',
                stream: '',
                loading: false,
                submitted: @js($workspace['submission_submitted']),
                error: '',
                fieldErrors: {}
            },
            get progress() {
                return this.totalSteps ? Math.round((this.completedSteps.length / this.totalSteps) * 100) : 0;
            },
            get allStepsComplete() {
                return this.totalSteps > 0 && this.completedSteps.length >= this.totalSteps;
            },
            isComplete(number) {
                return this.completedSteps.includes(number);
            },
            isUnlocked(number) {
                return number === 1 || this.completedSteps.includes(number - 1) || this.completedSteps.includes(number);
            },
            stepState(number) {
                if (this.isComplete(number)) return 'completed';
                if (this.isUnlocked(number)) return 'active';
                return 'locked';
            },
            async markStepComplete(number, url) {
                if (!this.isUnlocked(number) || this.isComplete(number)) return;

                this.stepErrors[number] = '';
                this.progressMessage = 'Saving your progress...';

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({})
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(payload.message || 'Progress could not be saved.');
                    }

                    this.completedSteps = payload.completed_steps || [...this.completedSteps, number];
                    this.submissionUnlocked = payload.all_complete || this.allStepsComplete;
                    this.progressMessage = this.submissionUnlocked
                        ? 'All tasks are complete. Final submission is now unlocked.'
                        : `Nice work - Step ${number} completed. The next step is unlocked.`;
                } catch (error) {
                    this.stepErrors[number] = error.message || 'Progress could not be saved. Please try again.';
                    this.progressMessage = '';
                }

                setTimeout(() => {
                    this.progressMessage = '';
                    this.stepErrors[number] = '';
                }, 5000);
            },
            copyCode(code) {
                navigator.clipboard?.writeText(code);
            },
            validateSubmission() {
                this.submission.fieldErrors = {};
                this.submission.error = '';

                const githubPattern = /^https:\/\/(www\.)?github\.com\/[A-Za-z0-9_.-]+\/[A-Za-z0-9_.-]+\/?$/;
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const allowedStreams = ['IT', 'MBA', 'Law', 'Eng', 'UIUX'];

                if (!this.submission.name.trim()) {
                    this.submission.fieldErrors.name = 'Please enter your name.';
                }

                if (!this.submission.email.trim()) {
                    this.submission.fieldErrors.email = 'Please enter your email.';
                } else if (!emailPattern.test(this.submission.email.trim())) {
                    this.submission.fieldErrors.email = 'Please enter a valid email address.';
                }

                if (!this.submission.githubUrl.trim()) {
                    this.submission.fieldErrors.githubUrl = 'Please paste your GitHub project link.';
                } else if (!githubPattern.test(this.submission.githubUrl.trim())) {
                    this.submission.fieldErrors.githubUrl = 'Use a valid GitHub repository URL, like https://github.com/name/project.';
                }

                if (!this.submission.stream) {
                    this.submission.fieldErrors.stream = 'Please select your stream.';
                } else if (!allowedStreams.includes(this.submission.stream)) {
                    this.submission.fieldErrors.stream = 'Please select a valid stream.';
                }

                return Object.keys(this.submission.fieldErrors).length === 0;
            },
            async submitProject() {
                if (!this.submissionUnlocked) {
                    this.submission.error = 'Complete every task before final submission.';
                    return;
                }

                if (!this.validateSubmission()) return;

                this.submission.loading = true;
                this.submission.error = '';

                try {
                    const response = await fetch(@js($workspace['submission_url']), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({
                            name: this.submission.name.trim(),
                            email: this.submission.email.trim(),
                            github_url: this.submission.githubUrl.trim(),
                            stream: this.submission.stream,
                            learning_note: this.submission.learningNote.trim()
                        })
                    });

                    if (!response.ok) {
                        const payload = await response.json().catch(() => ({}));
                        throw new Error(payload.message || 'Submission failed');
                    }

                    this.submission.submitted = true;
                } catch (error) {
                    this.submission.error = 'Something went wrong. Please try again.';
                } finally {
                    this.submission.loading = false;
                }
            },
            updateCurrentSection() {
                let active = this.currentSection;

                for (const section of this.sections) {
                    const element = document.getElementById(section);

                    if (element && element.getBoundingClientRect().top <= 140) {
                        active = section;
                    }
                }

                this.currentSection = active;
            }
        }"
        x-init="updateCurrentSection(); window.addEventListener('scroll', () => updateCurrentSection(), { passive: true })"
        @keydown.escape.window="mobileSidebarOpen = false">
        @include('dashboard.student-dashboard.components.workspace-sidebar')

        <div x-cloak x-show="mobileSidebarOpen" class="fixed inset-0 z-50 lg:hidden">
            <button type="button" class="absolute inset-0 bg-slate-950/45 backdrop-blur-sm" @click="mobileSidebarOpen = false" aria-label="Close workspace navigation"></button>
            <div x-show="mobileSidebarOpen"
                x-transition:enter="transition duration-200 ease-out"
                x-transition:enter-start="-translate-x-full opacity-70"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition duration-150 ease-in"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="-translate-x-full opacity-70"
                class="relative h-full w-[20rem] max-w-[86vw]">
                @include('dashboard.student-dashboard.components.workspace-sidebar', ['isMobile' => true])
            </div>
        </div>

        <div class="min-w-0 flex-1">
            @include('dashboard.student-dashboard.components.workspace-topbar')

            <main class="mx-auto grid max-w-6xl gap-8 px-4 py-8 sm:px-6 lg:px-10 xl:grid-cols-[minmax(0,1fr)_17rem]">
                <div class="space-y-8">
                    @include('dashboard.student-dashboard.components.workspace-header')

                    <section id="steps" class="space-y-4">
                        <div class="max-w-2xl">
                            <p class="text-sm font-semibold text-blue-600">Steps</p>
                            <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">Follow this project slowly</h2>
                            <p class="mt-2 text-base leading-7 text-slate-600">Open one step, complete the task, then move to the next. You do not need to rush.</p>
                        </div>

                        @foreach ($steps as $step)
                        @include('dashboard.student-dashboard.components.workspace-step-card', ['step' => $step])
                        @endforeach
                    </section>

                    <section id="resources" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-semibold text-blue-600">Resources</p>
                        <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">Helpful links for this project</h2>
                        <div class="mt-6 grid gap-5 md:grid-cols-3">
                            @foreach ($resources as $group)
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <h3 class="text-sm font-bold text-slate-950">{{ $group['category'] }}</h3>
                                <div class="mt-3 space-y-2">
                                    @foreach ($group['items'] as $resource)
                                    <a href="{{ $resource['href'] }}" target="_blank" rel="noreferrer"
                                        class="block rounded-xl border border-slate-200 bg-white p-3 transition hover:border-blue-200 hover:bg-blue-50">
                                        <span class="flex items-center gap-2 text-sm font-semibold text-slate-800">
                                            <i class="{{ $resource['icon'] }} text-blue-600"></i>
                                            {{ $resource['label'] }}
                                        </span>
                                        <span class="mt-1 block text-xs leading-5 text-slate-500">{{ $resource['description'] }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>

                    <section id="submission" class="rounded-3xl border border-blue-100 bg-blue-50 p-6">
                        <template x-if="!submissionUnlocked && !submission.submitted">
                            <div class="rounded-3xl border border-slate-200 bg-white p-6 text-center">
                                <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-slate-100 text-slate-500">
                                    <i class="fi fi-rr-lock text-2xl"></i>
                                </div>
                                <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-950">Final submission is locked</h2>
                                <p class="mx-auto mt-2 max-w-md text-base leading-7 text-slate-600">
                                    Complete every project task first. Your progress is saved to your student account after each completed step.
                                </p>
                                <a href="#steps" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                                    Continue tasks
                                </a>
                            </div>
                        </template>

                        <template x-if="submissionUnlocked && !submission.submitted">
                            <div>
                                <p class="text-sm font-semibold text-blue-700">Submission</p>
                                <h2 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">Submit your project for review</h2>
                                <p class="mt-2 max-w-2xl text-base leading-7 text-slate-600">Share your details and GitHub project link so mentors can review your work.</p>

                                <form class="mt-6 space-y-4" @submit.prevent="submitProject">
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="student_name" class="text-sm font-semibold text-slate-800">Name</label>
                                            <input id="student_name" type="text" x-model.trim="submission.name" placeholder="Enter your name"
                                                class="mt-2 w-full rounded-2xl border bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-300 focus:ring-4 focus:ring-blue-100"
                                                :class="submission.fieldErrors.name ? 'border-red-300' : 'border-slate-200'">
                                            <p x-cloak x-show="submission.fieldErrors.name" class="mt-2 text-sm font-medium text-red-600" x-text="submission.fieldErrors.name"></p>
                                        </div>
                                        <div>
                                            <label for="student_email" class="text-sm font-semibold text-slate-800">Email</label>
                                            <input id="student_email" type="email" x-model.trim="submission.email" placeholder="Enter your email"
                                                class="mt-2 w-full rounded-2xl border bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-300 focus:ring-4 focus:ring-blue-100"
                                                :class="submission.fieldErrors.email ? 'border-red-300' : 'border-slate-200'">
                                            <p x-cloak x-show="submission.fieldErrors.email" class="mt-2 text-sm font-medium text-red-600" x-text="submission.fieldErrors.email"></p>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="github_url" class="text-sm font-semibold text-slate-800">GitHub project link</label>
                                        <input id="github_url" type="url" x-model.trim="submission.githubUrl" placeholder="Paste your GitHub project link"
                                            class="mt-2 w-full rounded-2xl border bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-300 focus:ring-4 focus:ring-blue-100"
                                            :class="submission.fieldErrors.githubUrl ? 'border-red-300' : 'border-slate-200'">
                                        <p x-cloak x-show="submission.fieldErrors.githubUrl" class="mt-2 text-sm font-medium text-red-600" x-text="submission.fieldErrors.githubUrl"></p>
                                    </div>
                                    <div>
                                        <label for="stream" class="text-sm font-semibold text-slate-800">Stream</label>
                                        <select id="stream" x-model="submission.stream"
                                            class="mt-2 w-full rounded-2xl border bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-300 focus:ring-4 focus:ring-blue-100"
                                            :class="submission.fieldErrors.stream ? 'border-red-300' : 'border-slate-200'">
                                            <option value="">Select your stream</option>
                                            <option value="IT">IT</option>
                                            <option value="MBA">MBA</option>
                                            <option value="Law">Law</option>
                                            <option value="Eng">Eng</option>
                                            <option value="UIUX">UIUX</option>
                                        </select>
                                        <p x-cloak x-show="submission.fieldErrors.stream" class="mt-2 text-sm font-medium text-red-600" x-text="submission.fieldErrors.stream"></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-semibold text-slate-800">Screenshot upload</label>
                                        <div class="mt-2 rounded-2xl border border-dashed border-blue-200 bg-white px-4 py-8 text-center">
                                            <i class="fi fi-rr-picture text-2xl text-blue-500"></i>
                                            <p class="mt-2 text-sm font-semibold text-slate-700">Drop a screenshot here</p>
                                            <p class="mt-1 text-xs text-slate-500">Placeholder only for now. File upload can be connected later.</p>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="learning_note" class="text-sm font-semibold text-slate-800">What did you learn?</label>
                                        <textarea id="learning_note" rows="5" x-model.trim="submission.learningNote" placeholder="Write a few lines about what you learned. What part was difficult for you?"
                                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-blue-300 focus:ring-4 focus:ring-blue-100"></textarea>
                                    </div>

                                    <p x-cloak x-show="submission.error" class="rounded-2xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-medium text-red-700" x-text="submission.error"></p>

                                    <button type="submit" :disabled="submission.loading"
                                        class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-blue-400 sm:w-auto">
                                        <span x-show="!submission.loading">Submit for review</span>
                                        <span x-show="submission.loading">Submitting...</span>
                                    </button>
                                </form>
                            </div>
                        </template>

                        <template x-if="submission.submitted">
                            <div class="rounded-3xl border border-emerald-100 bg-emerald-50 p-6 text-center">
                                <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-emerald-100 text-emerald-700">
                                    <i class="fi fi-rr-check text-2xl"></i>
                                </div>
                                <h2 class="mt-4 text-2xl font-bold tracking-tight text-slate-950">Your project has been submitted successfully 🎉</h2>
                                <p class="mx-auto mt-2 max-w-md text-base leading-7 text-slate-600">Our mentor team will review your work soon.</p>
                                <a href="{{ route('dashboard.enrolled-courses') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    Back to dashboard
                                </a>
                            </div>
                        </template>
                    </section>
                </div>

                @include('dashboard.student-dashboard.components.workspace-right-panel')
            </main>
        </div>
    </div>
</body>

</html>
