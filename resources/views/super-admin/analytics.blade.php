@extends('layouts.app')

@section('title', 'Platform Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('super-admin.dashboard') }}" class="text-primary hover:underline inline-block text-sm mb-6">
        <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
    </a>

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Platform Analytics</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Overview of your entire platform</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $totalUsers }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Users</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalInstructors }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Instructors</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $totalClasses }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Classes</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $totalContent }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Content</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
            <p class="text-xl sm:text-2xl font-bold text-teal-600 dark:text-teal-400">{{ $newThisMonth }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">New This Month</p>
        </div>
    </div>

    {{-- User Growth Chart --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-8">
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">User Growth (Last 30 Days)</h2>
        <div class="relative" style="height: 250px;">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>

    {{-- Top Classes + Content Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Top Classes --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Top Classes</h2>
            </div>
            @if(count($topClasses) > 0)
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($topClasses as $index => $class)
                        <div class="px-5 py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="text-sm font-bold text-gray-400 w-6">
                                    #{{ $index + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $class['name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $class['instructor'] }}
                                        @if($class['section'])
                                            · Block {{ $class['section'] }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-xs flex-shrink-0">
                                <span class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-users mr-1"></i>{{ $class['students_count'] }}
                                </span>
                                <span class="font-semibold {{ $class['avg_score'] >= 75 ? 'text-green-600' : ($class['avg_score'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $class['avg_score'] }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8 text-sm">No classes yet.</p>
            @endif
        </div>

        {{-- Content Breakdown --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">Content Breakdown</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <i class="fas fa-microchip text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sensors</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $contentBreakdown['sensors'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                            <i class="fas fa-project-diagram text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Projects</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $contentBreakdown['projects'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                            <i class="fas fa-video text-red-600 dark:text-red-400"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Videos</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $contentBreakdown['videos'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Products</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $contentBreakdown['products'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('userGrowthChart').getContext('2d');
    const growthData = @json($userGrowth);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: growthData.map(item => item.date),
            datasets: [{
                label: 'New Users',
                data: growthData.map(item => item.count),
                borderColor: '#6366F1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 2,
                pointHoverRadius: 5,
            }]
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