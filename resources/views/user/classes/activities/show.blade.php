@extends('layouts.app')

@section('title', $activity->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.classes.activities.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-8">
            <i class="fas fa-arrow-left mr-1"></i> Back to Activities
        </a>
    @else
        <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-8">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
    @endif

    {{-- Header Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8 mb-6">
        <div class="flex items-center gap-4 mb-6">
            @if($submission && $submission->score !== null)
                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            @elseif($submission)
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clock text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            @elseif($activity->due_date && now()->isAfter($activity->due_date))
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            @else
                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-circle text-gray-400 text-xs"></i>
                </div>
            @endif
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $activity->title }}</h1>
                <div class="flex items-center gap-3 mt-1 text-sm text-gray-500 dark:text-gray-400">
                    <span><i class="fas fa-star text-yellow-500 mr-1"></i>{{ $activity->points }} points</span>
                    @if($activity->due_date)
                        <span>· Due {{ $activity->due_date->format('M d, Y h:i A') }}</span>
                    @else
                        <span>· No deadline</span>
                    @endif
                </div>
            </div>
        </div>

        @if($activity->description)
            <div class="mb-6">
                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Description</h2>
                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">{{ $activity->description }}</p>
            </div>
        @endif

        @if($activity->instructions)
            <div>
                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Instructions</h2>
                <div class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line">{{ $activity->instructions }}</div>
            </div>
        @endif
    </div>

    {{-- Submission (Students Only) --}}
    @if(!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
        @if($submission)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-green-200 dark:border-green-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-green-200 dark:border-green-700 flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <h2 class="text-sm font-semibold text-green-700 dark:text-green-400">Your Submission</h2>
                    <span class="ml-auto text-xs text-gray-400">{{ $submission->submitted_at->diffForHumans() }}</span>
                </div>
                <div class="p-5 sm:p-6">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-4">
                        <pre class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono">{{ $submission->content }}</pre>
                    </div>
                    @if($submission->score !== null)
                        <div class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-green-700 dark:text-green-300">{{ $submission->score }}/{{ $activity->points }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Graded</p>
                                @if($submission->feedback)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">{{ $submission->feedback }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @elseif(!$activity->due_date || now()->lessThanOrEqualTo($activity->due_date))
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6">
                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Submit Your Work</h2>
                <form method="POST" action="{{ route('dashboard.classes.activities.submit', [$class, $activity]) }}">
                    @csrf
                    <textarea name="content" rows="8" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none mb-4"
                        placeholder="Write your answer or paste your code here...">{{ old('content') }}</textarea>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-paper-plane mr-1.5"></i> Submit
                    </button>
                </form>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-yellow-200 dark:border-yellow-700 p-5 sm:p-6 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-yellow-700 dark:text-yellow-400">Past Due Date</p>
                    <p class="text-xs text-yellow-600 dark:text-yellow-500 mt-0.5">This activity is no longer accepting submissions.</p>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection