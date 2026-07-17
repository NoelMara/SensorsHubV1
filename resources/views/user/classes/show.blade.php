@extends('layouts.app')

@section('title', $class->name)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('dashboard.classes.index') }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Classes
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">{{ $class->name }}</h1>
        @if($class->section)
            <span class="inline-block mt-2 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-semibold">
                Block {{ $class->section }}
            </span>
        @endif
    </div>

    <!-- Class Info -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>Class Info
        </h2>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Instructor</p>
                <p class="font-semibold text-gray-900 dark:text-white">{{ $class->instructor->name }}</p>
            </div>
            @if($class->description)
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Description</p>
                <p class="text-gray-700 dark:text-gray-300">{{ $class->description }}</p>
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Class Code</p>
                <p class="text-2xl font-bold text-primary tracking-[0.3em]">{{ $class->code }}</p>
            </div>
        </div>
    </div>

    <!-- Announcements -->
    @php $announcements = $class->announcements()->where('is_published', true)->latest()->take(3)->get(); @endphp
    @if($announcements->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-bullhorn text-yellow-500 mr-2"></i>Announcements
            </h2>
            <a href="{{ route('dashboard.classes.announcements.index', $class) }}" class="text-sm text-primary hover:underline">View All</a>
        </div>
        <div class="space-y-3">
            @foreach($announcements as $announcement)
                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $announcement->title }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($announcement->content, 100) }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">{{ $announcement->created_at->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Modules -->
    @php $modules = $class->modules()->where('is_published', true)->orderBy('order')->take(3)->get(); @endphp
    @if($modules->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-book-open text-green-500 mr-2"></i>Modules
            </h2>
            <a href="{{ route('dashboard.classes.modules.index', $class) }}" class="text-sm text-primary hover:underline">View All</a>
        </div>
        <div class="space-y-3">
            @foreach($modules as $module)
                <a href="{{ route('dashboard.classes.modules.show', [$class, $module]) }}" class="block p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $module->order }}. {{ $module->title }}</h3>
                            @if($module->content)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($module->content, 80) }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if($module->file_path)
                                <i class="fas fa-paperclip text-gray-400"></i>
                            @endif
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Activities -->
    @php $activities = $class->activities()->where('is_published', true)->latest()->take(3)->get(); @endphp
    @if($activities->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-tasks text-purple-500 mr-2"></i>Activities
            </h2>
            <a href="{{ route('dashboard.classes.activities.index', $class) }}" class="text-sm text-primary hover:underline">View All</a>
        </div>
        <div class="space-y-3">
            @foreach($activities as $activity)
                @php $sub = $activity->submissions()->where('user_id', auth()->id())->first(); @endphp
                <a href="{{ route('dashboard.classes.activities.show', [$class, $activity]) }}" class="block p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $activity->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $activity->points }} pts 
                                @if($activity->due_date) · Due: {{ $activity->due_date->format('M d') }} @endif
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
    </div>
    @endif

    <div class="text-center">
        <a href="{{ route('dashboard.classes.index') }}" class="text-primary hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back to My Classes
        </a>
    </div>
</div>
@endsection