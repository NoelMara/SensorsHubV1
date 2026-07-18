<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivitySubmission;
use App\Models\Classroom;
use App\Helpers\NotificationHelper;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $activities = $class->activities()->latest()->paginate(5);
        return view('admin.classes.activities.index', compact('class', 'activities'));
    }

    public function create(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        return view('admin.classes.activities.create', compact('class'));
    }

    public function store(Request $request, Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
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
        ActivityLogHelper::log('created', 'activity', "created activity '{$activity->title}' in '{$class->name}'");

        if ($activity->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                '📋 New Activity: ' . $activity->title,
                'Due: ' . ($activity->due_date ? $activity->due_date->format('M d, Y') : 'No deadline') . ' | ' . $activity->points . ' points',
                route('dashboard.classes.activities.show', [$class, $activity])
            );
        }

        return redirect()->route('admin.classes.activities.index', $class)
            ->with('success', 'Activity created!');
    }

    public function edit(Classroom $class, Activity $activity)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        return view('admin.classes.activities.edit', compact('class', 'activity'));
    }

    public function update(Request $request, Classroom $class, Activity $activity)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
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

    public function show(Classroom $class, Activity $activity)
    {
        // Allow instructors to preview
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            $submission = ActivitySubmission::where('activity_id', $activity->id)
                ->where('user_id', auth()->id())
                ->first();
            return view('user.classes.activities.show', compact('class', 'activity', 'submission'));
        }
        
        // Students must be enrolled
        $enrolled = $class->students()
            ->where('user_id', auth()->id())
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$enrolled) {
            abort(403);
        }

        $submission = ActivitySubmission::where('activity_id', $activity->id)
            ->where('user_id', auth()->id())
            ->first();

        return view('user.classes.activities.show', compact('class', 'activity', 'submission'));
    }

    public function submit(Request $request, Classroom $class, Activity $activity)
    {
            // Students must be enrolled
        $enrolled = $class->students()
            ->where('user_id', auth()->id())
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$enrolled) {
            abort(403);
        }
        
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

    public function submissions(Classroom $class, Activity $activity)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $submissions = $activity->submissions()->with('user')->get();
        return view('admin.classes.activities.submissions', compact('class', 'activity', 'submissions'));
    }

    public function grade(Request $request, Classroom $class, Activity $activity, ActivitySubmission $submission)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
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
            '✅ Activity Graded: ' . $activity->title,
            'You scored ' . $validated['score'] . '/' . $activity->points,
            route('dashboard.classes.activities.show', [$class, $activity])
        );

        return back()->with('success', 'Submission graded!');
    }

    public function destroy(Classroom $class, Activity $activity)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $activity->delete();
        ActivityLogHelper::log('deleted', 'activity', "deleted activity '{$activity->title}'");
        return back()->with('success', 'Activity deleted!');
    }

    public function import(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $otherClasses = Classroom::where('instructor_id', auth()->id())
            ->where('id', '!=', $class->id)
            ->get();
        return view('admin.classes.activities.import', compact('class', 'otherClasses'));
    }

    public function copyActivities(Request $request, Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
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

    public function studentIndex(Classroom $class)
    {
        // Allow instructors to view
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            $activities = $class->activities()->where('is_published', true)->latest()->paginate(10);
            return view('user.classes.activities.index', compact('class', 'activities'));
        }
        
        // Students must be enrolled
        $enrolled = $class->students()
            ->where('user_id', auth()->id())
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$enrolled) {
            abort(403);
        }

        $activities = $class->activities()->where('is_published', true)->latest()->paginate(10);
        return view('user.classes.activities.index', compact('class', 'activities'));
    }
}