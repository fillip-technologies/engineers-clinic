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
                <div>
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
                <div>
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
                            <textarea name="hero_badge" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('hero_badge') border-red-300 @enderror" 
                                      placeholder="Hero badge text">{{ old('hero_badge', 'BUILD MODERN SOFTWARE SYSTEMS THROUGH APPLIED ENGINEERING TASKS') }}</textarea>
                            @error('hero_badge')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="career_path" class="block text-sm font-medium text-gray-700">Career Path</label>
                            <input type="text" name="career_path" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('career_path') border-red-300 @enderror" 
                                   value="{{ old('career_path', 'Architecture, implementation, testing, and deployment workflows') }}" placeholder="e.g., Frontend Developer, Full Stack Engineer">
                            @error('career_path')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Description Section -->
                <div>
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
                
                <!-- Modules Section (Simple Array) -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Course Modules</h4>
                    <div>
                        <label for="modules" class="block text-sm font-medium text-gray-700">Modules (JSON Array)</label>
                        <textarea name="modules" rows="6" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('modules') border-red-300 @enderror" 
                                  placeholder='["Module 1", "Module 2", "Module 3"]'>{{ old('modules', json_encode([
                                    "HTML and CSS Foundations",
                                    "Component Thinking with React",
                                    "Responsive Interface Systems"
                                ], JSON_PRETTY_PRINT)) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter valid JSON array of module names</p>
                        @error('modules')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Phases Section (Complex Structure) -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Learning Phases</h4>
                    <div>
                        <label for="phases" class="block text-sm font-medium text-gray-700">Phases (JSON Array)</label>
                        <textarea name="phases" rows="12" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('phases') border-red-300 @enderror" 
                                  placeholder='Enter phases with modules and tasks'>{{ old('phases', json_encode([
                                    [
                                        "title" => "Phase 1: Pure Foundation",
                                        "modules" => [
                                            [
                                                "title" => "Module 1: UI Planning",
                                                "tasks" => [
                                                    "Digital Persona Card",
                                                    "Landing Page",
                                                    "Cafe Menu UI",
                                                    "Pricing Table",
                                                    "FAQ Accordion",
                                                    "Image Gallery",
                                                    "Agency Landing Page",
                                                    "Timeline UI",
                                                    "Dashboard Layout"
                                                ]
                                            ]
                                        ]
                                    ]
                                ], JSON_PRETTY_PRINT)) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter valid JSON array of phases with modules and tasks</p>
                        @error('phases')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Curriculum Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Curriculum</h4>
                    <div>
                        <label for="curriculum" class="block text-sm font-medium text-gray-700">Curriculum (JSON Array)</label>
                        <textarea name="curriculum" rows="12" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('curriculum') border-red-300 @enderror" 
                                  placeholder='Enter curriculum tasks'>{{ old('curriculum', json_encode([
                                    [
                                        "title" => "Task 1: Frontend Layout Sprint",
                                        "tasks" => [
                                            [
                                                "title" => "Responsive Landing Build",
                                                "assignment" => "Create a structured landing page with reusable sections and responsive behavior.",
                                                "submission" => "Hosted URL or HTML/CSS source.",
                                                "ai_review" => "The AI checks for semantic markup, responsive spacing, and consistent component structure."
                                            ]
                                        ]
                                    ]
                                ], JSON_PRETTY_PRINT)) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter valid JSON array of curriculum tasks</p>
                        @error('curriculum')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Program Overview Section (Object Structure) -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Program Overview</h4>
                    <div>
                        <label for="program_overview" class="block text-sm font-medium text-gray-700">Program Overview (JSON Object)</label>
                        <textarea name="program_overview" rows="20" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('program_overview') border-red-300 @enderror" 
                                  placeholder='Enter program overview object'>{{ old('program_overview', json_encode([
                                    "features" => [
                                        [
                                            "title" => "HTML and CSS Foundations workflow",
                                            "description" => "Learn how HTML and CSS Foundations connects to real software work through guided tasks, examples, and applied review checkpoints."
                                        ],
                                        [
                                            "title" => "Build Ready execution",
                                            "description" => "Move from concepts to practical outputs by working on components, APIs, security checks, and deployable system outputs with clear submission expectations."
                                        ]
                                    ],
                                    "stats" => [
                                        [
                                            "value" => "3 Months",
                                            "label" => "Structured path with focused software practice and review"
                                        ],
                                        [
                                            "value" => "Systems + Code",
                                            "label" => "HTML and CSS Foundations, Component Thinking with React, and applied workflow practice"
                                        ],
                                        [
                                            "value" => "Build Ready",
                                            "label" => "Built to strengthen components, APIs, security checks, and deployable system outputs"
                                        ]
                                    ],
                                    "cta" => [
                                        "button_text" => "Explore Web Ecosystems & Frontend Architecture Program",
                                        "batch_info" => "Enrollment is open for the next Web Ecosystems & Frontend Architecture cohort."
                                    ]
                                ], JSON_PRETTY_PRINT)) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter valid JSON object with features, stats, and CTA</p>
                        @error('program_overview')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Why Choose Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Why Choose This Course</h4>
                    <div>
                        <label for="why_choose" class="block text-sm font-medium text-gray-700">Why Choose (JSON Array)</label>
                        <textarea name="why_choose" rows="10" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('why_choose') border-red-300 @enderror" 
                                  placeholder='Enter reasons to choose this course'>{{ old('why_choose', json_encode([
                                    [
                                        "title" => "Project-First Engineering",
                                        "description" => "Practice components, APIs, security checks, and deployable system outputs through structured assignments tied to realistic learning scenarios."
                                    ],
                                    [
                                        "title" => "Architecture Thinking",
                                        "description" => "Understand the reasoning behind each workflow so your decisions become clearer and more confident."
                                    ],
                                    [
                                        "title" => "Hands-On Build Practice",
                                        "description" => "Create reviewable outputs that help you explain your work during internships, interviews, or project discussions."
                                    ]
                                ], JSON_PRETTY_PRINT)) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter valid JSON array of benefits</p>
                        @error('why_choose')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Testimonials Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Testimonials</h4>
                    <div>
                        <label for="testimonials" class="block text-sm font-medium text-gray-700">Testimonials (JSON Array)</label>
                        <textarea name="testimonials" rows="10" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('testimonials') border-red-300 @enderror" 
                                  placeholder='Enter student testimonials'>{{ old('testimonials', json_encode([
                                    [
                                        "name" => "Aarav Mehta",
                                        "role" => "Tech Learner",
                                        "text" => "The structure helped me understand Web Ecosystems & Frontend Architecture through practical tasks and clear checkpoints."
                                    ],
                                    [
                                        "name" => "Nisha Kapoor",
                                        "role" => "College Student",
                                        "text" => "I liked that every module ended with something concrete to submit, review, and improve."
                                    ]
                                ], JSON_PRETTY_PRINT)) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter valid JSON array of testimonials</p>
                        @error('testimonials')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- FAQ Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Frequently Asked Questions</h4>
                    <div>
                        <label for="faq" class="block text-sm font-medium text-gray-700">FAQ (JSON Array)</label>
                        <textarea name="faq" rows="12" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('faq') border-red-300 @enderror" 
                                  placeholder='Enter FAQ items'>{{ old('faq', json_encode([
                                    [
                                        "question" => "Do I need prior experience before joining?",
                                        "answer" => "No. The track starts with foundations and then moves into applied tasks, so beginners can build confidence step by step."
                                    ],
                                    [
                                        "question" => "Will I build practical projects inside this course?",
                                        "answer" => "Yes. The curriculum is organized around structured assignments, submissions, and reviewable outputs."
                                    ],
                                    [
                                        "question" => "What kind of support will I get during the program?",
                                        "answer" => "You get guided learning structure, task clarity, and review-focused feedback to help you improve your work."
                                    ]
                                ], JSON_PRETTY_PRINT)) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter valid JSON array of questions and answers</p>
                        @error('faq')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Outcome Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2 mb-4">Learning Outcomes</h4>
                    <div>
                        <label for="outcome" class="block text-sm font-medium text-gray-700">Outcome (JSON Array/Object)</label>
                        <textarea name="outcome" rows="8" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('outcome') border-red-300 @enderror" 
                                  placeholder='Enter learning outcomes'>{{ old('outcome', json_encode([
                                    [
                                        "title" => "3 Months Structured Path",
                                        "description" => "Structured path with focused software practice and review",
                                        "outcomes" => ["Systems + Code", "Build Ready"]
                                    ]
                                ], JSON_PRETTY_PRINT)) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Enter valid JSON for learning outcomes</p>
                        @error('outcome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
    
    // JSON validation helper
    function validateJSON(textarea) {
        if (!textarea.value.trim()) return;
        
        try {
            JSON.parse(textarea.value);
            textarea.classList.remove('border-red-300', 'border-red-500');
            textarea.classList.add('border-green-500', 'border-2');
            setTimeout(() => {
                textarea.classList.remove('border-green-500', 'border-2');
                textarea.classList.add('border-gray-300');
            }, 2000);
        } catch(e) {
            textarea.classList.remove('border-green-500');
            textarea.classList.add('border-red-500', 'border-2');
        }
    }
    
    // Add validation to all JSON textareas
    const jsonFields = ['modules', 'phases', 'curriculum', 'program_overview', 'why_choose', 'testimonials', 'faq', 'outcome'];
    jsonFields.forEach(field => {
        const element = document.querySelector(`textarea[name="${field}"]`);
        if (element) {
            element.addEventListener('blur', () => validateJSON(element));
            // Add helpful tooltip
            element.setAttribute('title', 'Must be valid JSON format');
        }
    });
    
    // Preview formatted JSON helper
    function formatJSON(textarea) {
        try {
            const parsed = JSON.parse(textarea.value);
            textarea.value = JSON.stringify(parsed, null, 4);
            validateJSON(textarea);
        } catch(e) {
            alert('Invalid JSON format. Please check your syntax.');
        }
    }
    
    // Add format buttons (optional)
    jsonFields.forEach(field => {
        const textarea = document.querySelector(`textarea[name="${field}"]`);
        if (textarea && textarea.parentElement) {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = 'Format JSON';
            button.className = 'mt-2 text-xs text-indigo-600 hover:text-indigo-900';
            button.onclick = () => formatJSON(textarea);
            textarea.parentElement.appendChild(button);
        }
    });
</script>
@endpush
@endsection