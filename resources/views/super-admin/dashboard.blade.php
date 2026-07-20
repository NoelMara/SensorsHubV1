@extends('layouts.app')

@section('title', 'Administrator Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Welcome back, {{ auth()->user()->name }} 👋</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your entire platform from one place.</p>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if(auth()->user()->profile_image)
                    <img src="{{ Str::startsWith(auth()->user()->profile_image, ['http://', 'https://']) ? auth()->user()->profile_image : asset(auth()->user()->profile_image) }}"
                         alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xl font-bold text-gray-400">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ auth()->user()->name }}</h2>
                    <span class="px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                        Administrator
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ route('super-admin.profile') }}"
               class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-xs font-medium flex-shrink-0">
                <i class="fas fa-user-edit mr-1"></i> Edit Profile
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Users</p>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['users'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fas fa-user-shield text-blue-600 dark:text-blue-400"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Instructors</p>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['admins'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <i class="fas fa-layer-group text-emerald-600 dark:text-emerald-400"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Content Items</p>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['sensors'] + $stats['projects'] + $stats['products'] + $stats['videos'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 dark:text-amber-400"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Pending</p>
            </div>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending_suggestions'] }}</p>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Links</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('super-admin.users.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-md transition text-center">
                <i class="fas fa-users text-xl text-indigo-500 mb-2"></i>
                <p class="text-xs text-gray-500 dark:text-gray-400">Users</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['users'] }}</p>
            </a>
            <a href="{{ route('super-admin.suggestions.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:border-yellow-300 dark:hover:border-yellow-600 hover:shadow-md transition text-center">
                <i class="fas fa-lightbulb text-xl text-yellow-500 mb-2"></i>
                <p class="text-xs text-gray-500 dark:text-gray-400">Suggestions</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['suggestions'] }}</p>
            </a>
            <a href="{{ route('super-admin.sensors.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md transition text-center">
                <i class="fas fa-microchip text-xl text-blue-500 mb-2"></i>
                <p class="text-xs text-gray-500 dark:text-gray-400">Sensors</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['sensors'] }}</p>
            </a>
            <a href="{{ route('super-admin.projects.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-md transition text-center">
                <i class="fas fa-project-diagram text-xl text-emerald-500 mb-2"></i>
                <p class="text-xs text-gray-500 dark:text-gray-400">Projects</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['projects'] }}</p>
            </a>
            <a href="{{ route('super-admin.products.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:border-purple-300 dark:hover:border-purple-600 hover:shadow-md transition text-center">
                <i class="fas fa-shopping-cart text-xl text-purple-500 mb-2"></i>
                <p class="text-xs text-gray-500 dark:text-gray-400">Products</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['products'] }}</p>
            </a>
            <a href="{{ route('super-admin.videos.index') }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:border-red-300 dark:hover:border-red-600 hover:shadow-md transition text-center">
                <i class="fas fa-video text-xl text-red-500 mb-2"></i>
                <p class="text-xs text-gray-500 dark:text-gray-400">Videos</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $stats['videos'] }}</p>
            </a>
        </div>
    </div>

    <!-- Recent Users + Pending -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Recent Users</h2>
                <a href="{{ route('super-admin.users.index') }}" class="text-xs text-primary hover:underline">View all →</a>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($recentUsers as $user)
                    <div class="px-5 py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-semibold text-gray-500">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full flex-shrink-0
                            {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300' : '' }}
                            {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' : '' }}
                            {{ $user->role === 'user' ? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                            {{ $user->role === 'super_admin' ? 'Administrator' : ($user->role === 'admin' ? 'Instructor' : 'Student') }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8 text-sm">No users yet</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Pending Suggestions</h2>
            </div>
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($recentSuggestions as $suggestion)
                    <a href="{{ route('super-admin.suggestions.show', $suggestion) }}" class="block px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ Str::limit($suggestion->title, 50) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">by {{ $suggestion->user?->name ?? 'Deleted user' }} · {{ $suggestion->created_at->diffForHumans() }}</p>
                    </a>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8 text-sm">All clear! 🎉</p>
                @endforelse
            </div>
            @if($recentSuggestions->count() > 0)
                <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('super-admin.suggestions.index') }}" class="text-xs text-primary hover:underline">Review all →</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Latest Comments -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Latest Comments</h2>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recentComments as $comment)
                <a href="{{ route('super-admin.suggestions.show', $comment->suggestion) }}" class="block px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-semibold text-gray-500">
                            {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $comment->user?->name ?? 'Deleted user' }}</span>
                                <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($comment->body, 80) }}</p>
                            <p class="text-xs text-primary mt-0.5">on: {{ $comment->suggestion?->title ?? 'Deleted suggestion' }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-8 text-sm">No comments yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection