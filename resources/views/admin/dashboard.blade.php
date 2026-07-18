@extends('layouts.app')

@section('title', 'Instructor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-1">Instructor Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Welcome back! Here's what's happening with your platform.</p>
    </div>

    {{-- Faculty Head Section --}}
    @if(auth()->user()->isSuperAdmin())
        <div class="bg-gray-900 dark:bg-gray-950 rounded-xl p-6 mb-8 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-sm text-blue-300 mb-1">Faculty Head Controls</p>
                    <h2 class="text-xl font-bold">Manage instructor and student accounts</h2>
                </div>
                <a href="{{ route('super-admin.users.index') }}" class="inline-flex items-center justify-center bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-blue-600 transition text-sm font-medium">
                    <i class="fas fa-users-cog mr-2"></i> Manage Accounts
                </a>
            </div>
        </div>
    @endif

    {{-- Welcome Card --}}
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
                    <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ auth()->user()->isSuperAdmin() ? 'Faculty Head' : 'Instructor' }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
            </div>
            <a href="{{ auth()->user()->isSuperAdmin() ? route('super-admin.profile') : route('dashboard.profile') }}"
               class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-xs font-medium flex-shrink-0">
                <i class="fas fa-user-edit mr-1"></i> Edit Profile
            </a>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fas fa-microchip text-blue-600 dark:text-blue-400"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Sensors</p>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['sensors'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fas fa-project-diagram text-green-600 dark:text-green-400"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Projects</p>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['projects'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-purple-600 dark:text-purple-400"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Products</p>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['products'] }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <i class="fas fa-video text-red-600 dark:text-red-400"></i>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Videos</p>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['videos'] }}</p>
        </div>
    </div>

    {{-- Second Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['users'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <i class="fas fa-lightbulb text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Suggestions</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['suggestions'] }}</p>
                    @if($stats['pending_suggestions'] > 0)
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-0.5">{{ $stats['pending_suggestions'] }} pending</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Recent Users</h3>
            @if($recentUsers->count() > 0)
                <div class="space-y-2">
                    @foreach($recentUsers as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center flex-shrink-0 text-xs font-semibold text-gray-500">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 dark:text-white text-sm truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 flex-shrink-0 ml-2">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8 text-sm">No users yet</p>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Recent Suggestions</h3>
            @if($recentSuggestions->count() > 0)
                <div class="space-y-2">
                    @foreach($recentSuggestions as $suggestion)
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 dark:text-white text-sm truncate">{{ Str::limit($suggestion->title, 40) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">by {{ $suggestion->user?->name ?? 'Deleted user' }}</p>
                                </div>
                                <span class="px-2 py-0.5 text-xs rounded-full flex-shrink-0
                                    @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300
                                    @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300
                                    @else bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 @endif">
                                    {{ $suggestion->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8 text-sm">No suggestions yet</p>
            @endif
        </div>
    </div>
</div>
@endsection