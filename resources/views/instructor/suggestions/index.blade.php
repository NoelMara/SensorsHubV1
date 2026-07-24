@extends('layouts.app')

@section('title', 'Suggestions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Suggestions</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Review user feedback and track status.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Reviewed</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['reviewed'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Implemented</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['implemented'] }}</p>
        </div>
    </div>

    @if($suggestions->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Submitted By</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Suggestion</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Date</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($suggestions as $suggestion)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $suggestion->user?->name ?? 'Deleted user' }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $suggestion->user?->email ?? 'No email' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Str::limit($suggestion->title, 50) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($suggestion->description, 70) }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 text-xs rounded-full
                                        @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                        @elseif($suggestion->status === 'implemented') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                        @endif">
                                        {{ ucfirst($suggestion->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $suggestion->created_at->format('M d, Y') }}</td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('instructor.suggestions.show', $suggestion) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium bg-primary text-white hover:bg-blue-600 transition">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($suggestions->hasPages())
            <div class="mt-6">{{ $suggestions->links() }}</div>
        @endif
    @else
        <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <i class="fas fa-lightbulb text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-lg font-bold text-gray-600 dark:text-gray-400">No Suggestions Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">User feedback will appear here once submitted.</p>
        </div>
    @endif
</div>
@endsection