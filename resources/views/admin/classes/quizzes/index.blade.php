@extends('layouts.app')

@section('title', 'Quizzes - ' . $class->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Quizzes - {{ $class->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $quizzes->total() }} {{ Str::plural('quiz', $quizzes->total()) }}</p>
            </div>
            <a href="{{ route('admin.classes.quizzes.create', $class) }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm whitespace-nowrap">
                <i class="fas fa-plus mr-1"></i> Add Quiz
            </a>
        </div>
    </div>

    @if($quizzes->count() > 0)
        <div class="space-y-4">
            @foreach($quizzes as $index => $quiz)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-5">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-500 dark:text-gray-400 mt-0.5">
                            {{ $index + 1 }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white truncate mb-1.5">
                                {{ Str::limit($quiz->title, 60) }}
                            </h3>
                            <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                @if($quiz->is_published)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Published</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">Draft</span>
                                @endif
                                @if($quiz->due_date && $quiz->due_date->isPast())
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">Overdue</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 text-xs sm:text-sm text-gray-500 dark:text-gray-400 flex-wrap">
                                <span><i class="fas fa-star text-yellow-500 mr-1"></i>{{ $quiz->points }} pts</span>
                                <span><i class="fas fa-check-circle mr-1"></i>Pass: {{ $quiz->passing_score }}%</span>
                                <span><i class="fas fa-question-circle mr-1"></i>{{ $quiz->questions->count() }} questions</span>
                                <span><i class="fas fa-users mr-1"></i>{{ $quiz->submissions->count() }} submissions</span>
                                @if($quiz->due_date)
                                    <span><i class="fas fa-clock mr-1"></i>Due: {{ $quiz->due_date->format('M d') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex sm:flex-col items-center gap-0.5 sm:gap-1 flex-shrink-0">
                            <a href="{{ route('admin.classes.quizzes.show', [$class, $quiz]) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition" title="Preview">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.classes.quizzes.edit', [$class, $quiz]) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition" title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <a href="{{ route('admin.classes.quizzes.submissions', [$class, $quiz]) }}" 
                               class="p-1.5 rounded-lg text-gray-500 hover:text-primary hover:bg-primary/10 dark:text-gray-400 dark:hover:text-primary dark:hover:bg-primary/10 transition" title="Submissions">
                                <i class="fas fa-users text-sm"></i>
                            </a>
                            <form action="{{ route('admin.classes.quizzes.destroy', [$class, $quiz]) }}" method="POST"
                                onsubmit="return confirm('Delete this quiz?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 transition" title="Delete">
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
            <i class="fas fa-question-circle text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Quizzes Yet</h3>
            <p class="text-gray-500">Create your first quiz for this class!</p>
        </div>
    @endif

    @if($quizzes->hasPages())
        <div class="mt-6">{{ $quizzes->links() }}</div>
    @endif
</div>
@endsection