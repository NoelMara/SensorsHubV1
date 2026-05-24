@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('super-admin.users.index') }}" class="text-primary hover:underline mb-2 inline-block">
            <i class="fas fa-arrow-left mr-1"></i>Back to Manage Users
        </a>
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white">Edit User</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Update account details for {{ $user->name }}.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-600 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('super-admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Avatar Preview --}}
            <div class="flex items-center gap-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                    @if($user->profile_image)
                        <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-gray-400 text-2xl"></i>
                    @endif
                </div>
                <div>
                    <p class="font-medium text-gray-800 dark:text-white">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full
                        {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
            </div>

            @if(!$user->isSuperAdmin())
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                <select id="role" name="role" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
                    <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>
            </div>
            @endif

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span>
                </label>
                <input type="password" id="password" name="password"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary focus:ring-primary">
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('super-admin.users.index') }}"
                    class="px-5 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-primary text-white hover:bg-blue-600">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection