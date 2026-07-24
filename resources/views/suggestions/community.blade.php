@extends('layouts.app')

@section('title', 'Community Suggestions')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Community Suggestions</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Discover and discuss project ideas from the community.</p>
        </div>
        @auth
            @if(auth()->user()->isInstructor() || auth()->user()->isAdministrator())
                <a href="{{ route('instructor.suggestions.index') }}" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium flex-shrink-0 shadow-sm">
                    <i class="fas fa-clipboard-list mr-1.5"></i> Manage Suggestions
                </a>
            @else
                <a href="{{ route('dashboard.suggestions') }}" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium flex-shrink-0 shadow-sm">
                    <i class="fas fa-list mr-1.5"></i> My Suggestions
                </a>
            @endif
        @endauth
    </div>

    @if($suggestions->count() > 0)
        <div class="space-y-4">
            @foreach($suggestions as $suggestion)
                <a href="{{ auth()->user() && (auth()->user()->isInstructor() || auth()->user()->isAdministrator()) ? route('instructor.suggestions.show', $suggestion) : route('dashboard.suggestions.show', $suggestion) }}" 
                   class="flex items-start gap-5 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6 hover:shadow-md hover:border-primary/30 dark:hover:border-primary/30 transition group">
                    
                    <div class="w-12 h-12 rounded-xl bg-primary/10 dark:bg-primary/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="text-lg font-bold text-primary">{{ strtoupper(substr($suggestion->user?->name ?? '?', 0, 1)) }}</span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white group-hover:text-primary transition truncate">{{ $suggestion->title }}</h3>
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full flex-shrink-0
                                @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300
                                @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                @elseif($suggestion->status === 'implemented') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                                @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                @endif">
                                {{ ucfirst($suggestion->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-3">{{ Str::limit($suggestion->description, 200) }}</p>
                        <div class="flex items-center gap-4 text-xs text-gray-400 dark:text-gray-500">
                            <span class="font-medium text-gray-600 dark:text-gray-300">{{ $suggestion->user?->name ?? 'Deleted user' }}</span>
                            <span>{{ $suggestion->created_at->diffForHumans() }}</span>
                            <div class="flex items-center gap-3 ml-auto">
                                @if($suggestion->difficulty)
                                    <span><i class="fas fa-signal mr-1"></i>{{ $suggestion->difficulty }}</span>
                                @endif
                                @if($suggestion->sensor_type)
                                    <span><i class="fas fa-microchip mr-1"></i>{{ $suggestion->sensor_type }}</span>
                                @endif
                                <span><i class="fas fa-comments mr-1"></i>{{ $suggestion->comments->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <i class="fas fa-chevron-right text-gray-300 dark:text-gray-600 group-hover:text-primary transition flex-shrink-0 mt-1"></i>
                </a>
            @endforeach
        </div>

        <div class="mt-8">{{ $suggestions->links() }}</div>
    @else
        <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-lightbulb text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">No Suggestions Yet</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">Be the first to share your project idea with the community!</p>
            @auth
                @if(auth()->user()->isInstructor() || auth()->user()->isAdministrator())
                    <a href="{{ route('instructor.suggestions.index') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-clipboard-list mr-1.5"></i> Manage Suggestions
                    </a>
                @else
                    <a href="{{ route('dashboard.suggestions') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-list mr-1.5"></i> My Suggestions
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                    <i class="fas fa-sign-in-alt mr-1.5"></i> Login to Submit
                </a>
            @endauth
        </div>
    @endif
</div>
@endsection