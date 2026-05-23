@extends('layouts.app')

@section('title', 'Super Admin Profile')

@section('content')
<div class="min-h-screen bg-gray-100 dark:bg-gray-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center text-sm font-semibold text-primary hover:underline mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Back to Super Admin
            </a>
            <div class="bg-gray-900 rounded-lg border border-gray-800 shadow-sm p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div>
                        <p class="text-sm font-semibold text-blue-300 uppercase">Account Settings</p>
                        <h1 class="text-3xl sm:text-4xl font-bold text-white mt-2">Super Admin Profile</h1>
                        <p class="text-gray-300 mt-2">Manage your super admin identity and password.</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-lg bg-primary text-white flex items-center justify-center text-2xl overflow-hidden">
                        @if($user->profile_image && file_exists(public_path($user->profile_image)))
                            <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-user-shield"></i>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Role</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-1">Super Administrator</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 dark:border-gray-800 p-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Email Status</p>
                        <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $user->email_verified_at ? 'Verified' : 'Pending verification' }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Profile Information</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Update the name and email used for super admin access.</p>

                    <form action="{{ route('super-admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Picture</label>
                            <div class="flex items-center gap-4">
                                <div class="w-24 h-24 rounded-lg bg-gray-200 dark:bg-gray-700 overflow-hidden flex items-center justify-center">
                                    @if($user->profile_image && file_exists(public_path($user->profile_image)))
                                        <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user-shield text-4xl text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, or GIF up to 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center bg-primary hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Update Profile
                        </button>
                    </form>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-800 shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Security</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Change the password for this super admin account.</p>

                    <form action="{{ route('super-admin.profile.password') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="current_password" class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                                <button type="button" class="absolute inset-y-0 right-0 px-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="togglePasswordVisibility('current_password', this)" aria-label="Toggle current password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                                <button type="button" class="absolute inset-y-0 right-0 px-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="togglePasswordVisibility('password', this)" aria-label="Toggle new password visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 8 characters</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-white" required>
                                <button type="button" class="absolute inset-y-0 right-0 px-4 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="togglePasswordVisibility('password_confirmation', this)" aria-label="Toggle password confirmation visibility">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg transition">
                            <i class="fas fa-key mr-2"></i>Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);

        if (!input || !button) {
            return;
        }

        const icon = button.querySelector('i');
        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';

        if (icon) {
            icon.classList.toggle('fa-eye', !isPassword);
            icon.classList.toggle('fa-eye-slash', isPassword);
        }
    }
</script>
@endpush
