<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Announcement;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // Admin: list all announcements for a class
    public function index(Classroom $class)
    {
        $announcements = $class->announcements()->latest()->paginate(10);
        return view('admin.classes.announcements.index', compact('class', 'announcements'));
    }

    // Admin: show create form
    public function create(Classroom $class)
    {
        return view('admin.classes.announcements.create', compact('class'));
    }

    // Admin: store
    public function store(Request $request, Classroom $class)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $validated['class_id'] = $class->id;
        $validated['is_published'] = $request->has('is_published');

        $announcement = Announcement::create($validated);

        if ($announcement->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                '📢 ' . $announcement->title,
                \Str::limit($announcement->content, 100),
                route('dashboard.classes.announcements.index', $class)
            );
        }

        return redirect()
            ->route('admin.classes.announcements.index', $class)
            ->with('success', 'Announcement posted!');
    }

    // Admin: show edit form
    public function edit(Classroom $class, Announcement $announcement)
    {
        return view('admin.classes.announcements.edit', compact('class', 'announcement'));
    }

    // Admin: update
    public function update(Request $request, Classroom $class, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');
        $announcement->update($validated);

        if ($announcement->wasChanged('is_published') && $announcement->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                '📢 ' . $announcement->title,
                \Str::limit($announcement->content, 100),
                route('dashboard.classes.announcements.index', $class)
            );
        }

        return redirect()
            ->route('admin.classes.announcements.index', $class)
            ->with('success', 'Announcement updated!');
    }

    // Admin: delete
    public function destroy(Classroom $class, Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted!');
    }

    // Student: view announcements
    public function studentIndex(Classroom $class)
    {
        $announcements = $class->announcements()
            ->where('is_published', true)
            ->latest()
            ->paginate(10);
        return view('user.classes.announcements.index', compact('class', 'announcements'));
    }
}