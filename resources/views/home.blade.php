@extends('layouts.app')

@section('title', 'Home')

@section('content')
@auth
<!-- Dashboard Content for Authenticated Users -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 sm:p-8 text-white mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold mb-2 break-words">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="text-blue-100 text-sm sm:text-base">Manage your projects, suggestions, and explore new sensors.</p>
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
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Profile</p>
                    <p class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white break-all">{{ $user->email }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full shrink-0">
                    <i class="fas fa-user text-purple-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('dashboard.profile') }}" class="text-primary text-sm hover:underline mt-2 inline-block">Edit profile →</a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="#" onclick="document.getElementById('suggestionModal').classList.remove('hidden')" class="flex items-start sm:items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition gap-3">
                <i class="fas fa-plus-circle text-primary text-2xl shrink-0"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">Submit New Suggestion</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Share your project idea</p>
                </div>
            </a>
            <a href="{{ route('projects.index') }}" class="flex items-start sm:items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition gap-3">
                <i class="fas fa-search text-secondary text-2xl shrink-0"></i>
                <div>
                    <p class="font-semibold text-gray-800 dark:text-white">Browse Projects</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Explore sensor projects</p>
                </div>
            </a>
            <a href="https://sensors-hub-simulator.vercel.app/" target="_blank" class="flex items-start sm:items-center p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary transition gap-3">
                <i class="fas fa-flask text-orange-600 text-2xl shrink-0"></i>
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
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
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
                        @if($suggestion->status == 'implemented') bg-green-100 text-green-800
                        @elseif($suggestion->status == 'rejected') bg-red-100 text-red-800
                        @elseif($suggestion->status == 'reviewed') bg-blue-100 text-blue-800
                        @else bg-yellow-100 text-yellow-800
                        @endif
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
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-start gap-4 mb-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Submit Project Suggestion</h2>
                <button onclick="document.getElementById('suggestionModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('dashboard.suggestions.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Project Title</label>
                        <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Difficulty</label>
                            <select name="difficulty" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                                <option value="">Select difficulty</option>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sensor Type</label>
                            <input type="text" name="sensor_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('suggestionModal').classList.add('hidden')" class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Cancel</button>
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-600">Submit Suggestion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@else
{{-- ==================== GUEST HOME (redesigned) ==================== --}}

{{-- Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .guest-home { font-family: 'IBM Plex Sans', system-ui, sans-serif; }
    .guest-mono { font-family: 'IBM Plex Mono', ui-monospace, monospace; }
</style>

<div class="guest-home">

{{-- Hero Section --}}
<section class="border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Left: Text --}}
            <div>
                <p class="guest-mono text-xs uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-4">
                    &gt; sensor.lab_
                </p>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.05] text-gray-900 dark:text-white mb-6">
                    Learn sensors.<br>
                    Build real circuits.
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 max-w-lg">
                    A hands-on workspace for students and makers. Study components, follow real projects, test circuits in a live simulator.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('sensors.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        Browse Sensors
                        <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        View Projects
                    </a>
                </div>
            </div>

            {{-- Right: Technical Spec Card --}}
            <div class="border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-950">
                {{-- Terminal Header --}}
                <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900">
                    <span class="w-3 h-3 rounded-full bg-red-400"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                    <span class="w-3 h-3 rounded-full bg-green-400"></span>
                    <span class="guest-mono text-xs text-gray-500 dark:text-gray-400 ml-2">sensorshub — specs</span>
                </div>
                {{-- Specs Grid --}}
                <div class="p-6 space-y-4 guest-mono text-sm">
                    <div class="flex items-center justify-between border-b border-dashed border-gray-200 dark:border-gray-800 pb-3">
                        <span class="text-gray-500 dark:text-gray-400 uppercase tracking-wide text-xs">Sensors</span>
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ str_pad($featuredSensors->count(), 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-dashed border-gray-200 dark:border-gray-800 pb-3">
                        <span class="text-gray-500 dark:text-gray-400 uppercase tracking-wide text-xs">Projects</span>
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ str_pad($featuredProjects->count(), 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-dashed border-gray-200 dark:border-gray-800 pb-3">
                        <span class="text-gray-500 dark:text-gray-400 uppercase tracking-wide text-xs">Tutorials</span>
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ str_pad($latestVideos->count(), 3, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400 uppercase tracking-wide text-xs">Simulator</span>
                        <span class="text-green-600 dark:text-green-400 font-semibold">● ONLINE</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Featured Sensors — Datasheet Style --}}
<section class="bg-gray-50 dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8 gap-4">
            <div>
                <p class="guest-mono text-xs uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-2">// 01</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Sensors</h2>
            </div>
            <a href="{{ route('sensors.index') }}" class="guest-mono text-xs uppercase tracking-wide text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap">
                All Sensors →
            </a>
        </div>

        <div class="border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden bg-white dark:bg-gray-900">
            @foreach($featuredSensors as $index => $sensor)
            <a href="{{ route('sensors.show', $sensor->slug) }}" class="flex items-center gap-4 sm:gap-6 px-4 sm:px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition border-b border-gray-100 dark:border-gray-800 last:border-0 group">
                {{-- Index --}}
                <span class="guest-mono text-xs text-gray-400 dark:text-gray-600 w-8 flex-shrink-0">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </span>
                {{-- Thumbnail --}}
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center flex-shrink-0 overflow-hidden border border-gray-200 dark:border-gray-700">
                    @if($sensor->image)
                        <img src="{{ Str::startsWith($sensor->image, ['http://', 'https://']) ? $sensor->image : (Str::startsWith($sensor->image, ['images/', '/images/']) ? asset($sensor->image) : asset('storage/' . $sensor->image)) }}" alt="{{ $sensor->name }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <i class="fas fa-microchip text-gray-400"></i>
                    @endif
                </div>
                {{-- Name + Description --}}
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition truncate">
                        {{ $sensor->name }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($sensor->description, 70) }}</p>
                </div>
                {{-- Arrow --}}
                <i class="fas fa-arrow-right text-gray-300 dark:text-gray-600 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:translate-x-1 transition-all flex-shrink-0"></i>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Featured Projects — Numbered List --}}
