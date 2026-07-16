@extends('layouts.app')

@section('title', 'Faculty Head Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }} 👋</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your entire platform from one place.</p>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg">
                        <i class="fas fa-users text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['users'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                        <i class="fas fa-user-shield text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Instructors</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['admins'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg">
                        <i class="fas fa-layer-group text-emerald-600 dark:text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Content Items</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['sensors'] + $stats['projects'] + $stats['products'] + $stats['videos'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-100 dark:bg-amber-900/50 rounded-lg">
                        <i class="fas fa-clock text-amber-600 dark:text-amber-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pending Reviews</p>
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $stats['pending_suggestions'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Overview - 6 Cards -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Content Overview</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="{{ route('super-admin.users.index') }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition text-center">
                    <i class="fas fa-users text-2xl text-indigo-500 mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Users</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['users'] }}</p>
                </a>
                <a href="{{ route('super-admin.suggestions.index') }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:border-yellow-300 dark:hover:border-yellow-700 hover:shadow-md transition text-center">
                    <i class="fas fa-lightbulb text-2xl text-yellow-500 mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Suggestions</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['suggestions'] }}</p>
                </a>
                <a href="{{ route('super-admin.sensors.index') }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:border-blue-300 dark:hover:border-blue-700 hover:shadow-md transition text-center">
                    <i class="fas fa-microchip text-2xl text-blue-500 mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sensors</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['sensors'] }}</p>
                </a>
                <a href="{{ route('super-admin.projects.index') }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-md transition text-center">
                    <i class="fas fa-project-diagram text-2xl text-emerald-500 mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Projects</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['projects'] }}</p>
                </a>
                <a href="{{ route('super-admin.products.index') }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:border-purple-300 dark:hover:border-purple-700 hover:shadow-md transition text-center">
                    <i class="fas fa-shopping-cart text-2xl text-purple-500 mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Products</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['products'] }}</p>
                </a>
                <a href="{{ route('super-admin.videos.index') }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:border-red-300 dark:hover:border-red-700 hover:shadow-md transition text-center">
                    <i class="fas fa-video text-2xl text-red-500 mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Videos</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['videos'] }}</p>
                </a>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Recent Users -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
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
                                {{ $user->role === 'super_admin' ? 'Faculty Head' : ($user->role === 'admin' ? 'Instructor' : 'Student') }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No users yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Pending Suggestions -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pending Suggestions</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Needs your review</p>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($recentSuggestions as $suggestion)
                        <a href="{{ route('super-admin.suggestions.show', $suggestion) }}" class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ Str::limit($suggestion->title, 50) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">by {{ $suggestion->user?->name ?? 'Deleted user' }} · {{ $suggestion->created_at->diffForHumans() }}</p>
                        </a>
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

        <!-- Latest Comments -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Latest Comments</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Recent discussion activity</p>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($recentComments as $comment)
                        <a href="{{ route('super-admin.suggestions.show', $comment->suggestion) }}" class="block px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-user text-gray-500 text-xs"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ $comment->user?->name ?? 'Deleted user' }}</span>
                                        <span class="text-xs text-gray-400">· {{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">{{ Str::limit($comment->body, 80) }}</p>
                                    <p class="text-xs text-primary mt-1">on: {{ $comment->suggestion?->title ?? 'Deleted suggestion' }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-8">No comments yet</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection