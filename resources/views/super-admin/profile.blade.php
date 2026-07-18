@extends('layouts.app')

@section('title', 'Faculty Head Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <a href="{{ route('super-admin.dashboard') }}" class="text-primary hover:underline mb-2 inline-block text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white">Account Settings</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your profile and security settings.</p>
    </div>

    <div class="space-y-6">
        {{-- Profile Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                        @if($user->profile_image)
                            <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-3xl font-bold text-gray-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                </div>
                <div class="text-center sm:text-left flex-1">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    <div class="flex items-center gap-2 mt-2 justify-center sm:justify-start">
                        <span class="px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">Faculty Head</span>
                        <span class="text-xs text-gray-400">· Joined {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Profile Information</h2>
            <form action="{{ route('super-admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Profile Picture</label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*"
                        class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-5 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-300 hover:file:bg-gray-200 dark:hover:file:bg-gray-600">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, or GIF up to 2MB.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-primary hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg transition text-sm">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Password Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sm:p-8">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Change Password</h2>
            <form action="{{ route('super-admin.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Current Password</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password"
                            class="w-full px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent" required>
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" onclick="togglePasswordVisibility('current_password', this)">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" onclick="togglePasswordVisibility('password', this)">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary focus:border-transparent" required>
                            <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" onclick="togglePasswordVisibility('password_confirmation', this)">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-gray-800 dark:bg-gray-200 hover:bg-gray-700 dark:hover:bg-gray-300 text-white dark:text-gray-800 font-medium py-2.5 px-6 rounded-lg transition text-sm">
                        <i class="fas fa-key mr-2"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, button) {
        const input = document.getElementById(inputId);
        if (!input || !button) return;
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