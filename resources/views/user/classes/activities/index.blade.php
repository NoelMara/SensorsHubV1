@extends('layouts.app')

@section('title', 'Activities - ' . $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.classes.activities.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
            <i class="fas fa-arrow-left mr-1"></i> Back to Activities
        </a>
    @else
        <a href="{{ route('dashboard.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
    @endif

    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-2">Activities</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">{{ $class->name }} · {{ $activities->total() }} {{ Str::plural('activity', $activities->total()) }}</p>

    @if($activities->count() > 0)
        <div class="space-y-3">
            @foreach($activities as $activity)
                @php $sub = $activity->submissions()->where('user_id', auth()->id())->first(); @endphp
                <a href="{{ route('dashboard.classes.activities.show', [$class, $activity]) }}" 
                   class="flex items-center gap-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-5 hover:border-primary/30 dark:hover:border-primary/30 hover:shadow-md transition group">
                    
                    <div class="flex-shrink-0">
                        @if($sub && $sub->score !== null)
                            <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                            </div>
                        @elseif($sub)
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <i class="fas fa-clock text-blue-600 dark:text-blue-400"></i>
                            </div>
                        @elseif($activity->due_date && now()->isAfter($activity->due_date))
                            <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-circle text-gray-400 text-xs"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $activity->title }}</h3>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500 dark:text-gray-400">
                            <span><i class="fas fa-star text-yellow-500 mr-0.5"></i>{{ $activity->points }} pts</span>
                            @if($activity->due_date)
                                <span>{{ $activity->due_date->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>

                    @if($sub && $sub->score !== null)
                        <span class="text-sm font-bold text-green-600 dark:text-green-400 flex-shrink-0">{{ $sub->score }}/{{ $activity->points }}</span>
                    @elseif($sub)
                        <span class="text-xs text-blue-600 dark:text-blue-400 flex-shrink-0">Submitted</span>
                    @elseif($activity->due_date && now()->isAfter($activity->due_date))
                        <span class="text-xs text-red-600 dark:text-red-400 flex-shrink-0">Overdue</span>
                    @else
                        <span class="text-xs text-gray-400 dark:text-gray-500 flex-shrink-0">Pending</span>
                    @endif

                    <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 group-hover:text-primary transition flex-shrink-0"></i>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-tasks text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Activities Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Check back later!</p>
        </div>
    @endif

    @if($activities->hasPages())
        <div class="mt-6">{{ $activities->links() }}</div>
    @endif
</div>
@endsection