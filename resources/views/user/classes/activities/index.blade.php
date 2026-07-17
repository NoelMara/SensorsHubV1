@extends('layouts.app')

@section('title', 'Activities - ' . $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Activities</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $class->name }}</p>
    </div>

    @if($activities->count() > 0)
        <div class="space-y-4">
            @foreach($activities as $activity)
                @php $sub = $activity->submissions()->where('user_id', auth()->id())->first(); @endphp
                <a href="{{ route('dashboard.classes.activities.show', [$class, $activity]) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6 hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $activity->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $activity->points }} pts 
                                @if($activity->due_date) · Due: {{ $activity->due_date->format('M d, Y') }} @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($sub)
                                @if($sub->score !== null)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $sub->score }}/{{ $activity->points }}</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Submitted</span>
                                @endif
                           @elseif($activity->due_date && now()->isAfter($activity->due_date))
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Overdue</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-tasks text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Activities Yet</h3>
            <p class="text-gray-500">Check back later!</p>
        </div>
    @endif

    @if($activities->hasPages())
        <div class="mt-6">{{ $activities->links() }}</div>
    @endif
</div>
@endsection