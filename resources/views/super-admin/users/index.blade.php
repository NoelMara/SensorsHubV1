@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Manage Users</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage student and instructor accounts.</p>
        </div>
        <a href="{{ route('super-admin.users.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
            <i class="fas fa-plus mr-1"></i> Add User
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('super-admin.users.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !$role ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">All ({{ $roleCounts['all'] }})</a>
        <a href="{{ route('super-admin.users.index', ['role' => 'user']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $role === 'user' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">Students ({{ $roleCounts['user'] }})</a>
        <a href="{{ route('super-admin.users.index', ['role' => 'admin']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $role === 'admin' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">Instructors ({{ $roleCounts['admin'] }})</a>
        <a href="{{ route('super-admin.users.index', ['role' => 'super_admin']) }}" class="px-4 py-2 rounded-lg text-sm font-medium transition {{ $role === 'super_admin' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }}">Faculty Head ({{ $roleCounts['super_admin'] }})</a>
    </div>

    {{-- Users Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Role</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Verified</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Joined</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-5 py-3">
                                <form method="POST" action="{{ route('super-admin.users.role', $user) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white py-1.5">
                                        <option value="user" @selected($user->role === 'user')>Student</option>
                                        <option value="admin" @selected($user->role === 'admin')>Instructor</option>
                                        <option value="super_admin" @selected($user->role === 'super_admin')>Faculty Head</option>
                                    </select>
                                    <button type="submit" class="px-3 py-1.5 bg-primary text-white rounded-lg text-xs hover:bg-blue-600 transition">Save</button>
                                </form>
                            </td>
                            <td class="px-5 py-3">
                                @if($user->email_verified_at)
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">Verified</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">Pending</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('super-admin.users.show', $user) }}" class="p-1.5 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    @if(!$user->isSuperAdmin() || $user->is(auth()->user()))
                                        <a href="{{ route('super-admin.users.edit', $user) }}" class="p-1.5 text-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    @endif
                                    @if(!$user->is(auth()->user()) && !$user->isSuperAdmin())
                                        <form method="POST" action="{{ route('super-admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Remove this user?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Remove">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <i class="fas fa-users text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No users found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection