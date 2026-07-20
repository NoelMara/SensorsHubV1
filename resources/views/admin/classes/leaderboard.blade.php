@extends('layouts.app')

@section('title', 'Leaderboard - ' . $class->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Class
    </a>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Leaderboard</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $class->name }} · {{ $leaderboard->count() }} {{ Str::plural('student', $leaderboard->count()) }}</p>
        </div>
    </div>

    @if($leaderboard->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="pl-5 pr-3 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase w-16">Rank</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Student</th>
                            <th class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Points</th>
                            <th class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Assessments</th>
                            <th class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Quizzes</th>
                            <th class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Pending</th>
                            <th class="pl-3 pr-5 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Overdue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($leaderboard as $index => $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="pl-5 pr-3 py-4 text-center">
                                    @if($index == 0)
                                        <div class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mx-auto text-sm">🥇</div>
                                    @elseif($index == 1)
                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto text-sm">🥈</div>
                                    @elseif($index == 2)
                                        <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mx-auto text-sm">🥉</div>
                                    @else
                                        <span class="text-sm font-semibold text-gray-400">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            {{ strtoupper(substr($row['student']->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $row['student']->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-sm font-bold text-primary">
                                        <i class="fas fa-star text-yellow-500 text-xs"></i>{{ $row['total_points'] }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $row['graded_assessments'] }}/{{ $row['total_assessments'] }}</span>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $row['graded_quizzes'] }}/{{ $row['total_quizzes'] }}</span>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    @if($row['pending'] > 0)
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">{{ $row['pending'] }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="pl-3 pr-5 py-4 text-center">
                                    @if($row['overdue'] > 0)
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">{{ $row['overdue'] }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trophy text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Students Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Students will appear here once approved.</p>
        </div>
    @endif
</div>
@endsection