<section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8 gap-4">
            <div>
                <p class="guest-mono text-xs uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-2">// 02</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Projects</h2>
            </div>
            <a href="{{ route('projects.index') }}" class="guest-mono text-xs uppercase tracking-wide text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap">
                All Projects →
            </a>
        </div>

        <div class="border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
            @foreach($featuredProjects as $index => $project)
            <a href="{{ route('projects.show', $project->slug) }}" class="block px-4 sm:px-6 py-5 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition border-b border-gray-100 dark:border-gray-800 last:border-0 group">
                <div class="flex items-start gap-4 sm:gap-6">
                    <span class="guest-mono text-xs text-gray-400 dark:text-gray-600 w-8 flex-shrink-0 pt-1">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-lg text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition mb-2">
                            {{ $project->title }}
                        </h3>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 guest-mono text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            <span class="{{ $project->difficulty === 'Beginner' ? 'text-green-600 dark:text-green-400' : ($project->difficulty === 'Intermediate' ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }}">
                                {{ $project->difficulty }}
                            </span>
                            <span>·</span>
                            <span>{{ $project->sensor?->name ?? 'GENERAL' }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">{{ Str::limit($project->description, 120) }}</p>
                    </div>
                    <i class="fas fa-arrow-right text-gray-300 dark:text-gray-600 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:translate-x-1 transition-all flex-shrink-0 mt-1"></i>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Latest Tutorials --}}
@if($latestVideos->count() > 0)
<section class="bg-gray-50 dark:bg-gray-950 border-b border-gray-200 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-end justify-between mb-8 gap-4">
            <div>
                <p class="guest-mono text-xs uppercase tracking-widest text-blue-600 dark:text-blue-400 mb-2">// 03</p>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Tutorials</h2>
            </div>
            <a href="{{ route('videos.index') }}" class="guest-mono text-xs uppercase tracking-wide text-blue-600 dark:text-blue-400 hover:underline whitespace-nowrap">
                All Tutorials →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($latestVideos as $video)
            <a href="{{ $video->youtube_link ?? route('videos.index') }}" target="_blank" class="group border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden bg-white dark:bg-gray-900 hover:border-blue-500 dark:hover:border-blue-500 transition">
                <div class="relative pb-[56.25%] bg-gray-200 dark:bg-gray-800">
                    @if($video->youtube_id)
                        <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/mqdefault.jpg" alt="{{ $video->title }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/30 transition flex items-center justify-center">
                            <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                <i class="fas fa-play text-white text-sm ml-0.5"></i>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <p class="guest-mono text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">
                        {{ $video->category ?? 'Tutorial' }}
                    </p>
                    <h3 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2 mb-2">{{ $video->title }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ Str::limit($video->description, 60) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA Section --}}
<section class="bg-gray-900 dark:bg-black">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <p class="guest-mono text-xs uppercase tracking-widest text-blue-400 mb-3">// ready?</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                    Build something.<br>
                    Share it with the community.
                </h2>
                <p class="text-gray-400 max-w-md">
                    Create an account to submit project ideas, join classes, and track your learning progress.
                </p>
            </div>
            <div class="lg:text-right">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Create Account
                    <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</section>

</div>
@endauth
@endsection