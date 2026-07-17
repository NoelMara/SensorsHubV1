@extends('layouts.app')

@section('title', 'Activities - ' . $class->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Activities - {{ $class->name }}</h1>
            <a href="{{ route('admin.classes.activities.create', $class) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm whitespace-nowrap">
                <i class="fas fa-plus mr-1"></i> Add Activity
            </a>
        </div>
    </div>

    @if($activities->count() > 0)
        <div class="space-y-4">
            @foreach($activities as $activity)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $activity->title }}</h3>
                            <div class="flex items-center gap-3 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <span><i class="fas fa-star text-yellow-500 mr-1"></i> {{ $activity->points }} pts</span>
                                @if($activity->due_date)
                                    <span><i class="fas fa-clock mr-1"></i> Due: {{ $activity->due_date->format('M d, Y h:i A') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($activity->is_published)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Published</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">Draft</span>
                            @endif
                            <a href="{{ route('admin.classes.activities.submissions', [$class, $activity]) }}" class="text-primary hover:text-blue-700 text-sm" title="View Submissions">
                                <i class="fas fa-users"></i>
                            </a>
                            <form action="{{ route('admin.classes.activities.destroy', [$class, $activity]) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-tasks text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Activities Yet</h3>
            <p class="text-gray-500">Add your first activity!</p>
        </div>
    @endif
</div>
@endsection