<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseWorkspace;
use App\Models\TaskProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CourseWorkspaceController extends Controller
{
    public function index()
    {
        $workspaces = CourseWorkspace::with(['course', 'steps', 'resources', 'goals'])
            ->latest()
            ->paginate(10);

        return view('Admin.course_workspaces.index', compact('workspaces'));
    }

    public function create()
    {
        return view('Admin.course_workspaces.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validatedWorkspace($request);

        $workspace = CourseWorkspace::create($validated['workspace']);
        $this->syncChildren($workspace, $validated);

        return redirect()
            ->route('admin.course-workspaces.edit', $workspace)
            ->with('success', 'Workspace created successfully.');
    }

    public function show(CourseWorkspace $courseWorkspace)
    {
        $courseWorkspace->load(['course', 'steps.taskProgress.student', 'resources', 'goals']);

        return view('Admin.course_workspaces.show', compact('courseWorkspace'));
    }

    public function edit(CourseWorkspace $courseWorkspace)
    {
        $courseWorkspace->load(['steps.taskProgress.student', 'resources', 'goals']);

        return view('Admin.course_workspaces.edit', [
            ...$this->formData(),
            'workspace' => $courseWorkspace,
            'workspaceSteps' => $this->stepsForForm($courseWorkspace),
            'workspaceResources' => $this->resourcesForForm($courseWorkspace),
            'workspaceGoals' => $this->goalsForForm($courseWorkspace),
            'taskProgressRows' => $this->progressForForm($courseWorkspace),
        ]);
    }

    public function update(Request $request, CourseWorkspace $courseWorkspace)
    {
        $validated = $this->validatedWorkspace($request);

        $courseWorkspace->update($validated['workspace']);
        $this->syncChildren($courseWorkspace, $validated);

        return redirect()
            ->route('admin.course-workspaces.edit', $courseWorkspace)
            ->with('success', 'Workspace updated successfully.');
    }

    public function destroy(CourseWorkspace $courseWorkspace)
    {
        $courseWorkspace->delete();

        return redirect()
            ->route('admin.course-workspaces.index')
            ->with('success', 'Workspace deleted successfully.');
    }

    protected function formData(): array
    {
        return [
            'workspace' => new CourseWorkspace(['progress' => 0, 'status' => true]),
            'courses' => Course::orderBy('title')->get(['id', 'title']),
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
            'workspaceSteps' => [],
            'workspaceResources' => [],
            'workspaceGoals' => [],
            'taskProgressRows' => [],
        ];
    }

    protected function validatedWorkspace(Request $request): array
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'track' => ['nullable', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'next_milestone' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'boolean'],

            'steps' => ['nullable', 'array'],
            'steps.*.id' => ['nullable', 'integer', 'exists:workspace_steps,id'],
            'steps.*.step_no' => ['nullable', 'integer', 'min:1'],
            'steps.*.slug' => ['nullable', 'string', 'max:255'],
            'steps.*.nav_label' => ['nullable', 'string', 'max:255'],
            'steps.*.title' => ['nullable', 'string', 'max:255'],
            'steps.*.description' => ['nullable', 'string'],
            'steps.*.status' => ['nullable', 'string', 'max:255'],
            'steps.*.state' => ['nullable', 'string', 'max:255'],
            'steps.*.active' => ['nullable', 'boolean'],
            'steps.*.build_goal' => ['nullable', 'string'],
            'steps.*.why_text' => ['nullable', 'string'],
            'steps.*.lesson' => ['nullable', 'string'],
            'steps.*.file_name' => ['nullable', 'string', 'max:255'],
            'steps.*.code_snippet' => ['nullable', 'string'],
            'steps.*.expected_output' => ['nullable', 'string'],
            'steps.*.preview_title' => ['nullable', 'string'],
            'steps.*.task' => ['nullable', 'string'],
            'steps.*.hint' => ['nullable', 'string'],
            'steps.*.mentor_tip' => ['nullable', 'string'],
            'steps.*.preview_points' => ['nullable', 'string'],
            'steps.*.mistakes' => ['nullable', 'string'],
            'steps.*.tips' => ['nullable', 'string'],
            'steps.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'steps.*._delete' => ['nullable', 'boolean'],

            'resources' => ['nullable', 'array'],
            'resources.*.id' => ['nullable', 'integer', 'exists:workspace_resources,id'],
            'resources.*.category' => ['nullable', 'string', 'max:255'],
            'resources.*.label' => ['nullable', 'string', 'max:255'],
            'resources.*.description' => ['nullable', 'string'],
            'resources.*.icon' => ['nullable', 'string', 'max:255'],
            'resources.*.href' => ['nullable', 'string'],
            'resources.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'resources.*._delete' => ['nullable', 'boolean'],

            'goals' => ['nullable', 'array'],
            'goals.*.id' => ['nullable', 'integer', 'exists:workspace_goals,id'],
            'goals.*.title' => ['nullable', 'string', 'max:255'],
            'goals.*.body' => ['nullable', 'string'],
            'goals.*.duration' => ['nullable', 'string', 'max:255'],
            'goals.*.type' => ['nullable', 'string', 'max:255'],
            'goals.*._delete' => ['nullable', 'boolean'],

            'progress_rows' => ['nullable', 'array'],
            'progress_rows.*.id' => ['nullable', 'integer', 'exists:task_progress,id'],
            'progress_rows.*.student_id' => ['nullable', 'exists:users,id'],
            'progress_rows.*.course_id' => ['nullable', 'exists:courses,id'],
            'progress_rows.*.step_id' => ['nullable', 'exists:workspace_steps,id'],
            'progress_rows.*.completed' => ['nullable', 'boolean'],
            'progress_rows.*.submitted' => ['nullable', 'boolean'],
            'progress_rows.*.github_link' => ['nullable', 'string'],
            'progress_rows.*.live_link' => ['nullable', 'string'],
            'progress_rows.*.notes' => ['nullable', 'string'],
            'progress_rows.*.completed_at' => ['nullable', 'date'],
            'progress_rows.*._delete' => ['nullable', 'boolean'],
        ]);

        return [
            'workspace' => [
                ...Arr::only($validated, ['course_id', 'title', 'track', 'headline', 'summary', 'progress', 'next_milestone']),
                'status' => $request->boolean('status'),
            ],
            'steps' => $validated['steps'] ?? [],
            'resources' => $validated['resources'] ?? [],
            'goals' => $validated['goals'] ?? [],
            'progress_rows' => $validated['progress_rows'] ?? [],
        ];
    }

    protected function syncChildren(CourseWorkspace $workspace, array $validated): void
    {
        foreach ($validated['steps'] as $step) {
            if (! empty($step['_delete']) && ! empty($step['id'])) {
                $workspace->steps()->whereKey($step['id'])->delete();
                continue;
            }

            if (empty($step['title'])) {
                continue;
            }

            $payload = [
                ...Arr::only($step, [
                    'step_no',
                    'slug',
                    'nav_label',
                    'title',
                    'description',
                    'status',
                    'state',
                    'build_goal',
                    'why_text',
                    'lesson',
                    'file_name',
                    'code_snippet',
                    'expected_output',
                    'preview_title',
                    'task',
                    'hint',
                    'mentor_tip',
                    'sort_order',
                ]),
                'active' => (bool) ($step['active'] ?? false),
                'preview_points' => $this->linesToArray($step['preview_points'] ?? ''),
                'mistakes' => $this->linesToArray($step['mistakes'] ?? ''),
                'tips' => $this->linesToArray($step['tips'] ?? ''),
            ];

            $workspace->steps()->updateOrCreate(['id' => $step['id'] ?? null], $payload);
        }

        foreach ($validated['resources'] as $resource) {
            if (! empty($resource['_delete']) && ! empty($resource['id'])) {
                $workspace->resources()->whereKey($resource['id'])->delete();
                continue;
            }

            if (empty($resource['label'])) {
                continue;
            }

            $workspace->resources()->updateOrCreate(
                ['id' => $resource['id'] ?? null],
                Arr::only($resource, ['category', 'label', 'description', 'icon', 'href', 'sort_order'])
            );
        }

        foreach ($validated['goals'] as $goal) {
            if (! empty($goal['_delete']) && ! empty($goal['id'])) {
                $workspace->goals()->whereKey($goal['id'])->delete();
                continue;
            }

            if (empty($goal['title'])) {
                continue;
            }

            $workspace->goals()->updateOrCreate(
                ['id' => $goal['id'] ?? null],
                Arr::only($goal, ['title', 'body', 'duration', 'type'])
            );
        }

        foreach ($validated['progress_rows'] as $progress) {
            if (! empty($progress['_delete']) && ! empty($progress['id'])) {
                TaskProgress::whereKey($progress['id'])->delete();
                continue;
            }

            if (empty($progress['student_id']) || empty($progress['step_id'])) {
                continue;
            }

            TaskProgress::updateOrCreate(
                ['id' => $progress['id'] ?? null],
                [
                    'student_id' => $progress['student_id'],
                    'course_id' => $progress['course_id'] ?? $workspace->course_id,
                    'step_id' => $progress['step_id'],
                    'completed' => (bool) ($progress['completed'] ?? false),
                    'submitted' => (bool) ($progress['submitted'] ?? false),
                    'github_link' => $progress['github_link'] ?? null,
                    'live_link' => $progress['live_link'] ?? null,
                    'notes' => $progress['notes'] ?? null,
                    'completed_at' => $progress['completed_at'] ?? null,
                ]
            );
        }
    }

    protected function linesToArray(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    protected function stepsForForm(CourseWorkspace $workspace): array
    {
        return $workspace->steps->map(fn ($step) => [
            ...$step->only([
                'id',
                'step_no',
                'slug',
                'nav_label',
                'title',
                'description',
                'status',
                'state',
                'active',
                'build_goal',
                'why_text',
                'lesson',
                'file_name',
                'code_snippet',
                'expected_output',
                'preview_title',
                'task',
                'hint',
                'mentor_tip',
                'sort_order',
            ]),
            'preview_points' => implode("\n", $step->preview_points ?? []),
            'mistakes' => implode("\n", $step->mistakes ?? []),
            'tips' => implode("\n", $step->tips ?? []),
        ])->values()->all();
    }

    protected function resourcesForForm(CourseWorkspace $workspace): array
    {
        return $workspace->resources->map->only(['id', 'category', 'label', 'description', 'icon', 'href', 'sort_order'])
            ->values()
            ->all();
    }

    protected function goalsForForm(CourseWorkspace $workspace): array
    {
        return $workspace->goals->map->only(['id', 'title', 'body', 'duration', 'type'])
            ->values()
            ->all();
    }

    protected function progressForForm(CourseWorkspace $workspace): array
    {
        return $workspace->steps
            ->flatMap(fn ($step) => $step->taskProgress)
            ->map(fn ($progress) => [
                ...$progress->only(['id', 'student_id', 'course_id', 'step_id', 'completed', 'submitted', 'github_link', 'live_link', 'notes']),
                'completed_at' => $progress->completed_at?->format('Y-m-d\TH:i'),
            ])
            ->values()
            ->all();
    }
}
