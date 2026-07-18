<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::latest()->paginate(10);
        return view('admin.sensors.index', compact('sensors'));
    }

    public function create()
    {
        return view('admin.sensors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'how_it_works' => 'required|string',
            'use_cases' => 'required|string',
            'components_needed' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true],
            ]);
            $result = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath());
            $validated['image'] = $result['secure_url'];
        }

        $sensor = Sensor::create($validated);
        ActivityLogHelper::log('created', 'sensor', "created a new sensor '{$sensor->name}'");

        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor created successfully!');
    }

    public function edit(Sensor $sensor)
    {
        return view('admin.sensors.edit', compact('sensor'));
    }

    public function update(Request $request, Sensor $sensor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'how_it_works' => 'required|string',
            'use_cases' => 'required|string',
            'components_needed' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

         if ($request->hasFile('image')) {
            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
                'url' => ['secure' => true],
            ]);
            $result = $cloudinary->uploadApi()->upload($request->file('image')->getRealPath());
            $validated['image'] = $result['secure_url'];
        } else {
            unset($validated['image']);
        }

        $sensor->update($validated);

        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor updated successfully!');
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        ActivityLogHelper::log('deleted', 'sensor', "deleted sensor '{$sensor->name}'");
        return redirect()->route('admin.sensors.index')
            ->with('success', 'Sensor deleted successfully!');
    }
}
