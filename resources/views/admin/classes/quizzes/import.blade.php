@extends('layouts.app')

@section('title', 'Import Quizzes - ' . $class->name)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('admin.classes.quizzes.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Quizzes
    </a>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Import Quizzes</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Select quizzes to copy to {{ $class->name }}.</p>
    </div>

    @if($otherClasses->count() > 0)
        @foreach($otherClasses as $otherClass)
            @if($otherClass->quizzes()->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white">{{ $otherClass->name }}</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $otherClass->quizzes()->count() }} {{ Str::plural('quiz', $otherClass->quizzes()->count()) }}</p>
                </div>
                <form method="POST" action="{{ route('admin.classes.quizzes.copy', $class) }}">
                    @csrf
                    <input type="hidden" name="from_class" value="{{ $otherClass->id }}">
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($otherClass->quizzes as $quiz)
                            <label class="flex items-center gap-3 px-5 py-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <input type="checkbox" name="quizzes[]" value="{{ $quiz->id }}" checked
                                    class="h-4 w-4 text-primary rounded border-gray-300 flex-shrink-0">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $quiz->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $quiz->points }} pts · {{ $quiz->questions->count() }} questions · Pass: {{ $quiz->passing_score }}%</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="w-full px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium">
                            <i class="fas fa-download mr-1.5"></i> Import Selected
                        </button>
                    </div>
                </form>
            </div>
            @endif
        @endforeach
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-copy text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Other Classes</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">You don't have any other classes with quizzes to import from.</p>
        </div>
    @endif
</div>
@endsection