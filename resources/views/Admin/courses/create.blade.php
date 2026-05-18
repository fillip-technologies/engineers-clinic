@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Create New Course: Web Ecosystems & Frontend Architecture</h3>
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Courses
                </a>
            </div>
        </div>

        <form action="{{ route('admin.courses.store') }}" method="POST" id="courseForm">
            @csrf
            <div class="px-6 py-4 space-y-8">

                <!-- Basic Information Section -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Basic Information</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                            <input type="text" name="title" id="title" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-300 @enderror"
                                   value="{{ old('title', 'Web Ecosystems & Frontend Architecture') }}" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug *</label>
                            <input type="text" name="slug" id="slug" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('slug') border-red-300 @enderror"
                                   value="{{ old('slug', 'web-ecosystems-frontend') }}" required>
                            <p class="mt-1 text-xs text-gray-500">URL-friendly version of the title</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="level" class="block text-sm font-medium text-gray-700">Level *</label>
                            <select name="level" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('level') border-red-300 @enderror" required>
                                <option value="Beginner" {{ old('level', 'Beginner') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="Intermediate" {{ old('level') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="Advanced" {{ old('level') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                            @error('level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                            <input type="text" name="category" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('category') border-red-300 @enderror"
                                   value="{{ old('category', 'Internship') }}" required>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration_months" class="block text-sm font-medium text-gray-700">Duration (Months) *</label>
                            <input type="number" name="duration_months" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('duration_months') border-red-300 @enderror"
                                   value="{{ old('duration_months', 3) }}" required min="1">
                            @error('duration_months')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fee" class="block text-sm font-medium text-gray-700">Fee ($)</label>
                            <input type="number" step="0.01" name="fee" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('fee') border-red-300 @enderror"
                                   value="{{ old('fee') }}" placeholder="Enter fee amount">
                            @error('fee')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Media & Badges Section -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Media & Branding</h4>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
                            <input type="text" name="image" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('image') border-red-300 @enderror"
                                   value="{{ old('image', '/images/courses/frontend-development.svg') }}" placeholder="/images/courses/hero-image.jpg">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="hero_badge" class="block text-sm font-medium text-gray-700">Hero Badge</label>
                            <textarea name="hero_badge" rows="2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('hero_badge') border-red-300 @enderror"
                                      placeholder="Hero badge text">{{ old('hero_badge', 'BUILD MODERN SOFTWARE SYSTEMS THROUGH APPLIED ENGINEERING TASKS') }}</textarea>
                            @error('hero_badge')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="career_path" class="block text-sm font-medium text-gray-700">Career Path</label>
                            <input type="text" name="career_path" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('career_path') border-red-300 @enderror"
                                   value="{{ old('career_path', 'Architecture, implementation, testing, and deployment workflows') }}">
                            @error('career_path')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Course Description</h4>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description *</label>
                        <textarea name="description" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror"
                                  required placeholder="Enter course description">{{ old('description', 'Learn HTML, CSS, React, and frontend systems through practical layout, component, and interface architecture work.') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- MODULES SECTION -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Course Modules</h4>
                    <div id="modules-container">
                        <div id="modules-list" class="space-y-3">
                            @php
                                $defaultModules = ['HTML and CSS Foundations', 'Component Thinking with React', 'Responsive Interface Systems'];
                                $modules = old('modules_array', $defaultModules);
                            @endphp
                            @foreach($modules as $index => $module)
                            <div class="flex items-center space-x-2 module-item">
                                <div class="flex-1">
                                    <input type="text" name="modules_array[]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           value="{{ $module }}" placeholder="Module {{ $index + 1 }}">
                                </div>
                                <button type="button" class="remove-module text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-module" class="mt-3 inline-flex items-center px-3 py-1.5 text-sm text-indigo-600 hover:text-indigo-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Module
                        </button>
                    </div>
                    <input type="hidden" name="modules" id="modules-json">
                </div>

                <!-- PHASES SECTION -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Learning Phases</h4>
                    <div id="phases-container">
                        <div id="phases-list">
                            @php
                                $defaultPhases = [
                                    [
                                        'title' => 'Phase 1: Pure Foundation',
                                        'modules' => [
                                            [
                                                'title' => 'Module 1: UI Planning',
                                                'tasks' => [
                                                    'Digital Persona Card',
                                                    'Landing Page',
                                                    'Cafe Menu UI',
                                                    'Pricing Table',
                                                    'FAQ Accordion',
                                                    'Image Gallery',
                                                    'Agency Landing Page',
                                                    'Timeline UI',
                                                    'Dashboard Layout'
                                                ]
                                            ]
                                        ]
                                    ]
                                ];
                                $phases = old('phases_data', $defaultPhases);
                            @endphp
                            @foreach($phases as $phaseIndex => $phase)
                            <div class="phase-item mb-6 p-4 border border-gray-200 rounded-lg" data-phase-index="{{ $phaseIndex }}">
                                <div class="flex justify-between items-center mb-3">
                                    <h5 class="font-medium text-gray-900">Phase {{ $phaseIndex + 1 }}</h5>
                                    <button type="button" class="remove-phase text-red-600 hover:text-red-800 text-sm">Remove Phase</button>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Phase Title</label>
                                    <input type="text" name="phases_data[{{ $phaseIndex }}][title]" class="phase-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           value="{{ $phase['title'] }}">
                                </div>
                                <div class="phase-modules space-y-4">
                                    @foreach($phase['modules'] as $moduleIndex => $module)
                                    <div class="module-item p-3 bg-gray-50 rounded-lg" data-module-index="{{ $moduleIndex }}">
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="text-sm font-medium text-gray-700">Module {{ $moduleIndex + 1 }}</label>
                                            <button type="button" class="remove-module-from-phase text-red-600 hover:text-red-800 text-xs">Remove Module</button>
                                        </div>
                                        <div class="mb-2">
                                            <label class="block text-xs font-medium text-gray-600">Module Title</label>
                                            <input type="text" name="phases_data[{{ $phaseIndex }}][modules][{{ $moduleIndex }}][title]"
                                                   class="module-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                   value="{{ $module['title'] }}">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Tasks</label>
                                            <div class="tasks-list space-y-2 mt-1">
                                                @foreach($module['tasks'] as $taskIndex => $task)
                                                <div class="flex items-center space-x-2 task-item">
                                                    <input type="text" name="phases_data[{{ $phaseIndex }}][modules][{{ $moduleIndex }}][tasks][]"
                                                           class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                           value="{{ $task }}" placeholder="Task name">
                                                    <button type="button" class="remove-task text-red-600 hover:text-red-800">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="add-task mt-2 text-xs text-indigo-600 hover:text-indigo-800">+ Add Task</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="add-module-to-phase mt-3 text-sm text-indigo-600 hover:text-indigo-800">+ Add Module</button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-phase" class="mt-3 inline-flex items-center px-3 py-1.5 text-sm text-indigo-600 hover:text-indigo-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Phase
                        </button>
                    </div>
                    <input type="hidden" name="phases" id="phases-json">
                </div>

                <!-- CURRICULUM SECTION -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Curriculum Tasks</h4>
                    <div id="curriculum-container">
                        <div id="curriculum-list">
                            @php
                                $defaultCurriculum = [
                                    [
                                        'title' => 'Task 1: Frontend Layout Sprint',
                                        'tasks' => [
                                            [
                                                'title' => 'Responsive Landing Build',
                                                'assignment' => 'Create a structured landing page with reusable sections and responsive behavior.',
                                                'submission' => 'Hosted URL or HTML/CSS source.',
                                                'ai_review' => 'The AI checks for semantic markup, responsive spacing, and consistent component structure.'
                                            ]
                                        ]
                                    ]
                                ];
                                $curriculum = old('curriculum_data', $defaultCurriculum);
                            @endphp
                            @foreach($curriculum as $taskGroupIndex => $taskGroup)
                            <div class="curriculum-item mb-6 p-4 border border-gray-200 rounded-lg" data-group-index="{{ $taskGroupIndex }}">
                                <div class="flex justify-between items-center mb-3">
                                    <h5 class="font-medium text-gray-900">Task Group {{ $taskGroupIndex + 1 }}</h5>
                                    <button type="button" class="remove-curriculum text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700">Group Title</label>
                                    <input type="text" name="curriculum_data[{{ $taskGroupIndex }}][title]" class="group-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           value="{{ $taskGroup['title'] }}">
                                </div>
                                <div class="curriculum-tasks space-y-4">
                                    @foreach($taskGroup['tasks'] as $taskIndex => $task)
                                    <div class="task-detail-item p-3 bg-gray-50 rounded-lg" data-task-index="{{ $taskIndex }}">
                                        <div class="flex justify-between items-center mb-2">
                                            <label class="text-sm font-medium text-gray-700">Task {{ $taskIndex + 1 }}</label>
                                            <button type="button" class="remove-task-detail text-red-600 hover:text-red-800 text-xs">Remove Task</button>
                                        </div>
                                        <div class="grid grid-cols-1 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Task Title</label>
                                                <input type="text" name="curriculum_data[{{ $taskGroupIndex }}][tasks][{{ $taskIndex }}][title]"
                                                       class="task-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                       value="{{ $task['title'] }}">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Assignment Description</label>
                                                <textarea name="curriculum_data[{{ $taskGroupIndex }}][tasks][{{ $taskIndex }}][assignment]" rows="2"
                                                          class="task-assignment block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $task['assignment'] }}</textarea>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">Submission Format</label>
                                                <input type="text" name="curriculum_data[{{ $taskGroupIndex }}][tasks][{{ $taskIndex }}][submission]"
                                                       class="task-submission block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                       value="{{ $task['submission'] }}">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600">AI Review Criteria</label>
                                                <textarea name="curriculum_data[{{ $taskGroupIndex }}][tasks][{{ $taskIndex }}][ai_review]" rows="2"
                                                          class="task-ai-review block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $task['ai_review'] }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="add-task-detail mt-3 text-sm text-indigo-600 hover:text-indigo-800">+ Add Task</button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-curriculum" class="mt-3 inline-flex items-center px-3 py-1.5 text-sm text-indigo-600 hover:text-indigo-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Task Group
                        </button>
                    </div>
                    <input type="hidden" name="curriculum" id="curriculum-json">
                </div>

                <!-- PROGRAM OVERVIEW SECTION -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Program Overview</h4>

                    <!-- Features -->
                    <div class="mb-6">
                        <label class="block text-md font-medium text-gray-800 mb-2">Program Features</label>
                        <div id="features-container">
                            <div id="features-list" class="space-y-3">
                                @php
                                    $defaultFeatures = [
                                        [
                                            'title' => 'HTML and CSS Foundations workflow',
                                            'description' => 'Learn how HTML and CSS Foundations connects to real software work through guided tasks, examples, and applied review checkpoints.'
                                        ],
                                        [
                                            'title' => 'Build Ready execution',
                                            'description' => 'Move from concepts to practical outputs by working on components, APIs, security checks, and deployable system outputs with clear submission expectations.'
                                        ]
                                    ];
                                    $features = old('program_overview_features', $defaultFeatures);
                                @endphp
                                @foreach($features as $featureIndex => $feature)
                                <div class="feature-item p-3 border border-gray-200 rounded-lg" data-feature-index="{{ $featureIndex }}">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-sm font-medium text-gray-700">Feature {{ $featureIndex + 1 }}</label>
                                        <button type="button" class="remove-feature text-red-600 hover:text-red-800 text-sm">Remove</button>
                                    </div>
                                    <div class="mb-2">
                                        <label class="block text-xs font-medium text-gray-600">Feature Title</label>
                                        <input type="text" name="program_overview_features[{{ $featureIndex }}][title]"
                                               class="feature-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                               value="{{ $feature['title'] }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600">Feature Description</label>
                                        <textarea name="program_overview_features[{{ $featureIndex }}][description]" rows="2"
                                                  class="feature-description block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $feature['description'] }}</textarea>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-feature" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">+ Add Feature</button>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="mb-6">
                        <label class="block text-md font-medium text-gray-800 mb-2">Program Statistics</label>
                        <div id="stats-container">
                            <div id="stats-list" class="space-y-3">
                                @php
                                    $defaultStats = [
                                        [
                                            'value' => '3 Months',
                                            'label' => 'Structured path with focused software practice and review'
                                        ],
                                        [
                                            'value' => 'Systems + Code',
                                            'label' => 'HTML and CSS Foundations, Component Thinking with React, and applied workflow practice'
                                        ],
                                        [
                                            'value' => 'Build Ready',
                                            'label' => 'Built to strengthen components, APIs, security checks, and deployable system outputs'
                                        ]
                                    ];
                                    $stats = old('program_overview_stats', $defaultStats);
                                @endphp
                                @foreach($stats as $statIndex => $stat)
                                <div class="stat-item p-3 border border-gray-200 rounded-lg" data-stat-index="{{ $statIndex }}">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="text-sm font-medium text-gray-700">Stat {{ $statIndex + 1 }}</label>
                                        <button type="button" class="remove-stat text-red-600 hover:text-red-800 text-sm">Remove</button>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Value</label>
                                            <input type="text" name="program_overview_stats[{{ $statIndex }}][value]"
                                                   class="stat-value block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                   value="{{ $stat['value'] }}">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600">Label</label>
                                            <input type="text" name="program_overview_stats[{{ $statIndex }}][label]"
                                                   class="stat-label block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                   value="{{ $stat['label'] }}">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-stat" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">+ Add Stat</button>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-md font-medium text-gray-800 mb-2">Call to Action</label>
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Button Text</label>
                                <input type="text" name="program_overview_cta_button"
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       value="{{ old('program_overview_cta_button', 'Explore Web Ecosystems & Frontend Architecture Program') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Batch Information</label>
                                <input type="text" name="program_overview_cta_batch"
                                       class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                       value="{{ old('program_overview_cta_batch', 'Enrollment is open for the next Web Ecosystems & Frontend Architecture cohort.') }}">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="program_overview" id="program-overview-json">
                </div>

                <!-- WHY CHOOSE SECTION -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Why Choose This Course</h4>
                    <div id="why-choose-container">
                        <div id="why-choose-list" class="space-y-3">
                            @php
                                $defaultWhyChoose = [
                                    [
                                        'title' => 'Project-First Engineering',
                                        'description' => 'Practice components, APIs, security checks, and deployable system outputs through structured assignments tied to realistic learning scenarios.'
                                    ],
                                    [
                                        'title' => 'Architecture Thinking',
                                        'description' => 'Understand the reasoning behind each workflow so your decisions become clearer and more confident.'
                                    ],
                                    [
                                        'title' => 'Hands-On Build Practice',
                                        'description' => 'Create reviewable outputs that help you explain your work during internships, interviews, or project discussions.'
                                    ]
                                ];
                                $whyChoose = old('why_choose_data', $defaultWhyChoose);
                            @endphp
                            @foreach($whyChoose as $wcIndex => $wc)
                            <div class="why-choose-item p-3 border border-gray-200 rounded-lg" data-wc-index="{{ $wcIndex }}">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-sm font-medium text-gray-700">Reason {{ $wcIndex + 1 }}</label>
                                    <button type="button" class="remove-why-choose text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-gray-600">Title</label>
                                    <input type="text" name="why_choose_data[{{ $wcIndex }}][title]"
                                           class="wc-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           value="{{ $wc['title'] }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Description</label>
                                    <textarea name="why_choose_data[{{ $wcIndex }}][description]" rows="2"
                                              class="wc-description block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $wc['description'] }}</textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-why-choose" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">+ Add Reason</button>
                    </div>
                    <input type="hidden" name="why_choose" id="why-choose-json">
                </div>

                <!-- TESTIMONIALS SECTION -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Student Testimonials</h4>
                    <div id="testimonials-container">
                        <div id="testimonials-list" class="space-y-3">
                            @php
                                $defaultTestimonials = [
                                    [
                                        'name' => 'Aarav Mehta',
                                        'role' => 'Tech Learner',
                                        'text' => 'The structure helped me understand Web Ecosystems & Frontend Architecture through practical tasks and clear checkpoints.'
                                    ],
                                    [
                                        'name' => 'Nisha Kapoor',
                                        'role' => 'College Student',
                                        'text' => 'I liked that every module ended with something concrete to submit, review, and improve.'
                                    ]
                                ];
                                $testimonials = old('testimonials_data', $defaultTestimonials);
                            @endphp
                            @foreach($testimonials as $testIndex => $test)
                            <div class="testimonial-item p-3 border border-gray-200 rounded-lg" data-test-index="{{ $testIndex }}">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-sm font-medium text-gray-700">Testimonial {{ $testIndex + 1 }}</label>
                                    <button type="button" class="remove-testimonial text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                                <div class="grid grid-cols-2 gap-3 mb-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600">Student Name</label>
                                        <input type="text" name="testimonials_data[{{ $testIndex }}][name]"
                                               class="test-name block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                               value="{{ $test['name'] }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600">Role/Title</label>
                                        <input type="text" name="testimonials_data[{{ $testIndex }}][role]"
                                               class="test-role block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                               value="{{ $test['role'] }}">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Testimonial Text</label>
                                    <textarea name="testimonials_data[{{ $testIndex }}][text]" rows="2"
                                              class="test-text block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $test['text'] }}</textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-testimonial" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">+ Add Testimonial</button>
                    </div>
                    <input type="hidden" name="testimonials" id="testimonials-json">
                </div>

                <!-- FAQ SECTION -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Frequently Asked Questions</h4>
                    <div id="faq-container">
                        <div id="faq-list" class="space-y-3">
                            @php
                                $defaultFaq = [
                                    [
                                        'question' => 'Do I need prior experience before joining?',
                                        'answer' => 'No. The track starts with foundations and then moves into applied tasks, so beginners can build confidence step by step.'
                                    ],
                                    [
                                        'question' => 'Will I build practical projects inside this course?',
                                        'answer' => 'Yes. The curriculum is organized around structured assignments, submissions, and reviewable outputs.'
                                    ],
                                    [
                                        'question' => 'What kind of support will I get during the program?',
                                        'answer' => 'You get guided learning structure, task clarity, and review-focused feedback to help you improve your work.'
                                    ]
                                ];
                                $faq = old('faq_data', $defaultFaq);
                            @endphp
                            @foreach($faq as $faqIndex => $faqItem)
                            <div class="faq-item p-3 border border-gray-200 rounded-lg" data-faq-index="{{ $faqIndex }}">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-sm font-medium text-gray-700">FAQ {{ $faqIndex + 1 }}</label>
                                    <button type="button" class="remove-faq text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-gray-600">Question</label>
                                    <input type="text" name="faq_data[{{ $faqIndex }}][question]"
                                           class="faq-question block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           value="{{ $faqItem['question'] }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Answer</label>
                                    <textarea name="faq_data[{{ $faqIndex }}][answer]" rows="2"
                                              class="faq-answer block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $faqItem['answer'] }}</textarea>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-faq" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">+ Add FAQ</button>
                    </div>
                    <input type="hidden" name="faq" id="faq-json">
                </div>

                <!-- OUTCOME SECTION -->
                <div class="bg-white rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Learning Outcomes</h4>
                    <div id="outcome-container">
                        <div id="outcome-list" class="space-y-3">
                            @php
                                $defaultOutcomes = [
                                    [
                                        'title' => '3 Months Structured Path',
                                        'description' => 'Structured path with focused software practice and review',
                                        'outcomes' => ['Systems + Code', 'Build Ready']
                                    ]
                                ];
                                $outcomes = old('outcome_data', $defaultOutcomes);
                            @endphp
                            @foreach($outcomes as $outIndex => $outcome)
                            <div class="outcome-item p-3 border border-gray-200 rounded-lg" data-out-index="{{ $outIndex }}">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-sm font-medium text-gray-700">Outcome Set {{ $outIndex + 1 }}</label>
                                    <button type="button" class="remove-outcome text-red-600 hover:text-red-800 text-sm">Remove</button>
                                </div>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-gray-600">Title</label>
                                    <input type="text" name="outcome_data[{{ $outIndex }}][title]"
                                           class="outcome-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           value="{{ $outcome['title'] }}">
                                </div>
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-gray-600">Description</label>
                                    <input type="text" name="outcome_data[{{ $outIndex }}][description]"
                                           class="outcome-description block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           value="{{ $outcome['description'] }}">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Outcomes (comma-separated)</label>
                                    <input type="text" name="outcome_data[{{ $outIndex }}][outcomes_string]"
                                           class="outcome-list block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           value="{{ implode(', ', $outcome['outcomes']) }}" placeholder="Outcome 1, Outcome 2, Outcome 3">
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-outcome" class="mt-2 text-sm text-indigo-600 hover:text-indigo-800">+ Add Outcome Set</button>
                    </div>
                    <input type="hidden" name="outcome" id="outcome-json">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Course
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Auto-generate slug from title
const titleInput = document.getElementById('title');
const slugInput = document.getElementById('slug');

titleInput.addEventListener('blur', function() {
    if (!slugInput.value || slugInput.value === 'web-ecosystems-frontend') {
        let slug = this.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugInput.value = slug;
    }
});

// ========== MODULES MANAGEMENT ==========
document.getElementById('add-module')?.addEventListener('click', function() {
    const container = document.getElementById('modules-list');
    const index = container.children.length;
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2 module-item';
    div.innerHTML = `
        <div class="flex-1">
            <input type="text" name="modules_array[]" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Module ${index + 1}">
        </div>
        <button type="button" class="remove-module text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
    div.querySelector('.remove-module').addEventListener('click', function() {
        div.remove();
    });
});

document.querySelectorAll('.remove-module').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.module-item').remove();
    });
});

// ========== PHASES MANAGEMENT ==========
let phaseCounter = {{ count($phases) }};

function addTaskToModule(moduleDiv, phaseIndex, moduleIndex) {
    const tasksList = moduleDiv.querySelector('.tasks-list');
    const taskDiv = document.createElement('div');
    taskDiv.className = 'flex items-center space-x-2 task-item';
    taskDiv.innerHTML = `
        <input type="text" name="phases_data[${phaseIndex}][modules][${moduleIndex}][tasks][]"
               class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Task name">
        <button type="button" class="remove-task text-red-600 hover:text-red-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    tasksList.appendChild(taskDiv);
    taskDiv.querySelector('.remove-task').addEventListener('click', () => taskDiv.remove());
}

function addModuleToPhase(phaseDiv, phaseIndex, moduleCounter) {
    const modulesContainer = phaseDiv.querySelector('.phase-modules');
    const moduleDiv = document.createElement('div');
    moduleDiv.className = 'module-item p-3 bg-gray-50 rounded-lg';
    moduleDiv.setAttribute('data-module-index', moduleCounter);
    moduleDiv.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-medium text-gray-700">Module ${moduleCounter + 1}</label>
            <button type="button" class="remove-module-from-phase text-red-600 hover:text-red-800 text-xs">Remove Module</button>
        </div>
        <div class="mb-2">
            <label class="block text-xs font-medium text-gray-600">Module Title</label>
            <input type="text" name="phases_data[${phaseIndex}][modules][${moduleCounter}][title]"
                   class="module-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600">Tasks</label>
            <div class="tasks-list space-y-2 mt-1"></div>
            <button type="button" class="add-task mt-2 text-xs text-indigo-600 hover:text-indigo-800">+ Add Task</button>
        </div>
    `;
    modulesContainer.appendChild(moduleDiv);

    // Remove module handler
    moduleDiv.querySelector('.remove-module-from-phase').addEventListener('click', () => moduleDiv.remove());

    // Add task handler
    moduleDiv.querySelector('.add-task').addEventListener('click', () => {
        addTaskToModule(moduleDiv, phaseIndex, moduleCounter);
    });
}

function addPhase() {
    const container = document.getElementById('phases-list');
    const phaseDiv = document.createElement('div');
    phaseDiv.className = 'phase-item mb-6 p-4 border border-gray-200 rounded-lg';
    phaseDiv.setAttribute('data-phase-index', phaseCounter);
    phaseDiv.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h5 class="font-medium text-gray-900">Phase ${phaseCounter + 1}</h5>
            <button type="button" class="remove-phase text-red-600 hover:text-red-800 text-sm">Remove Phase</button>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Phase Title</label>
            <input type="text" name="phases_data[${phaseCounter}][title]" class="phase-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="phase-modules space-y-4"></div>
        <button type="button" class="add-module-to-phase mt-3 text-sm text-indigo-600 hover:text-indigo-800">+ Add Module</button>
    `;
    container.appendChild(phaseDiv);

    let moduleCounter = 0;
    const addModuleBtn = phaseDiv.querySelector('.add-module-to-phase');
    addModuleBtn.addEventListener('click', () => {
        addModuleToPhase(phaseDiv, phaseCounter, moduleCounter);
        moduleCounter++;
    });

    phaseDiv.querySelector('.remove-phase').addEventListener('click', () => phaseDiv.remove());
    phaseCounter++;
}

document.getElementById('add-phase')?.addEventListener('click', addPhase);

// Initialize existing phase buttons
document.querySelectorAll('.phase-item').forEach(phase => {
    const phaseIndex = parseInt(phase.getAttribute('data-phase-index'));
    let moduleCounter = phase.querySelectorAll('.module-item').length;

    phase.querySelectorAll('.add-task').forEach(btn => {
        const moduleDiv = btn.closest('.module-item');
        const moduleIndex = parseInt(moduleDiv.getAttribute('data-module-index'));
        btn.addEventListener('click', () => addTaskToModule(moduleDiv, phaseIndex, moduleIndex));
    });

    phase.querySelectorAll('.remove-module-from-phase').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.module-item').remove());
    });

    const addModuleBtn = phase.querySelector('.add-module-to-phase');
    if (addModuleBtn) {
        addModuleBtn.addEventListener('click', () => {
            addModuleToPhase(phase, phaseIndex, moduleCounter);
            moduleCounter++;
        });
    }

    phase.querySelector('.remove-phase')?.addEventListener('click', () => phase.remove());
});

// ========== CURRICULUM MANAGEMENT ==========
let curriculumCounter = {{ count($curriculum) }};

function addTaskToCurriculum(curriculumDiv, groupIndex, taskCounter) {
    const tasksContainer = curriculumDiv.querySelector('.curriculum-tasks');
    const taskDiv = document.createElement('div');
    taskDiv.className = 'task-detail-item p-3 bg-gray-50 rounded-lg';
    taskDiv.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-medium text-gray-700">Task ${taskCounter + 1}</label>
            <button type="button" class="remove-task-detail text-red-600 hover:text-red-800 text-xs">Remove Task</button>
        </div>
        <div class="grid grid-cols-1 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600">Task Title</label>
                <input type="text" name="curriculum_data[${groupIndex}][tasks][${taskCounter}][title]" class="task-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">Assignment Description</label>
                <textarea name="curriculum_data[${groupIndex}][tasks][${taskCounter}][assignment]" rows="2" class="task-assignment block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">Submission Format</label>
                <input type="text" name="curriculum_data[${groupIndex}][tasks][${taskCounter}][submission]" class="task-submission block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">AI Review Criteria</label>
                <textarea name="curriculum_data[${groupIndex}][tasks][${taskCounter}][ai_review]" rows="2" class="task-ai-review block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
        </div>
    `;
    tasksContainer.appendChild(taskDiv);
    taskDiv.querySelector('.remove-task-detail').addEventListener('click', () => taskDiv.remove());
}

function addCurriculumGroup() {
    const container = document.getElementById('curriculum-list');
    const groupDiv = document.createElement('div');
    groupDiv.className = 'curriculum-item mb-6 p-4 border border-gray-200 rounded-lg';
    groupDiv.setAttribute('data-group-index', curriculumCounter);
    groupDiv.innerHTML = `
        <div class="flex justify-between items-center mb-3">
            <h5 class="font-medium text-gray-900">Task Group ${curriculumCounter + 1}</h5>
            <button type="button" class="remove-curriculum text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium text-gray-700">Group Title</label>
            <input type="text" name="curriculum_data[${curriculumCounter}][title]" class="group-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="curriculum-tasks space-y-4"></div>
        <button type="button" class="add-task-detail mt-3 text-sm text-indigo-600 hover:text-indigo-800">+ Add Task</button>
    `;
    container.appendChild(groupDiv);

    let taskCounter = 0;
    const addTaskBtn = groupDiv.querySelector('.add-task-detail');
    addTaskBtn.addEventListener('click', () => {
        addTaskToCurriculum(groupDiv, curriculumCounter, taskCounter);
        taskCounter++;
    });

    groupDiv.querySelector('.remove-curriculum').addEventListener('click', () => groupDiv.remove());
    curriculumCounter++;
}

document.getElementById('add-curriculum')?.addEventListener('click', addCurriculumGroup);

// Initialize existing curriculum buttons
document.querySelectorAll('.curriculum-item').forEach(group => {
    const groupIndex = parseInt(group.getAttribute('data-group-index'));
    let taskCounter = group.querySelectorAll('.task-detail-item').length;

    group.querySelectorAll('.remove-task-detail').forEach(btn => {
        btn.addEventListener('click', () => btn.closest('.task-detail-item').remove());
    });

    const addTaskBtn = group.querySelector('.add-task-detail');
    if (addTaskBtn) {
        addTaskBtn.addEventListener('click', () => {
            addTaskToCurriculum(group, groupIndex, taskCounter);
            taskCounter++;
        });
    }

    group.querySelector('.remove-curriculum')?.addEventListener('click', () => group.remove());
});

// ========== FEATURES MANAGEMENT ==========
let featureCounter = {{ count($features) }};

document.getElementById('add-feature')?.addEventListener('click', function() {
    const container = document.getElementById('features-list');
    const div = document.createElement('div');
    div.className = 'feature-item p-3 border border-gray-200 rounded-lg';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-medium text-gray-700">Feature ${featureCounter + 1}</label>
            <button type="button" class="remove-feature text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
        <div class="mb-2">
            <label class="block text-xs font-medium text-gray-600">Feature Title</label>
            <input type="text" name="program_overview_features[${featureCounter}][title]" class="feature-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600">Feature Description</label>
            <textarea name="program_overview_features[${featureCounter}][description]" rows="2" class="feature-description block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
        </div>
    `;
    container.appendChild(div);
    div.querySelector('.remove-feature').addEventListener('click', () => div.remove());
    featureCounter++;
});

document.querySelectorAll('.remove-feature').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.feature-item').remove();
    });
});

// ========== STATS MANAGEMENT ==========
let statCounter = {{ count($stats) }};

document.getElementById('add-stat')?.addEventListener('click', function() {
    const container = document.getElementById('stats-list');
    const div = document.createElement('div');
    div.className = 'stat-item p-3 border border-gray-200 rounded-lg';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-medium text-gray-700">Stat ${statCounter + 1}</label>
            <button type="button" class="remove-stat text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600">Value</label>
                <input type="text" name="program_overview_stats[${statCounter}][value]" class="stat-value block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">Label</label>
                <input type="text" name="program_overview_stats[${statCounter}][label]" class="stat-label block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
    `;
    container.appendChild(div);
    div.querySelector('.remove-stat').addEventListener('click', () => div.remove());
    statCounter++;
});

document.querySelectorAll('.remove-stat').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.stat-item').remove();
    });
});

// ========== WHY CHOOSE MANAGEMENT ==========
let whyChooseCounter = {{ count($whyChoose) }};

document.getElementById('add-why-choose')?.addEventListener('click', function() {
    const container = document.getElementById('why-choose-list');
    const div = document.createElement('div');
    div.className = 'why-choose-item p-3 border border-gray-200 rounded-lg';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-medium text-gray-700">Reason ${whyChooseCounter + 1}</label>
            <button type="button" class="remove-why-choose text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
        <div class="mb-2">
            <label class="block text-xs font-medium text-gray-600">Title</label>
            <input type="text" name="why_choose_data[${whyChooseCounter}][title]" class="wc-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600">Description</label>
            <textarea name="why_choose_data[${whyChooseCounter}][description]" rows="2" class="wc-description block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
        </div>
    `;
    container.appendChild(div);
    div.querySelector('.remove-why-choose').addEventListener('click', () => div.remove());
    whyChooseCounter++;
});

document.querySelectorAll('.remove-why-choose').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.why-choose-item').remove();
    });
});

// ========== TESTIMONIALS MANAGEMENT ==========
let testimonialCounter = {{ count($testimonials) }};

document.getElementById('add-testimonial')?.addEventListener('click', function() {
    const container = document.getElementById('testimonials-list');
    const div = document.createElement('div');
    div.className = 'testimonial-item p-3 border border-gray-200 rounded-lg';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-medium text-gray-700">Testimonial ${testimonialCounter + 1}</label>
            <button type="button" class="remove-testimonial text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
        <div class="grid grid-cols-2 gap-3 mb-2">
            <div>
                <label class="block text-xs font-medium text-gray-600">Student Name</label>
                <input type="text" name="testimonials_data[${testimonialCounter}][name]" class="test-name block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600">Role/Title</label>
                <input type="text" name="testimonials_data[${testimonialCounter}][role]" class="test-role block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600">Testimonial Text</label>
            <textarea name="testimonials_data[${testimonialCounter}][text]" rows="2" class="test-text block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
        </div>
    `;
    container.appendChild(div);
    div.querySelector('.remove-testimonial').addEventListener('click', () => div.remove());
    testimonialCounter++;
});

document.querySelectorAll('.remove-testimonial').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.testimonial-item').remove();
    });
});

// ========== FAQ MANAGEMENT ==========
let faqCounter = {{ count($faq) }};

document.getElementById('add-faq')?.addEventListener('click', function() {
    const container = document.getElementById('faq-list');
    const div = document.createElement('div');
    div.className = 'faq-item p-3 border border-gray-200 rounded-lg';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-medium text-gray-700">FAQ ${faqCounter + 1}</label>
            <button type="button" class="remove-faq text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
        <div class="mb-2">
            <label class="block text-xs font-medium text-gray-600">Question</label>
            <input type="text" name="faq_data[${faqCounter}][question]" class="faq-question block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600">Answer</label>
            <textarea name="faq_data[${faqCounter}][answer]" rows="2" class="faq-answer block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
        </div>
    `;
    container.appendChild(div);
    div.querySelector('.remove-faq').addEventListener('click', () => div.remove());
    faqCounter++;
});

document.querySelectorAll('.remove-faq').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.faq-item').remove();
    });
});

