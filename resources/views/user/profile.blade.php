@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">My Profile</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage your account settings and preferences.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Profile Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">
                <i class="fas fa-user mr-2 text-primary"></i>Profile Information
            </h2>
            
            <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <!-- Profile Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-image mr-1 text-blue-500"></i>Profile Image
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                @if($user->profile_image && file_exists(public_path($user->profile_image)))
                                    <img src="{{ asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-4xl text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" 
                                       name="profile_image" 
                                       id="profile_image" 
                                       accept="image/*"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Full Name
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                               required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Email Address
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Account Type
                        </label>
                        <input type="text" 
                               value="{{ $user->isSuperAdmin() ? 'Super Administrator' : ($user->isAdmin() ? 'Administrator' : 'Regular User') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 dark:text-white"
                               disabled>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Member Since
                        </label>
                        <input type="text" 
                               value="{{ $user->created_at->format('F d, Y') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 dark:text-white"
                               disabled>
                    </div>

                    <button type="submit" 
                            class="w-full bg-primary hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">
                <i class="fas fa-lock mr-2 text-secondary"></i>Change Password
            </h2>
            
            <form action="{{ route('dashboard.profile.password') }}" method="POST">
                @csrf
                @method('POST')
                
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Current Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   required>
                            <button type="button" class="absolute right-3 top-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" onclick="togglePasswordVisibility('current_password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            New Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   required>
                            <button type="button" class="absolute right-3 top-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" onclick="togglePasswordVisibility('password', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimum 8 characters</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   required>
                            <button type="button" class="absolute right-3 top-2 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200" onclick="togglePasswordVisibility('password_confirmation', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-secondary hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-key mr-2"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Back to Dashboard -->
    <div class="mt-8">
        <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('dashboard.index') }}" class="inline-flex items-center text-primary hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
        </a>
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
