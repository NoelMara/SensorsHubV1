<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\AssessmentSubmission;
use App\Models\QuizSubmission;
use App\Models\ClassResource;
use App\Models\User;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classes = Classroom::where('instructor_id', auth()->id())->latest()->paginate(3);
        return view('instructor.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('instructor.classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $validated['instructor_id'] = auth()->id();
        $validated['code'] = Classroom::generateCode();

        Classroom::create($validated);
        ActivityLogHelper::log('created', 'class', "created a new class '{$validated['name']}'");

        return redirect()->route('instructor.classes.index')
            ->with('success', 'Class created successfully! Code: ' . $validated['code']);
    }

    public function show(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        return view('instructor.classes.show', compact('class'));
    }

    public function edit(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        return view('instructor.classes.edit', compact('class'));
    }

    public function update(Request $request, Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $class->update($validated);

        return redirect()->route('instructor.classes.show', $class)
            ->with('success', 'Class updated successfully!');
    }

    public function destroy(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $class->delete();
        ActivityLogHelper::log('deleted', 'class', "deleted class '{$class->name}'");
        return redirect()->route('instructor.classes.index')
            ->with('success', 'Class deleted successfully!');
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $class = Classroom::whereRaw('UPPER(code) = ?', [strtoupper($request->code)])->first();

        if (!$class) {
            return back()->with('error', 'Invalid class code. Please check and try again.');
        }

        if ($class->students()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'You are already enrolled in this class.');
        }

        $class->students()->attach(auth()->id(), ['status' => 'pending']);

        return redirect()->route('dashboard.classes.index')
            ->with('success', 'Join request sent! Wait for your instructor to approve.');
    }

    public function studentClasses()
    {
        $classes = auth()->user()->classes()->wherePivot('status', 'approved')->latest()->get();
        return view('user.classes.index', compact('classes'));
    }

    public function studentShow(Classroom $class)
    {
        $enrollment = $class->students()
            ->where('user_id', auth()->id())
            ->wherePivot('status', 'approved')
            ->first();
            
        if (!$enrollment) {
            return redirect()->route('dashboard.classes.index')
                ->with('error', 'You must be approved to view this class.');
        }
        
        $assessments = $class->assessments()->where('is_published', true)->get();
        $quizzes = $class->quizzes()->where('is_published', true)->get();
        
        $assessmentSubmissions = AssessmentSubmission::where('user_id', auth()->id())
            ->whereIn('assessment_id', $assessments->pluck('id'))
            ->get()
            ->keyBy('assessment_id');
        
        $quizSubmissions = QuizSubmission::where('user_id', auth()->id())
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');
        
        $assessmentPoints = $assessmentSubmissions->sum('score');
        $quizPoints = $quizSubmissions->sum('score');
        $totalAssessmentPoints = $assessments->sum('points');
        $totalQuizPoints = $quizzes->sum('points');
        $totalPoints = $assessmentPoints + $quizPoints;
        $totalPossible = $totalAssessmentPoints + $totalQuizPoints;
        
        return view('user.classes.show', compact(
            'class', 'assessments', 'quizzes', 'assessmentSubmissions',
            'quizSubmissions', 'assessmentPoints', 'quizPoints',
            'totalAssessmentPoints', 'totalQuizPoints', 'totalPoints', 'totalPossible'
        ));
    }

    public function approve(Classroom $class, $userId)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $class->students()->updateExistingPivot($userId, ['status' => 'approved']);
        
        $user = User::find($userId);
        if ($user && $user->role === 'user') {
            $user->update(['role' => 'student']);
        }
        
        return back()->with('success', 'Student approved!');
    }

    public function reject(Classroom $class, $userId)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $class->students()->detach($userId);
        return back()->with('success', 'Student removed!');
    }

    public function leaderboard(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $students = $class->students()->wherePivot('status', 'approved')->get();
        $assessments = $class->assessments()->where('is_published', true)->get();
        $quizzes = $class->quizzes()->where('is_published', true)->get();
        $totalAssessments = $assessments->count();
        $totalQuizzes = $quizzes->count();
        
        $leaderboard = $students->map(function ($student) use ($assessments, $quizzes, $totalAssessments, $totalQuizzes) {
            $submissions = AssessmentSubmission::where('user_id', $student->id)
                ->whereIn('assessment_id', $assessments->pluck('id'))->get();
            $quizSubmissions = QuizSubmission::where('user_id', $student->id)
                ->whereIn('quiz_id', $quizzes->pluck('id'))->get();
            
            $assessmentPoints = $submissions->sum('score');
            $quizPoints = $quizSubmissions->sum('score');
            $totalPoints = $assessmentPoints + $quizPoints;
            
            $pending = $assessments->filter(function ($assessment) use ($student) {
                return !AssessmentSubmission::where('assessment_id', $assessment->id)
                    ->where('user_id', $student->id)->exists() 
                    && (!$assessment->due_date || now()->lessThanOrEqualTo($assessment->due_date));
            })->count();
            $pending += $quizzes->filter(function ($quiz) use ($student) {
                return !QuizSubmission::where('quiz_id', $quiz->id)
                    ->where('user_id', $student->id)->exists() 
                    && (!$quiz->due_date || now()->lessThanOrEqualTo($quiz->due_date));
            })->count();
            
            $overdue = $assessments->filter(function ($assessment) use ($student) {
                return !AssessmentSubmission::where('assessment_id', $assessment->id)
                    ->where('user_id', $student->id)->exists() 
                    && $assessment->due_date && now()->isAfter($assessment->due_date);
            })->count();
            $overdue += $quizzes->filter(function ($quiz) use ($student) {
                return !QuizSubmission::where('quiz_id', $quiz->id)
                    ->where('user_id', $student->id)->exists() 
                    && $quiz->due_date && now()->isAfter($quiz->due_date);
            })->count();
            
            return [
                'student' => $student, 'total_points' => $totalPoints,
                'graded_assessments' => $submissions->whereNotNull('score')->count(),
                'total_assessments' => $totalAssessments,
                'graded_quizzes' => $quizSubmissions->count(),
                'total_quizzes' => $totalQuizzes, 'pending' => $pending, 'overdue' => $overdue,
            ];
        })->sortByDesc('total_points')->values();
        
        return view('instructor.classes.leaderboard', compact('class', 'leaderboard'));
    }

    public function analytics(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }

        $cacheKey = "class_analytics_{$class->id}";

        return cache()->remember($cacheKey, 900, function () use ($class) {
            $students = $class->students()->wherePivot('status', 'approved')->get();
            $studentCount = $students->count();
            $assessments = $class->assessments()->where('is_published', true)->get();
            $quizzes = $class->quizzes()->where('is_published', true)->get();
            $assessmentCount = $assessments->count();
            $quizCount = $quizzes->count();
            $totalAssessmentPoints = $assessments->sum('points');
            $totalQuizPoints = $quizzes->sum('points');
            $allAssessmentScores = AssessmentSubmission::whereIn('assessment_id', $assessments->pluck('id'))->whereNotNull('score')->sum('score');
            $allQuizScores = QuizSubmission::whereIn('quiz_id', $quizzes->pluck('id'))->whereNotNull('score')->sum('score');
            $assessmentAvg = $totalAssessmentPoints > 0 ? round(($allAssessmentScores / $totalAssessmentPoints) * 100, 1) : 0;
            $quizAvg = $totalQuizPoints > 0 ? round(($allQuizScores / $totalQuizPoints) * 100, 1) : 0;

            $assessmentBreakdown = [];
            foreach ($assessments as $assessment) {
                $submissions = $assessment->submissions()->whereNotNull('score')->get();
                $submittedCount = $submissions->count();
                $avg = $submittedCount > 0 ? round($submissions->avg('score') / $assessment->points * 100, 1) : 0;
                $assessmentBreakdown[] = ['title' => $assessment->title, 'average' => $avg, 'submitted' => $submittedCount, 'total' => $studentCount, 'submission_rate' => $studentCount > 0 ? round(($submittedCount / $studentCount) * 100) : 0];
            }

            $quizBreakdown = [];
            foreach ($quizzes as $quiz) {
                $submissions = $quiz->submissions()->whereNotNull('score')->get();
                $submittedCount = $submissions->count();
                $avg = $submittedCount > 0 ? round($submissions->avg('score') / $quiz->points * 100, 1) : 0;
                $quizBreakdown[] = ['title' => $quiz->title, 'average' => $avg, 'submitted' => $submittedCount, 'total' => $studentCount, 'submission_rate' => $studentCount > 0 ? round(($submittedCount / $studentCount) * 100) : 0];
            }

            $submissionTimeline = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $submissionTimeline[] = ['date' => $date, 'assessments' => AssessmentSubmission::whereIn('assessment_id', $assessments->pluck('id'))->whereDate('submitted_at', $date)->count(), 'quizzes' => QuizSubmission::whereIn('quiz_id', $quizzes->pluck('id'))->whereDate('submitted_at', $date)->count()];
            }

            $studentPerformance = [];
            foreach ($students as $student) {
                $sa = AssessmentSubmission::where('user_id', $student->id)->whereIn('assessment_id', $assessments->pluck('id'))->whereNotNull('score')->sum('score');
                $sq = QuizSubmission::where('user_id', $student->id)->whereIn('quiz_id', $quizzes->pluck('id'))->whereNotNull('score')->sum('score');
                $studentPerformance[] = ['name' => $student->name, 'assessment_avg' => $totalAssessmentPoints > 0 ? round(($sa / $totalAssessmentPoints) * 100, 1) : null, 'quiz_avg' => $totalQuizPoints > 0 ? round(($sq / $totalQuizPoints) * 100, 1) : null, 'overall' => ($totalAssessmentPoints + $totalQuizPoints) > 0 ? round((($sa + $sq) / ($totalAssessmentPoints + $totalQuizPoints)) * 100, 1) : null];
            }

            return view('instructor.classes.analytics', compact('class', 'studentCount', 'assessmentCount', 'quizCount', 'assessmentAvg', 'quizAvg', 'assessmentBreakdown', 'quizBreakdown', 'submissionTimeline', 'studentPerformance'));
        });
    }

    public function resources(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $resources = $class->resources()->get();
        $sensors = \App\Models\Sensor::where('is_active', true)->get();
        $projects = \App\Models\Project::where('is_active', true)->get();
        $videos = \App\Models\Video::where('is_active', true)->get();
        return view('instructor.classes.resources', compact('class', 'resources', 'sensors', 'projects', 'videos'));
    }

    public function storeResource(Request $request, Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $validated = $request->validate(['resource_type' => 'required|in:sensor,project,video', 'resource_id' => 'required|integer']);
        $exists = $class->resources()->where('resource_type', $validated['resource_type'])->where('resource_id', $validated['resource_id'])->exists();
        if (!$exists) {
            $class->resources()->create(['resource_type' => $validated['resource_type'], 'resource_id' => $validated['resource_id']]);
        }
        return back()->with('success', 'Resource added!');
    }

    public function destroyResource(Classroom $class, ClassResource $resource)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $resource->delete();
        return back()->with('success', 'Resource removed.');
    }

    public function approveAll(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) abort(403);
        $pendingStudents = $class->students()->wherePivot('status', 'pending')->get();
        foreach ($pendingStudents as $student) {
            $class->students()->updateExistingPivot($student->id, ['status' => 'approved']);
            if ($student->role === 'user') $student->update(['role' => 'student']);
        }
        return back()->with('success', count($pendingStudents) . ' students approved!');
    }
}