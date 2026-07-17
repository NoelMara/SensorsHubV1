<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // Show activities for a class (instructor)
    public function index(Classroom $class)
    {
        $activities = $class->activities()->latest()->get();
        return view('admin.classes.activities.index', compact('class', 'activities'));
    }

    // Show create form
    public function create(Classroom $class)
    {
        return view('admin.classes.activities.create', compact('class'));
    }

    // Store activity
    public function store(Request $request, Classroom $class)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'points' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        $validated['class_id'] = $class->id;
        $validated['is_published'] = $request->has('is_published');

        Activity::create($validated);

        return redirect()->route('admin.classes.activities.index', $class)
            ->with('success', 'Activity created!');
    }

    // Show activity for students
    public function show(Classroom $class, Activity $activity)
    {
        $submission = ActivitySubmission::where('activity_id', $activity->id)
            ->where('user_id', auth()->id())
            ->first();

        return view('user.classes.activities.show', compact('class', 'activity', 'submission'));
    }

    // Student submit
    public function submit(Request $request, Classroom $class, Activity $activity)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        ActivitySubmission::create([
            'activity_id' => $activity->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Submission sent!');
    }

    // View submissions (instructor)
    public function submissions(Classroom $class, Activity $activity)
    {
        $submissions = $activity->submissions()->with('user')->get();
        return view('admin.classes.activities.submissions', compact('class', 'activity', 'submissions'));
    }

    // Grade submission
    public function grade(Request $request, Classroom $class, Activity $activity, ActivitySubmission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:' . $activity->points,
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'status' => 'graded',
        ]);

        return back()->with('success', 'Submission graded!');
    }

    // Delete activity
    public function destroy(Classroom $class, Activity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Activity deleted!');
    }
}