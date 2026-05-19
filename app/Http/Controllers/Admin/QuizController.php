<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('course')->withCount('questions')->latest()->get();
        return view('Admin.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();
        return view('Admin.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'total_marks' => 'required|integer|min:1',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'nullable|string',
            'questions.*.option_a' => 'nullable|string|max:255',
            'questions.*.option_b' => 'nullable|string|max:255',
            'questions.*.option_c' => 'nullable|string|max:255',
            'questions.*.option_d' => 'nullable|string|max:255',
            'questions.*.correct_option' => 'nullable|string|in:a,b,c,d,A,B,C,D',
            'questions.*.marks' => 'nullable|integer|min:1',
            'bulk_questions_json' => 'nullable|string',
            'questions_json_file' => 'nullable|file|mimes:json,txt|max:2048',
        ]);

        $questions = $this->validatedQuestions($request);

        DB::transaction(function () use ($validated, $questions) {
            $quiz = Quiz::create([
                'course_id' => $validated['course_id'],
                'title' => $validated['title'],
                'total_marks' => $validated['total_marks'],
            ]);

            $quiz->questions()->createMany($questions);
        });

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz created successfully.');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('course', 'quizResults.student', 'questions');
        return view('Admin.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $courses = Course::orderBy('title')->get();
        $quiz->load('questions');
        return view('Admin.quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'total_marks' => 'required|integer|min:1',
            'questions' => 'nullable|array',
            'questions.*.id' => 'nullable|exists:questions,id',
            'questions.*.question_text' => 'nullable|string',
            'questions.*.option_a' => 'nullable|string|max:255',
            'questions.*.option_b' => 'nullable|string|max:255',
            'questions.*.option_c' => 'nullable|string|max:255',
            'questions.*.option_d' => 'nullable|string|max:255',
            'questions.*.correct_option' => 'nullable|string|in:a,b,c,d,A,B,C,D',
            'questions.*.marks' => 'nullable|integer|min:1',
            'bulk_questions_json' => 'nullable|string',
            'questions_json_file' => 'nullable|file|mimes:json,txt|max:2048',
        ]);

        $questions = $this->validatedQuestions($request);

        DB::transaction(function () use ($quiz, $validated, $questions) {
            $quiz->update([
                'course_id' => $validated['course_id'],
                'title' => $validated['title'],
                'total_marks' => $validated['total_marks'],
            ]);

            $quiz->questions()->delete();
            $quiz->questions()->createMany($questions);
        });

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz deleted successfully.');
    }

    private function validatedQuestions(Request $request): array
    {
        $questions = $this->questionsFromRequest($request);

        if ($questions === []) {
            return [];
        }

        $validator = Validator::make(
            ['questions' => $questions],
            [
                'questions' => 'array',
                'questions.*.question_text' => 'required|string',
                'questions.*.option_a' => 'required|string|max:255',
                'questions.*.option_b' => 'required|string|max:255',
                'questions.*.option_c' => 'required|string|max:255',
                'questions.*.option_d' => 'required|string|max:255',
                'questions.*.correct_option' => 'required|string|in:a,b,c,d',
                'questions.*.marks' => 'required|integer|min:1',
            ],
            [],
            [
                'questions.*.question_text' => 'question',
                'questions.*.option_a' => 'option A',
                'questions.*.option_b' => 'option B',
                'questions.*.option_c' => 'option C',
                'questions.*.option_d' => 'option D',
                'questions.*.correct_option' => 'correct option',
                'questions.*.marks' => 'marks',
            ]
        );

        return $validator->validate()['questions'];
    }

    private function questionsFromRequest(Request $request): array
    {
        $questions = [];

        foreach ($request->input('questions', []) as $question) {
            $normalized = $this->normalizeQuestion($question);

            if ($normalized !== null) {
                $questions[] = $normalized;
            }
        }

        foreach ($this->jsonQuestionPayloads($request) as $payload) {
            foreach ($payload as $question) {
                if (! is_array($question)) {
                    throw ValidationException::withMessages([
                        'bulk_questions_json' => 'Each JSON question must be an object.',
                    ]);
                }

                $normalized = $this->normalizeQuestion($question);

                if ($normalized !== null) {
                    $questions[] = $normalized;
                }
            }
        }

        return $questions;
    }

    private function jsonQuestionPayloads(Request $request): array
    {
        $payloads = [];

        if ($request->filled('bulk_questions_json')) {
            $payloads[] = $this->decodeQuestionsJson($request->input('bulk_questions_json'));
        }

        if ($request->hasFile('questions_json_file')) {
            $payloads[] = $this->decodeQuestionsJson(file_get_contents($request->file('questions_json_file')->getRealPath()));
        }

        return $payloads;
    }

    private function decodeQuestionsJson(string $json): array
    {
        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                'bulk_questions_json' => 'Question JSON is invalid: ' . json_last_error_msg(),
            ]);
        }

        $questions = $decoded['questions'] ?? $decoded;

        if (! is_array($questions)) {
            throw ValidationException::withMessages([
                'bulk_questions_json' => 'Question JSON must be an array or an object with a questions array.',
            ]);
        }

        if (array_key_exists('question_text', $questions) || array_key_exists('question', $questions) || array_key_exists('text', $questions)) {
            return [$questions];
        }

        return $questions;
    }

    private function normalizeQuestion(array $question): ?array
    {
        $options = $question['options'] ?? [];

        $correctOption = strtolower(trim((string) ($question['correct_option'] ?? $question['answer'] ?? $question['correct_answer'] ?? '')));
        $correctOption = str_replace(['option_', 'option ', 'option'], '', $correctOption);

        $normalized = [
            'question_text' => trim((string) ($question['question_text'] ?? $question['question'] ?? $question['text'] ?? '')),
            'option_a' => trim((string) ($question['option_a'] ?? $options['a'] ?? $options['A'] ?? '')),
            'option_b' => trim((string) ($question['option_b'] ?? $options['b'] ?? $options['B'] ?? '')),
            'option_c' => trim((string) ($question['option_c'] ?? $options['c'] ?? $options['C'] ?? '')),
            'option_d' => trim((string) ($question['option_d'] ?? $options['d'] ?? $options['D'] ?? '')),
            'correct_option' => $correctOption,
            'marks' => (int) ($question['marks'] ?? 1),
        ];

        $hasContent = collect($normalized)->except('marks')->filter()->isNotEmpty();

        return $hasContent ? $normalized : null;
    }
}
