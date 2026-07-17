@extends('layouts.app')

@section('title', 'Submissions - ' . $activity->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('admin.classes.activities.index', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Activities
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Submissions - {{ $activity->title }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $activity->points }} points</p>
    </div>

    @if($submissions->count() > 0)
        <div class="space-y-6">
            @foreach($submissions as $submission)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $submission->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $submission->submitted_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @if($submission->score !== null)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $submission->score }}/{{ $activity->points }}
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @endif
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                        <pre class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono">{{ $submission->content }}</pre>
                    </div>

                    <form method="POST" action="{{ route('admin.classes.activities.grade', [$class, $activity, $submission]) }}" class="flex items-end gap-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Score (max {{ $activity->points }})</label>
                            <input type="number" name="score" value="{{ $submission->score }}" required min="0" max="{{ $activity->points }}"
                                class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Feedback</label>
                            <input type="text" name="feedback" value="{{ $submission->feedback }}" placeholder="Optional feedback..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition text-sm whitespace-nowrap">
                            <i class="fas fa-check mr-1"></i> Save Grade
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-users text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Submissions Yet</h3>
            <p class="text-gray-500">Wait for students to submit.</p>
        </div>
    @endif
</div>
@endsection