@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Header - Clean & Simple -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }} 👋</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Here's what's happening across the platform.</p>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['users'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Admins</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['admins'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Content Items</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['sensors'] + $stats['projects'] + $stats['products'] + $stats['videos'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-sm text-gray-500 dark:text-gray-400">Pending Reviews</p>
                <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ $stats['pending_suggestions'] }}</p>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Content - Recent Users -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Recent Users</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Newest registered accounts</p>
                    </div>
                    <a href="{{ route('super-admin.users.index') }}" class="text-sm font-semibold text-primary hover:underline">View all →</a>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($recentUsers as $user)
                        <div class="px-6 py-4 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($user->profile_image)
                                        <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-gray-500"></i>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                            <span class="shrink-0 px-2.5 py-1 text-xs font-semibold rounded-full 
                                {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                {{ $user->role === 'user' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-200' : '' }}">
                                {{ ucwords(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No users yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar - Pending Suggestions -->
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pending Suggestions</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Needs your review</p>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($recentSuggestions as $suggestion)
                        <div class="px-6 py-4">
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ Str::limit($suggestion->title, 50) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">by {{ $suggestion->user?->name ?? 'Deleted user' }} · {{ $suggestion->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8 text-sm">All clear! 🎉</p>
                    @endforelse
                </div>
                @if($recentSuggestions->count() > 0)
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
                        <a href="{{ route('super-admin.suggestions.index') }}" class="text-sm font-semibold text-primary hover:underline">Review all →</a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Content Overview Cards -->
        <div class="mt-8">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Content Overview</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('super-admin.sensors.index') }}" class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5 hover:border-blue-300 dark:hover:border-blue-700 transition">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sensors</p>
                        <i class="fas fa-microchip text-blue-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['sensors'] }}</p>
                </a>
                <a href="{{ route('super-admin.projects.index') }}" class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5 hover:border-emerald-300 dark:hover:border-emerald-700 transition">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Projects</p>
                        <i class="fas fa-project-diagram text-emerald-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['projects'] }}</p>
                </a>
                <a href="{{ route('super-admin.products.index') }}" class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5 hover:border-purple-300 dark:hover:border-purple-700 transition">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Products</p>
                        <i class="fas fa-shopping-cart text-purple-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['products'] }}</p>
                </a>
                <a href="{{ route('super-admin.videos.index') }}" class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 p-5 hover:border-red-300 dark:hover:border-red-700 transition">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Videos</p>
                        <i class="fas fa-video text-red-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['videos'] }}</p>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection