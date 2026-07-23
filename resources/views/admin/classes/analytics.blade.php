@extends('layouts.app')

@section('title', 'Analytics - ' . $class->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Class
    </a>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">
                <i class="fas fa-chart-bar text-teal-600 dark:text-teal-400 mr-2"></i>Analytics
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $class->name }} · {{ $studentCount }} {{ Str::plural('student', $studentCount) }}</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $studentCount }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Students</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $assessmentCount }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Assessments</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $quizCount }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Quizzes</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-teal-600 dark:text-teal-400">{{ $assessmentAvg }}%</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Assess Avg</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-cyan-600 dark:text-cyan-400">{{ $quizAvg }}%</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Quiz Avg</p>
        </div>
    </div>

    {{-- Submission Timeline Chart --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-8">
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-chart-line text-blue-600 dark:text-blue-400 mr-2"></i>Submission Timeline
            <span class="text-xs font-normal text-gray-500 ml-4">
                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full mr-1"></span> Assessments
                <span class="inline-block w-3 h-3 bg-purple-500 rounded-full mr-1 ml-3"></span> Quizzes
            </span>
        </h2>
        <div class="relative" style="height: 250px;">
            <canvas id="submissionChart"></canvas>
        </div>
    </div>

    {{-- Breakdown Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Assessment Breakdown --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-tasks text-purple-600 dark:text-purple-400 mr-2"></i>Assessment Breakdown
            </h2>
            @if(count($assessmentBreakdown) > 0)
                <div class="space-y-4">
                    @foreach($assessmentBreakdown as $item)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $item['title'] }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $item['submitted'] }}/{{ $item['total'] }} · {{ $item['average'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full 
                                    {{ $item['average'] >= 75 ? 'bg-green-500' : ($item['average'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                    style="width: {{ $item['submission_rate'] }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">No assessments published yet.</p>
            @endif
        </div>

        {{-- Quiz Breakdown --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">
                <i class="fas fa-question-circle text-indigo-600 dark:text-indigo-400 mr-2"></i>Quiz Breakdown
            </h2>
            @if(count($quizBreakdown) > 0)
                <div class="space-y-4">
                    @foreach($quizBreakdown as $item)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $item['title'] }}</span>
                                <span class="text-gray-500 dark:text-gray-400">{{ $item['submitted'] }}/{{ $item['total'] }} · {{ $item['average'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full 
                                    {{ $item['average'] >= 75 ? 'bg-green-500' : ($item['average'] >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                    style="width: {{ $item['submission_rate'] }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">No quizzes published yet.</p>
            @endif
        </div>
    </div>

    {{-- Student Performance --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">
                <i class="fas fa-users text-blue-600 dark:text-blue-400 mr-2"></i>Student Performance
            </h2>
        </div>
        @if(count($studentPerformance) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50">
                            <th class="pl-5 pr-3 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Student</th>
                            <th class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Assessments</th>
                            <th class="px-3 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Quizzes</th>
                            <th class="pl-3 pr-5 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Overall</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($studentPerformance as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="pl-5 pr-3 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            {{ strtoupper(substr($student['name'], 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $student['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if($student['assessment_avg'] !== null)
                                        @php
                                            $aColor = $student['assessment_avg'] >= 75 ? 'green' : ($student['assessment_avg'] >= 50 ? 'yellow' : 'red');
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                                            {{ $aColor === 'green' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                            {{ $aColor === 'yellow' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                            {{ $aColor === 'red' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                            {{ $student['assessment_avg'] }}%
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    @if($student['quiz_avg'] !== null)
                                        @php
                                            $qColor = $student['quiz_avg'] >= 75 ? 'green' : ($student['quiz_avg'] >= 50 ? 'yellow' : 'red');
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full 
                                            {{ $qColor === 'green' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                            {{ $qColor === 'yellow' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                            {{ $qColor === 'red' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' : '' }}">
                                            {{ $student['quiz_avg'] }}%
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="pl-3 pr-5 py-3 text-center">
                                    @if($student['overall'] !== null)
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $student['overall'] }}%</span>
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Students Yet</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Approved students will appear here.</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('submissionChart').getContext('2d');
    const timelineData = @json($submissionTimeline);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: timelineData.map(item => item.date),
            datasets: [
                {
                    label: 'Assessments',
                    data: timelineData.map(item => item.assessments),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                },
                {
                    label: 'Quizzes',
                    data: timelineData.map(item => item.quizzes),
                    borderColor: '#A855F7',
                    backgroundColor: 'rgba(168, 85, 247, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
});
</script>
@endpush
@endsection