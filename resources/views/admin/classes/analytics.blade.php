@extends('layouts.app')

@section('title', 'Analytics - ' . $class->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('admin.classes.show', $class) }}" class="text-primary hover:underline mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Class
        </a>
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">
                📊 Analytics: {{ $class->name }}
            </h1>
            @if($class->section)
                <span class="inline-block mt-2 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm font-semibold">
                    Block {{ $class->section }}
                </span>
            @endif
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
            📈 Submission Timeline (Last 30 Days)
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
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">📋 Assessment Breakdown</h2>
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
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">📋 Quiz Breakdown</h2>
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

    {{-- Students at Risk --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-red-200 dark:border-red-700 p-5">
        <h2 class="text-base font-bold text-red-700 dark:text-red-400 mb-4">🚨 Students at Risk (Below 50%)</h2>
        @if(count($studentsAtRisk) > 0)
            <div class="space-y-3">
                @foreach($studentsAtRisk as $student)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $student['name'] }}</span>
                        <div class="flex items-center gap-3 text-xs">
                            @if($student['assessment_avg'] !== null)
                                <span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                    Assessments: {{ $student['assessment_avg'] }}%
                                </span>
                            @endif
                            @if($student['quiz_avg'] !== null)
                                <span class="px-2 py-0.5 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                    Quizzes: {{ $student['quiz_avg'] }}%
                                </span>
                            @endif
                            <span class="font-bold text-red-600 dark:text-red-400">{{ $student['overall'] }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-green-600 dark:text-green-400 text-sm">🎉 No students at risk!</p>
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