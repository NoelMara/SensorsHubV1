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

    {{-- 📊 Progress Summary --}}
    @if($totalPossible > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-chart-line text-primary mr-2"></i>My Progress
        </h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                <p class="text-xl font-bold text-green-600">{{ $assessmentSubmissions->count() }}/{{ $assessments->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Assessments Done</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                <p class="text-xl font-bold text-indigo-600">{{ $quizSubmissions->count() }}/{{ $quizzes->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">Quizzes Done</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                <p class="text-xl font-bold text-primary">{{ $totalPoints }}</p>
                <p class="text-xs text-gray-500 mt-1">Points Earned</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 text-center">
                <p class="text-xl font-bold text-primary">{{ $totalPossible > 0 ? round(($totalPoints / $totalPossible) * 100) : 0 }}%</p>
                <p class="text-xs text-gray-500 mt-1">Overall</p>
            </div>
        </div>

        <div class="bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
            <div class="bg-primary h-2.5 rounded-full" style="width: {{ $totalPossible > 0 ? round(($totalPoints / $totalPossible) * 100) : 0 }}%"></div>
        </div>
    </div>
    @endif

    {{-- 📝 Assessments Progress --}}
    @if($assessments->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-tasks text-purple-500 mr-2"></i>Assessments
                <span class="text-sm font-normal text-gray-500">({{ $assessmentPoints }}/{{ $totalAssessmentPoints }} pts)</span>
            </h2>
            <a href="{{ route('dashboard.classes.assessments.index', $class) }}" class="text-sm text-primary hover:underline">View All</a>
        </div>
        <div class="space-y-2">
            @foreach($assessments as $assessment)
                @php $sub = $assessmentSubmissions->get($assessment->id); @endphp
                <a href="{{ route('dashboard.classes.assessments.show', [$class, $assessment]) }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="flex-shrink-0">
                            @if($sub && $sub->score !== null)
                                <i class="fas fa-check-circle text-green-500"></i>
                            @elseif($sub)
                                <i class="fas fa-clock text-blue-500"></i>
                            @else
                                <i class="fas fa-circle text-gray-300 text-xs"></i>
                            @endif
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $assessment->title }}</span>
                    </div>
                    <span class="text-sm font-semibold flex-shrink-0 ml-2 
                        {{ $sub && $sub->score !== null ? 'text-green-600' : ($sub ? 'text-blue-600' : 'text-gray-400') }}">
                        {{ $sub && $sub->score !== null ? $sub->score : '--' }}/{{ $assessment->points }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- 📝 Quizzes Progress --}}
    @if($quizzes->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                <i class="fas fa-question-circle text-indigo-500 mr-2"></i>Quizzes
                <span class="text-sm font-normal text-gray-500">({{ $quizPoints }}/{{ $totalQuizPoints }} pts)</span>
            </h2>
            <a href="{{ route('dashboard.classes.quizzes.index', $class) }}" class="text-sm text-primary hover:underline">View All</a>
        </div>
        <div class="space-y-2">
            @foreach($quizzes as $quiz)
                @php $sub = $quizSubmissions->get($quiz->id); @endphp
                <a href="{{ route('dashboard.classes.quizzes.show', [$class, $quiz]) }}" 
                   class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="flex-shrink-0">
                            @if($sub)
                                @php $pct = ($sub->correct_answers / max($sub->total_questions, 1)) * 100; @endphp
                                @if($pct >= $quiz->passing_score)
                                    <i class="fas fa-check-circle text-green-500"></i>
                                @else
                                    <i class="fas fa-times-circle text-red-500"></i>
                                @endif
                            @else
                                <i class="fas fa-circle text-gray-300 text-xs"></i>
                            @endif
                        </span>
                        <span class="text-sm text-gray-700 dark:text-gray-300 truncate">{{ $quiz->title }}</span>
                    </div>
                    <span class="text-sm font-semibold flex-shrink-0 ml-2 
                        {{ $sub ? ($sub->correct_answers / max($sub->total_questions, 1) * 100 >= $quiz->passing_score ? 'text-green-600' : 'text-red-600') : 'text-gray-400' }}">
                        {{ $sub ? $sub->score : '--' }}/{{ $quiz->points }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
    @endif

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

    <div class="text-center">
        <a href="{{ route('dashboard.classes.index') }}" class="text-primary hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back to My Classes
        </a>
    </div>
</div>
@endsection