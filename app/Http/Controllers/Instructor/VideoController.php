<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Sensor;
use App\Helpers\ActivityLogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::with('sensor')->latest()->paginate(10);
        $stats = [
            'total' => Video::count(),
            'active' => Video::where('is_active', true)->count(),
            'inactive' => Video::where('is_active', false)->count(),
        ];
        return view('instructor.videos.index', compact('videos', 'stats'));
    }
    public function create()
    {
        $sensors = Sensor::where('is_active', true)->get();
        return view('instructor.videos.create', compact('sensors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:videos,title',
            'youtube_link' => 'required|url',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sensor_id' => 'nullable|exists:sensors,id',
        ]);

        // Extract YouTube video ID
        $youtubeId = $this->extractYouTubeId($validated['youtube_link']);
        $validated['youtube_id'] = $youtubeId;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        $video = Video::create($validated);
        ActivityLogHelper::log('created', 'video', "created a new video '{$video->title}'");

        return redirect()->route('instructor.videos.index')
            ->with('success', 'Video created successfully!');
    }

    public function edit(Video $video)
    {
        $sensors = Sensor::where('is_active', true)->get();
        return view('instructor.videos.edit', compact('video', 'sensors'));
    }

    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:videos,title,' . $video->id,
            'youtube_link' => 'required|url',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sensor_id' => 'nullable|exists:sensors,id',
        ]);

        // Extract YouTube video ID
        $youtubeId = $this->extractYouTubeId($validated['youtube_link']);
        $validated['youtube_id'] = $youtubeId;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        $video->update($validated);

        return redirect()->route('instructor.videos.index')
            ->with('success', 'Video updated successfully!');
    }

    public function destroy(Video $video)
    {
        $video->delete();
        ActivityLogHelper::log('deleted', 'video', "deleted video '{$video->title}'");
        return redirect()->route('instructor.videos.index')
            ->with('success', 'Video deleted successfully!');
    }

    private function extractYouTubeId($url)
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\/?]+)/', $url, $matches);
        return $matches[1] ?? null;
    }
}
