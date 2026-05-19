@php
    $workspaceSteps = old('steps', $workspaceSteps ?? []);
    $workspaceResources = old('resources', $workspaceResources ?? []);
    $workspaceGoals = old('goals', $workspaceGoals ?? []);
    $taskProgressRows = old('progress_rows', $taskProgressRows ?? []);
@endphp

<div
    x-data="workspaceForm({
        steps: @js($workspaceSteps),
        resources: @js($workspaceResources),
        goals: @js($workspaceGoals),
        progressRows: @js($taskProgressRows)
    })"
    class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-brand">Course workspace</p>
            <h1 class="mt-1 text-2xl font-semibold text-gray-950">{{ $title }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        </div>
        <a href="{{ route('admin.course-workspaces.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            <i class="fi fi-rr-arrow-small-left"></i>
            Back
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-semibold">Please fix the form errors.</p>
            <ul class="mt-2 list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $action }}" method="POST" class="space-y-6">
        @csrf
        @if($method !== 'POST')
            @method($method)
        @endif

        <div class="rounded-lg border border-gray-200 bg-white p-2 shadow-sm">
            <div class="flex gap-2 overflow-x-auto">
                @foreach([
                    'overview' => ['Overview', 'fi-rr-home'],
                    'steps' => ['Steps', 'fi-rr-list-check'],
                    'resources' => ['Resources', 'fi-rr-link'],
                    'goals' => ['Goals', 'fi-rr-target'],
                    'progress' => ['Progress', 'fi-rr-chart-histogram'],
                ] as $key => $tabData)
                    <button type="button" @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'bg-brand text-white' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-950'"
                        class="inline-flex shrink-0 items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition">
                        <i class="fi {{ $tabData[1] }}"></i>
                        {{ $tabData[0] }}
                    </button>
                @endforeach
            </div>
        </div>

        <section x-show="tab === 'overview'" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
            <div class="grid gap-5 lg:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-gray-700">Course</label>
                    <select name="course_id" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                        <option value="">Select course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" @selected(old('course_id', $workspace->course_id) == $course->id)>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Workspace Title</label>
                    <input name="title" value="{{ old('title', $workspace->title) }}" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Track</label>
                    <input name="track" value="{{ old('track', $workspace->track) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Headline</label>
                    <input name="headline" value="{{ old('headline', $workspace->headline) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                </div>
                <div class="lg:col-span-2">
                    <label class="text-sm font-semibold text-gray-700">Summary</label>
                    <textarea name="summary" rows="4" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">{{ old('summary', $workspace->summary) }}</textarea>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Progress (%)</label>
                    <input type="number" name="progress" min="0" max="100" value="{{ old('progress', $workspace->progress ?? 0) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-700">Next Milestone</label>
                    <input name="next_milestone" value="{{ old('next_milestone', $workspace->next_milestone) }}" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                </div>
                <label class="inline-flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" @checked(old('status', $workspace->status ?? true)) class="h-4 w-4 rounded border-gray-300 text-brand focus:ring-brand">
                    Active workspace
                </label>
            </div>
        </section>

        <section x-show="tab === 'steps'" class="space-y-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div>
                    <h2 class="font-semibold text-gray-950">Workspace Steps</h2>
                    <p class="mt-1 text-sm text-gray-500">Add guided build steps for the student workspace.</p>
                </div>
                <button type="button" @click="addStep()" class="inline-flex items-center gap-2 rounded-lg bg-brand px-3 py-2 text-sm font-semibold text-white hover:bg-brandLight">
                    <i class="fi fi-rr-plus"></i>
                    Add Step
                </button>
            </div>

            <template x-for="(step, index) in steps" :key="step.uid">
                <div x-show="!step._delete" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <input type="hidden" :name="`steps[${index}][id]`" x-model="step.id">
                    <input type="hidden" :name="`steps[${index}][_delete]`" x-model="step._delete">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-950">Step <span x-text="index + 1"></span></h3>
                        <button type="button" @click="removeRow(step, 'steps')" class="rounded-lg p-2 text-red-600 hover:bg-red-50" title="Remove step">
                            <i class="fi fi-rr-trash"></i>
                        </button>
                    </div>
                    <div class="grid gap-4 lg:grid-cols-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Step No</label>
                            <input type="number" min="1" :name="`steps[${index}][step_no]`" x-model="step.step_no" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Sort</label>
                            <input type="number" min="0" :name="`steps[${index}][sort_order]`" x-model="step.sort_order" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Status</label>
                            <select :name="`steps[${index}][status]`" x-model="step.status" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                                <option>Locked</option>
                                <option>In Progress</option>
                                <option>Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">State</label>
                            <select :name="`steps[${index}][state]`" x-model="step.state" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="locked">locked</option>
                                <option value="active">active</option>
                                <option value="completed">completed</option>
                            </select>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Title</label>
                            <input :name="`steps[${index}][title]`" x-model="step.title" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Slug</label>
                            <input :name="`steps[${index}][slug]`" x-model="step.slug" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Nav Label</label>
                            <input :name="`steps[${index}][nav_label]`" x-model="step.nav_label" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                        </div>
                        <div class="lg:col-span-4">
                            <label class="text-sm font-semibold text-gray-700">Description</label>
                            <textarea rows="2" :name="`steps[${index}][description]`" x-model="step.description" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Build Goal</label>
                            <textarea rows="3" :name="`steps[${index}][build_goal]`" x-model="step.build_goal" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Why Text</label>
                            <textarea rows="3" :name="`steps[${index}][why_text]`" x-model="step.why_text" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea>
                        </div>
                        <div class="lg:col-span-4">
                            <label class="text-sm font-semibold text-gray-700">Lesson</label>
                            <textarea rows="4" :name="`steps[${index}][lesson]`" x-model="step.lesson" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">File Name</label>
                            <input :name="`steps[${index}][file_name]`" x-model="step.file_name" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                        </div>
                        <label class="mt-7 inline-flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700">
                            <input type="hidden" :name="`steps[${index}][active]`" value="0">
                            <input type="checkbox" :name="`steps[${index}][active]`" value="1" x-model="step.active" class="h-4 w-4 rounded border-gray-300 text-brand">
                            Active
                        </label>
                        <div class="lg:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Expected Output</label>
                            <input :name="`steps[${index}][expected_output]`" x-model="step.expected_output" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                        </div>
                        <div class="lg:col-span-4">
                            <label class="text-sm font-semibold text-gray-700">Code Snippet</label>
                            <textarea rows="5" :name="`steps[${index}][code_snippet]`" x-model="step.code_snippet" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 font-mono text-sm"></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Preview Title</label>
                            <input :name="`steps[${index}][preview_title]`" x-model="step.preview_title" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Preview Points</label>
                            <textarea rows="3" :name="`steps[${index}][preview_points]`" x-model="step.preview_points" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm" placeholder="One per line"></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Mistakes</label>
                            <textarea rows="3" :name="`steps[${index}][mistakes]`" x-model="step.mistakes" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm" placeholder="One per line"></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Tips</label>
                            <textarea rows="3" :name="`steps[${index}][tips]`" x-model="step.tips" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm" placeholder="One per line"></textarea>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="text-sm font-semibold text-gray-700">Task</label>
                            <textarea rows="3" :name="`steps[${index}][task]`" x-model="step.task" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Hint</label>
                            <textarea rows="3" :name="`steps[${index}][hint]`" x-model="step.hint" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Mentor Tip</label>
                            <textarea rows="3" :name="`steps[${index}][mentor_tip]`" x-model="step.mentor_tip" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea>
                        </div>
                    </div>
                </div>
            </template>
        </section>

        <section x-show="tab === 'resources'" class="space-y-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="font-semibold text-gray-950">Resources</h2>
                <button type="button" @click="addResource()" class="inline-flex items-center gap-2 rounded-lg bg-brand px-3 py-2 text-sm font-semibold text-white hover:bg-brandLight"><i class="fi fi-rr-plus"></i>Add Resource</button>
            </div>
            <template x-for="(resource, index) in resources" :key="resource.uid">
                <div x-show="!resource._delete" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <input type="hidden" :name="`resources[${index}][id]`" x-model="resource.id">
                    <input type="hidden" :name="`resources[${index}][_delete]`" x-model="resource._delete">
                    <div class="grid gap-4 lg:grid-cols-4">
                        <div><label class="text-sm font-semibold text-gray-700">Category</label><input :name="`resources[${index}][category]`" x-model="resource.category" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div><label class="text-sm font-semibold text-gray-700">Label</label><input :name="`resources[${index}][label]`" x-model="resource.label" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div><label class="text-sm font-semibold text-gray-700">Icon</label><input :name="`resources[${index}][icon]`" x-model="resource.icon" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div><label class="text-sm font-semibold text-gray-700">Sort</label><input type="number" :name="`resources[${index}][sort_order]`" x-model="resource.sort_order" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div class="lg:col-span-2"><label class="text-sm font-semibold text-gray-700">Href</label><input :name="`resources[${index}][href]`" x-model="resource.href" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div class="lg:col-span-2"><label class="text-sm font-semibold text-gray-700">Description</label><textarea rows="2" :name="`resources[${index}][description]`" x-model="resource.description" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea></div>
                    </div>
                    <button type="button" @click="removeRow(resource, 'resources')" class="mt-4 text-sm font-semibold text-red-600 hover:text-red-700">Remove resource</button>
                </div>
            </template>
        </section>

        <section x-show="tab === 'goals'" class="space-y-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="font-semibold text-gray-950">Goals</h2>
                <button type="button" @click="addGoal()" class="inline-flex items-center gap-2 rounded-lg bg-brand px-3 py-2 text-sm font-semibold text-white hover:bg-brandLight"><i class="fi fi-rr-plus"></i>Add Goal</button>
            </div>
            <template x-for="(goal, index) in goals" :key="goal.uid">
                <div x-show="!goal._delete" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <input type="hidden" :name="`goals[${index}][id]`" x-model="goal.id">
                    <input type="hidden" :name="`goals[${index}][_delete]`" x-model="goal._delete">
                    <div class="grid gap-4 lg:grid-cols-4">
                        <div class="lg:col-span-2"><label class="text-sm font-semibold text-gray-700">Title</label><input :name="`goals[${index}][title]`" x-model="goal.title" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div><label class="text-sm font-semibold text-gray-700">Duration</label><input :name="`goals[${index}][duration]`" x-model="goal.duration" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div><label class="text-sm font-semibold text-gray-700">Type</label><input :name="`goals[${index}][type]`" x-model="goal.type" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div class="lg:col-span-4"><label class="text-sm font-semibold text-gray-700">Body</label><textarea rows="3" :name="`goals[${index}][body]`" x-model="goal.body" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea></div>
                    </div>
                    <button type="button" @click="removeRow(goal, 'goals')" class="mt-4 text-sm font-semibold text-red-600 hover:text-red-700">Remove goal</button>
                </div>
            </template>
        </section>

        <section x-show="tab === 'progress'" class="space-y-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <div>
                    <h2 class="font-semibold text-gray-950">Task Progress</h2>
                    <p class="mt-1 text-sm text-gray-500">Progress rows can be attached after steps are saved.</p>
                </div>
                <button type="button" @click="addProgress()" class="inline-flex items-center gap-2 rounded-lg bg-brand px-3 py-2 text-sm font-semibold text-white hover:bg-brandLight"><i class="fi fi-rr-plus"></i>Add Progress</button>
            </div>
            <template x-for="(progress, index) in progressRows" :key="progress.uid">
                <div x-show="!progress._delete" class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <input type="hidden" :name="`progress_rows[${index}][id]`" x-model="progress.id">
                    <input type="hidden" :name="`progress_rows[${index}][_delete]`" x-model="progress._delete">
                    <input type="hidden" :name="`progress_rows[${index}][course_id]`" value="{{ old('course_id', $workspace->course_id) }}">
                    <div class="grid gap-4 lg:grid-cols-4">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Student</label>
                            <select :name="`progress_rows[${index}][student_id]`" x-model="progress.student_id" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">Select student</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}{{ $user->email ? ' (' . $user->email . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Step</label>
                            <select :name="`progress_rows[${index}][step_id]`" x-model="progress.step_id" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm">
                                <option value="">Select step</option>
                                <template x-for="step in steps.filter(item => item.id && !item._delete)" :key="step.uid">
                                    <option :value="step.id" x-text="step.title || `Step ${step.step_no}`"></option>
                                </template>
                            </select>
                        </div>
                        <label class="mt-7 inline-flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700">
                            <input type="hidden" :name="`progress_rows[${index}][completed]`" value="0">
                            <input type="checkbox" :name="`progress_rows[${index}][completed]`" value="1" x-model="progress.completed" class="h-4 w-4 rounded border-gray-300 text-brand">
                            Completed
                        </label>
                        <label class="mt-7 inline-flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700">
                            <input type="hidden" :name="`progress_rows[${index}][submitted]`" value="0">
                            <input type="checkbox" :name="`progress_rows[${index}][submitted]`" value="1" x-model="progress.submitted" class="h-4 w-4 rounded border-gray-300 text-brand">
                            Submitted
                        </label>
                        <div><label class="text-sm font-semibold text-gray-700">GitHub Link</label><input :name="`progress_rows[${index}][github_link]`" x-model="progress.github_link" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div><label class="text-sm font-semibold text-gray-700">Live Link</label><input :name="`progress_rows[${index}][live_link]`" x-model="progress.live_link" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div><label class="text-sm font-semibold text-gray-700">Completed At</label><input type="datetime-local" :name="`progress_rows[${index}][completed_at]`" x-model="progress.completed_at" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></div>
                        <div class="lg:col-span-4"><label class="text-sm font-semibold text-gray-700">Notes</label><textarea rows="2" :name="`progress_rows[${index}][notes]`" x-model="progress.notes" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm"></textarea></div>
                    </div>
                    <button type="button" @click="removeRow(progress, 'progressRows')" class="mt-4 text-sm font-semibold text-red-600 hover:text-red-700">Remove progress</button>
                </div>
            </template>
        </section>

        <div class="sticky bottom-4 z-10 flex flex-col gap-3 rounded-lg border border-gray-200 bg-white/95 p-4 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">Save once after editing any tab.</p>
            <div class="flex gap-2">
                <a href="{{ route('admin.course-workspaces.index') }}" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-brandLight">Save Workspace</button>
            </div>
        </div>
    </form>
</div>

<script>
    function workspaceForm(initial) {
        const withUid = (row) => ({ uid: crypto.randomUUID(), _delete: 0, ...row });

        return {
            tab: 'overview',
            steps: (initial.steps || []).map(withUid),
            resources: (initial.resources || []).map(withUid),
            goals: (initial.goals || []).map(withUid),
            progressRows: (initial.progressRows || []).map(withUid),
            addStep() {
                this.steps.push(withUid({
                    step_no: this.steps.length + 1,
                    sort_order: this.steps.length,
                    status: 'Locked',
                    state: 'locked',
                    active: false
                }));
            },
            addResource() {
                this.resources.push(withUid({ sort_order: this.resources.length }));
            },
            addGoal() {
                this.goals.push(withUid({ type: 'daily' }));
            },
            addProgress() {
                this.progressRows.push(withUid({ completed: false, submitted: false }));
            },
            removeRow(row, collection) {
                if (row.id) {
                    row._delete = 1;
                    return;
                }

                this[collection] = this[collection].filter(item => item.uid !== row.uid);
            }
        };
    }
</script>
