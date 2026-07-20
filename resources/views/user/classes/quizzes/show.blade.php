@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.classes.quizzes.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
            <i class="fas fa-arrow-left mr-1"></i> Back to Quizzes
        </a>
    @else
        <a href="{{ route('dashboard.classes.quizzes.index', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
            <i class="fas fa-arrow-left mr-1"></i> Back to Quizzes
        </a>
    @endif

    {{-- Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6 mb-6">
        <h1 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h1>
        
        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
            <span class="inline-flex items-center gap-1"><i class="fas fa-star text-yellow-500"></i>{{ $quiz->points }} pts</span>
            <span class="hidden sm:inline text-gray-300">·</span>
            <span class="inline-flex items-center gap-1"><i class="fas fa-check-circle"></i>Pass: {{ $quiz->passing_score }}%</span>
            <span class="hidden sm:inline text-gray-300">·</span>
            <span class="inline-flex items-center gap-1"><i class="fas fa-question-circle"></i>{{ $quiz->questions->count() }} Qs</span>
            @if($quiz->due_date)
                <span class="hidden sm:inline text-gray-300">·</span>
                <span class="inline-flex items-center gap-1"><i class="fas fa-clock"></i>{{ $quiz->due_date->format('M d, h:i A') }}</span>
            @endif
        </div>

        @if($quiz->description)
            <p class="text-gray-600 dark:text-gray-300 text-sm mt-3">{{ $quiz->description }}</p>
        @endif
        @if($quiz->instructions)
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 mt-3 text-xs sm:text-sm text-gray-600 dark:text-gray-300">
                <strong>Instructions:</strong> {{ $quiz->instructions }}
            </div>
        @endif
    </div>

    {{-- Already Submitted --}}
    @if($submission)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8 text-center">
            @php $percent = ($submission->correct_answers / max($submission->total_questions, 1)) * 100; @endphp
            @if($percent >= $quiz->passing_score)
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trophy text-green-600 dark:text-green-400 text-xl sm:text-2xl"></i>
                </div>
                <h2 class="text-lg sm:text-xl font-bold text-green-600 dark:text-green-400">Congratulations! 🎉</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">You passed the quiz!</p>
            @else
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book text-red-600 dark:text-red-400 text-xl sm:text-2xl"></i>
                </div>
                <h2 class="text-lg sm:text-xl font-bold text-red-600 dark:text-red-400">Keep Trying! 📚</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">You didn't pass this time. Review and try again.</p>
            @endif

            <div class="grid grid-cols-2 gap-3 sm:gap-4 mt-6">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 sm:p-4">
                    <p class="text-xl sm:text-2xl font-bold text-primary">{{ $submission->score }}/{{ $quiz->points }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Score</p>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 sm:p-4">
                    <p class="text-xl sm:text-2xl font-bold text-primary">{{ $submission->correct_answers }}/{{ $submission->total_questions }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Correct</p>
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-4">Submitted {{ $submission->submitted_at->diffForHumans() }}</p>
        </div>

    {{-- Not Submitted + Not Overdue --}}
    @elseif(!$quiz->due_date || now()->lessThanOrEqualTo($quiz->due_date))
        @if(!auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
            <form method="POST" action="{{ route('dashboard.classes.quizzes.submit', [$class, $quiz]) }}">
                @csrf
                <div class="space-y-3 sm:space-y-4">
                    @foreach($quiz->questions as $index => $question)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-5">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">
                                Q{{ $index + 1 }}. {{ $question->question }}
                            </h3>
                            <div class="space-y-1.5 sm:space-y-2">
                                @foreach($question->options as $option)
                                    <label class="flex items-center gap-2 sm:gap-3 p-2.5 sm:p-3 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required
                                            class="h-4 w-4 text-primary focus:ring-primary flex-shrink-0">
                                        <span class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="mt-6 w-full px-5 py-3 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                    <i class="fas fa-paper-plane mr-1.5"></i> Submit Quiz
                </button>
            </form>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="space-y-3 sm:space-y-4">
                    @foreach($quiz->questions as $index => $question)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3 sm:p-4">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-2">
                                Q{{ $index + 1 }}. {{ $question->question }}
                            </h3>
                            <div class="space-y-1">
                                @foreach($question->options as $option)
                                    <div class="flex items-center gap-2 text-xs sm:text-sm {{ $option->is_correct ? 'text-green-600 dark:text-green-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                                        <span class="flex-shrink-0">{{ $option->is_correct ? '✅' : '○' }}</span>
                                        <span>{{ $option->option_text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-gray-400 mt-4 text-center">Instructor preview — correct answers shown</p>
            </div>
        @endif

    {{-- Past Due --}}
    @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-yellow-200 dark:border-yellow-700 p-4 sm:p-5 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-circle text-yellow-600 dark:text-yellow-400"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-yellow-700 dark:text-yellow-400">Past Due Date</p>
                <p class="text-xs text-yellow-600 dark:text-yellow-500 mt-0.5">This quiz is no longer accepting submissions.</p>
            </div>
        </div>
    @endif
</div>
@endsection