<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\Classroom;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // Show activities for a class (instructor)
    public function index(Classroom $class)
    {
        $activities = $class->activities()->latest()->paginate(5);
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

        $activity = Activity::create($validated);

        // Send notification if published
        if ($activity->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                'New Activity: ' . $activity->title,
                'Due: ' . ($activity->due_date ? $activity->due_date->format('M d, Y') : 'No deadline') . ' | ' . $activity->points . ' points',
                route('dashboard.classes.activities.show', [$class, $activity])
            );
        }

        return redirect()->route('admin.classes.activities.index', $class)
            ->with('success', 'Activity created!');
    }

    // Show edit form
    public function edit(Classroom $class, Activity $activity)
    {
        return view('admin.classes.activities.edit', compact('class', 'activity'));
    }

    // Update activity
    public function update(Request $request, Classroom $class, Activity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'points' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
       $activity->update($validated);

        // Send notification if newly published
        if ($activity->wasChanged('is_published') && $activity->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                'New Activity: ' . $activity->title,
                'Due: ' . ($activity->due_date ? $activity->due_date->format('M d, Y') : 'No deadline') . ' | ' . $activity->points . ' points',
                route('dashboard.classes.activities.show', [$class, $activity])
            );
        }

        return redirect()->route('admin.classes.activities.index', $class)
            ->with('success', 'Activity updated!');
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
        // Check if past due date
        if ($activity->due_date && now()->isAfter($activity->due_date)) {
            return back()->with('error', 'This activity is past the due date.');
        }

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

        NotificationHelper::send(
            $submission->user_id,
            'Activity Graded: ' . $activity->title,
            'You scored ' . $validated['score'] . '/' . $activity->points,
            route('dashboard.classes.activities.show', [$class, $activity])
        );

        return back()->with('success', 'Submission graded!');
    }

    // Delete activity
    public function destroy(Classroom $class, Activity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Activity deleted!');
    }

    public function import(Classroom $class)
{
    $otherClasses = Classroom::where('instructor_id', auth()->id())
        ->where('id', '!=', $class->id)
        ->get();
    return view('admin.classes.activities.import', compact('class', 'otherClasses'));
}

public function copyActivities(Request $request, Classroom $class)
{
    $request->validate([
        'from_class' => 'required|exists:classes,id',
        'activities' => 'required|array',
    ]);

    $sourceClass = Classroom::where('instructor_id', auth()->id())
        ->findOrFail($request->from_class);

    $activities = $sourceClass->activities()->whereIn('id', $request->activities)->get();

    foreach ($activities as $activity) {
        Activity::create([
            'class_id' => $class->id,
            'title' => $activity->title,
            'description' => $activity->description,
            'instructions' => $activity->instructions,
            'points' => $activity->points,
            'due_date' => $activity->due_date,
            'is_published' => false,
        ]);
    }

    return redirect()->route('admin.classes.activities.index', $class)
        ->with('success', count($activities) . ' activities imported!');
}
}