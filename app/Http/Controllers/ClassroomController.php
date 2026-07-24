<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\AssessmentSubmission;
use App\Models\QuizSubmission;
use App\Models\User;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classes = Classroom::where('instructor_id', auth()->id())->latest()->paginate(3);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
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

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully! Code: ' . $validated['code']);
    }

    public function show(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        return view('admin.classes.show', compact('class'));
    }

    public function edit(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        return view('admin.classes.edit', compact('class'));
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

        return redirect()->route('admin.classes.show', $class)
            ->with('success', 'Class updated successfully!');
    }

    public function destroy(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $class->delete();
        ActivityLogHelper::log('deleted', 'class', "deleted class '{$class->name}'");
        return redirect()->route('admin.classes.index')
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
        
        // Progress data
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
            'class',
            'assessments',
            'quizzes',
            'assessmentSubmissions',
            'quizSubmissions',
            'assessmentPoints',
            'quizPoints',
            'totalAssessmentPoints',
            'totalQuizPoints',
            'totalPoints',
            'totalPossible'
        ));
    }

    public function approve(Classroom $class, $userId)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $class->students()->updateExistingPivot($userId, ['status' => 'approved']);
        
        // Upgrade user to student
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
                ->whereIn('assessment_id', $assessments->pluck('id'))
                ->get();
            
            $quizSubmissions = QuizSubmission::where('user_id', $student->id)
                ->whereIn('quiz_id', $quizzes->pluck('id'))
                ->get();
            
            $assessmentPoints = $submissions->sum('score');
            $quizPoints = $quizSubmissions->sum('score');
            $totalPoints = $assessmentPoints + $quizPoints;
            
            $submitted = $submissions->count() + $quizSubmissions->count();
            $graded = $submissions->whereNotNull('score')->count() + $quizSubmissions->count();
            
            $pending = $assessments->filter(function ($assessment) use ($student) {
                return !AssessmentSubmission::where('assessment_id', $assessment->id)
                    ->where('user_id', $student->id)
                    ->exists() 
                    && (!$assessment->due_date || now()->lessThanOrEqualTo($assessment->due_date));
            })->count();
            
            $pending += $quizzes->filter(function ($quiz) use ($student) {
                return !QuizSubmission::where('quiz_id', $quiz->id)
                    ->where('user_id', $student->id)
                    ->exists() 
                    && (!$quiz->due_date || now()->lessThanOrEqualTo($quiz->due_date));
            })->count();
            
            $overdue = $assessments->filter(function ($assessment) use ($student) {
                return !AssessmentSubmission::where('assessment_id', $assessment->id)
                    ->where('user_id', $student->id)
                    ->exists() 
                    && $assessment->due_date && now()->isAfter($assessment->due_date);
            })->count();
            
            $overdue += $quizzes->filter(function ($quiz) use ($student) {
                return !QuizSubmission::where('quiz_id', $quiz->id)
                    ->where('user_id', $student->id)
                    ->exists() 
                    && $quiz->due_date && now()->isAfter($quiz->due_date);
            })->count();
            
            return [
                'student' => $student,
                'total_points' => $totalPoints,
                'graded_assessments' => $submissions->whereNotNull('score')->count(),
                'total_assessments' => $totalAssessments,
                'graded_quizzes' => $quizSubmissions->count(),
                'total_quizzes' => $totalQuizzes,
                'pending' => $pending,
                'overdue' => $overdue,
            ];
        })->sortByDesc('total_points')->values();
        
        return view('admin.classes.leaderboard', compact('class', 'leaderboard'));
    }

    public function analytics(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }

        $students = $class->students()->wherePivot('status', 'approved')->get();
        $studentCount = $students->count();

        $assessments = $class->assessments()->where('is_published', true)->get();
        $quizzes = $class->quizzes()->where('is_published', true)->get();

        $assessmentCount = $assessments->count();
        $quizCount = $quizzes->count();

        // Weighted averages (total earned / total possible * 100)
        $totalAssessmentPoints = $assessments->sum('points');
        $totalQuizPoints = $quizzes->sum('points');

        $allAssessmentScores = AssessmentSubmission::whereIn('assessment_id', $assessments->pluck('id'))
            ->whereNotNull('score')->sum('score');
        $allQuizScores = QuizSubmission::whereIn('quiz_id', $quizzes->pluck('id'))
            ->whereNotNull('score')->sum('score');

        $assessmentAvg = $totalAssessmentPoints > 0 ? round(($allAssessmentScores / $totalAssessmentPoints) * 100, 1) : 0;
        $quizAvg = $totalQuizPoints > 0 ? round(($allQuizScores / $totalQuizPoints) * 100, 1) : 0;

        // Assessment Breakdown
        $assessmentBreakdown = [];
        foreach ($assessments as $assessment) {
            $submissions = $assessment->submissions()->whereNotNull('score')->get();
            $submittedCount = $submissions->count();
            $avg = $submittedCount > 0 ? round($submissions->avg('score') / $assessment->points * 100, 1) : 0;

            $assessmentBreakdown[] = [
                'title' => $assessment->title,
                'average' => $avg,
                'submitted' => $submittedCount,
                'total' => $studentCount,
                'submission_rate' => $studentCount > 0 ? round(($submittedCount / $studentCount) * 100) : 0,
            ];
        }

        // Quiz Breakdown
        $quizBreakdown = [];
        foreach ($quizzes as $quiz) {
            $submissions = $quiz->submissions()->whereNotNull('score')->get();
            $submittedCount = $submissions->count();
            $avg = $submittedCount > 0 ? round($submissions->avg('score') / $quiz->points * 100, 1) : 0;

            $quizBreakdown[] = [
                'title' => $quiz->title,
                'average' => $avg,
                'submitted' => $submittedCount,
                'total' => $studentCount,
                'submission_rate' => $studentCount > 0 ? round(($submittedCount / $studentCount) * 100) : 0,
            ];
        }

        // Submission timeline - split by type
        $submissionTimeline = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $assessmentCount = AssessmentSubmission::whereIn('assessment_id', $assessments->pluck('id'))
                ->whereDate('submitted_at', $date)
                ->count();
            $quizCount = QuizSubmission::whereIn('quiz_id', $quizzes->pluck('id'))
                ->whereDate('submitted_at', $date)
                ->count();

            $submissionTimeline[] = [
                'date' => $date,
                'assessments' => $assessmentCount,
                'quizzes' => $quizCount,
            ];
        }

        // Student Performance - weighted like student view
        $studentPerformance = [];
        foreach ($students as $student) {
            $studentAssessmentScore = AssessmentSubmission::where('user_id', $student->id)
                ->whereIn('assessment_id', $assessments->pluck('id'))
                ->whereNotNull('score')->sum('score');
            $studentAssessmentAvg = $totalAssessmentPoints > 0 ? round(($studentAssessmentScore / $totalAssessmentPoints) * 100, 1) : null;

            $studentQuizScore = QuizSubmission::where('user_id', $student->id)
                ->whereIn('quiz_id', $quizzes->pluck('id'))
                ->whereNotNull('score')->sum('score');
            $studentQuizAvg = $totalQuizPoints > 0 ? round(($studentQuizScore / $totalQuizPoints) * 100, 1) : null;

            $totalEarned = $studentAssessmentScore + $studentQuizScore;
            $totalPossible = $totalAssessmentPoints + $totalQuizPoints;
            $overall = $totalPossible > 0 ? round(($totalEarned / $totalPossible) * 100, 1) : null;

            $studentPerformance[] = [
                'name' => $student->name,
                'assessment_avg' => $studentAssessmentAvg,
                'quiz_avg' => $studentQuizAvg,
                'overall' => $overall,
            ];
        }

        return view('admin.classes.analytics', compact(
            'class',
            'studentCount',
            'assessmentCount',
            'quizCount',
            'assessmentAvg',
            'quizAvg',
            'assessmentBreakdown',
            'quizBreakdown',
            'submissionTimeline',
            'studentPerformance'
        ));
    }

    public function approveAll(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        
        $pendingStudents = $class->students()->wherePivot('status', 'pending')->get();
        
        foreach ($pendingStudents as $student) {
            $class->students()->updateExistingPivot($student->id, ['status' => 'approved']);
            
            // Upgrade user to student
            if ($student->role === 'user') {
                $student->update(['role' => 'student']);
            }
        }
        
        return back()->with('success', count($pendingStudents) . ' students approved!');
    }
}