@extends('layouts.app')

@section('title', 'Activities - ' . $class->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Activities - {{ $class->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $activities->total() }} {{ Str::plural('activity', $activities->total()) }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.classes.activities.create', $class) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm whitespace-nowrap">
                    <i class="fas fa-plus mr-1"></i> Add Activity
                </a>
                <a href="{{ route('admin.classes.activities.import', $class) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm whitespace-nowrap">
                    <i class="fas fa-download mr-1"></i> Import
                </a>
            </div>
        </div>
    </div>

    @if($activities->count() > 0)
        <div class="space-y-4">
            @foreach($activities as $index => $activity)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-5">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate mb-1.5" title="{{ $activity->title }}">
                                {{ Str::limit($activity->title, 60) }}
                            </h3>
                            <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                @if($activity->is_published)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Published</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Draft</span>
                                @endif
                                @if($activity->due_date && $activity->due_date->isPast())
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">Overdue</span>
                                @elseif($activity->due_date && $activity->due_date->diffInDays(now()) <= 2)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">Due Soon</span>
                                @endif
                            </div>
                            @if($activity->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1.5 line-clamp-1">{{ Str::limit($activity->description, 120) }}</p>
                            @endif
                            <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-500 dark:text-gray-400 flex-wrap">
                                <span><i class="fas fa-star text-yellow-500 mr-1"></i>{{ $activity->points }} pts</span>
                                @if($activity->due_date)
                                    <span><i class="fas fa-clock mr-1"></i>Due: {{ $activity->due_date->format('M d') }}</span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500"><i class="fas fa-clock mr-1"></i>No deadline</span>
                                @endif
                                <span><i class="fas fa-users mr-1"></i>{{ $activity->submissions->count() }}</span>
                            </div>
                        </div>

                        <div class="flex sm:flex-col items-center gap-0.5 sm:gap-1 flex-shrink-0">
                            <a href="{{ route('admin.classes.activities.show', [$class, $activity]) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition"
                               title="Preview">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.classes.activities.edit', [$class, $activity]) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition"
                               title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <a href="{{ route('admin.classes.activities.submissions', [$class, $activity]) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition"
                               title="Submissions">
                                <i class="fas fa-users text-sm"></i>
                            </a>
                            <form action="{{ route('admin.classes.activities.destroy', [$class, $activity]) }}" method="POST"
                                onsubmit="return confirm('Delete this activity?');">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="p-1.5 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 transition"
                                        title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
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
            <p class="text-gray-500">Add your first activity or import from another class!</p>
        </div>
     @endif

    @if($activities->hasPages())
        <div class="mt-6 mb-8">{{ $activities->links() }}</div>
    @endif
</div>
@endsection