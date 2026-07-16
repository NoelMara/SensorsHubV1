<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Module;
use Illuminate\Http\Request;

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

            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true],
            ]);
            $result = $cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'resource_type' => 'raw',
                    'public_id' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                ]
            );
            $validated['file_path'] = $result['secure_url'];
            $validated['file_name'] = $file->getClientOriginalName();
        }

        Module::create($validated);

        return redirect()->route('admin.classes.modules.index', $class)
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
}