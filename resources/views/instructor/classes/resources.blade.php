@extends('layouts.app')

@section('title', 'Resources - ' . $class->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('instructor.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Class
    </a>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Manage Resources</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $class->name }}</p>
        </div>
    </div>

    {{-- Current Resources --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-8">
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">📚 Recommended Resources ({{ $resources->count() }})</h2>
        @if($resources->count() > 0)
            <div class="space-y-2">
                @foreach($resources as $resource)
                    @php
                        $item = null;
                        if ($resource->resource_type === 'sensor') $item = $sensors->find($resource->resource_id);
                        elseif ($resource->resource_type === 'project') $item = $projects->find($resource->resource_id);
                        elseif ($resource->resource_type === 'video') $item = $videos->find($resource->resource_id);
                    @endphp
                    @if($item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    {{ $resource->resource_type === 'sensor' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                    {{ $resource->resource_type === 'project' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                    {{ $resource->resource_type === 'video' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                    {{ ucfirst($resource->resource_type) }}
                                </span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title ?? $item->name }}</span>
                            </div>
                            <form method="POST" action="{{ route('instructor.classes.resources.destroy', [$class, $resource]) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                    <i class="fas fa-times"></i> Remove
                                </button>
                            </form>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-sm">No resources added yet.</p>
        @endif
    </div>

    {{-- Add Resources --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Sensors --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-microchip text-blue-600 mr-2"></i>Sensors
                <span class="text-xs font-normal text-gray-400 ml-1">({{ $sensors->count() }})</span>
            </h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($sensors as $sensor)
                    <div class="flex items-start gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg group">
                        {{-- Thumbnail --}}
                        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($sensor->image)
                                <img src="{{ Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : asset($sensor->image) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-microchip text-gray-400"></i>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $sensor->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">{{ Str::limit($sensor->description, 50) }}</p>
                            <a href="{{ route('sensors.show', $sensor->slug) }}" target="_blank" class="text-xs text-primary hover:underline inline-flex items-center gap-1 mt-0.5">
                                <i class="fas fa-external-link-alt text-[10px]"></i> View details
                            </a>
                        </div>
                        {{-- Add Button --}}
                        <form method="POST" action="{{ route('instructor.classes.resources.store', $class) }}" class="flex-shrink-0">
                            @csrf
                            <input type="hidden" name="resource_type" value="sensor">
                            <input type="hidden" name="resource_id" value="{{ $sensor->id }}">
                            <button type="submit" class="px-2 py-1 text-xs text-primary border border-primary/30 rounded-md hover:bg-primary/10 transition" title="Add to class">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Projects --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-project-diagram text-green-600 mr-2"></i>Projects
                <span class="text-xs font-normal text-gray-400 ml-1">({{ $projects->count() }})</span>
            </h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($projects as $project)
                    <div class="flex items-start gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg group">
                        {{-- Thumbnail/Icon --}}
                        <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-project-diagram text-green-600 dark:text-green-400"></i>
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $project->title }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $project->difficulty }}</span>
                                @if($project->sensor)
                                    <span class="text-xs text-gray-400">·</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $project->sensor->name }}</span>
                                @endif
                            </div>
                            <a href="{{ route('projects.show', $project->slug) }}" target="_blank" class="text-xs text-primary hover:underline inline-flex items-center gap-1 mt-0.5">
                                <i class="fas fa-external-link-alt text-[10px]"></i> View details
                            </a>
                        </div>
                        {{-- Add Button --}}
                        <form method="POST" action="{{ route('instructor.classes.resources.store', $class) }}" class="flex-shrink-0">
                            @csrf
                            <input type="hidden" name="resource_type" value="project">
                            <input type="hidden" name="resource_id" value="{{ $project->id }}">
                            <button type="submit" class="px-2 py-1 text-xs text-primary border border-primary/30 rounded-md hover:bg-primary/10 transition" title="Add to class">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Videos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-video text-red-600 mr-2"></i>Videos
                <span class="text-xs font-normal text-gray-400 ml-1">({{ $videos->count() }})</span>
            </h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($videos as $video)
                    <div class="flex items-start gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg group">
                        {{-- YouTube Thumbnail --}}
                        <div class="w-12 h-12 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($video->youtube_id)
                                <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-video text-red-600 dark:text-red-400"></i>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $video->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $video->category ?? 'Uncategorized' }}</p>
                            @if($video->youtube_link)
                                <a href="{{ $video->youtube_link }}" target="_blank" class="text-xs text-primary hover:underline inline-flex items-center gap-1 mt-0.5">
                                    <i class="fas fa-external-link-alt text-[10px]"></i> Watch
                                </a>
                            @endif
                        </div>
                        {{-- Add Button --}}
                        <form method="POST" action="{{ route('instructor.classes.resources.store', $class) }}" class="flex-shrink-0">
                            @csrf
                            <input type="hidden" name="resource_type" value="video">
                            <input type="hidden" name="resource_id" value="{{ $video->id }}">
                            <button type="submit" class="px-2 py-1 text-xs text-primary border border-primary/30 rounded-md hover:bg-primary/10 transition" title="Add to class">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection