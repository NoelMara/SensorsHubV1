@extends('layouts.app')

@section('title', 'Submissions - ' . $quiz->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('admin.classes.quizzes.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Quizzes
    </a>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Submissions - {{ $quiz->title }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $submissions->count() }} {{ Str::plural('submission', $submissions->count()) }} · {{ $quiz->points }} points · Pass: {{ $quiz->passing_score }}%</p>
    </div>

    @if($submissions->count() > 0)
        <div class="space-y-4">
            @foreach($submissions as $submission)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $submission->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $submission->submitted_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold {{ $submission->correct_answers / max($submission->total_questions, 1) * 100 >= $quiz->passing_score ? 'text-green-600' : 'text-red-600' }}">
                                {{ $submission->correct_answers }}/{{ $submission->total_questions }}
                            </span>
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $submission->correct_answers / max($submission->total_questions, 1) * 100 >= $quiz->passing_score ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">
                                {{ $submission->score }}/{{ $quiz->points }} pts
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Submissions Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Wait for students to take this quiz.</p>
        </div>
    @endif
</div>
@endsection