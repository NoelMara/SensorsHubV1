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
        $modules = $class->modules()->orderBy('order')->get();
        return view('admin.classes.modules.index', compact('class', 'modules'));
    }

    public function create(Classroom $class)
    {
        return view('admin.classes.modules.create', compact('class'));
    }

    public function store(Request $request, Classroom $class)
    {
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
            ])->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $fileName
            )->post($url);

            if ($response->successful()) {
                $validated['file_path'] = env('SUPABASE_URL') . '/storage/v1/object/public/modules/' . $fileName;
                $validated['file_name'] = $file->getClientOriginalName();
            }
        }

        $module = Module::create($validated);

        if ($module->is_published) {
            NotificationHelper::sendToClass(
                $class->id,
                'New Module: ' . $module->title,
                'A new module is now available',
                route('dashboard.classes.modules.show', [$class, $module])
            );
        }

        return redirect()
            ->route('admin.classes.modules.index', $class)
            ->with('success', 'Module created successfully!');
    }

    public function show(Classroom $class, Module $module)
    {
        return view('user.classes.modules.show', compact('class', 'module'));
    }

    public function destroy(Classroom $class, Module $module)
    {
        $module->delete();

        return back()->with('success', 'Module deleted!');
    }

        public function edit(Classroom $class, Module $module)
    {
        return view('admin.classes.modules.edit', compact('class', 'module'));
    }

    public function update(Request $request, Classroom $class, Module $module)
    {
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
            ])->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $fileName
            )->post($url);

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

        public function import(Classroom $class)
    {
        $otherClasses = Classroom::where('instructor_id', auth()->id())
            ->where('id', '!=', $class->id)
            ->get();
        return view('admin.classes.modules.import', compact('class', 'otherClasses'));
    }

    public function copyModules(Request $request, Classroom $class)
    {
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
}