@extends('layouts.app')

@section('title', 'Super Admin Suggestions')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">
            <div>
                <a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center text-sm font-semibold text-primary hover:underline mb-4">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Super Admin
                </a>
                <p class="text-sm font-semibold text-primary uppercase">Review Center</p>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mt-1">User Suggestions</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2 max-w-2xl">Review user feedback, track implementation status, and prioritize improvements.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['pending'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Reviewed</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['reviewed'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Implemented</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['implemented'] }}</p>
            </div>
        </div>

        @if($suggestions->count() > 0)
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Suggestion Records</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Newest feedback appears first.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Submitted By</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Suggestion</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($suggestions as $suggestion)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $suggestion->user->name ?? 'Deleted user' }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $suggestion->user->email ?? 'No email available' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ Str::limit($suggestion->title, 56) }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($suggestion->message, 82) }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full
                                            @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($suggestion->status === 'implemented') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @endif">
                                            {{ ucfirst($suggestion->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $suggestion->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('super-admin.suggestions.show', $suggestion) }}" class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950 dark:text-blue-200 dark:hover:bg-blue-900 transition">
                                            <i class="fas fa-eye mr-2"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($suggestions->hasPages())
                <div class="flex justify-center mt-8">{{ $suggestions->links() }}</div>
            @endif
        @else
            <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                <i class="fas fa-lightbulb text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Suggestions Yet</h2>
                <p class="text-gray-500 dark:text-gray-400">User feedback will appear here once submitted.</p>
            </div>
        @endif
    </div>
</div>
@endsection
