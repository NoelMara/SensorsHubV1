@extends('layouts.app')

@section('title', 'View User')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('super-admin.users.index') }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i>Back to Manage Users
        </a>
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white">User Details</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Viewing account information for {{ $user->name }}.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-600 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm text-red-600 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Profile Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-6">
        <div class="p-6 flex items-center gap-5 border-b border-gray-200 dark:border-gray-700">
            <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if($user->profile_image)
                    <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user text-gray-400 text-3xl"></i>
                @endif
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h2>
                <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-1 text-xs font-medium rounded-full
                    {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                   {{ $user->role === 'super_admin' ? 'Faculty Head' : ($user->role === 'admin' ? 'Instructor' : 'Student') }}
                </span>
            </div>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">Email Verified</span>
                @if($user->email_verified_at)
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                        Verified on {{ $user->email_verified_at->format('M d, Y') }}
                    </span>
                @else
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                @endif
            </div>
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">Account Created</span>
                <span class="text-sm text-gray-800 dark:text-white">{{ $user->created_at->format('M d, Y') }}</span>
            </div>
            <div class="px-6 py-4 flex justify-between items-center">
                <span class="text-sm text-gray-500 dark:text-gray-400">Last Updated</span>
                <span class="text-sm text-gray-800 dark:text-white">{{ $user->updated_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex flex-wrap items-center gap-3">
        @if(!$user->isSuperAdmin() || $user->is(auth()->user()))
            <a href="{{ route('super-admin.users.edit', $user) }}"
                class="px-5 py-2 rounded-lg bg-primary text-white hover:bg-blue-600 flex items-center gap-2">
                <i class="fas fa-edit"></i> Edit Account
            </a>
        @endif

        @if(!$user->is(auth()->user()) && !$user->isSuperAdmin())
            <form method="POST" action="{{ route('super-admin.users.destroy', $user) }}"
                onsubmit="return confirm('Are you sure you want to remove this account?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 flex items-center gap-2">
                    <i class="fas fa-trash"></i> Remove Account
                </button>
            </form>
        @endif

        <a href="{{ route('super-admin.users.index') }}"
            class="px-5 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
            Back to Users
        </a>
    </div>
</div>
@endsection