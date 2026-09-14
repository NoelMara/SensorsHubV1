@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">

    {{-- Back link --}}
    <a href="{{ (auth()->user()->isInstructor() || auth()->user()->isAdministrator()) ? route('instructor.classes.quizzes.index', $class) : route('dashboard.classes.quizzes.index', $class) }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition mb-10">
        <i class="fas fa-arrow-left text-xs"></i>
        Back to Quizzes
    </a>

    {{-- Header --}}
    <div class="mb-12">
        <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-500 dark:text-gray-400 mb-3">
            Quiz
        </p>
        <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 dark:text-white mb-4 break-words">
            {{ $quiz->title }}
        </h1>

        {{-- Meta line --}}
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
            <span class="inline-flex items-center gap-1.5">
                <i class="fas fa-star text-xs"></i>
                {{ $quiz->points }} points
            </span>
            <span class="text-gray-300 dark:text-gray-700">·</span>
            <span class="inline-flex items-center gap-1.5">
                <i class="fas fa-check text-xs"></i>
                Pass: {{ $quiz->passing_score }}%
            </span>
            <span class="text-gray-300 dark:text-gray-700">·</span>
            <span class="inline-flex items-center gap-1.5">
                <i class="fas fa-question-circle text-xs"></i>
                {{ $quiz->questions->count() }} {{ Str::plural('question', $quiz->questions->count()) }}
            </span>
            @if($quiz->due_date)
                <span class="text-gray-300 dark:text-gray-700">·</span>
                <span>Due {{ $quiz->due_date->format('M d · h:i A') }}</span>
            @endif
        </div>
    </div>

    {{-- Description + Instructions --}}
    @if($quiz->description || $quiz->instructions)
        <section class="border border-gray-200 dark:border-gray-800 rounded-lg p-6 sm:p-8 mb-8">
            @if($quiz->description)
                <div @if($quiz->instructions) class="mb-8" @endif>
                    <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-500 dark:text-gray-400 mb-3">
                        Description
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                        {{ $quiz->description }}
                    </p>
                </div>
            @endif

            @if($quiz->instructions)
                <div @if($quiz->description) class="pt-8 border-t border-gray-100 dark:border-gray-800" @endif>
                    <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-500 dark:text-gray-400 mb-3">
                        Instructions
                    </p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">
                        {{ $quiz->instructions }}
                    </p>
                </div>
            @endif
        </section>
    @endif

    {{-- ============================================================= --}}
    {{-- Already Submitted — results view --}}
    {{-- ============================================================= --}}
    @if($submission)
        @php $percent = ($submission->correct_answers / max($submission->total_questions, 1)) * 100; @endphp
        @php $passed = $percent >= $quiz->passing_score; @endphp

        <section class="border border-gray-200 dark:border-gray-800 rounded-lg p-8">
            {{-- Result headline --}}
            <div class="text-center mb-8">
                @if($passed)
                    <i class="fas fa-trophy text-emerald-500 text-4xl mb-4 block"></i>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                        You passed!
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Submitted {{ $submission->submitted_at->diffForHumans() }}
                    </p>
                @else
                    <i class="fas fa-book text-red-500 text-4xl mb-4 block"></i>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                        Not quite
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Review the material and try again. Submitted {{ $submission->submitted_at->diffForHumans() }}
                    </p>
                @endif
            </div>

            {{-- Score grid --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="border border-gray-200 dark:border-gray-800 rounded-lg p-4 text-center">
                    <p class="text-3xl font-semibold text-gray-900 dark:text-white tabular-nums mb-1">
                        {{ $submission->score }}<span class="text-lg text-gray-400 dark:text-gray-600">/{{ $quiz->points }}</span>
                    </p>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Score
                    </p>
                </div>
                <div class="border border-gray-200 dark:border-gray-800 rounded-lg p-4 text-center">
                    <p class="text-3xl font-semibold text-gray-900 dark:text-white tabular-nums mb-1">
                        {{ $submission->correct_answers }}<span class="text-lg text-gray-400 dark:text-gray-600">/{{ $submission->total_questions }}</span>
                    </p>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Correct
                    </p>
                </div>
            </div>

            {{-- Pass/fail threshold bar --}}
            <div class="border-t border-gray-100 dark:border-gray-800 pt-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Your result
                    </span>
                    <span class="text-sm font-semibold tabular-nums {{ $passed ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ round($percent) }}% · Passing {{ $quiz->passing_score }}%
                    </span>
                </div>
                <div class="bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden relative">
                    <div class="{{ $passed ? 'bg-emerald-500' : 'bg-red-500' }} h-1.5 rounded-full transition-all"
                         style="width: {{ min(100, round($percent)) }}%"></div>
                    <div class="absolute top-0 bottom-0 w-px bg-gray-400 dark:bg-gray-600"
                         style="left: {{ min(100, $quiz->passing_score) }}%"></div>
                </div>
            </div>
        </section>

    {{-- ============================================================= --}}
    {{-- Not submitted + not overdue — student takes the quiz --}}
    {{-- ============================================================= --}}
    @elseif(!$quiz->due_date || now()->lessThanOrEqualTo($quiz->due_date))

        @if(!auth()->user()->isInstructor() && !auth()->user()->isAdministrator())

            <form method="POST" action="{{ route('dashboard.classes.quizzes.submit', [$class, $quiz]) }}">
                @csrf

                <div class="space-y-4">
                    @foreach($quiz->questions as $index => $question)
                        <div class="border border-gray-200 dark:border-gray-800 rounded-lg p-5">
                            <div class="flex items-start gap-3 mb-4">
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-600 tabular-nums flex-shrink-0 mt-0.5">
                                    Q{{ $index + 1 }}
                                </span>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $question->question }}
                                </h3>
                            </div>

                            <div class="space-y-2 ml-7">
                                @foreach($question->options as $option)
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-800 rounded-lg hover:border-gray-400 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800/40 cursor-pointer transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/50 dark:has-[:checked]:bg-emerald-950/20">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required
                                            class="h-4 w-4 text-emerald-500 focus:ring-emerald-500 border-gray-300 dark:border-gray-700 flex-shrink-0">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $option->option_text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition text-sm">
                        <i class="fas fa-paper-plane text-xs"></i>
                        Submit quiz
                    </button>
                </div>
            </form>

        @else
            {{-- Instructor preview --}}
            <section class="border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="mb-6">
                    <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-500 dark:text-gray-400 mb-2">
                        Preview
                    </p>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Instructor view · correct answers shown
                    </h2>
                </div>

                <div class="space-y-4">
                    @foreach($quiz->questions as $index => $question)
                        <div class="border border-gray-200 dark:border-gray-800 rounded-lg p-5">
                            <div class="flex items-start gap-3 mb-4">
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-600 tabular-nums flex-shrink-0 mt-0.5">
                                    Q{{ $index + 1 }}
                                </span>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $question->question }}
                                </h3>
                            </div>

                            <div class="space-y-2 ml-7">
                                @foreach($question->options as $option)
                                    <div class="flex items-center gap-3 p-3 border rounded-lg
                                        {{ $option->is_correct ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20' : 'border-gray-200 dark:border-gray-800' }}">
                                        @if($option->is_correct)
                                            <i class="fas fa-check-circle text-emerald-500 text-sm flex-shrink-0"></i>
                                        @else
                                            <i class="fas fa-circle text-gray-300 dark:text-gray-700 text-[8px] flex-shrink-0"></i>
                                        @endif
                                        <span class="text-sm {{ $option->is_correct ? 'text-gray-900 dark:text-white font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                            {{ $option->option_text }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    {{-- ============================================================= --}}
    {{-- Past Due --}}
    {{-- ============================================================= --}}
    @else
        <section class="border border-gray-200 dark:border-gray-800 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <i class="fas fa-exclamation-circle text-amber-500 text-lg flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white mb-1">
                        Past due date
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        This quiz is no longer accepting submissions.
                    </p>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection