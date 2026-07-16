@extends('layouts.app')

@section('title', 'Instructor Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Header -->
    <div class="mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">Instructor Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">
                Welcome back! Here's what's happening with your SensorHub platform.
            </p>
        </div>
    </div>

   {{-- Faculty Head Section --}}
    @if(auth()->user()->isSuperAdmin())
        <div class="bg-gray-900 rounded-lg shadow p-6 mb-8 text-white">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <p class="text-sm text-blue-200 mb-1">Faculty Head Controls</p>
                    <h2 class="text-2xl font-bold">Manage instructor and student accounts</h2>
                    <p class="text-gray-300 mt-2">
                        Open user management to manage instructors, students, and update account roles.
                    </p>
                </div>

                <a href="{{ route('super-admin.users.index') }}"
                   class="inline-flex items-center justify-center bg-primary text-white px-5 py-3 rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-users-cog mr-2"></i>
                    Manage Accounts
                </a>

            </div>
        </div>
    @endif

    {{-- Profile Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

            <div class="flex items-center gap-4 min-w-0">

                <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0">

                   @if(auth()->user()->profile_image)
                            <img src="{{ Str::startsWith(auth()->user()->profile_image, ['http://', 'https://']) ? auth()->user()->profile_image : asset(auth()->user()->profile_image) }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user-shield text-2xl text-gray-500 dark:text-gray-300"></i>
                    @endif

                </div>

                <div class="min-w-0">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Instructor Profile</p>
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white truncate">
                        {{ auth()->user()->name }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 truncate">
                        {{ auth()->user()->email }}
                    </p>
                </div>

            </div>

            <div class="flex flex-col sm:items-end gap-3">

                <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 text-sm font-medium">
                    <i class="fas fa-user-cog mr-2"></i>
                    {{ auth()->user()->isSuperAdmin() ? 'Faculty Head' : 'Instructor' }}
                </span>

                <a href="{{ auth()->user()->isSuperAdmin() ? route('super-admin.profile') : route('dashboard.profile') }}"
                   class="inline-flex items-center justify-center bg-primary hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg transition">
                    <i class="fas fa-user-edit mr-2"></i>
                    Edit Profile
                </a>

            </div>

        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <!-- Sensors -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Sensors</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $stats['sensors'] }}
                    </p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3">
                    <i class="fas fa-microchip text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <a href="{{ route('admin.sensors.index') }}" class="mt-4 text-sm text-primary hover:underline">
                Manage Sensors →
            </a>
        </div>

        <!-- Projects -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $stats['projects'] }}
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 rounded-full p-3">
                    <i class="fas fa-project-diagram text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="mt-4 text-sm text-primary hover:underline">
                Manage Projects →
            </a>
        </div>

        <!-- Products -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Products</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $stats['products'] }}
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-3">
                    <i class="fas fa-shopping-cart text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="mt-4 text-sm text-primary hover:underline">
                Manage Products →
            </a>
        </div>

        <!-- Videos -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Videos</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $stats['videos'] }}
                    </p>
                </div>
                <div class="bg-red-100 dark:bg-red-900 rounded-full p-3">
                    <i class="fas fa-video text-2xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
            <a href="{{ route('admin.videos.index') }}" class="mt-4 text-sm text-primary hover:underline">
                Manage Videos →
            </a>
        </div>

    </div>

    {{-- Second Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <!-- Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $stats['users'] }}
                    </p>
                </div>
                <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-3">
                    <i class="fas fa-users text-2xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
        </div>

        <!-- Suggestions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Suggestions</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ $stats['suggestions'] }}
                    </p>

                    @if($stats['pending_suggestions'] > 0)
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $stats['pending_suggestions'] ?? 0 }} pending
                        </p>
                    @endif
                </div>

                <div class="bg-yellow-100 dark:bg-yellow-900 rounded-full p-3">
                    <i class="fas fa-lightbulb text-2xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>

            <a href="{{ route('admin.suggestions.index') }}" class="mt-4 text-sm text-primary hover:underline">
                View Suggestions →
            </a>
        </div>

    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-users mr-2 text-primary"></i>
                Recent Users
            </h3>

            @if($recentUsers->count() > 0)
                <div class="space-y-3">
                    @foreach($recentUsers as $user)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded">

                            <div class="flex items-center gap-3 flex-1">

                                <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center overflow-hidden flex-shrink-0">

                                    @if($user->profile_image)
                                            <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}"
                                             alt="{{ $user->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-gray-500"></i>
                                    @endif

                                </div>

                                <div>
                                    <p class="font-medium text-gray-800 dark:text-white">
                                        {{ $user->name }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->email }}
                                    </p>
                                </div>

                            </div>

                            <span class="text-xs text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </span>

                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                    No users yet
                </p>
            @endif
        </div>

        <!-- Recent Suggestions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-lightbulb mr-2 text-yellow-500"></i>
                Recent Suggestions
            </h3>

            @if($recentSuggestions->count() > 0)
                <div class="space-y-3">
                    @foreach($recentSuggestions as $suggestion)
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded">

                            <div class="flex items-start justify-between">

                                <div class="flex-1">
                                    <p class="font-medium text-gray-800 dark:text-white">
                                        {{ Str::limit($suggestion->title, 40) }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        by {{ $suggestion->user?->name ?? 'Deleted user' }}
                                    </p>
                                </div>

                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($suggestion->status === 'pending')
                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($suggestion->status === 'reviewed')
                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @else
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @endif">
                                    {{ $suggestion->status }}
                                </span>

                            </div>

                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                    No suggestions yet
                </p>
            @endif
        </div>

    </div>

</div>
@endsection