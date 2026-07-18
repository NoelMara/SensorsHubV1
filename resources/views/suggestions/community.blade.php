@extends('layouts.app')

@section('title', 'Community Suggestions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">Community Suggestions</h1>
            <p class="text-gray-600 dark:text-gray-400">Discover and discuss project ideas from the community</p>
        </div>
        @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.suggestions.index') }}" 
                   class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                    <i class="fas fa-clipboard-list mr-2"></i> Manage Suggestions
                </a>
            @else
                <a href="{{ route('dashboard.suggestions') }}" 
                   class="bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                    <i class="fas fa-list mr-2"></i> My Suggestions
                </a>
            @endif
        @endauth
    </div>

    {{-- Suggestions List --}}
    @if($suggestions->count() > 0)
        <div class="space-y-4">
            @foreach($suggestions as $suggestion)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-primary font-bold">{{ strtoupper(substr($suggestion->user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $suggestion->user?->name ?? 'Deleted user' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $suggestion->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($suggestion->status === 'implemented') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ ucfirst($suggestion->status) }}
                        </span>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">{{ $suggestion->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($suggestion->description, 200) }}</p>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                        @if($suggestion->difficulty)
                            <span><i class="fas fa-signal mr-1"></i> {{ $suggestion->difficulty }}</span>
                        @endif
                        @if($suggestion->sensor_type)
                            <span><i class="fas fa-microchip mr-1"></i> {{ $suggestion->sensor_type }}</span>
                        @endif
                        <span><i class="fas fa-comments mr-1"></i> {{ $suggestion->comments->count() }} comments</span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ auth()->user() && (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) ? route('admin.suggestions.show', $suggestion) : route('dashboard.suggestions.show', $suggestion) }}" 
                        class="inline-flex items-center text-primary hover:underline font-semibold">
                            <i class="fas fa-eye mr-2"></i> View Discussion
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $suggestions->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-16 text-center">
            <i class="fas fa-lightbulb text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">No Suggestions Yet</h3>
            <p class="text-gray-500 dark:text-gray-500 mb-6">Be the first to share your project idea with the community!</p>
            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.suggestions.index') }}" 
                       class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                        <i class="fas fa-clipboard-list mr-2"></i> Manage Suggestions
                    </a>
                @else
                    <a href="{{ route('dashboard.suggestions') }}" 
                       class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                        <i class="fas fa-list mr-2"></i> My Suggestions
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" 
                   class="bg-primary text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login to Submit
                </a>
            @endauth
        </div>
    @endif
</div>
@endsection