// ========== OUTCOME MANAGEMENT ==========
let outcomeCounter = {{ count($outcomes) }};

document.getElementById('add-outcome')?.addEventListener('click', function() {
    const container = document.getElementById('outcome-list');
    const div = document.createElement('div');
    div.className = 'outcome-item p-3 border border-gray-200 rounded-lg';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-medium text-gray-700">Outcome Set ${outcomeCounter + 1}</label>
            <button type="button" class="remove-outcome text-red-600 hover:text-red-800 text-sm">Remove</button>
        </div>
        <div class="mb-2">
            <label class="block text-xs font-medium text-gray-600">Title</label>
            <input type="text" name="outcome_data[${outcomeCounter}][title]" class="outcome-title block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div class="mb-2">
            <label class="block text-xs font-medium text-gray-600">Description</label>
            <input type="text" name="outcome_data[${outcomeCounter}][description]" class="outcome-description block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600">Outcomes (comma-separated)</label>
            <input type="text" name="outcome_data[${outcomeCounter}][outcomes_string]" class="outcome-list block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Outcome 1, Outcome 2, Outcome 3">
        </div>
    `;
    container.appendChild(div);
    div.querySelector('.remove-outcome').addEventListener('click', () => div.remove());
    outcomeCounter++;
});

document.querySelectorAll('.remove-outcome').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.outcome-item').remove();
    });
});

// Build JSON before form submission
document.getElementById('courseForm').addEventListener('submit', function(e) {
    // Build modules JSON
    const modulesArray = Array.from(document.querySelectorAll('input[name="modules_array[]"]')).map(input => input.value).filter(v => v);
    document.getElementById('modules-json').value = JSON.stringify(modulesArray);

    // Build phases JSON - collect all phase data
    const phasesData = [];
    document.querySelectorAll('.phase-item').forEach((phase, idx) => {
        const phaseTitle = phase.querySelector('.phase-title')?.value || '';
        const modules = [];
        phase.querySelectorAll('.module-item').forEach((module, modIdx) => {
            const moduleTitle = module.querySelector('.module-title')?.value || '';
            const tasks = [];
            module.querySelectorAll('.task-item input').forEach(taskInput => {
                if (taskInput.value) tasks.push(taskInput.value);
            });
            if (moduleTitle || tasks.length) {
                modules.push({ title: moduleTitle, tasks: tasks });
            }
        });
        if (phaseTitle || modules.length) {
            phasesData.push({ title: phaseTitle, modules: modules });
        }
    });
    document.getElementById('phases-json').value = JSON.stringify(phasesData);

    // Build curriculum JSON
    const curriculumData = [];
    document.querySelectorAll('.curriculum-item').forEach((group, idx) => {
        const groupTitle = group.querySelector('.group-title')?.value || '';
        const tasks = [];
        group.querySelectorAll('.task-detail-item').forEach((task, taskIdx) => {
            const taskTitle = task.querySelector('.task-title')?.value || '';
            const assignment = task.querySelector('.task-assignment')?.value || '';
            const submission = task.querySelector('.task-submission')?.value || '';
            const aiReview = task.querySelector('.task-ai-review')?.value || '';
            if (taskTitle || assignment) {
                tasks.push({ title: taskTitle, assignment: assignment, submission: submission, ai_review: aiReview });
            }
        });
        if (groupTitle || tasks.length) {
            curriculumData.push({ title: groupTitle, tasks: tasks });
        }
    });
    document.getElementById('curriculum-json').value = JSON.stringify(curriculumData);

    // Build program overview JSON
    const features = [];
    document.querySelectorAll('.feature-item').forEach((feature, idx) => {
        const title = feature.querySelector('.feature-title')?.value || '';
        const description = feature.querySelector('.feature-description')?.value || '';
        if (title || description) {
            features.push({ title: title, description: description });
        }
    });

    const stats = [];
    document.querySelectorAll('.stat-item').forEach((stat, idx) => {
        const value = stat.querySelector('.stat-value')?.value || '';
        const label = stat.querySelector('.stat-label')?.value || '';
        if (value || label) {
            stats.push({ value: value, label: label });
        }
    });

    const ctaButton = document.querySelector('input[name="program_overview_cta_button"]')?.value || '';
    const ctaBatch = document.querySelector('input[name="program_overview_cta_batch"]')?.value || '';

    const programOverview = { features: features, stats: stats, cta: { button_text: ctaButton, batch_info: ctaBatch } };
    document.getElementById('program-overview-json').value = JSON.stringify(programOverview);

    // Build why choose JSON
    const whyChooseData = [];
    document.querySelectorAll('.why-choose-item').forEach((item, idx) => {
        const title = item.querySelector('.wc-title')?.value || '';
        const description = item.querySelector('.wc-description')?.value || '';
        if (title || description) {
            whyChooseData.push({ title: title, description: description });
        }
    });
    document.getElementById('why-choose-json').value = JSON.stringify(whyChooseData);

    // Build testimonials JSON
    const testimonialsData = [];
    document.querySelectorAll('.testimonial-item').forEach((item, idx) => {
        const name = item.querySelector('.test-name')?.value || '';
        const role = item.querySelector('.test-role')?.value || '';
        const text = item.querySelector('.test-text')?.value || '';
        if (name || text) {
            testimonialsData.push({ name: name, role: role, text: text });
        }
    });
    document.getElementById('testimonials-json').value = JSON.stringify(testimonialsData);

    // Build FAQ JSON
    const faqData = [];
    document.querySelectorAll('.faq-item').forEach((item, idx) => {
        const question = item.querySelector('.faq-question')?.value || '';
        const answer = item.querySelector('.faq-answer')?.value || '';
        if (question || answer) {
            faqData.push({ question: question, answer: answer });
        }
    });
    document.getElementById('faq-json').value = JSON.stringify(faqData);

    // Build outcome JSON
    const outcomeData = [];
    document.querySelectorAll('.outcome-item').forEach((item, idx) => {
        const title = item.querySelector('.outcome-title')?.value || '';
        const description = item.querySelector('.outcome-description')?.value || '';
        const outcomesString = item.querySelector('.outcome-list')?.value || '';
        const outcomes = outcomesString.split(',').map(s => s.trim()).filter(s => s);
        if (title || outcomes.length) {
            outcomeData.push({ title: title, description: description, outcomes: outcomes });
        }
    });
    document.getElementById('outcome-json').value = JSON.stringify(outcomeData);
});
</script>
@endpush
@endsection
