@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-800 dark:text-white mb-4">Project Library</h1>
        <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-400">Build amazing projects with sensors</p>
    </div>

    <!-- Filter Options -->
    <form method="GET" action="{{ route('projects.index') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <select name="difficulty" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">All Difficulties</option>
                <option value="Beginner" {{ request('difficulty') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="Intermediate" {{ request('difficulty') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="Advanced" {{ request('difficulty') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
            </select>
        </div>
        <div class="flex gap-2">
            <select name="sensor_id" class="flex-1 w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">All Sensors</option>
                @foreach($sensors as $sensor)
                    <option value="{{ $sensor->id }}" {{ request('sensor_id') == $sensor->id ? 'selected' : '' }}>{{ $sensor->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-600 transition">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
</form>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        @foreach($projects as $project)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1 h-full">
            <div class="p-8 h-full flex flex-col">
                <!-- Header with badges -->
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($project->difficulty == 'Beginner') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($project->difficulty == 'Intermediate') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @endif">
                        {{ $project->difficulty }}
                    </span>
                    @if($project->is_featured)
                    <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-semibold">
                        <i class="fas fa-star mr-1"></i> Featured
                    </span>
                    @endif
                </div>

                <!-- Title & Sensor -->
                <h3 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">{{ $project->title }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <i class="fas fa-microchip mr-1 text-primary"></i> 
                    {{ $project->sensor?->name ?? 'General' }}
                </p>

                <!-- Description -->
                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($project->description, 150) }}</p>

                <!-- Components Preview -->
                @if($project->components_needed)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-tools mr-1"></i> Components Needed:
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($project->components_needed, 100) }}</p>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mt-auto pt-2">
                    <a href="{{ route('projects.show', $project->slug) }}" class="flex-1 text-center bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition">
                        View Project <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    @auth
                    <form action="{{ route('dashboard.projects.save', $project) }}" method="POST" class="sm:self-stretch">
                        @csrf
                        <button type="submit" class="w-full h-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg hover:border-primary hover:text-primary transition" title="Save Project">
                            <i class="fas fa-bookmark"></i>
                        </button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($projects->hasPages())
    <div class="flex justify-center">
        {{ $projects->links() }}
    </div>
    @endif

    <!-- Empty State -->
    @if($projects->count() === 0)
    <div class="text-center py-16">
        <i class="fas fa-project-diagram text-8xl text-gray-300 dark:text-gray-600 mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-600 dark:text-gray-400 mb-2">No Projects Found</h3>
        <p class="text-gray-500 dark:text-gray-500">Check back later for new projects!</p>
    </div>
    @endif

    <!-- Call to Action -->
    <div class="mt-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 sm:p-8 text-white text-center">
        <h2 class="text-2xl sm:text-3xl font-bold mb-4">Have a Project Idea?</h2>
        <p class="text-base sm:text-lg mb-6 text-purple-100">Share your project suggestion with our community!</p>
        @auth
            <a href="{{ route('dashboard.suggestions') }}" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Submit Your Idea
            </a>
        @else
            <a href="{{ route('register') }}" class="inline-block bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                Join Now to Share Ideas
            </a>
        @endauth
    </div>
</div>
@endsection
