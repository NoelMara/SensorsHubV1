@extends('layouts.app')

@section('title', 'Leaderboard - ' . $class->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Leaderboard - {{ $class->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $leaderboard->count() }} {{ Str::plural('student', $leaderboard->count()) }}</p>
            </div>
        </div>
    </div>

    @if($leaderboard->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700 text-left">
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Rank</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Student</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-center">Points</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-center">Submitted</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-center">Pending</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-center">Overdue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($leaderboard as $index => $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                                <td class="px-4 py-3 text-center">
                                    @if($index == 0)
                                        🥇
                                    @elseif($index == 1)
                                        🥈
                                    @elseif($index == 2)
                                        🥉
                                    @else
                                        <span class="text-sm font-semibold text-gray-500">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $row['student']->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $row['student']->email }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold text-primary text-sm">{{ $row['total_points'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $row['graded'] }}/{{ $row['total_activities'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($row['pending'] > 0)
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700">{{ $row['pending'] }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($row['overdue'] > 0)
                                        <span class="px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700">{{ $row['overdue'] }}</span>
                                    @else
                                        <span class="text-sm text-gray-400">0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border">
            <i class="fas fa-trophy text-5xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600">No Students Yet</h3>
            <p class="text-gray-500">Students will appear here once approved.</p>
        </div>
    @endif
</div>
@endsection