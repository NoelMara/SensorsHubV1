<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Module;
use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ModuleController extends Controller
{
    public function index(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $modules = $class->modules()->orderBy('order')->paginate(5);
        return view('admin.classes.modules.index', compact('class', 'modules'));
    }

    public function create(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        return view('admin.classes.modules.create', compact('class'));
    }

    public function store(Request $request, Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'is_published' => 'boolean',
        ]);

        $validated['class_id'] = $class->id;
        $validated['order'] = $class->modules()->count() + 1;
        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $url = env('SUPABASE_URL') . '/storage/v1/object/modules/' . $fileName;
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'apikey' => env('SUPABASE_SERVICE_KEY'),
            ])->attach('file', file_get_contents($file->getRealPath()), $fileName)->post($url);
            if ($response->successful()) {
                $validated['file_path'] = env('SUPABASE_URL') . '/storage/v1/object/public/modules/' . $fileName;
                $validated['file_name'] = $file->getClientOriginalName();
            }
        }

        $module = Module::create($validated);

        if ($module->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                '📖 New Module: ' . $module->title,
                'A new module is now available',
                route('dashboard.classes.modules.show', [$class, $module])
            );
        }

        return redirect()->route('admin.classes.modules.index', $class)
            ->with('success', 'Module created successfully!');
    }

    public function show(Classroom $class, Module $module)
    {
        // Allow instructors to preview
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return view('user.classes.modules.show', compact('class', 'module'));
        }
        
        // Students must be enrolled
        $enrolled = $class->students()
            ->where('user_id', auth()->id())
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$enrolled) {
            abort(403);
        }

        return view('user.classes.modules.show', compact('class', 'module'));
    }

    public function edit(Classroom $class, Module $module)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        return view('admin.classes.modules.edit', compact('class', 'module'));
    }

    public function update(Request $request, Classroom $class, Module $module)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $url = env('SUPABASE_URL') . '/storage/v1/object/modules/' . $fileName;
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'apikey' => env('SUPABASE_SERVICE_KEY'),
            ])->attach('file', file_get_contents($file->getRealPath()), $fileName)->post($url);
            if ($response->successful()) {
                $validated['file_path'] = env('SUPABASE_URL') . '/storage/v1/object/public/modules/' . $fileName;
                $validated['file_name'] = $file->getClientOriginalName();
            }
        }

        $module->update($validated);

        if ($module->wasChanged('is_published') && $module->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                'New Module: ' . $module->title,
                'A new module is now available',
                route('dashboard.classes.modules.show', [$class, $module])
            );
        }

        return redirect()->route('admin.classes.modules.index', $class)
            ->with('success', 'Module updated!');
    }

    public function destroy(Classroom $class, Module $module)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $module->delete();
        return back()->with('success', 'Module deleted!');
    }

    public function import(Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $otherClasses = Classroom::where('instructor_id', auth()->id())
            ->where('id', '!=', $class->id)
            ->get();
        return view('admin.classes.modules.import', compact('class', 'otherClasses'));
    }

    public function copyModules(Request $request, Classroom $class)
    {
        if ($class->instructor_id !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'from_class' => 'required|exists:classes,id',
            'modules' => 'required|array',
        ]);

        $sourceClass = Classroom::where('instructor_id', auth()->id())
            ->findOrFail($request->from_class);

        $modules = $sourceClass->modules()->whereIn('id', $request->modules)->get();

        foreach ($modules as $module) {
            Module::create([
                'class_id' => $class->id,
                'title' => $module->title,
                'content' => $module->content,
                'file_path' => $module->file_path,
                'file_name' => $module->file_name,
                'order' => $class->modules()->count() + 1,
                'is_published' => false,
            ]);
        }

        return redirect()->route('admin.classes.modules.index', $class)
            ->with('success', count($modules) . ' modules imported!');
    }

    public function studentIndex(Classroom $class)
    {
        // Allow instructors to view
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            $modules = $class->modules()->where('is_published', true)->orderBy('order')->paginate(10);
            return view('user.classes.modules.index', compact('class', 'modules'));
        }
        
        // Students must be enrolled
        $enrolled = $class->students()
            ->where('user_id', auth()->id())
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$enrolled) {
            abort(403);
        }

        $modules = $class->modules()->where('is_published', true)->orderBy('order')->paginate(10);
        return view('user.classes.modules.index', compact('class', 'modules'));
    }
}