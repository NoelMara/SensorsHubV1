@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Activity Logs</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $logs->total() }} total entries</p>
    </div>

    @if($logs->count() > 0)
        @php $currentDate = ''; @endphp
        <div class="space-y-6">
            @foreach($logs as $log)
                @php $logDate = $log->created_at->format('F d, Y'); @endphp
                
                @if($currentDate !== $logDate)
                    @php $currentDate = $logDate; @endphp
                    <div class="flex items-center gap-3 pt-2 first:pt-0">
                        <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ $logDate }}</span>
                        <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                    </div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-5 py-3 flex items-center gap-3">
                    <div class="flex-shrink-0">
                        @if($log->type === 'user' && $log->action === 'created')
                            <span class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-sm">👤</span>
                        @elseif($log->type === 'user' && $log->action === 'deleted')
                            <span class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-sm">🚫</span>
                        @elseif($log->type === 'class')
                            <span class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-sm">📚</span>
                        @elseif($log->type === 'sensor')
                            <span class="w-8 h-8 rounded-full bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center text-sm">📡</span>
                        @elseif($log->type === 'project')
                            <span class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-sm">📐</span>
                        @elseif($log->type === 'product')
                            <span class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center text-sm">📦</span>
                        @elseif($log->type === 'video')
                            <span class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-sm">🎬</span>
                        @elseif($log->type === 'suggestion')
                            <span class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center text-sm">💡</span>
                        @elseif($log->type === 'profile' || $log->type === 'password')
                            <span class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm">🔐</span>
                        @else
                            <span class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-sm">📄</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $log->user_name }}</span>
                            <span class="text-gray-400 text-xs ml-1">({{ $log->user_role === 'super_admin' ? 'Administrator' : ($log->user_role === 'admin' ? 'Instructor' : 'Student') }})</span>
                            {{ ' ' . $log->description }}
                        </p>
                    </div>
                    <span class="text-xs text-gray-400 flex-shrink-0">{{ $log->created_at->format('h:i A') }}</span>
                </div>
            @endforeach
        </div>

        @if($logs->hasPages())
            <div class="mt-8">{{ $logs->links() }}</div>
        @endif
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-history text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-base font-semibold text-gray-600 dark:text-gray-400">No Activity Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Logs will appear here once actions are recorded.</p>
        </div>
    @endif
</div>
@endsection