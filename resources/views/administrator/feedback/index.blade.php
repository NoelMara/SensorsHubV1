@extends('layouts.app')

@section('title', 'Feedback')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">

    {{-- Back link --}}
    <a href="{{ route('administrator.dashboard') }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition mb-10">
        <i class="fas fa-arrow-left text-xs"></i>
        Back to Dashboard
    </a>

    {{-- Header --}}
    <div class="mb-10">
        <p class="text-xs font-medium uppercase tracking-[0.15em] text-gray-500 dark:text-gray-400 mb-3">
            Administrator · Feedback
        </p>
        <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight text-gray-900 dark:text-white mb-4">
            User feedback
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl">
            Bugs, feature requests, and other messages from students and instructors.
        </p>
    </div>

    {{-- Filter tabs --}}
    <div class="flex flex-wrap items-center gap-2 mb-8 pb-6 border-b border-gray-100 dark:border-gray-800">
        @foreach([
            'all' => 'All',
            'new' => 'New',
            'read' => 'Read',
            'resolved' => 'Resolved',
            'wont_fix' => "Won't fix",
        ] as $key => $label)
            <a href="{{ route('administrator.feedback.index', ['status' => $key]) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition
                {{ (request('status', 'all') === $key || (!request('status') && $key === 'all'))
                    ? 'bg-gray-900 dark:bg-white text-white dark:text-gray-900'
                    : 'border border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-400 hover:border-gray-900 dark:hover:border-white hover:text-gray-900 dark:hover:text-white' }}">
                {{ $label }}
                <span class="text-[10px] opacity-60">({{ $counts[$key] ?? 0 }})</span>
            </a>
        @endforeach
    </div>

    @if($feedback->count() > 0)
        <div class="space-y-3">
            @foreach($feedback as $item)
                @php
                    $typeIcons = ['bug' => 'fa-bug', 'feature' => 'fa-lightbulb', 'other' => 'fa-comment'];
                    $statusColors = [
                        'new' => 'text-blue-600 dark:text-blue-400',
                        'read' => 'text-gray-500 dark:text-gray-400',
                        'resolved' => 'text-emerald-600 dark:text-emerald-400',
                        'wont_fix' => 'text-red-600 dark:text-red-400',
                    ];
                @endphp
                <article class="border border-gray-200 dark:border-gray-800 rounded-lg p-5">
                    {{-- Top row: user + type + status --}}
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center flex-shrink-0">
                                <i class="fas {{ $typeIcons[$item->type] ?? 'fa-comment' }} text-xs text-gray-600 dark:text-gray-400"></i>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ $item->user->name ?? 'Unknown' }}
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">
                                        · {{ $item->user_role }}
                                    </span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->created_at->format('M d, Y · h:i A') }}
                                </p>
                            </div>
                        </div>
                        <span class="text-[10px] font-medium uppercase tracking-wider flex-shrink-0 {{ $statusColors[$item->status] ?? '' }}">
                            {{ str_replace('_', ' ', $item->status) }}
                        </span>
                    </div>

                    {{-- Message --}}
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line mb-3">
                        {{ $item->message }}
                    </p>

                    {{-- Context: page url --}}
                    @if($item->page_url)
                        <p class="text-xs text-gray-400 dark:text-gray-600 mb-3 break-all">
                            <i class="fas fa-link text-[10px] mr-1"></i>
                            {{ $item->page_url }}
                        </p>
                    @endif

                    {{-- Admin actions --}}
                    <form method="POST" action="{{ route('administrator.feedback.status', $item) }}"
                          class="pt-3 border-t border-gray-100 dark:border-gray-800">
                        @csrf @method('PUT')
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <div class="flex flex-wrap items-center gap-1.5">
                                @foreach([
                                    'new' => 'New',
                                    'read' => 'Read',
                                    'resolved' => 'Resolved',
                                    'wont_fix' => "Won't fix",
                                ] as $statusKey => $statusLabel)
                                    <button type="submit" name="status" value="{{ $statusKey }}"
                                        class="px-2.5 py-1 rounded text-[10px] font-medium uppercase tracking-wider transition
                                        {{ $item->status === $statusKey
                                            ? 'bg-gray-900 dark:bg-white text-white dark:text-gray-900'
                                            : 'border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400 hover:border-gray-900 dark:hover:border-white hover:text-gray-900 dark:hover:text-white' }}">
                                        {{ $statusLabel }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($feedback->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $feedback->links() }}
            </div>
        @endif
    @else
        {{-- Empty state --}}
        <div class="text-center py-20 border border-dashed border-gray-200 dark:border-gray-800 rounded-lg">
            <i class="fas fa-comment-dots text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No feedback yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">When users send feedback, it'll appear here.</p>
        </div>
    @endif
</div>
@endsection