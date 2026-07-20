@extends('layouts.app')

@section('title', 'View User')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('super-admin.users.index') }}" class="text-primary hover:underline mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Users
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">User Details</h1>
    </div>

    {{-- Profile Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8 mb-6">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
            <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if($user->profile_image)
                    <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-2xl font-bold text-gray-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 justify-center sm:justify-start flex-wrap">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <span class="px-2 py-0.5 text-xs rounded-full
                        {{ $user->role === 'administrator' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : '' }}
                        {{ $user->role === 'instructor' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                        {{ $user->role === 'student' ? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' : '' }}">
                        {{ $user->role === 'administrator' ? 'Administrator' : ($user->role === 'instructor' ? 'Instructor' : 'Student') }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if(!$user->isAdministrator() || $user->is(auth()->user()))
                    <a href="{{ route('super-admin.users.edit', $user) }}"
                        class="px-3 py-2 rounded-lg bg-primary text-white hover:bg-blue-600 transition text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                @endif
                @if(!$user->is(auth()->user()) && !$user->isAdministrator())
                    <form method="POST" action="{{ route('super-admin.users.destroy', $user) }}"
                        onsubmit="return confirm('Remove this account?');">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i> Remove
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 text-center">
            <i class="fas fa-calendar-check text-2xl {{ $user->email_verified_at ? 'text-green-500' : 'text-yellow-500' }} mb-2"></i>
            <p class="text-xs text-gray-500 dark:text-gray-400">Email Status</p>
            <p class="text-sm font-semibold {{ $user->email_verified_at ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }} mt-1">
                {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
            </p>
            @if($user->email_verified_at)
                <p class="text-xs text-gray-400 mt-0.5">{{ $user->email_verified_at->format('M d, Y') }}</p>
            @endif
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 text-center">
            <i class="fas fa-user-plus text-2xl text-blue-500 mb-2"></i>
            <p class="text-xs text-gray-500 dark:text-gray-400">Account Created</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $user->created_at->format('M d, Y') }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $user->created_at->diffForHumans() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 text-center">
            <i class="fas fa-clock text-2xl text-gray-400 mb-2"></i>
            <p class="text-xs text-gray-500 dark:text-gray-400">Last Updated</p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $user->updated_at->format('M d, Y') }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $user->updated_at->diffForHumans() }}</p>
        </div>
    </div>

    {{-- Back Link --}}
    <div class="text-center">
        <a href="{{ route('super-admin.users.index') }}" class="text-sm text-primary hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back to Users
        </a>
    </div>
</div>
@endsection