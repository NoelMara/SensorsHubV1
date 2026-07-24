@extends('layouts.app')

@section('title', 'Submissions - ' . $assessment->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('instructor.classes.assessments.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Assessments
    </a>

    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Submissions - {{ $assessment->title }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $submissions->count() }} {{ Str::plural('submission', $submissions->count()) }} · {{ $assessment->points }} points</p>
    </div>

    @if($submissions->count() > 0)
        <div class="space-y-4">
            @foreach($submissions as $submission)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $submission->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $submission->submitted_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @if($submission->score !== null)
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                {{ $submission->score }}/{{ $assessment->points }}
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">
                                Pending
                            </span>
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-4">
                            <pre class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono">{{ $submission->content }}</pre>
                        </div>

                        <form method="POST" action="{{ route('instructor.classes.assessments.grade', [$class, $assessment, $submission]) }}" class="flex items-end gap-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Score (max {{ $assessment->points }})</label>
                                <input type="number" name="score" value="{{ $submission->score }}" required min="0" max="{{ $assessment->points }}"
                                    class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Feedback</label>
                                <input type="text" name="feedback" value="{{ $submission->feedback }}" placeholder="Optional feedback..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium whitespace-nowrap">
                                <i class="fas fa-check mr-1"></i> Save
                            </button>
                        </form>
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
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Wait for students to submit their work.</p>
        </div>
    @endif
</div>
@endsection