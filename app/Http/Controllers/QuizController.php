<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use App\Models\QuizAnswer;
use App\Models\QuizSubmission;
use App\Models\Classroom;
use App\Helpers\NotificationHelper;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // Instructor: List Quizzes
    public function index(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $quizzes = $class->quizzes()->latest()->paginate(5);
        return view('admin.classes.quizzes.index', compact('class', 'quizzes'));
    }

    // Instructor: Create Form
    public function create(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        return view('admin.classes.quizzes.create', compact('class'));
    }

    // Instructor: Store Quiz
    public function store(Request $request, Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'points' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'due_date' => 'nullable|date',
            'is_published' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.is_correct' => 'nullable|boolean',
        ]);

        $quiz = Quiz::create([
            'class_id' => $class->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'points' => $validated['points'],
            'passing_score' => $validated['passing_score'],
            'due_date' => $validated['due_date'] ?? null,
            'is_published' => $request->has('is_published'),
        ]);

        foreach ($validated['questions'] as $qIndex => $qData) {
            $question = $quiz->questions()->create([
                'question' => $qData['question'],
                'order' => $qIndex + 1,
            ]);

            foreach ($qData['options'] as $oIndex => $oData) {
                $question->options()->create([
                    'option_text' => $oData['text'],
                    'is_correct' => $oData['is_correct'] ?? false,
                    'order' => $oIndex + 1,
                ]);
            }
        }

        ActivityLogHelper::log('created', 'quiz', "created quiz '{$quiz->title}' in '{$class->name}'");

        if ($quiz->is_published) {
            NotificationHelper::sendToClass($class->id, '📝 New Quiz: ' . $quiz->title, $quiz->points . ' points', route('dashboard.classes.quizzes.show', [$class, $quiz]));
        }

        return redirect()->route('admin.classes.quizzes.index', $class)->with('success', 'Quiz created!');
    }

    // Instructor: Edit Form
    public function edit(Classroom $class, Quiz $quiz)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $quiz->load('questions.options');
        return view('admin.classes.quizzes.edit', compact('class', 'quiz'));
    }

    // Instructor: Update Quiz
    public function update(Request $request, Classroom $class, Quiz $quiz)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'points' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'due_date' => 'nullable|date',
            'is_published' => 'boolean',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.options' => 'required|array|min:2',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.is_correct' => 'nullable|boolean',
        ]);

        $quiz->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'points' => $validated['points'],
            'passing_score' => $validated['passing_score'],
            'due_date' => $validated['due_date'] ?? null,
            'is_published' => $request->has('is_published'),
        ]);

        // Notify students when quiz gets published
        if ($quiz->wasChanged('is_published') && $quiz->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                '📝 New Quiz: ' . $quiz->title,
                $quiz->points . ' points',
                route('dashboard.classes.quizzes.show', [$class, $quiz])
            );
        }

        // Delete old questions and options
        $quiz->questions()->delete();

        // Create new ones
        foreach ($validated['questions'] as $qIndex => $qData) {
            $question = $quiz->questions()->create([
                'question' => $qData['question'],
                'order' => $qIndex + 1,
            ]);

            foreach ($qData['options'] as $oIndex => $oData) {
                $question->options()->create([
                    'option_text' => $oData['text'],
                    'is_correct' => $oData['is_correct'] ?? false,
                    'order' => $oIndex + 1,
                ]);
            }
        }
        return redirect()->route('admin.classes.quizzes.index', $class)->with('success', 'Quiz updated!');
    }

    // Instructor: Delete Quiz
    public function destroy(Classroom $class, Quiz $quiz)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $quiz->delete();
        ActivityLogHelper::log('deleted', 'quiz', "deleted quiz '{$quiz->title}'");
        return back()->with('success', 'Quiz deleted!');
    }

    // Student/Instructor: Show Quiz
    public function show(Classroom $class, Quiz $quiz)
    {
        $quiz->load('questions.options');
        $submission = QuizSubmission::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->first();
        return view('user.classes.quizzes.show', compact('class', 'quiz', 'submission'));
    }

    // Student: Submit Quiz
    public function submit(Request $request, Classroom $class, Quiz $quiz)
    {
        if ($quiz->due_date && now()->isAfter($quiz->due_date)) {
            return back()->with('error', 'This quiz is past the due date.');
        }

        $existing = QuizSubmission::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->first();
        if ($existing) return back()->with('error', 'You already submitted this quiz.');

        $answers = $request->input('answers', []);
        $questions = $quiz->questions()->with('options')->get();
        $correctCount = 0;
        $totalQuestions = $questions->count();

        foreach ($answers as $questionId => $optionId) {
            $question = $questions->find($questionId);
            if (!$question) continue;

            $correctOption = $question->options->where('is_correct', true)->first();
            $isCorrect = $correctOption && $correctOption->id == $optionId;

            if ($isCorrect) $correctCount++;

            QuizAnswer::create([
                'quiz_question_id' => $questionId,
                'user_id' => auth()->id(),
                'selected_option_id' => $optionId,
                'is_correct' => $isCorrect,
            ]);
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * $quiz->points) : 0;

        QuizSubmission::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'status' => 'graded',
            'submitted_at' => now(),
        ]);

        $passed = ($correctCount / max($totalQuestions, 1)) * 100 >= $quiz->passing_score;
        $emoji = $passed ? '🎉' : '📚';
        NotificationHelper::send(auth()->id(), "{$emoji} Quiz Result: {$quiz->title}", "{$correctCount}/{$totalQuestions} correct · {$score} points", route('dashboard.classes.quizzes.show', [$class, $quiz]));

        return redirect()->route('dashboard.classes.quizzes.show', [$class, $quiz])->with('success', 'Quiz submitted! ' . $correctCount . '/' . $totalQuestions . ' correct.');
    }

    // Student: List Quizzes
    public function studentIndex(Classroom $class)
    {
        $quizzes = $class->quizzes()->where('is_published', true)->latest()->paginate(10);
        return view('user.classes.quizzes.index', compact('class', 'quizzes'));
    }

    // Instructor: View Submissions
    public function submissions(Classroom $class, Quiz $quiz)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $submissions = $quiz->submissions()->with('user')->latest()->get();
        return view('admin.classes.quizzes.submissions', compact('class', 'quiz', 'submissions'));
    }
    // Instructor: Import Form
    public function import(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $otherClasses = Classroom::where('instructor_id', auth()->id())
            ->where('id', '!=', $class->id)
            ->get();
        return view('admin.classes.quizzes.import', compact('class', 'otherClasses'));
    }

    // Instructor: Copy Quizzes
    public function copyQuizzes(Request $request, Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        
        $request->validate([
            'from_class' => 'required|exists:classes,id',
            'quizzes' => 'required|array',
        ]);

        $sourceClass = Classroom::where('instructor_id', auth()->id())
            ->findOrFail($request->from_class);

        $quizzes = $sourceClass->quizzes()->whereIn('id', $request->quizzes)->with('questions.options')->get();

        foreach ($quizzes as $quiz) {
            $newQuiz = Quiz::create([
                'class_id' => $class->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'instructions' => $quiz->instructions,
                'points' => $quiz->points,
                'passing_score' => $quiz->passing_score,
                'due_date' => null,
                'is_published' => false,
            ]);

            foreach ($quiz->questions as $question) {
                $newQuestion = $newQuiz->questions()->create([
                    'question' => $question->question,
                    'order' => $question->order,
                ]);

                foreach ($question->options as $option) {
                    $newQuestion->options()->create([
                        'option_text' => $option->option_text,
                        'is_correct' => $option->is_correct,
                        'order' => $option->order,
                    ]);
                }
            }
        }

        return redirect()->route('admin.classes.quizzes.index', $class)
            ->with('success', count($quizzes) . ' quizzes imported!');
    }
}