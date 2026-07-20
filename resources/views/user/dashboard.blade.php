@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-8 text-white mb-8">
        <h1 class="text-4xl font-bold mb-2">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="text-blue-100">Manage your projects, suggestions, and explore new sensors.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">My Suggestions</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $suggestionsCount }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <i class="fas fa-lightbulb text-primary text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.suggestions') }}" class="text-primary text-sm hover:underline mt-2 inline-block">View all →</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Saved Projects</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $savedProjectsCount }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <i class="fas fa-bookmark text-secondary text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.saved') }}" class="text-primary text-sm hover:underline mt-2 inline-block">View all →</a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Profile</p>
                    <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ $user->email }}</p>
                </div>
                <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900 rounded-full overflow-hidden flex items-center justify-center">
                    @if($user->profile_image)
                        <img src="{{ Str::startsWith($user->profile_image, ['http://', 'https://']) ? $user->profile_image : asset($user->profile_image) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-purple-600 text-2xl"></i>
                    @endif
                </div>
            </div>
            <a href="{{ route('dashboard.profile') }}" class="text-primary text-sm hover:underline mt-2 inline-block">Edit profile →</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="#" onclick="document.getElementById('suggestionModal').classList.remove('hidden')" class="flex items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition">
                <i class="fas fa-plus-circle text-primary text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">Submit New Suggestion</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Share your project idea</p>
                </div>
            </a>
            <a href="{{ route('projects.index') }}" class="flex items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition">
                <i class="fas fa-search text-secondary text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">Browse Projects</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Explore sensor projects</p>
                </div>
            </a>
            <a href="https://sensorshub.infinityfree.me/" target="_blank" class="flex items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition">
                <i class="fas fa-flask text-orange-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">Simulation</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Test sensor circuits online</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Featured Sensors -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Featured Sensors</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredSensors as $sensor)
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-32 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center overflow-hidden">
                    @if($sensor->image)
                        <img src="{{ Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : (Str::startsWith($sensor->image, ['images/', '/images/']) ? asset($sensor->image) : asset('storage/' . $sensor->image)) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-microchip text-4xl text-white"></i>
                    @endif
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold mb-2 text-gray-800 dark:text-white">{{ $sensor->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-3 flex-1 text-sm">{{ Str::limit($sensor->description, 80) }}</p>
                    <a href="{{ route('sensors.show', $sensor->slug) }}" class="text-primary font-semibold hover:underline text-sm">
                        Learn More <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('sensors.index') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition text-sm">
                View All Sensors
            </a>
        </div>
    </div>

    <!-- Featured Projects -->
    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Featured Projects</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($featuredProjects as $project)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="p-6 flex-1 flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <span class="bg-blue-100 dark:bg-blue-900 text-primary px-2 py-1 rounded-full text-xs font-semibold">
                            {{ $project->difficulty }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400 text-xs">
                            <i class="fas fa-microchip mr-1"></i> {{ $project->sensor?->name ?? 'General' }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold mb-2 text-gray-800 dark:text-white">{{ $project->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-3 flex-1 text-sm">{{ Str::limit($project->description, 100) }}</p>
                    <a href="{{ route('projects.show', $project->slug) }}" class="text-primary font-semibold hover:underline text-sm">
                        View Project <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-6">
            <a href="{{ route('projects.index') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition text-sm">
                View All Projects
            </a>
        </div>
    </div>

    <!-- Recent Suggestions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Recent Suggestions</h2>
        @if($recentSuggestions->count() > 0)
            <div class="space-y-4">
                @foreach($recentSuggestions as $suggestion)
                <div class="border-l-4 border-primary pl-4 py-2">
                    <h3 class="font-semibold text-gray-800 dark:text-white">{{ $suggestion->title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($suggestion->description, 100) }}</p>
                    <span class="inline-block mt-2 px-3 py-1 text-xs rounded-full 
                    @if($suggestion->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($suggestion->status == 'reviewed') bg-blue-100 text-blue-800
                    @elseif($suggestion->status == 'implemented') bg-green-100 text-green-800
                    @elseif($suggestion->status == 'rejected') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($suggestion->status) }}
                </span>
                </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">No suggestions yet. Submit your first idea!</p>
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