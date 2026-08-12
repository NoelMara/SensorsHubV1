@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 dark:text-white mb-1">Welcome back, {{ $user->name }} 👋</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Manage your projects, suggestions, and explore new sensors.</p>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0">
                @if($user->profile_image)
                    <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}"
                         alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xl font-bold text-gray-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</h2>
                    <span class="px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                        Student
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</p>
            </div>
            <a href="{{ route('dashboard.profile') }}"
               class="px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition text-xs font-medium flex-shrink-0">
                <i class="fas fa-user-edit mr-1"></i> Edit Profile
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8 max-w-2xl">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <i class="fas fa-lightbulb text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">My Suggestions</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $suggestionsCount }}</p>
                </div>
            </div>
            <a href="{{ route('dashboard.suggestions') }}" class="text-xs text-primary hover:underline">View all →</a>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <i class="fas fa-bookmark text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Saved Projects</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $savedProjectsCount }}</p>
                </div>
            </div>
            <a href="{{ route('dashboard.saved') }}" class="text-xs text-primary hover:underline">View all →</a>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 sm:p-6 mb-8">
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <button onclick="document.getElementById('suggestionModal').classList.remove('hidden')" 
                class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl hover:border-primary dark:hover:border-primary transition text-left">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-plus-circle text-blue-600 dark:text-blue-400"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm">Submit Suggestion</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Share your project idea</p>
                </div>
            </button>
            <a href="{{ route('projects.index') }}" 
                class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl hover:border-primary dark:hover:border-primary transition">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-search text-green-600 dark:text-green-400"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm">Browse Projects</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Explore sensor projects</p>
                </div>
            </a>
            <a href="https://sensorshub.infinityfree.me/" target="_blank" 
                class="flex items-center gap-3 p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl hover:border-primary dark:hover:border-primary transition">
                <div class="w-10 h-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-flask text-orange-600 dark:text-orange-400"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white text-sm">Simulation</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Test sensor circuits</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Recent Suggestions --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-base font-bold text-gray-900 dark:text-white">Recent Suggestions</h2>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recentSuggestions as $suggestion)
                <a href="{{ route('dashboard.suggestions.show', $suggestion) }}" class="block px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $suggestion->title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ Str::limit($suggestion->description, 80) }}</p>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full flex-shrink-0
                            @if($suggestion->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-300
                            @elseif($suggestion->status === 'reviewed') bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300
                            @elseif($suggestion->status === 'implemented') bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300
                            @else bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 @endif">
                            {{ ucfirst($suggestion->status) }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-8 text-sm">No suggestions yet. Submit your first idea!</p>
            @endforelse
        </div>
        @if($recentSuggestions->count() > 0)
            <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('dashboard.suggestions') }}" class="text-xs text-primary hover:underline">View all →</a>
            </div>
        @endif
    </div>

</div>

<!-- Suggestion Modal -->
<div id="suggestionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Submit Project Suggestion</h2>
            <button onclick="document.getElementById('suggestionModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('dashboard.suggestions.store') }}">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Project Title</label>
                        <input type="text" name="title" id="title" required 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition @error('title') border-red-500 @enderror"
                            placeholder="e.g., Automatic Plant Watering System">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                        <textarea name="description" id="description" rows="5" required 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition resize-none @error('description') border-red-500 @enderror"
                            placeholder="Describe your project idea in detail..."></textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Difficulty Level</label>
                            <select name="difficulty" id="difficulty" 
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition">
                                <option value="">Select difficulty</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label for="sensor_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Sensor Type</label>
                            <input type="text" name="sensor_type" id="sensor_type" 
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition"
                                placeholder="e.g., DHT11, HC-SR04">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('suggestionModal').classList.add('hidden')" 
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-5 py-2.5 bg-primary text-white rounded-xl hover:bg-blue-600 transition text-sm font-medium shadow-sm">
                        <i class="fas fa-paper-plane mr-1.5"></i> Submit Suggestion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection