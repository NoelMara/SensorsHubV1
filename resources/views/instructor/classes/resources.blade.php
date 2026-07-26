@extends('layouts.app')

@section('title', 'Resources - ' . $class->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
            </h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($sensors as $sensor)
                    <form method="POST" action="{{ route('instructor.classes.resources.store', $class) }}" class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                        @csrf
                        <input type="hidden" name="resource_type" value="sensor">
                        <input type="hidden" name="resource_id" value="{{ $sensor->id }}">
                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $sensor->name }}</span>
                        <button type="submit" class="text-primary text-xs hover:underline flex-shrink-0 ml-2">+ Add</button>
                    </form>
                @endforeach
            </div>
        </div>

        {{-- Projects --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-project-diagram text-green-600 mr-2"></i>Projects
            </h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($projects as $project)
                    <form method="POST" action="{{ route('instructor.classes.resources.store', $class) }}" class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                        @csrf
                        <input type="hidden" name="resource_type" value="project">
                        <input type="hidden" name="resource_id" value="{{ $project->id }}">
                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $project->title }}</span>
                        <button type="submit" class="text-primary text-xs hover:underline flex-shrink-0 ml-2">+ Add</button>
                    </form>
                @endforeach
            </div>
        </div>

        {{-- Videos --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-video text-red-600 mr-2"></i>Videos
            </h3>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($videos as $video)
                    <form method="POST" action="{{ route('instructor.classes.resources.store', $class) }}" class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                        @csrf
                        <input type="hidden" name="resource_type" value="video">
                        <input type="hidden" name="resource_id" value="{{ $video->id }}">
                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $video->title }}</span>
                        <button type="submit" class="text-primary text-xs hover:underline flex-shrink-0 ml-2">+ Add</button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection