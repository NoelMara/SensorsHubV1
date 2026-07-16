@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white">Manage Users</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Manage student and instructor accounts, or change account roles.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('super-admin.users.index') }}" class="px-4 py-2 rounded-lg {{ !$role ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">All ({{ $roleCounts['all'] }})</a>
            <a href="{{ route('super-admin.users.index', ['role' => 'user']) }}" class="px-4 py-2 rounded-lg {{ $role === 'user' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Students ({{ $roleCounts['user'] }})</a>
            <a href="{{ route('super-admin.users.index', ['role' => 'admin']) }}" class="px-4 py-2 rounded-lg {{ $role === 'admin' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Instructors ({{ $roleCounts['admin'] }})</a>
            <a href="{{ route('super-admin.users.index', ['role' => 'super_admin']) }}" class="px-4 py-2 rounded-lg {{ $role === 'super_admin' ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">Faculty Head ({{ $roleCounts['super_admin'] }})</a>
            <a href="{{ route('super-admin.users.create') }}" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 flex items-center gap-2">
                <i class="fas fa-plus"></i> Add User
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Verified</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if($user->profile_image)
                                            <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-gray-500"></i>
                                        @endif
                                    </div>
                                    <span>{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('super-admin.users.role', $user) }}" class="flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                        <option value="user" @selected($user->role === 'user')>Student</option>
                                        <option value="admin" @selected($user->role === 'admin')>Instructor</option>
                                        <option value="super_admin" @selected($user->role === 'super_admin')>Faculty Head</option>
                                    </select>
                                    <button type="submit" class="px-3 py-2 bg-primary text-white rounded-lg text-sm hover:bg-blue-600">Save</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($user->email_verified_at)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Verified</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-3">
                                    {{-- View --}}
                                    <a href="{{ route('super-admin.users.show', $user) }}"
                                        class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>

                                    {{-- Edit --}}
                                    @if(!$user->isSuperAdmin() || $user->is(auth()->user()))
                                        <a href="{{ route('super-admin.users.edit', $user) }}"
                                            class="text-yellow-500 hover:text-yellow-700">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                    @endif

                                    {{-- Delete --}}
                                    @if(!$user->is(auth()->user()) && !$user->isSuperAdmin())
                                        <form method="POST" action="{{ route('super-admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Remove this {{ $user->role === 'super_admin' ? 'Faculty Head' : ($user->role === 'admin' ? 'Instructor' : 'Student') }} account?');"
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash mr-1"></i>Remove
                                            </button>
                                        </form>
                                    @elseif($user->is(auth()->user()))
                                        <span class="text-gray-400">Current account</span>
                                    @else
                                        <span class="text-gray-400">Protected</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No users found.</td>
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