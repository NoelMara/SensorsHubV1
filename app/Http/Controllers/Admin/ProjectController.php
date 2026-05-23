<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('sensor')->latest()->paginate(10);
        $stats = [
            'total' => Project::count(),
            'active' => Project::where('is_active', true)->count(),
            'featured' => Project::where('is_featured', true)->count(),
            'advanced' => Project::where('difficulty', 'Advanced')->count(),
        ];

        return view('admin.projects.index', compact('projects', 'stats'));
    }

    public function create()
    {
        $sensors = Sensor::where('is_active', true)->get();
        return view('admin.projects.create', compact('sensors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:Beginner,Intermediate,Advanced',
            'sensor_id' => 'required|exists:sensors,id',
            'components_needed' => 'required|string',
            'instructions' => 'required|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully!');
    }

    public function edit(Project $project)
    {
        $sensors = Sensor::where('is_active', true)->get();
        return view('admin.projects.edit', compact('project', 'sensors'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|in:Beginner,Intermediate,Advanced',
            'sensor_id' => 'required|exists:sensors,id',
            'components_needed' => 'required|string',
            'instructions' => 'required|string',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
