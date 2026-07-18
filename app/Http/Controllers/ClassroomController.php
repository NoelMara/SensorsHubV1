<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\ActivitySubmission;
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

        return redirect()->route('admin.classes.index')
            ->with('success', 'Class created successfully! Code: ' . $validated['code']);
    }

    public function show(Classroom $class)
    {
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
        return redirect()->route('admin.classes.index')
            ->with('success', 'Class deleted successfully!');
    }

    public function join(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $class = Classroom::where('code', $request->code)->first();

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
        
        return view('user.classes.show', compact('class'));
    }

    public function approve(Classroom $class, $userId)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $class->students()->updateExistingPivot($userId, ['status' => 'approved']);
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
        $students = $class->students()->wherePivot('status', 'approved')->get();
        $activities = $class->activities()->where('is_published', true)->get();
        $totalActivities = $activities->count();
        
        $leaderboard = $students->map(function ($student) use ($activities, $totalActivities) {
            $submissions = ActivitySubmission::where('user_id', $student->id)
                ->whereIn('activity_id', $activities->pluck('id'))
                ->get();
            
            $totalPoints = $submissions->sum('score');
            $submitted = $submissions->count();
            $graded = $submissions->whereNotNull('score')->count();
            
            $pending = $activities->filter(function ($activity) use ($student) {
                return !ActivitySubmission::where('activity_id', $activity->id)
                    ->where('user_id', $student->id)
                    ->exists() 
                    && (!$activity->due_date || now()->lessThanOrEqualTo($activity->due_date));
            })->count();
            
            $overdue = $activities->filter(function ($activity) use ($student) {
                return !ActivitySubmission::where('activity_id', $activity->id)
                    ->where('user_id', $student->id)
                    ->exists() 
                    && $activity->due_date && now()->isAfter($activity->due_date);
            })->count();
            
            return [
                'student' => $student,
                'total_points' => $totalPoints,
                'submitted' => $submitted,
                'graded' => $graded,
                'pending' => $pending,
                'overdue' => $overdue,
                'total_activities' => $totalActivities,
            ];
        })->sortByDesc('total_points')->values();
        
        return view('admin.classes.leaderboard', compact('class', 'leaderboard'));
    }
    public function approveAll(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        
        $pendingStudents = $class->students()->wherePivot('status', 'pending')->get();
        
        foreach ($pendingStudents as $student) {
            $class->students()->updateExistingPivot($student->id, ['status' => 'approved']);
        }
        
        return back()->with('success', count($pendingStudents) . ' students approved!');
    }
    
}