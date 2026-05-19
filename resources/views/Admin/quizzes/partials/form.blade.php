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

@php
    $questionRows = old('questions');

    if ($questionRows === null && isset($quiz)) {
        $questionRows = $quiz->questions->map(fn ($question) => [
            'question_text' => $question->question_text,
            'option_a' => $question->option_a,
            'option_b' => $question->option_b,
            'option_c' => $question->option_c,
            'option_d' => $question->option_d,
            'correct_option' => $question->correct_option,
            'marks' => $question->marks,
        ])->toArray();
    }

    if (empty($questionRows)) {
        $questionRows = [[
            'question_text' => '',
            'option_a' => '',
            'option_b' => '',
            'option_c' => '',
            'option_d' => '',
            'correct_option' => 'a',
            'marks' => 1,
        ]];
    }
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-5">
        <div>
            <label for="course_id" class="text-sm font-semibold text-gray-700">Course</label>
            <select name="course_id" id="course_id" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                <option value="">Select Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $quiz->course_id ?? null) == $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="title" class="text-sm font-semibold text-gray-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $quiz->title ?? '') }}" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
        </div>

        <div>
            <label for="total_marks" class="text-sm font-semibold text-gray-700">Total Marks</label>
            <input type="number" name="total_marks" id="total_marks" min="1" value="{{ old('total_marks', $quiz->total_marks ?? '') }}" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
        </div>

        <div class="border-t border-gray-100 pt-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-gray-950">Questions</h2>
                    <p class="mt-1 text-xs text-gray-500">Add questions manually, or paste/upload JSON below.</p>
                </div>
                <button type="button" id="add-question" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                    <i class="fi fi-rr-plus"></i>
                    Add Question
                </button>
            </div>

            <div id="questions-wrapper" class="mt-4 space-y-4">
                @foreach($questionRows as $index => $question)
                    <div class="question-row rounded-lg border border-gray-200 bg-gray-50/60 p-4" data-question-row>
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-sm font-semibold text-gray-950">Question <span data-question-number>{{ $loop->iteration }}</span></p>
                            <button type="button" class="remove-question rounded-lg p-2 text-red-600 transition hover:bg-red-50" title="Remove question">
                                <i class="fi fi-rr-trash"></i>
                            </button>
                        </div>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="text-sm font-semibold text-gray-700">Question</label>
                                <textarea name="questions[{{ $index }}][question_text]" rows="3" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">{{ $question['question_text'] ?? '' }}</textarea>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                    <div>
                                        <label class="text-sm font-semibold text-gray-700">Option {{ $label }}</label>
                                        <input type="text" name="questions[{{ $index }}][option_{{ $key }}]" value="{{ $question['option_' . $key] ?? '' }}" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                                    </div>
                                @endforeach
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-semibold text-gray-700">Correct Option</label>
                                    <select name="questions[{{ $index }}][correct_option]" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                                        @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                            <option value="{{ $key }}" @selected(($question['correct_option'] ?? 'a') === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-700">Marks</label>
                                    <input type="number" min="1" name="questions[{{ $index }}][marks]" value="{{ $question['marks'] ?? 1 }}" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-gray-50/60 p-4">
            <h2 class="text-sm font-semibold text-gray-950">JSON Bulk Upload</h2>
            <div class="mt-4 space-y-4">
                <div>
                    <label for="bulk_questions_json" class="text-sm font-semibold text-gray-700">Paste JSON</label>
                    <textarea name="bulk_questions_json" id="bulk_questions_json" rows="8" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 font-mono text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20" placeholder='[{"question_text":"What is HTML?","option_a":"Markup language","option_b":"Database","option_c":"Server","option_d":"Browser","correct_option":"a","marks":1}]'>{{ old('bulk_questions_json') }}</textarea>
                </div>

                <div>
                    <label for="questions_json_file" class="text-sm font-semibold text-gray-700">Upload JSON File</label>
                    <input type="file" name="questions_json_file" id="questions_json_file" accept=".json,application/json,text/plain" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-brand file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.quizzes.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brandLight">
            {{ $buttonText }}
        </button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('questions-wrapper');
        const addButton = document.getElementById('add-question');

        if (!wrapper || !addButton) {
            return;
        }

        const refreshQuestions = () => {
            wrapper.querySelectorAll('[data-question-row]').forEach((row, index) => {
                row.querySelector('[data-question-number]').textContent = index + 1;
                row.querySelectorAll('textarea, input, select').forEach((field) => {
                    field.name = field.name.replace(/questions\[\d+\]/, `questions[${index}]`);
                });
            });
        };

        const newQuestionRow = () => {
            const index = wrapper.querySelectorAll('[data-question-row]').length;
            const row = document.createElement('div');
            row.className = 'question-row rounded-lg border border-gray-200 bg-gray-50/60 p-4';
            row.setAttribute('data-question-row', '');
            row.innerHTML = `
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-semibold text-gray-950">Question <span data-question-number>${index + 1}</span></p>
                    <button type="button" class="remove-question rounded-lg p-2 text-red-600 transition hover:bg-red-50" title="Remove question">
                        <i class="fi fi-rr-trash"></i>
                    </button>
                </div>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Question</label>
                        <textarea name="questions[${index}][question_text]" rows="3" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20"></textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        ${['a', 'b', 'c', 'd'].map((key) => `
                            <div>
                                <label class="text-sm font-semibold text-gray-700">Option ${key.toUpperCase()}</label>
                                <input type="text" name="questions[${index}][option_${key}]" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                            </div>
                        `).join('')}
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Correct Option</label>
                            <select name="questions[${index}][correct_option]" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                                <option value="a">A</option>
                                <option value="b">B</option>
                                <option value="c">C</option>
                                <option value="d">D</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Marks</label>
                            <input type="number" min="1" name="questions[${index}][marks]" value="1" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                        </div>
                    </div>
                </div>
            `;

            return row;
        };

        addButton.addEventListener('click', () => {
            wrapper.appendChild(newQuestionRow());
            refreshQuestions();
        });

        wrapper.addEventListener('click', (event) => {
            const removeButton = event.target.closest('.remove-question');

            if (!removeButton) {
                return;
            }

            removeButton.closest('[data-question-row]').remove();
            refreshQuestions();
        });
    });
</script>
@endpush
