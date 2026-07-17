@extends('layouts.app')

@section('title', $activity->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.classes.activities.index', $class) }}" class="text-primary hover:underline mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Activities
            </a>
        @else
            <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
                <i class="fas fa-arrow-left mr-1"></i> Back to Class
            </a>
        @endif
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">{{ $activity->title }}</h1>
        <div class="flex items-center gap-3 mt-2 text-sm text-gray-500 dark:text-gray-400">
            <span><i class="fas fa-star text-yellow-500 mr-1"></i> {{ $activity->points }} points</span>
            @if($activity->due_date)
                <span><i class="fas fa-clock mr-1"></i> Due: {{ $activity->due_date->format('M d, Y h:i A') }}</span>
            @endif
        </div>
    </div>

    @if($activity->description)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Description</h2>
        <p class="text-gray-700 dark:text-gray-300">{{ $activity->description }}</p>
    </div>
    @endif

    @if($activity->instructions)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Instructions</h2>
        <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $activity->instructions }}</div>
    </div>
    @endif

    @if($submission)
        <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-700 p-6 mb-6">
            <h2 class="text-lg font-bold text-green-800 dark:text-green-200 mb-3">Your Submission</h2>
            <div class="text-gray-700 dark:text-gray-300 whitespace-pre-line mb-3">{{ $submission->content }}</div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Submitted: {{ $submission->submitted_at->format('M d, Y h:i A') }}</p>
            @if($submission->score !== null)
                <div class="mt-3 p-3 bg-white dark:bg-gray-800 rounded-lg">
                    <p class="font-semibold text-gray-900 dark:text-white">Score: {{ $submission->score }}/{{ $activity->points }}</p>
                    @if($submission->feedback)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><strong>Feedback:</strong> {{ $submission->feedback }}</p>
                    @endif
                </div>
            @endif
        </div>
    @elseif(!$activity->due_date || now()->lessThanOrEqualTo($activity->due_date))
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Submit Your Work</h2>
            <form method="POST" action="{{ route('dashboard.classes.activities.submit', [$class, $activity]) }}">
                @csrf
                <textarea name="content" rows="8" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white mb-4"
                    placeholder="Write your answer or paste your code here...">{{ old('content') }}</textarea>
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition font-semibold">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Activity
                </button>
            </form>
        </div>
    @else
        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl border border-yellow-200 dark:border-yellow-700 p-6">
            <h2 class="text-lg font-bold text-yellow-800 dark:text-yellow-200 mb-2">Past Due Date</h2>
            <p class="text-yellow-700 dark:text-yellow-300">This activity is past the due date and no longer accepting submissions.</p>
        </div>
    @endif
</div>
@endsection